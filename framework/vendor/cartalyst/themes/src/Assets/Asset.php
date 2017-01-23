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

namespace Cartalyst\Themes\Assets;

use Assetic\Asset\FileAsset;
use Cartalyst\Dependencies\DependentInterface;

class Asset extends FileAsset implements DependentInterface
{
    /**
     * The slug of the theme asset, used to identify it
     * easily for when it is a dependency for another asset.
     *
     * @var string
     */
    protected $slug;

    /**
     * The key of the asset, which is the encoded string representing
     * the location of the asset in the theme.
     *
     * @var string
     */
    protected $key;

    /**
     * Array of dependencies for the given asset.
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Get the alias for the asset.
     *
     * @deprecated since 2.0.0
     * @return string
     */
    public function getAlias()
    {
        return $this->getSlug();
    }

    /**
     * Set the alias for the asset.
     *
     * @deprecated since 2.0.0
     * @param  string  $alias
     * @return void
     */
    public function setAlias($alias)
    {
        $this->setSlug($alias);
    }

    /**
     * Get the slug for the asset.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the slug for the asset.
     *
     * @param  string  $slug
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get the key for the asset.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the key for the asset.
     *
     * @param  string  $key
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the dependencies for the asset.
     *
     * @return string
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Set the dependencies for the asset.
     *
     * @param  array  $dependencies
     * @return void
     */
    public function setDependencies(array $dependencies)
    {
        $this->dependencies = $dependencies;
    }
}
