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

namespace Cartalyst\Themes\Locations;

use RuntimeException;
use Illuminate\Routing\UrlGenerator;

class IlluminateGenerator implements GeneratorInterface
{
    /**
     * The URL generator which generates the URLs.
     *
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * The fully qualified public path.
     *
     * @var string
     */
    protected $publicPath;

    /**
     * Create a new Illuminate location generator instance.
     *
     * @param  \Illuminate\Routing\UrlGenerator  $urlGenerator
     * @param  string  $publicPath
     * @return void
     */
    public function __construct(UrlGenerator $urlGenerator, $publicPath)
    {
        $this->urlGenerator = $urlGenerator;
        $this->publicPath   = $this->realPath($publicPath);
    }

    /**
     * Returns a path relative to the public directory.
     *
     * @param  string  $relativePath
     * @return strin
     */
    public function getPublicPath($relativePath)
    {
        return $this->realPath($this->publicPath.'/'.$relativePath);
    }

    /**
     * Returns the URL for the relative URI.
     *
     * @param  string  $uri
     * @return string
     */
    public function getUrl($uri)
    {
        return $this->urlGenerator->to($this->removeWindowsSeparator($uri));
    }

    /**
     * Returns the corresponding URL for the given fully qualified path.
     * If a URL cannot be determined a Runtime Exception is thrown.
     *
     * @param  string  $path
     * @return string
     * @throws \RuntimeException
     */
    public function getPathUrl($path)
    {
        $path = $this->stripPublicPath($path);

        return $this->urlGenerator->asset($this->removeWindowsSeparator($path));
    }

    /**
     * Strips the public path out of the given path, so it is safe to
     * be used in URLs. If the public path cannot be extracted, an
     * Exception will be thrown.
     *
     * @param  string  $path
     * @return string
     * @throws \RuntimeException
     */
    protected function stripPublicPath($path)
    {
        if (starts_with($realPath = $this->realPath($path), $this->publicPath)) {
            return ltrim(str_replace($this->publicPath, '', $realPath), '/\\');
        }

        throw new RuntimeException("Path [{$path}] must be located in public path [{$this->publicPath}] in order to strip it.");
    }

    /**
     * Returns the real path to the given path.
     *
     * @param  string  $path
     * @return string
     */
    protected function realPath($path)
    {
        $path = $this->sanitizePath($path);

        if ($realpath = realpath($path)) {
            return $realpath;
        }

        return $path;
    }

    /**
     * Sanitizes the path to remove relative references.
     *
     * @param  string  $path
     * @return string  $path
     */
    public function sanitizePath($path)
    {
        $path = str_replace('/./', '/', $path);

        // Strip out any relative paths ("foo/bar/../baz")
        do {
            $path = preg_replace('@/[^/]+/\\.\\./@', '/', $path, 1, $changed);
        } while ($changed);

        return $path;
    }

    /**
     * Removes windows separators from a path, safe for URLs.
     *
     * @param  string  $path
     * @return string
     */
    protected function removeWindowsSeparator($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
}
