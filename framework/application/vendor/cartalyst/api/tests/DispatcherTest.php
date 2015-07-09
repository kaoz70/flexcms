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
use Cartalyst\Api\Http\ApiHttpException;
use Cartalyst\Api\Dispatcher;
use Cartalyst\Api\UriParser;
use Cartalyst\Api\Http\Response as InternalResponse;
use PHPUnit_Framework_TestCase;

class DispatcherTest extends PHPUnit_Framework_TestCase {

	protected $request;

	protected $uriParser;

	protected $router;

	protected $dispatcher;

	/**
	 * Setup resources and dependencies.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->request    = m::mock('Illuminate\Http\Request');
		$this->uriParser  = new UriParser($this->request, 1, 'api');
		$this->router     = m::mock('Cartalyst\Api\Routing\Router');
		$this->dispatcher = new Dispatcher($this->router, $this->request, $this->uriParser);
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

	public function testSimpleGetRequest()
	{
		$dispatcher = m::mock('Cartalyst\Api\Dispatcher[dispatch]');
		$dispatcher->shouldReceive('dispatch')->with('foo/bar', 'GET', array(), null)->once();

		$dispatcher->get('foo/bar');
	}

	public function testSimpleHeadRequest()
	{
		$dispatcher = m::mock('Cartalyst\Api\Dispatcher[dispatch]');
		$dispatcher->shouldReceive('dispatch')->with('foo/bar', 'HEAD', array(), null)->once();

		$dispatcher->head('foo/bar');
	}

	public function testSimpleDeleteRequest()
	{
		$dispatcher = m::mock('Cartalyst\Api\Dispatcher[dispatch]');
		$dispatcher->shouldReceive('dispatch')->with('foo/bar', 'DELETE', array(), null)->once();

		$dispatcher->delete('foo/bar');
	}

	public function testSimplePostRequest()
	{
		$dispatcher = m::mock('Cartalyst\Api\Dispatcher[dispatch]');
		$dispatcher->shouldReceive('dispatch')->with('foo/bar', 'POST', array(), null)->once();

		$dispatcher->post('foo/bar');
	}

	public function testSimplePutRequest()
	{
		$dispatcher = m::mock('Cartalyst\Api\Dispatcher[dispatch]');
		$dispatcher->shouldReceive('dispatch')->with('foo/bar', 'PUT', array(), null)->once();

		$dispatcher->put('foo/bar');
	}

	public function testSimplePatchRequest()
	{
		$dispatcher = m::mock('Cartalyst\Api\Dispatcher[dispatch]');
		$dispatcher->shouldReceive('dispatch')->with('foo/bar', 'PATCH', array(), null)->once();

		$dispatcher->patch('foo/bar');
	}

	public function testRequestIsProperlyCreated()
	{
		$this->request->cookies = m::mock('Symfony\Component\HttpFoundation\ParameterBag');
		$this->request->files   = m::mock('Symfony\Component\HttpFoundation\FileBag');
		$this->request->server  = m::mock('Symfony\Component\HttpFoundation\ServerBag');

		$this->request->cookies->shouldReceive('all')->once()->andReturn(array('__api.dispatcher' => true));
		$this->request->files->shouldReceive('all')->once()->andReturn(array());
		$this->request->server->shouldReceive('all')->once()->andReturn(array('__api.dispatcher' => true));

		$request = $this->dispatcher->createRequest('http://www.foo.com/api/v1/test', 'GET', array('foo' => 'bar', 'baz' => 'qux'));

		$expected = array('__api.dispatcher' => true);

		$cookies = $request->cookies->all();
		$this->assertArrayHasKey('__api.dispatcher', $cookies);
		$this->assertTrue($cookies['__api.dispatcher']);

		$server = $request->server->all();
		$this->assertArrayHasKey('__api.dispatcher', $server);
		$this->assertTrue($server['__api.dispatcher']);

		$expected = array(
			'foo' => 'bar',
			'baz' => 'qux',
		);

		$this->assertEquals($expected, $request->input());
	}

	public function testDispatcherThrowsExceptionsForBadInternalResponses1()
	{
		$statusCode = 422;

		try
		{
			$response = new InternalResponse('', $statusCode);
			$this->dispatcher->checkResponse($response);
		}
		catch (ApiHttpException $e)
		{
			if (($receivedStatusCode = $e->getStatusCode()) == $statusCode)
			{
				return;
			}
		}

		$this->fail("Failed checking correct status code \"$statusCode\" was sent with Exception thrown for bad internal request, received \"$receivedStatusCode\".");
	}

	public function testDispatcherThrowsExceptionsForBadInternalResponses2()
	{
		$statusCode = 500;

		try
		{
			$response = new InternalResponse('', $statusCode);
			$this->dispatcher->checkResponse($response);
		}
		catch (ApiHttpException $e)
		{
			if (($receivedStatusCode = $e->getStatusCode()) == $statusCode)
			{
				return;
			}
		}

		$this->fail("Failed checking correct status code \"$statusCode\" was sent with Exception thrown for bad internal request, received \"$receivedStatusCode\".");
	}

	public function testClosureIsCalledBeforeDispatching()
	{
		$response = m::mock('Cartalyst\Api\Http\Response');
		$response->shouldReceive('isSuccessful')->once()->andReturn(true);
		$response->shouldReceive('getContent')->once()->andReturn('success');

		$this->request->shouldReceive('root')->once()->andReturn('http://localhost');

		$this->router->shouldReceive('dispatch')->once()->andReturn($response);

		// Mock parameter bag
		$bag = m::mock('StdClass');
		$bag->shouldReceive('all')->times(3)->andReturn(array());

		$this->request->cookies = $bag;
		$this->request->files   = $bag;
		$this->request->server  = $bag;

		$this->dispatcher->dispatch('foo/bar', 'GET', array(), function()
		{
			$_SERVER['__api.before_send'] = true;
		});

		$this->assertTrue($_SERVER['__api.before_send']);
		unset($_SERVER['__api.before_send']);
	}

}
