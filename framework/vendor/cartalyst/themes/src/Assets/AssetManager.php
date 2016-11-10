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
 * @version    3.0.6
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Themes\Assets;

use Closure;
use InvalidArgumentException;
use Cartalyst\Themes\ThemeBag;
use Assetic\Asset\AssetInterface;
use Assetic\Asset\AssetCollection;
use Symfony\Component\Finder\Finder;
use Assetic\Asset\AssetCollectionInterface;
use Cartalyst\Dependencies\DependencySorter;
use Symfony\Component\Filesystem\Filesystem;
use Illuminate\Support\NamespacedItemResolver;
use Cartalyst\Themes\Views\ViewFinderInterface;
use Cartalyst\Themes\Locations\GeneratorInterface as LocationGeneratorInterface;

class AssetManager
{
    /**
     * The theme bag instance.
     *
     * @var \Cartalyst\Themes\ThemeBag
     */
    protected $themeBag;

    /**
     * The view finder instance.
     *
     * @var \Cartalyst\Themes\Views\ViewFinderInterface
     */
    protected $viewFinder;

    /**
     * The location generator instance.
     *
     * @var \Cartalyst\Themes\Locations\GeneratorInterface
     */
    protected $locationGenerator;

    /**
     * Whether we are in debug mode or not. Debug mode will compile
     * all the queued assets separately, production mode will
     * compile them all into one file only.
     *
     * @var bool
     */
    protected $debug = true;

    /**
     * Flag to wheiter we should force assets to be recompiled
     * on each page load.
     *
     * @var bool
     */
    protected $forceRecompile = false;

    /**
     * Array of registered styles.
     *
     * @var array
     */
    protected $styles = array();

    /**
     * Array of registered scripts.
     *
     * @var array
     */
    protected $scripts = array();

    /**
     * Filter mappings by file extension.
     *
     * @var array
     */
    protected $filters = array();

    /**
     * The cache path, relative to the public directory.
     *
     * @var string
     */
    protected $cachePath = 'assets/cache';

    /**
     * Create a new asset manager instance.
     *
     * @param  \Cartalyst\Themes\ThemeBag  $themeBag
     * @param  \Cartalyst\Themes\Views\ViewFinderInterface  $viewFinder
     * @param  \Cartalyst\Themes\Locations\GeneratorInterface  $locationGenerator
     * @return void
     */
    public function __construct(ThemeBag $themeBag, ViewFinderInterface $viewFinder, LocationGeneratorInterface $locationGenerator)
    {
        $this->themeBag          = $themeBag;
        $this->locationGenerator = $locationGenerator;
        $this->viewFinder        = $viewFinder;
    }

    /**
     * Returns the actual path to an asset based on the key provided,
     * by resolving to the correct location in the correct theme.
     *
     * @param  string  $key
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getPath($key)
    {
        list($section, $relativePath, $extension) = with(new NamespacedItemResolver)->parseKey($key);

        // If we have a section, let's check if it's a namespace
        // or a package section
        if (isset($section)) {
            // Namespace
            if (in_array($section, $this->viewFinder->getNamespaces())) {
                $paths = $this->themeBag->getCascadedNamespaceAssetPaths($section);
            }

            // Package
            else {
                $paths = $this->themeBag->getCascadedPackageAssetPaths($section);
            }
        }

        // Otherwise we'll just find assets for the theme itself without
        // the categorization of a section
        else {
            $paths = $this->themeBag->getCascadedAssetPaths();
        }

        // Now loop through the available paths to see if the asset
        // exists. If it does, we'll return the file.
        foreach ($paths as $path) {
            $file = rtrim($path, '/').'/'.$relativePath.'.'.$extension;

            if ($this->themeBag->getFilesystem()->exists($file)) {
                return $file;
            }
        }

        return $file;
    }

    /**
     * Returns a URL for the asset with the given key.
     *
     * @param  string  $key
     * @return string
     */
    public function getUrl($key)
    {
        $path = $this->getPath($key);

        return $this->locationGenerator->getPathUrl($path);
    }

    /**
     * Queues an asset with the asset manager. The slug is a human-friendly
     * version of the asset and is unique to the asset type.
     *
     * The key is the key of the asset in the current theme, much like loading
     * views and dependencies are an array of slug's which this asset
     * requires to be loaded first.
     *
     * @param  string  $slug
     * @param  string  $key
     * @param  array   $dependencies
     * @return void
     * @throws \InvalidArgumentException
     */
    public function queue($slug, $key, $dependencies = array())
    {
        switch ($extension = pathinfo($key, PATHINFO_EXTENSION)) {
            case 'css':
            case 'less':
            case 'sass':
            case 'scss':
                $type = 'styles';
                break;

            case 'js':
            case 'coffee':
                $type = 'scripts';
                break;

            default:
                throw new InvalidArgumentException("Cannot parse theme asset with Extension [$extension].");
        }

        $this->{$type}[] = $this->createAsset($slug, $key, (array) $dependencies);
    }

    /**
     * Creates an asset with the provided slug,
     * key and dependencies.
     *
     * @param  string  $slug
     * @param  string  $key
     * @param  array   $dependencies
     * @return \Cartalyst\Themes\Assets\Asset
     */
    public function createAsset($slug, $key, array $dependencies = array())
    {
        $path = $this->getPath($key);

        $asset = new Asset($path);

        $asset->setSlug($slug);
        $asset->setKey($key);
        $asset->setDependencies($dependencies);

        return $asset;
    }

    /**
     * Creates an asset collection from the passed
     * array of assets.
     *
     * @param  array  $assets
     * @return \Assetic\Asset\AssetCollectionInterface
     */
    public function createAssetCollection(array $assets)
    {
        return new AssetCollection($assets);
    }

    /**
     * Returns an array of style assets registered.
     *
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * Returns an array of script assets registered.
     *
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * Returns an array of dependency sorted
     * style assets registered.
     *
     * @return array
     */
    public function getSortedStyles()
    {
        return $this->getSorted('styles');
    }

    /**
     * Returns an array of dependency sorted
     * script assets registered.
     *
     * @return array
     */
    public function getSortedScripts()
    {
        return $this->getSorted('scripts');
    }

    /**
     * Returns an array of dependency sorted
     * assets of the provided type.
     *
     * @param  string  $type
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getSorted($type)
    {
        if (! in_array($type, array('scripts', 'styles'))) {
            throw new InvalidArgumentException("Invalid type [$type] to be sorted.");
        }

        return with(new DependencySorter($this->$type))->sort();
    }

    /**
     * Sorts and compiles all registered styles with
     * the manager and returns the URLs for each one.
     *
     * @return array
     */
    public function getCompiledStyles()
    {
        return $this->getCompiled('styles');
    }

    /**
     * Sorts and compiles all registered scripts with
     * the manager and returns the URLs for each one.
     *
     * @return array
     */
    public function getCompiledScripts()
    {
        return $this->getCompiled('scripts');
    }

    /**
     * Compiles assets of a given type which are registered.
     *
     * @param  string  $type
     * @return array
     */
    public function getCompiled($type)
    {
        // Get an array of separate assets
        $assets = $this->getSorted($type);

        $compileExtension = $this->getCompileFileExtension($type);

        // If we are not in debug mode, we'll
        // convert them to a collection which
        // means they'll all be combined
        if ($this->debug === false) {
            $collection = $this->createAssetCollection($assets);

            // Loop through all assets in collection and add appropriate
            // filters based off the extension each asset has
            foreach ($collection->all() as $asset) {
                if ($extension = pathinfo($asset->getSourcePath(), PATHINFO_EXTENSION)) {
                    foreach ($this->getFilterInstances($extension) as $filter) {
                        $asset->ensureFilter($filter);
                    }
                }
            }

            $assets = array($collection);
        }

        // Specific to non-debug mode, we'll loop through our assets
        // to apply filters to them now.
        else {
            foreach ($assets as $asset) {
                if ($extension = pathinfo($asset->getSourcePath(), PATHINFO_EXTENSION)) {
                    foreach ($this->getFilterInstances($extension) as $filter) {
                        $asset->ensureFilter($filter);
                    }
                }
            }
        }

        $urls = array();

        $cachePath    = $this->locationGenerator->getPublicPath($this->cachePath);
        $cachedAssets = $this->themeBag->getFilesystem()->files($cachePath);

        // Loop through assets to ensure they exist
        // and get their URL to append to our array.
        // This is common to non-debug and debug mode.
        foreach ($assets as $asset) {
            $filename = $this->getCompilePrefix() . $this->getCompileFileName($asset, $compileExtension);

            // If our file does not exist at the given path, we'll
            // compile our asset and put it there.
            $path = $cachePath.'/'.$filename;

            if ($this->forceRecompile or ! $this->themeBag->getFilesystem()->exists($path)) {
                $asset->load();

                preg_match('/.*_/', $filename, $pattern);

                $pattern = head($pattern);

                foreach ($cachedAssets as $cachedAsset) {
                    if (strpos($cachedAsset, $pattern) !== false) {
                        $this->themeBag->getFilesystem()->delete($cachedAsset);
                    }
                }

                $this->themeBag->getFilesystem()->put($path, $asset->dump());
            }

            $urls[] = $this->locationGenerator->getPathUrl($path);
        }

        // Clears all Asset objects for specified type
        $this->clearQueue($type);

        return $urls;
    }

    /**
     * Returns the correct file extension for the given
     * asset type.
     *
     * @param  string  $type
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCompileFileExtension($type)
    {
        switch ($type) {
            case 'styles':
                return 'css';

            case 'scripts':
                return 'js';
        }

        throw new InvalidArgumentException("Invalid type [{$type}] given.");
    }

    /**
     * Builds a compile filename for an asset with the
     * requested extension.
     *
     * @param  \Assetic\Asset\AssetInterface  $asset
     * @param  string  $extension
     * @return string
     */
    public function getCompileFileName(AssetInterface $asset, $extension)
    {
        $cacheKey = '';
        $assetName = '';

        if ($asset instanceof AssetCollectionInterface) {
            switch ($extension) {
                case 'css':
                    $assetName = 'styles';
                    break;

                case 'js':
                default:
                    $assetName = 'scripts';
                    break;
            }

            foreach ($asset->all() as $actualAsset) {
                $cacheKey .= $this->getCacheKey($actualAsset);
            }
        } else {
            $assetName = $asset->getSlug();

            $cacheKey .= $this->getCacheKey($asset);
        }

        if ($values = $asset->getValues()) {
            asort($values);

            $cacheKey .= serialize($values);
        }

        $lastModified = $asset->getLastModified();

        return $assetName.'.'.md5($cacheKey).'_'.$lastModified.".{$extension}";
    }

    /**
     * Returns the cache key for the given filters of an asset.
     *
     * @param  \Assetic\Asset\AssetInterface  $asset
     * @return string  $cacheKey
     * @throws \InvalidArgumentException
     */
    public function getCacheKey(AssetInterface $asset)
    {
        if ($asset instanceof AssetCollectionInterface) {
            throw new InvalidArgumentException('getCacheKey() should only be called on individual assets.');
        }

        $cacheKey  = $asset->getSourceRoot();
        $cacheKey .= $asset->getSourcePath();
        $cacheKey .= $asset->getTargetPath();

        foreach ($asset->getFilters() as $filter) {
            if ($filter instanceof HashableInterface) {
                $cacheKey .= $filter->hash();
            } else {
                $cacheKey .= serialize($filter);
            }
        }

        return $cacheKey;
    }

    /**
     * Adds a filter for the given file extension.
     *
     * @param  string   $extension
     * @param  \Closure  $callback
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addFilter($extension, $callback)
    {
        if (is_string($callback)) {
            $callback = function () use ($callback) {
                return new $callback;
            };
        } elseif (! $callback instanceof Closure) {
            throw new InvalidArgumentException('Invalid callback type ['.gettype($callback).'] provided as filter.');
        }

        $this->filters[$extension][] = $callback;
    }

    /**
     * Returns the filter instances for the given file extension.
     *
     * @param  string  $extension
     * @return array   $instances
     */
    public function getFilterInstances($extension)
    {
        $instances = array();

        // If there are no filters for the given extension
        if (! isset($this->filters[$extension])) {
            return array();
        }

        // Add each callback to the array of filter instances.
        foreach ($this->filters[$extension] as $callback) {
            $instances[] = $callback();
        }

        return $instances;
    }

    /**
     * Sets the cache path.
     *
     * @param  string  $cachePath
     * @return void
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * Set the debug flag.
     *
     * @param  bool  $debug
     * @return void
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Set the for recompile flag.
     *
     * @param  bool  $status
     * @return void
     */
    public function setForceRecompile($status)
    {
        $this->forceRecompile = $status;
    }

    /**
     * Clear all the compiled assets.
     *
     * @param  bool  $clean
     * @return void
     */
    public function clearAssets($clear = false)
    {
        if ($clear) {
            $path = $this->locationGenerator->getPublicPath($this->cachePath);

            $finder = new Finder;
            $finder->in($path);
            $finder->exclude(array('.gitignore'));

            $files = array();
            foreach ($finder->files() as $file) {
                $files[] = $path . DIRECTORY_SEPARATOR . $file->getRelativePathname();
            }

            with(new Filesystem)->remove($files);
        }
    }

    /**
     * Clears queued assets of the provided type.
     *
     * @param  string  $type
     * @return void
     * @throws \InvalidArgumentException
     */
    public function clearQueue($type)
    {
        if (! in_array($type, array('scripts', 'styles'))) {
            throw new InvalidArgumentException("Invalid type [$type] to clear.");
        }

        $this->{$type} = array();
    }

    /**
     * Returns the file prefix.
     *
     * @return string
     */
    protected function getCompilePrefix()
    {
        $theme = $this->themeBag->getActive();

        $area = str_replace(DIRECTORY_SEPARATOR, '.', $theme->getArea());
        $key  = str_replace(DIRECTORY_SEPARATOR, '.', $theme->getKey());

        return "{$area}.{$key}.";
    }
}
