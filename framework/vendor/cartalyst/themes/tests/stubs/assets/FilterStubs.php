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

class FilterStubEmpty
{
}
class FilterStub implements Assetic\Filter\FilterInterface
{
    /**
     * Filters an asset after it has been loaded.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterLoad(Assetic\Asset\AssetInterface $asset)
    {
        $_SERVER['__filter.stub.load'] = true;
    }

    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterDump(Assetic\Asset\AssetInterface $asset)
    {
        $_SERVER['__filter.stub.load'] = true;
    }
}
