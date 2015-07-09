<?php namespace Cartalyst\Api\Http;
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

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;

class Response extends \Illuminate\Http\Response {

	/**
     * Create a new response.
     *
     * @param  string  $content
     * @param  integer $status
     * @param  array   $headers
     * @param  bool    $internal
     * @return void
     */
    public function __construct($content = '', $status = 200, $headers = array())
    {
    	parent::__construct($content, $status, $headers);

    	$this->adjustContent();
    }

	/**
	 * Set the content on the response.
	 *
	 * @param  mixed  $content
	 * @return void
	 */
	public function setContent($content)
	{
		$this->content         = $content;
		$this->originalContent = $content;

		return $this;
	}

	/**
	 * Adjusts the content of the response so
	 * that it is formatted correctly. We store
	 * content as objects / arrays in this response
	 * so that, internally, the response can return
	 * object instances.
	 *
	 * @return void
	 */
	public function adjustContent()
	{
		// If our response is still a string it is a message
		// which should be setup in the correct array.
		if (is_string($this->content))
		{
			$this->content = array('message' => $this->content);
		}
	}

	/**
	 * JSON encodes the content so it is a string.
	 *
	 * @param  int  $options
	 * @return void
	 */
	public function encodeContent($options = 0)
	{
		// We are always serving JSON
		$this->headers->set('Content-Type', 'application/json');

		// If the content is "JSONable" we will set the appropriate header and convert
		// the content to JSON. This is useful when returning something like models
		// from routes that will be automatically transformed to their JSON form.
		if ($this->content instanceof JsonableInterface)
		{
			$this->content = $this->content->toJson($options);
			return;
		}

		// If the root object is arrayable
		if ($this->content instanceof ArrayableInterface)
		{
			$this->content = $this->content->toArray();
		}

		if (is_array($this->content))
		{
			// Loop through each item in the content and check if
			// we can cast it as an array. If we can, we'll do that
			// now so that our response encodes nicely as JSON.
			array_walk_recursive($this->content, function(&$item) use ($options)
			{
				// Check for arrayable
				if ($item instanceof ArrayableInterface)
				{
					$item = $item->toArray();
				}

				return $item;
			});
		}

		$this->content = json_encode($this->content, $options);
	}

	/**
	 * Returns an array of error messages for
	 * the current request.
	 *
	 * @return array
	 */
	public function getErrors()
	{
		// Don't try getting errors from a response with a good HTTP status
		// code.
		if ($this->isSuccessful())
		{
			throw new \RuntimeException("Cannot retrieve errors of response with status code [{$this->statusCode}].");
		}

		// Firstly we'll check if our response is a string. If so,
		// we'll try extract the errors out of it.
		if (is_string($this->content))
		{
			$asArray = json_decode($this->content, true);

			if (is_array($asArray) and isset($asArray['errors']))
			{
				return $asArray['errors'];
			}
		}

		// Otherwise if our content is Arrayable, we'll cast it to
		// an array and extract the errors.
		elseif ($this->content instanceof ArrayableInterface)
		{
			$asArray = $this->content->toArray();

			if (is_array($asArray) and isset($asArray['errors']))
			{
				return $asArray['errors'];
			}
		}

		// Finally, we'll check for errors in the content when it is
		// an array.
		elseif (is_array($this->content) and isset($this->content['errors']))
		{
			return $this->content['errors'];
		}

		return array();
	}

}
