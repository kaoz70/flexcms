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

namespace Cartalyst\Themes;

interface ThemeInterface
{
    /**
     * Creates a new theme instance.
     *
     * @param  \Cartalyst\Themes\ThemeBag  $themeBag
     * @param  string  $path
     * @return void
     */
    public function __construct(ThemeBag $themeBag, $path);

    /**
     * Returns the theme slug, which is the area and the key of the theme.
     *
     * Format is as follows:
     *
     * - Theme in area (prepend the theme key with the area name and "::"):
     *    "my-area::vendor/theme"
     *
     * - Theme out of/without an area (matches the theme's key):
     *    "vendor/theme"
     *
     * @return string
     */
    public function getSlug();

    /**
     * Returns the key for the theme, which is the unique
     * identifier for the theme within it's area.
     *
     * @return string
     */
    public function getKey();

    /**
     * Returns the area for the theme, if any.
     *
     * @return string|null
     */
    public function getArea();

    /**
     * Returns the parent slug for the theme.
     *
     * @return string
     */
    public function getParentSlug();

    /**
     * Get the fully qualified location of the view.
     *
     * @return string
     */
    public function getPath();

    /**
     * Get the packages path for the theme.
     *
     * @return string
     */
    public function getPackagesPath();

    /**
     * Get the namespaces path for the theme.
     *
     * @return string
     */
    public function getNamespacesPath();

    /**
     * Returns the path for a package.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackagePath($package);

    /**
     * Returns the path for a namespace.
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespacePath($namespace);

    /**
     * Returns the views path for the theme.
     *
     * @return string
     */
    public function getViewsPath();

    /**
     * Returns the views path for a package.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackageViewsPath($package);

    /**
     * Returns the views path for a namespace.
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespaceViewsPath($namespace);

    /**
     * Returns the assets path for the theme.
     *
     * @return string
     */
    public function getAssetsPath();

    /**
     * Returns the assets path for a package.
     *
     * @param  string  $package
     * @return string
     */
    public function getPackageAssetsPath($package);

    /**
     * Returns the views path for a namespace,
     *
     * @param  string  $namespace
     * @return string
     */
    public function getNamespaceAssetsPath($namespace);
}
