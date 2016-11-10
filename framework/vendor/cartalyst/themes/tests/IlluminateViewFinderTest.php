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

namespace Cartalyst\Themes\tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Illuminate\Filesystem\Filesystem;
use Cartalyst\Themes\Views\IlluminateViewFinder;

class IlluminateViewFinderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup resources and dependencies.
     *
     * @return void
     */
    public function setUp()
    {
    }

    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testNativeLoadingIsTriggeredIfTheThemeBagIsNotSet()
    {
        $finder = new IlluminateViewFinder($filesystem = m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__));
        $filesystem->shouldReceive('exists')->with($file = __DIR__.'/foo.blade.php')->once()->andReturn(true);
        $this->assertEquals($file, $finder->find('foo'));
    }

    public function testNamespacesOverridePackages()
    {
        $finder = new IlluminateViewFinder($filesystem = m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__));
        $finder->setThemeBag($bag = $this->getMockThemeBag());
        $finder->addNamespace('foo', __DIR__.'/namespaces/foo');

        $bag->shouldReceive('getCascadedNamespaceViewPaths')->with('foo')->once()->andReturn($paths = array('foo/1', 'foo/2'));
        $filesystem->shouldReceive('exists')->with('foo/1/bar.blade.php')->once()->andReturn(false);
        $filesystem->shouldReceive('exists')->with('foo/1/bar.php')->once()->andReturn(false);
        $filesystem->shouldReceive('exists')->with($path = 'foo/2/bar.blade.php')->once()->andReturn(true);

        // Because we use mocks, we know the package method calls are ever made
        $this->assertEquals($path, $finder->find('foo::bar'));
    }

    public function testPackageViews()
    {
        $finder = new IlluminateViewFinder($filesystem = m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__));
        $finder->setThemeBag($bag = $this->getMockThemeBag());

        $bag->shouldReceive('getCascadedPackageViewPaths')->with('foo')->once()->andReturn($paths = array('foo/1', 'foo/2'));
        $filesystem->shouldReceive('exists')->with('foo/1/bar.blade.php')->once()->andReturn(false);
        $filesystem->shouldReceive('exists')->with('foo/1/bar.php')->once()->andReturn(false);
        $filesystem->shouldReceive('exists')->with($path = 'foo/2/bar.blade.php')->once()->andReturn(true);

        $this->assertEquals($path, $finder->find('foo::bar'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonExistentViewThrowsException()
    {
        $finder = new IlluminateViewFinder($filesystem = m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__));
        $finder->setThemeBag($bag = $this->getMockThemeBag());

        $bag->shouldReceive('getCascadedPackageViewPaths')->with('foo')->once()->andReturn($paths = array('foo/1'));
        $filesystem->shouldReceive('exists')->with('foo/1/bar.blade.php')->once()->andReturn(false);
        $filesystem->shouldReceive('exists')->with('foo/1/bar.php')->once()->andReturn(false);

        $bag->shouldReceive('getActive')->once()->andReturn($active = m::mock('Cartalyst\Themes\ThemeInterface'));
        $active->shouldReceive('getSlug')->once()->andReturn('foo/bar');
        $active->shouldReceive('getParentSlug')->once()->andReturn('foo/baz');
        $bag->shouldReceive('getFallback')->once()->andReturn($fallback = m::mock('Cartalyst\Themes\ThemeInterface'));
        $fallback->shouldReceive('getSlug')->once()->andReturn('foo/corge');

        $finder->find('foo::bar');
    }

    protected function getMockThemeBag()
    {
        $themeBag = m::mock('Cartalyst\Themes\ThemeBag');
        $themeBag->shouldReceive('getFilesystem')->andReturn(m::mock('Illuminate\Filesystem\Filesystem'));
        return $themeBag;
    }
}
