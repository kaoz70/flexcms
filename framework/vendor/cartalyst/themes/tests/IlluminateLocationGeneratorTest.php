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
use Cartalyst\Themes\Locations\IlluminateGenerator;

class IlluminateLocationGeneratorTest extends PHPUnit_Framework_TestCase
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

    public function testGettingUrl()
    {
        $locationGenerator = new IlluminateGenerator($urlGenerator = m::mock('Illuminate\Routing\UrlGenerator'), __DIR__);
        $urlGenerator->shouldReceive('to')->with($uri = 'foo?bar=baz')->once()->andReturn($url = 'http://www.example.com');
        $this->assertEquals($url, $locationGenerator->getUrl($uri));
    }

    public function testPathUrl()
    {
        $locationGenerator = new IlluminateGenerator($urlGenerator = m::mock('Illuminate\Routing\UrlGenerator'), __DIR__);
        $urlGenerator->shouldReceive('asset')->with('foo/baz')->once()->andReturn($url = 'http://www.example.com');
        $this->assertEquals($url, $locationGenerator->getPathUrl(__DIR__.'/foo/bar/../baz'));
    }
}
