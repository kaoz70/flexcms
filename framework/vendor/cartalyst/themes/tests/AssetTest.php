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

namespace Cartalyst\Themes\tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Themes\Assets\Asset;

class AssetTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testAssetSlug()
    {
        $asset = new Asset(__FILE__);
        $this->assertNull($asset->getSlug());
        $asset->setSlug('foo');
        $this->assertEquals('foo', $asset->getSlug());
    }

    public function testAssetKey()
    {
        $asset = new Asset(__FILE__);
        $this->assertNull($asset->getKey());
        $asset->setKey('foo');
        $this->assertEquals('foo', $asset->getKey());
    }

    public function testDependencies()
    {
        $asset = new Asset(__FILE__);
        $this->assertEquals(array(), $asset->getDependencies());
        $asset->setDependencies(array('foo', 'bar'));
        $this->assertEquals(array('foo', 'bar'), $asset->getDependencies());
    }
}
