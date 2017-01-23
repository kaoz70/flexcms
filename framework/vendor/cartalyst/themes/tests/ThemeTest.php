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
use Cartalyst\Themes\Theme;
use PHPUnit_Framework_TestCase;

class ThemeTest extends PHPUnit_Framework_TestCase
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

    /**
     * @expectedException RuntimeException
     */
    public function testReadingInvalidJson()
    {
        $bag = $this->getMockThemeBag();
        $bag->getFilesystem()->shouldReceive('get')->with(__DIR__.'/theme.json')->once()->andReturn(<<<JSON
{
	"slug": "foo",
}
JSON
        );

        $theme = new Theme($bag, __DIR__);
    }

    public function testReadingJsonSetsUpTheme()
    {
        $bag = $this->getMockThemeBag();
        $bag->getFilesystem()->shouldReceive('get')->with(__DIR__.'/theme.json')->once()->andReturn(<<<JSON
{
	"slug": "foo::bar/baz",
	"author": "Cartalyst LLC"
}
JSON
        );

        $theme = new Theme($bag, __DIR__);
        $this->assertEquals('foo', $theme->getArea());
        $this->assertEquals('bar/baz', $theme->getKey());
        $this->assertEquals('foo::bar/baz', $theme->getSlug());
        $this->assertEquals(array('author' => 'Cartalyst LLC'), $theme->getAttributes());
    }

    protected function getMockThemeBag()
    {
        $themeBag = m::mock('Cartalyst\Themes\ThemeBag');
        $themeBag->shouldReceive('getFilesystem')->andReturn(m::mock('Illuminate\Filesystem\Filesystem'));
        return $themeBag;
    }
}
