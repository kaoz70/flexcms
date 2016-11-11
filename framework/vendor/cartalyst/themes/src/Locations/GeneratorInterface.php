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

namespace Cartalyst\Themes\Locations;

interface GeneratorInterface
{
    /**
     * Returns a path relative to the public directory.
     *
     * @param  string  $relativePath
     * @return strin
     */
    public function getPublicPath($relativePath);

    /**
     * Returns the URL for the relative URI.
     *
     * @param  string  $uri
     * @return string
     */
    public function getUrl($uri);

    /**
     * Returns the corresponding URL for the given fully qualified path.
     * If a URL cannot be determined a Runtime Exception is thrown.
     *
     * @param  string  $path
     * @return string
     * @throws \RuntimeException
     */
    public function getPathUrl($path);
}
