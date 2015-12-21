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
 * @version    3.0.3
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Themes\tests;

use Mockery as m;
use RuntimeException;
use PHPUnit_Framework_TestCase;
use Illuminate\Events\Dispatcher;
use Cartalyst\Themes\ThemePublisher;
use Illuminate\Filesystem\Filesystem;

class ThemePublisherTest extends PHPUnit_Framework_TestCase
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

    public function testGettingThemeSourcePaths()
    {
        $themeBag = $this->getMockThemeBag(false);
        $themeBag->shouldReceive('getFilesystem')->andReturn(new Filesystem);
        $publisher = new ThemePublisher($themeBag);

        $expected = array(
            __DIR__.'/stubs/publisher/source/area1/baz',
            __DIR__.'/stubs/publisher/source/area1/foo/bar',
            __DIR__.'/stubs/publisher/source/baz',
            __DIR__.'/stubs/publisher/source/corge/fred',
        );

        $paths = $publisher->getThemeSourcePaths(__DIR__.'/stubs/publisher/source');

        $this->assertCount(count($expected), $paths);
        $this->assertEquals(implode(PHP_EOL, $expected), implode(PHP_EOL, $paths));
    }

    public function testExtractingSlugsFromPaths()
    {
        $themeBag = $this->getMockThemeBag();
        $publisher = new ThemePublisher($themeBag);

        $mappings = array(
            'area1/baz'     => array('area1::baz', 'area1/baz'),
            'area1/foo/bar' => array('area1::foo/bar'),
            'baz'           => array('baz'),
            'corge/fred'    => array('corge::fred', 'corge/fred'),
        );

        foreach ($mappings as $path => $expected) {
            $this->assertCount(count($expected), $slugs = $publisher->extractSlugsFromPath($path));
            $this->assertEquals(implode(PHP_EOL, $expected), implode(PHP_EOL, $slugs));
        }
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExtractingSlugsScenario1()
    {
        $themeBag = m::mock('Cartalyst\Themes\ThemeBag');
        $themeBag->shouldReceive('getFilesystem')->andReturn(m::mock('Illuminate\Filesystem\Filesystem'));
        $themeBag->shouldReceive('getAreaDepth')->andReturn(2);
        $themeBag->shouldReceive('getMaxThemeDepth')->andReturn(1);
        $publisher = new ThemePublisher($themeBag);

        $this->assertEquals(array('bar'), $publisher->extractSlugsFromPath('bar'));
        $this->assertEquals(array('bar/baz::corge'), $publisher->extractSlugsFromPath('bar/baz/corge'));

        // In this scenario, the area depth is "2" and the theme depth
        // is "1". Therefore, the path must be either 1 or 3 segments
        // long, as 2 segments means the area could be fulfilled but
        // there would be no theme path left over. An exception should be thrown
        $publisher->extractSlugsFromPath('foo/bar');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExtractingSlugsScenario2()
    {
        $themeBag = m::mock('Cartalyst\Themes\ThemeBag');
        $themeBag->shouldReceive('getFilesystem')->andReturn(m::mock('Illuminate\Filesystem\Filesystem'));
        $themeBag->shouldReceive('getAreaDepth')->andReturn(1);
        $themeBag->shouldReceive('getMaxThemeDepth')->andReturn(3);
        $publisher = new ThemePublisher($themeBag);

        $this->assertEquals(array('foo::bar/baz/corge'), $publisher->extractSlugsFromPath('foo/bar/baz/corge'));
        $this->assertEquals(array('foo::bar/baz', 'foo/bar/baz'), $publisher->extractSlugsFromPath('foo/bar/baz'));

        // Because our area depth is "1" and the max theme depth
        // is "3", the total segments is 4. We've got too many.
        $publisher->extractSlugsFromPath('foo/bar/baz/corge/fred');
    }

    public function testGettingThemeForSlugs()
    {
        $themeBag = $this->getMockThemeBag();
        $publisher = new ThemePublisher($themeBag);

        $slugs = array('foo/bar', 'baz/corge');

        $themeBag->shouldReceive('ensureRegistered')->with($slugs[0])->once()->andThrow(new RuntimeException);
        $themeBag->shouldReceive('ensureRegistered')->with($slugs[1])->once()->andReturn($theme = m::mock('Cartalyst\Themes\ThemeInterface'));

        $this->assertEquals($theme, $publisher->getThemeForSlugs($slugs));
    }

    public function testGettingThemePaths()
    {
        $themeBag = $this->getMockThemeBag(false);
        $themeBag->shouldReceive('getFilesystem')->andReturn(new Filesystem);
        $publisher = new ThemePublisher($themeBag);

        $expected = array(
            __DIR__.'/stubs/publisher/theme/assets',
            __DIR__.'/stubs/publisher/theme/namespaces/corge/views',
            __DIR__.'/stubs/publisher/theme/namespaces/foo/bar/assets',
            __DIR__.'/stubs/publisher/theme/namespaces/foo/bar/views',
            __DIR__.'/stubs/publisher/theme/packages/qux/grault/views',
            __DIR__.'/stubs/publisher/theme/packages/waldo/assets',
            __DIR__.'/stubs/publisher/theme/packages/waldo/views',
            __DIR__.'/stubs/publisher/theme/views',
        );

        $paths = $publisher->getThemePaths(__DIR__.'/stubs/publisher/theme');

        $this->assertCount(count($expected), $paths);
        $this->assertEquals(implode(PHP_EOL, $expected), implode(PHP_EOL, $paths));
    }

    public function testGetCopyMappings()
    {
        $publisher = m::mock('Cartalyst\Themes\ThemePublisher[getThemePaths]', [$this->getMockThemeBag()]);

        $theme = m::mock('Cartalyst\Themes\ThemeInterface');

        // Paths
        $theme->shouldReceive('getNamespaceAssetsPath')->with('foo')->once()->andReturn('theme/namespaces/foo/assets');
        $theme->shouldReceive('getNamespaceViewsPath')->with('foo')->once()->andReturn('theme/namespaces/foo/views');
        $theme->shouldReceive('getPackageAssetsPath')->with('bar')->once()->andReturn('theme/extensions/bar/assets');
        $theme->shouldReceive('getPackageViewsPath')->with('bar')->once()->andReturn('theme/extensions/bar/views');
        $theme->shouldReceive('getPackageAssetsPath')->with('baz/qux')->once()->andReturn('theme/extensions/baz/qux/assets');
        $theme->shouldReceive('getPackageViewsPath')->with('baz/qux')->once()->andReturn('theme/extensions/baz/qux/views');
        $theme->shouldReceive('getAssetsPath')->once()->andReturn('theme/assets');
        $theme->shouldReceive('getViewsPath')->once()->andReturn('theme/views');

        $expected = array(
            ($source = __DIR__).'/assets'     => 'theme/assets',
            "$source/namespaces/foo/assets"   => 'theme/namespaces/foo/assets',
            "$source/namespaces/foo/views"    => 'theme/namespaces/foo/views',
            "$source/packages/bar/assets"     => 'theme/extensions/bar/assets',
            "$source/packages/bar/views"      => 'theme/extensions/bar/views',
            "$source/packages/baz/qux/assets" => 'theme/extensions/baz/qux/assets',
            "$source/packages/baz/qux/views"  => 'theme/extensions/baz/qux/views',
            "$source/views"                   => 'theme/views',
        );

        $publisher->shouldReceive('getThemePaths')->once()->andReturn(array_keys($expected));

        $this->assertCount(count($expected), $mappings = $publisher->getCopyMappings($source, $theme));
        $this->assertEquals(implode(PHP_EOL, array_keys($expected)), implode(PHP_EOL, array_keys($mappings)));
        $this->assertEquals(implode(PHP_EOL, array_values($expected)), implode(PHP_EOL, array_values($mappings)));
    }

    public function testNotes()
    {
        $themeBag = $this->getMockThemeBag();
        $publisher = new ThemePublisher($themeBag);

        $publisher->setDispatcher($dispatcher = new Dispatcher);
        $publisher->noting(function ($note) {
            $_SERVER['__theme.publisher.note'] = $note;
        });

        $publisher->note('foo', 'info');
        $this->assertTrue(isset($_SERVER['__theme.publisher.note']));
        $this->assertEquals('<info>foo</info>', $_SERVER['__theme.publisher.note']);
        unset($_SERVER['__theme.publisher.note']);
    }

    protected function getMockThemeBag($mockFilesystem = true)
    {
        $themeBag = m::mock('Cartalyst\Themes\ThemeBag');
        $themeBag->shouldReceive('getAreaDepth')->andReturn(1);
        $themeBag->shouldReceive('getMaxThemeDepth')->andReturn(2);
        $themeBag->shouldReceive('getMaxSectionDepth')->andReturn(2);

        if ($mockFilesystem === true) {
            $themeBag->shouldReceive('getFilesystem')->andReturn(m::mock('Illuminate\Filesystem\Filesystem'));
        }

        return $themeBag;
    }
}
