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
use Cartalyst\Api\FilterHandler;
use Cartalyst\Api\Http\InternalRequest;
use Cartalyst\Api\Http\Response as ApiResponse;
use Cartalyst\Api\Routing\Router;
use Cartalyst\Api\UriParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit_Framework_TestCase;

class FilterHandlerTest extends PHPUnit_Framework_TestCase {

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testAuthorizeFilterOnlyAppliesToApiRequests()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$route = m::mock('Illuminate\Routing\Route');
		$request = m::mock('Illuminate\Http\Request');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('not_api');

		$this->assertNull($handler->handleAuthorizeFilter($route, $request));
	}

	public function testAuthorizeFilterDoesNotApplyToInternalRequests()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$route = m::mock('Illuminate\Routing\Route');
		$request = m::mock('Cartalyst\Api\Http\InternalRequest');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('api');

		$this->assertNull($handler->handleAuthorizeFilter($route, $request));
	}

	public function testAuthorizeFilterReturnsCorrectResponseForFailedAuth()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$route = m::mock('Illuminate\Routing\Route');
		$request = m::mock('Illuminate\Http\Request');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('api');

		$auth->shouldReceive('authenticate')->with($request)->once()->andReturn(false);

		$this->assertInstanceOf('Cartalyst\Api\Http\Response', $response = $handler->handleAuthorizeFilter($route, $request));
		$this->assertEquals(401, $response->getStatusCode());
	}

	public function testAuthorizeFilterForSuccessfulAuthentication()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$route = m::mock('Illuminate\Routing\Route');
		$request = m::mock('Illuminate\Http\Request');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('api');

		$auth->shouldReceive('authenticate')->with($request)->once()->andReturn(true);

		$this->assertNull($handler->handleAuthorizeFilter($route, $request));
	}

	public function testResponseFilterOnlyAppliesToApiRequests()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$request  = m::mock('Illuminate\Http\Request');
		$response = m::mock('Cartalyst\Api\Http\Response');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('not_api');

		$this->assertEquals($response, $handler->handleResponseFilter($request, $response));
	}

	public function testResponseFilterDoesNotApplyToInternalRequests()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$request  = m::mock('Cartalyst\Api\Http\InternalRequest');
		$response = m::mock('Cartalyst\Api\Http\Response');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('api');

		$this->assertEquals($response, $handler->handleResponseFilter($request, $response));
	}

	public function testResponseFilterDoesNotApplyToNonApiResponses()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface')
		);

		$request  = m::mock('Illuminate\Http\Request');
		$response = m::mock('Symfony\Component\HttpFoundation\Response');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('api');

		$this->assertEquals($response, $handler->handleResponseFilter($request, $response));
	}

	public function testResponseFilterEncodesContent()
	{
		$handler = new FilterHandler(
			$router     = m::mock('Illuminate\Routing\Router'),
			$redirector = m::mock('Illuminate\Routing\Redirector'),
			$uriParser  = m::mock('Cartalyst\Api\UriParser'),
			$auth       = m::mock('Cartalyst\Api\Auth\AuthInterface'),
			JSON_NUMERIC_CHECK|JSON_FORCE_OBJECT
		);

		$request  = m::mock('Illuminate\Http\Request');
		$response = m::mock('Cartalyst\Api\Http\Response');

		$uriParser->shouldReceive('getUriNamespace')->once()->andReturn('api');
		$request->shouldReceive('path')->once()->andReturn('api');

		$response->shouldReceive('encodeContent')->with(JSON_NUMERIC_CHECK|JSON_FORCE_OBJECT)->once();

		$this->assertEquals($response, $handler->handleResponseFilter($request, $response));
	}

}
