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
use Cartalyst\Api\Http\Response;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;
use PHPUnit_Framework_TestCase;

class ResponseTest extends PHPUnit_Framework_TestCase {

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testAdjustingContent()
	{
		$response = new Response($message = 'My message here');

		$expected = array('message' => $message);
		$this->assertEquals($expected, $response->getContent());
	}

	public function testEncodingSimpleArrayContent()
	{
		$response = new Response($contents = array('foo' => 'bar'));

		$expected = '{"foo":"bar"}';
		$response->encodeContent();

		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
		$this->assertEquals($expected, $response->getContent());
	}

	public function testEncodingJsonableContent()
	{
		$jsonable = new JsonableObject(array('foo' => 'bar', 'baz' => array('qux')));

		$response = new Response($jsonable);
		$expected = '{"attributes":{"foo":"bar","baz":["qux"]}}';
		$response->encodeContent();
		$this->assertEquals($expected, $response->getContent());
	}

	public function testEncodingArrayableContent()
	{
		$arrayable = new ArrayableObject(array('foo' => 'bar', 'baz' => array('qux')));

		$response = new Response($arrayable);
		$expected = '{"attributes":{"foo":"bar","baz":["qux"]}}';
		$response->encodeContent();
		$this->assertEquals($expected, $response->getContent());
	}

	public function testEncodingNestedArrayable()
	{
		$contents  = array('corge' => new ArrayableObject(array('foo' => 'bar', 'baz' => array('qux'))));

		$response = new Response($contents);
		$expected = '{"corge":{"attributes":{"foo":"bar","baz":["qux"]}}}';
		$response->encodeContent();
		$this->assertEquals($expected, $response->getContent());
	}

	public function testEncodingRespectsJsonFlags()
	{
		$contents = array(
			'foo' => '1',
			'bar' => '2',
		);

		$response = new Response($contents);
		$response->encodeContent();
		$expected = '{"foo":"1","bar":"2"}';
		$this->assertEquals($expected, $response->getContent());

		$response = new Response($contents);
		$response->encodeContent(JSON_NUMERIC_CHECK);

		// Note how numeric strings have been converted
		// to integers as per the flag above
		$expected = '{"foo":1,"bar":2}';
		$this->assertEquals($expected, $response->getContent());
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testGettingErrorsFailsWhenOkResponse()
	{
		$response = new Response('');
		$response->getErrors();
	}

	public function testGettingErrors()
	{
		$response = new Response(array(), 422);
		$response->setContent(json_encode(array('errors' => array('foo'))));
		$this->assertEquals(array('foo'), $response->getErrors());

		$response = new Response(array('errors' => array('foo')), 422);
		$this->assertEquals(array('foo'), $response->getErrors());

		$response = new Response(new ArrayableErrorsObject, 422);
		$this->assertEquals(array('foo'), $response->getErrors());
	}

}


class JsonableObject implements JsonableInterface {

	protected $attributes = array();

	public function __construct(array $attributes = null)
	{
		if (isset($attributes))
		{
			$this->attributes = $attributes;
		}
	}

	/**
	 * Convert the object to its JSON representation.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode(array('attributes' => $this->attributes), $options);
	}

}

class ArrayableObject implements ArrayableInterface {

	protected $attributes = array();

	public function __construct(array $attributes = null)
	{
		if (isset($attributes))
		{
			$this->attributes = $attributes;
		}
	}

	public function toArray()
	{
		return array('attributes' => $this->attributes);
	}

}

class ArrayableErrorsObject implements ArrayableInterface {

	public function toArray()
	{
		return array('errors' => array('foo'));
	}


}
