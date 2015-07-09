<?php namespace Cartalyst\Api\Tests;
/**
 * Part of the API package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    API
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Mockery as m;
use Cartalyst\Api\Routing\Router;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Route;
use PHPUnit_Framework_TestCase;

class RouterTest extends PHPUnit_Framework_TestCase {

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testParsingRouteCallsCorrectMethods()
	{
		$router = new Router($events = new Dispatcher, $container = m::mock('Illuminate\Container\Container'));
		$router->setUriParser($uriParser = m::mock('Cartalyst\Api\UriParser'));

		$route = m::mock('Illuminate\Routing\Route');

		$route->shouldReceive('getPattern')->once()->andReturn('foo');
		$uriParser->shouldReceive('parseRoutePattern')->with('foo')->once()->andReturn(array('bar'));
		$uriParser->shouldReceive('convertParsedUriArrayToString')->with(array('bar'))->once()->andReturn('baz');
		$route->shouldReceive('setPattern')->with('baz')->once();

		$router->parseRoute($route);
	}

	public function testSettingRoutesCollection()
	{
		$router = new Router($events = new Dispatcher, $container = m::mock('Illuminate\Container\Container'));
		$router->setUriParser($uriParser = m::mock('Cartalyst\Api\UriParser'));

		$router->setRoutes($routes = new RouteCollection);
		$this->assertEquals($routes, $router->getRoutes());
	}

}
