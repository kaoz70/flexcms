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
use Cartalyst\Api\Auth\SentryAuth;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use PHPUnit_Framework_TestCase;
use stdClass;

class SentryAuthTest extends PHPUnit_Framework_TestCase {

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testCatchingUserBannedException()
	{
		$auth = new SentryAuth($sentry = m::mock('Cartalyst\Sentry\Sentry'));
		$request = m::mock('Illuminate\Http\Request');

		$request->shouldReceive('getUser')->once()->andReturn('foo');
		$request->shouldReceive('getPassword')->once()->andReturn('bar');

		$sentry->shouldReceive('authenticate')->with(array(
			'login'    => 'foo',
			'password' => 'bar',
		))->once()->andThrow(new UserBannedException);

		$this->assertFalse($auth->authenticate($request));
	}

	public function testCatchingUserSuspendedException()
	{
		$auth = new SentryAuth($sentry = m::mock('Cartalyst\Sentry\Sentry'));
		$request = m::mock('Illuminate\Http\Request');

		$request->shouldReceive('getUser')->once()->andReturn('foo');
		$request->shouldReceive('getPassword')->once()->andReturn('bar');

		$sentry->shouldReceive('authenticate')->with(array(
			'login'    => 'foo',
			'password' => 'bar',
		))->once()->andThrow(new UserSuspendedException);

		$this->assertFalse($auth->authenticate($request));
	}

	public function testCatchingLoginRequiredException()
	{
		$auth = new SentryAuth($sentry = m::mock('Cartalyst\Sentry\Sentry'));
		$request = m::mock('Illuminate\Http\Request');

		$request->shouldReceive('getUser')->once()->andReturn('foo');
		$request->shouldReceive('getPassword')->once()->andReturn('bar');

		$sentry->shouldReceive('authenticate')->with(array(
			'login'    => 'foo',
			'password' => 'bar',
		))->once()->andThrow(new LoginRequiredException);

		$this->assertFalse($auth->authenticate($request));
	}

	public function testCatchingPasswordRequiredException()
	{
		$auth = new SentryAuth($sentry = m::mock('Cartalyst\Sentry\Sentry'));
		$request = m::mock('Illuminate\Http\Request');

		$request->shouldReceive('getUser')->once()->andReturn('foo');
		$request->shouldReceive('getPassword')->once()->andReturn('bar');

		$sentry->shouldReceive('authenticate')->with(array(
			'login'    => 'foo',
			'password' => 'bar',
		))->once()->andThrow(new PasswordRequiredException);

		$this->assertFalse($auth->authenticate($request));
	}

	public function testCatchingUserNotFoundException()
	{
		$auth = new SentryAuth($sentry = m::mock('Cartalyst\Sentry\Sentry'));
		$request = m::mock('Illuminate\Http\Request');

		$request->shouldReceive('getUser')->once()->andReturn('foo');
		$request->shouldReceive('getPassword')->once()->andReturn('bar');

		$sentry->shouldReceive('authenticate')->with(array(
			'login'    => 'foo',
			'password' => 'bar',
		))->once()->andThrow(new UserNotFoundException);

		$this->assertFalse($auth->authenticate($request));
	}

	public function testAuthenticating()
	{
		$auth = new SentryAuth($sentry = m::mock('Cartalyst\Sentry\Sentry'));
		$request = m::mock('Illuminate\Http\Request');

		$request->shouldReceive('getUser')->once()->andReturn('foo');
		$request->shouldReceive('getPassword')->once()->andReturn('bar');

		$sentry->shouldReceive('authenticate')->with(array(
			'login'    => 'foo',
			'password' => 'bar',
		))->once()->andReturn(new stdClass);

		$this->assertTrue($auth->authenticate($request));
	}

}
