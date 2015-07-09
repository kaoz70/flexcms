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
use Cartalyst\Api\UriParser;
use PHPUnit_Framework_TestCase;

class UriParserTest extends PHPUnit_Framework_TestCase {

	protected $request;

	protected $auth;

	protected $dispatcher;

	protected $container;

	protected $router;

	/**
	 * Setup resources and dependencies.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->request    = m::mock('Illuminate\Http\Request');
		$this->uriParser = new UriParser($this->request, 1, 'api');
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

	public function testUriArrayIsConvertedAsExpected()
	{
		$uriArray = array(1, 'test/uri/here');
		$expected = 'api/v1/test/uri/here';
		$this->assertEquals($expected, $this->uriParser->convertParsedUriArrayToString($uriArray));

		$uriArray = array(99, 'test/uri/here');
		$expected = 'api/v99/test/uri/here';
		$this->assertEquals($expected, $this->uriParser->convertParsedUriArrayToString($uriArray));

		$uriArray = array(669, '{pattern}/{variables}/{here}');
		$expected = 'api/v669/{pattern}/{variables}/{here}';
		$this->assertEquals($expected, $this->uriParser->convertParsedUriArrayToString($uriArray));
	}

	public function testDispatcherCanParseRelativeUriCorrectly()
	{
		$uri      = 'some/uri/here';
		$expected = array(1, 'some/uri/here');
		$this->assertEquals($expected, $this->uriParser->parseRelativeUri($uri));

		$uri      = 'v2/some/uri/here';
		$expected = array(2, 'some/uri/here');
		$this->assertEquals($expected, $this->uriParser->parseRelativeUri($uri));

		$uri      = 'v29/some/uri/here';
		$expected = array(29, 'some/uri/here');
		$this->assertEquals($expected, $this->uriParser->parseRelativeUri($uri));

		$uri      = 'v07/some/uri/here';
		$expected = array(7, 'some/uri/here');
		$this->assertEquals($expected, $this->uriParser->parseRelativeUri($uri));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testDispatherDoesntTryToParseProperUriAsRelative()
	{
		$this->uriParser->parseRelativeUri('http://www.cartalyst.com');
	}

	public function testFullUriIsCreated()
	{
		$this->request->shouldReceive('root')->andReturn('http://www.foo.com');
		$uri = $this->uriParser->createUri(array(1, 'foo/bar'));
		$this->assertEquals('http://www.foo.com/api/v1/foo/bar', $uri);

		$this->request->shouldReceive('root')->andReturn('http://www.foo.com');
		$uri = $this->uriParser->createUri(array(99, 'baz/qux'));
		$this->assertEquals('http://www.foo.com/api/v99/baz/qux', $uri);
	}

	public function testFullUriParsesAStringAswellAsAnArray()
	{
		$this->request->shouldReceive('root')->andReturn('http://www.foo.com');
		$uri = $this->uriParser->createUri('baz/qux');
		$this->assertEquals('http://www.foo.com/api/v1/baz/qux', $uri);
	}

}
