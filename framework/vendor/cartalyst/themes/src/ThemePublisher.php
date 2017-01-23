<?php

/**
 * Part of the Themes package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Themes
 * @version    3.0.7
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Themes;

use Closure;
use RuntimeException;
use InvalidArgumentException;
use Illuminate\Events\Dispatcher;
use Cartalyst\Extensions\ExtensionInterface;

class ThemePublisher
{
    /**
     * The theme bag instance the publisher will use.
     *
     * @var \Cartalyst\Themes\ThemeBag
     */
    protected $themeBag;

    /**
     * The filesystem used for publishing.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The path where the packages are located.
     *
     * @var string
     */
    protected $packagePath;

    /**
     * Array of notes used in the publishing process for the command line.
     *
     * @var array
     */
    protected $notes = array();

    /**
     * Event dispatcher which listens to notes.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * The timestamp output flag.
     *
     * @var bool
     */
    public $showTimestampsOnWatch = false;

    /**
     * Create a new theme publisher.
     *
     * @param  \Cartalyst\Themes\ThemeBag  $themeBag
     * @return void
     */
    public function __construct(ThemeBag $themeBag)
    {
        $this->themeBag   = $themeBag;
        $this->filesystem = $themeBag->getFilesystem();
    }

    /**
     * Publishes all themes files in the given package to their
     * respective themes.
     *
     * @param  string  $source
     * @return bool
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function publish($source)
    {
        if (! $this->filesystem->isDirectory($source)) {
            throw new InvalidArgumentException("Source [{$source}] does not exist.");
        }

        $paths = $this->getThemeSourcePaths($source);

        foreach ($paths as $path) {
            $relativePath = str_replace(str_finish($source, '/'), '', $path);

            $slugs = $this->extractSlugsFromPath($relativePath);

            // If we cannot resolve a theme, the user has probably got assets
            // for a theme which isn't in this installation. Let's not punish
            // them for supporting more than the installed themes, we'll just
            // let them know we've skipped it.
            try {
                $theme = $this->getThemeForSlugs($slugs);
            } catch (RuntimeException $e) {
                $this->note(sprintf(
                    'Couldn\'t load theme for slug(s) [%s] in source [%s], skipping.',
                    implode(', ', $slugs),
                    $source
                ));
                continue;
            }

            $mappings = $this->getCopyMappings($path, $theme);

            foreach ($mappings as $_source => $destination) {
                $success = $this->filesystem->copyDirectory($_source, $destination);

                if (! $success) {
                    throw new RuntimeException("Failed to publish [{$_source}] to [{$destination}].");
                }
            }
        }

        return true;
    }

    /**
     * Publish a given package's theme files.
     *
     * @param  string  $package
     * @param  string  $packagePath
     * @return bool
     */
    public function publishPackage($package, $packagePath = null)
    {
        $source = $this->getPackagePublishSource($package, $packagePath);

        if ($this->publish($source)) {
            $this->note("Succesfully published [{$package}] package.", 'info');
        }
    }

    /**
     * Publish a given extension's theme files.
     *
     * @param  \Cartalyst\Extensions\ExtensionInterface  $extension
     * @return bool
     */
    public function publishExtension(ExtensionInterface $extension)
    {
        $source = $this->getExtensionPublishSource($extension);

        if ($this->publish($source)) {
            $note = array();

            if ($this->showTimestampsOnWatch === true) {
                $note[] = '<comment>'.date('H:i:s').'</comment>';
            }

            $note[] = "Successfully published theme files for [{$extension->getSlug()}] Extension.";

            $this->note(implode(' ', $note), 'info');
        }
    }

    public function getPackagePublishSource($package, $packagePath = null)
    {
        $packagePath = $packagePath ?: $this->packagePath;

        return $packagePath."/{$package}/themes";
    }

    public function getExtensionPublishSource(ExtensionInterface $extension)
    {
        return $extension->getPath().'/themes';
    }

    /**
     * Returns the possible theme source paths for the given source,
     * taking into account the depth of "areas" if they exist as well
     * as the maximum theme depth.
     *
     * @param  string  $source
     * @return array   $paths
     */
    public function getThemeSourcePaths($source)
    {
        $patterns      = array();
        $areaDepth     = $this->themeBag->getAreaDepth();
        $maxThemeDepth = $this->themeBag->getMaxThemeDepth();

        // The rules are simple, we start with themes which satisfy
        // the maximum depth and are in an area. We then cascade the
        // depth back to "1", keeping the area.
        for ($i = $maxThemeDepth; $i >= 1; $i--) {
            // Add the pattern without the area depth first
            $patterns[] = $pattern = sprintf(
                '%s/{%s}',
                str_repeat('/*', $i),
                implode(',', array('packages', 'namespaces', 'views', 'assets'))
            );

            // We repeat the wildcard "*" clause for the area
            // depth that contain our themes
            $patterns[] = str_repeat('/*', $areaDepth).$pattern;
        }

        return $this->manipulatePathResults($source, $patterns);
    }

    /**
     * Attempts to extract possible theme slugs from the relative
     * path given, which may be used to resolve those theme
     * instances on the theme bag lazily.
     *
     * @param  string  $path
     * @return array   $slugs
     * @throws \RuntimeException
     */
    public function extractSlugsFromPath($path)
    {
        $slugs         = array();
        $areaDepth     = $this->themeBag->getAreaDepth();
        $maxThemeDepth = $this->themeBag->getMaxThemeDepth();

        $path      = trim($path, '/');
        $pathParts = explode('/', $path);
        $pathCount = count($pathParts);

        // If the path count is less than or equal to the area depth,
        // the path cannot be in an area as there will be no parts
        // left for the slug.
        if ($pathCount <= $areaDepth) {
            // If the path count is greater than the maximum theme depth
            // but less than the area depth, then it can't match an area
            // or a theme.
            if ($pathCount > $maxThemeDepth) {
                throw new RuntimeException("Path [{$path}] is too small for area depth [{$areaDepth}] and too large for theme depth [{$maxThemeDepth}] with depth of [{$pathCount}].");
            }

            $slugs[] = $path;
        }

        // Otherwise, we need to account for the path depth in our slug
        else {
            // Slice up the array according to the area depth
            $areaPathParts       = array_slice($pathParts, 0, $areaDepth);
            $themePathParts      = array_slice($pathParts, $areaDepth);
            $themePathPartsCount = count($themePathParts);

            // If the path count is greater than the maximum theme depth
            // but less than the area depth, then it can't match an area
            // or a theme.
            if ($themePathPartsCount > $maxThemeDepth) {
                throw new RuntimeException(sprintf(
                    'Path [%s] resolved to area [%s] is too large for theme depth [%s] with depth of [%s].',
                    $path,
                    implode('/', $areaPathParts),
                    $maxThemeDepth,
                    $themePathPartsCount
                ));
            }

            // Firstly, we will provide a slug with the area prepended
            // to follow our convention of area-first before non-area themes.
            $slugs[] = sprintf(
                '%s::%s',
                implode('/', $areaPathParts),
                implode('/', $themePathParts)
            );

            // Now, we will check if the path parts are within the range of
            // the maximum theme depth. If they are, we'll also add that slug
            // to the array of slugs. If not, we won't throw an Exception as the
            // developer has already provided a valid slug above. Think of this
            // as a bonus.
            if ($pathCount <= $maxThemeDepth) {
                $slugs[] = $path;
            }
        }

        return $slugs;
    }

    /**
     * Attempts to get a theme for the array of slugs. The first
     * matching theme wins.
     *
     * @param  array  $slugs
     * @return \Cartalyst\Themes\ThemeInterface
     * @throws \RuntimeException
     */
    public function getThemeForSlugs(array $slugs)
    {
        foreach ($slugs as $slug) {
            try {
                return $this->themeBag->ensureRegistered($slug);
            } catch (RuntimeException $e) {
            }
        }

        throw new RuntimeException('Couldn\'t match ['.implode(', ', $slugs).'] to any themes.');
    }

    /**
     * Gets the copy mappings for the given source to the theme
     * instance. The mappings are a key / value pair where the
     * key is the folder source and the value is the theme
     * destination.
     *
     * @param  string  $source
     * @param  \Cartalyst\Themes\ThemeInterface  $theme
     * @return array
     */
    public function getCopyMappings($source, ThemeInterface $theme)
    {
        $mappings = array();
        $paths    = $this->getThemePaths($source);

        // Now, we want to replace any "package", "namespace", "views"
        // and "asset" paths to match that of the theme destination. We'll
        // use a couple of regular expressions to achieve this.
        foreach ($paths as $path) {
            // Build a regular expression which will capture the package
            // and namespace pattenrs within the path, which we will use
            // to update according to the theme's properties.
            $pattern = '/^'.preg_quote("$source/", '/').'(packages|namespaces)\/(.*?)\/(views|assets)$/';
            preg_match($pattern, $path, $matches);

            // If our regular expression has been matched; the path
            // refers to a section.
            if (count($matches) === 4) {
                array_shift($matches);
                list($sectionType, $section, $directory) = $matches;

                $method = 'get'.ucfirst(str_singular($sectionType)).ucfirst($directory).'Path';
                $mappings[$path] = $theme->$method($section);
                continue;
            }

            // Now, we will query for the basic directories, not inside
            // namespaces or packages.
            $pattern = '/'.preg_quote("$source/", '/').'(views|assets)/';
            preg_match($pattern, $path, $matches);

            if (count($matches) === 2) {
                array_shift($matches);
                list($directory) = $matches;

                $method = 'get'.ucfirst($directory).'Path';
                $mappings[$path] = $theme->$method();
            }
        }

        return $mappings;
    }

    /**
     * Gets the available theme paths from the given theme source.
     * Will only return valid package, namespace, view and asset paths
     * and nothing else.
     *
     * @param  string  $source
     * @return array   $paths
     */
    public function getThemePaths($source)
    {
        $patterns        = array();
        $maxSectionDepth = $this->themeBag->getMaxSectionDepth();

        for ($i = $maxSectionDepth; $i >= 1; $i--) {
            $patterns[] = sprintf(
                '/{%s}%s/{%s}',
                implode(',', array('packages', 'namespaces')),
                str_repeat('/*', $i),
                implode(',', array('views', 'assets'))
            );
        }

        // Add a pattern for the views and assets folder directly beneath
        // the source
        $patterns[] = sprintf('/{%s}', implode(',', array('views', 'assets')));

        return $this->manipulatePathResults($source, $patterns, false);
    }

    /**
     * Set the default package path.
     *
     * @param  string  $packagePath
     * @return void
     */
    public function setPackagePath($packagePath)
    {
        $this->packagePath = $packagePath;
    }

    /**
     * Note down a message.
     *
     * @param  string  $message
     * @return void
     */
    public function note($message, $type = null)
    {
        $note = (isset($type)) ? "<$type>$message</$type>" : $message;

        $this->notes[] = $note;

        // If the dispatcher is
        if (isset($this->dispatcher)) {
            $this->dispatcher->fire('theme.publisher.note', array($note));
        }
    }

    /**
     * Set a callback for when a note event is fired.
     *
     * @param  Closure  $callback
     * @return void
     */
    public function noting(Closure $callback)
    {
        if (! isset($this->dispatcher)) {
            throw new \RuntimeException('Cannot add callback to theme publisher as events dispatcher has not been set.');
        }

        $this->dispatcher->listen('theme.publisher.note', $callback);
    }

    /**
     * Get the notes which have been left.
     *
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set the event dispatcher which listens to notes being
     * generated.
     *
     * @param  Illuminate\Events\Dispatcher  $dispatcher
     * @return void
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Takes a source with an array of relative path patterns, finds
     * the corresponding files in the filesystem.
     *
     * @param  string  $source
     * @param  array   $patterns
     * @param  bool    $parentDir
     * @return array   $paths
     */
    protected function manipulatePathResults($source, array $patterns, $parentDir = true)
    {
        $paths = array();

        // Now we will glob our filesystem for each pattern
        foreach ($patterns as $pattern) {
            if ($results = $this->filesystem->glob(rtrim($source, '/').$pattern, GLOB_BRACE)) {
                $paths = array_merge($paths, array_map(function ($result) use ($parentDir) {
                    // Remove the parent directory if needed
                    if ($parentDir === true) {
                        $result = dirname($result);
                    }

                    return $result;
                }, $results));
            }
        }

        // We'll sort the paths now so they are processed systematically
        sort($paths);

        // It is likely that our glob patterns may have duplicated paths.
        // Let's ensure that's not the case by returning only unique paths.
        return array_unique($paths);
    }
}
