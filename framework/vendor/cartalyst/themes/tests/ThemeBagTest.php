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
use Cartalyst\Themes\ThemeBag;
use PHPUnit_Framework_TestCase;
use Illuminate\Filesystem\Filesystem;

class ThemeBagTest extends PHPUnit_Framework_TestCase
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

    /**
     * @expectedException RuntimeException
     */
    public function testRegisteringNonExistentTheme()
    {
        $bag = new ThemeBag($filesystem = m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__));
        $filesystem->shouldReceive('isDirectory')->with($path = __DIR__.'/foo/bar')->once()->andReturn(false);
        $bag->register('foo/bar');
    }

    public function testRegistering()
    {
        $bag = m::mock('Cartalyst\Themes\ThemeBag[createTheme]', [$filesystem = m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__)]);

        // Theme instance
        $theme1 = m::mock('Cartalyst\Themes\ThemeInterface');
        $theme1->shouldReceive('getSlug')->once()->andReturn('foo/bar');
        $this->assertEquals($theme1, $bag->register($theme1));
        $this->assertEquals($theme1, $bag['foo/bar']);

        // Physical path
        $bag->shouldReceive('createTheme')->with('foo')->once()->andReturn($theme2 = m::mock('Cartalyst\Themes\ThemeInterface'));
        $theme2->shouldReceive('getSlug')->once()->andReturn('bar::baz/bat');
        $this->assertEquals($theme2, $bag->register('path: foo'));
        $this->assertEquals($theme2, $bag['bar::baz/bat']);

        // Slug
        $filesystem->shouldReceive('isDirectory')->with($path = __DIR__.'/foo/bar')->once()->andReturn(true);
        $bag->shouldReceive('createTheme')->with($path)->once()->andReturn($theme3 = m::mock('Cartalyst\Themes\ThemeInterface'));
        $theme3->shouldReceive('getSlug')->once()->andReturn('foo/bar');
        $this->assertEquals($theme3, $bag->register('foo/bar'));
    }

    public function testManipulatingActive()
    {
        $bag = new ThemeBag($filesystem = new Filesystem, array(__DIR__));
        $active = m::mock('Cartalyst\Themes\ThemeInterface');
        $active->shouldReceive('getSlug')->times(3)->andReturn('foo/bar');
        $bag->setActive($active);
        $this->assertEquals($active, $bag['foo/bar']);
        $this->assertEquals($active, $bag->getActive());
    }

    public function testManipulatingFallback()
    {
        $bag = new ThemeBag($filesystem = new Filesystem, array(__DIR__));
        $fallback = m::mock('Cartalyst\Themes\ThemeInterface');
        $fallback->shouldReceive('getSlug')->times(3)->andReturn('foo/bar');
        $bag->setFallback($fallback);
        $this->assertEquals($fallback, $bag['foo/bar']);
        $this->assertEquals($fallback, $bag->getFallback());
    }

    public function testGettingCascadedPaths()
    {
        $theme1 = m::mock('Cartalyst\Themes\ThemeInterface');
        $theme1->shouldReceive('getSlug')->atLeast(1)->andReturn('foo/bar');
        $theme1->shouldReceive('getViewsPath')->once()->andReturn(__DIR__.'/foo/bar/views');
        $theme1->shouldReceive('getParentSlug')->once()->andReturn('foo/baz');

        $theme2 = m::mock('Cartalyst\Themes\ThemeInterface');
        $theme2->shouldReceive('getSlug')->atLeast(1)->andReturn('foo/baz');
        $theme2->shouldReceive('getViewsPath')->once()->andReturn(__DIR__.'/foo/baz/views');
        $theme2->shouldReceive('getParentSlug')->once()->andReturn('foo/corge');

        $theme4 = m::mock('Cartalyst\Themes\ThemeInterface');
        $theme4->shouldReceive('getSlug')->atLeast(1)->andReturn('foo/corge');
        $theme4->shouldReceive('getViewsPath')->twice()->andReturn(__DIR__.'/foo/corge/views');

        $bag = new ThemeBag($filesystem = new Filesystem, array(__DIR__));
        $bag->setActive($theme1);
        $bag->register($theme2);
        $bag->setFallback($theme4);

        $this->assertCount(3, $paths = $bag->getCascadedViewPaths());
        $this->assertEquals(
            __DIR__.'/foo/bar/views'.
            __DIR__.'/foo/baz/views'.
            __DIR__.'/foo/corge/views',
        implode('', $paths));

        $theme4->shouldReceive('getParentSlug')->once();

        $this->assertCount(1, $paths = $bag->getCascadedViewPaths($theme4));
        $this->assertEquals(__DIR__.'/foo/corge/views', reset($paths));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRegisteringThemeWithInvalidAreaDepth()
    {
        $bag = new ThemeBag($filesystem = new Filesystem, array(__DIR__));
        $bag->register('foo/bar::baz/bat');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRegisteringThemeWithInvalidThemeDepth()
    {
        $bag = new ThemeBag($filesystem = new Filesystem, array(__DIR__));
        $bag->register('bar::baz/bat/corge');
    }
}
