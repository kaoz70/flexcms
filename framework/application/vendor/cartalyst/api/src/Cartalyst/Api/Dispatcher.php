<?php namespace Cartalyst\Api;
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

use Closure;
use Cartalyst\Api\Http\ApiHttpException;
use Cartalyst\Api\Http\InternalRequest;
use Cartalyst\Api\Http\Response as ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Dispatcher {

	protected $router;

	protected $mainRequest;

	protected $additionalRequests = array();

	protected $uriParser;

	public function __construct(Router $router, Request $mainRequest, UriParser $uriParser)
	{
		$this->router         = $router;
		$this->mainRequest    = $mainRequest;
		$this->uriParser      = $uriParser;
	}

	/**
	 * Performs a GET request on a resource, used for retrieving
	 * information.
	 *
	 * @param  string   $uri
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function get($uri, array $parameters = array(), Closure $beforeSend = null)
	{
		return $this->dispatch($uri, 'GET', $parameters, $beforeSend);
	}

	/**
	 * Performs a HEAD request on a resource.
	 *
	 * @param  string   $uri
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function head($uri, array $parameters = array(), Closure $beforeSend = null)
	{
		return $this->dispatch($uri, 'HEAD', $parameters, $beforeSend);
	}

	/**
	 * Performs a DELETE request on a resource,
	 * used for deleting a resource.
	 *
	 * @param  string   $uri
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function delete($uri, array $parameters = array(), Closure $beforeSend = null)
	{
		return $this->dispatch($uri, 'DELETE', $parameters, $beforeSend);
	}

	/**
	 * Performs a POST request on a resource,
	 * used for creating a resource. Used for
	 * non-idempotent requests.
	 *
	 * @param  string   $uri
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function post($uri, array $parameters = array(), Closure $beforeSend = null)
	{
		return $this->dispatch($uri, 'POST', $parameters, $beforeSend);
	}

	/**
	 * Performs a PUT request on a resource,
	 * used for updating a resource or creating
	 * a new resource. Requests must be idempotent.
	 *
	 * @param  string   $uri
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function put($uri, array $parameters = array(), Closure $beforeSend = null)
	{
		return $this->dispatch($uri, 'PUT', $parameters, $beforeSend);
	}

	/**
	 * Similar to a PUT request, used for patching
	 * updates to a resource.
	 *
	 * @param  string   $uri
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function patch($uri, array $parameters = array(), Closure $beforeSend = null)
	{
		return $this->dispatch($uri, 'PATCH', $parameters, $beforeSend);
	}

	/**
	 * Dispatch a request.
	 *
	 * @param  string   $uri
	 * @param  string   $method
	 * @param  array    $parameters
	 * @param  Closure  $beforeSend
	 */
	public function dispatch($uri, $method = 'GET', array $parameters = array(), Closure $beforeSend = null)
	{
		$uri = $this->uriParser->createUri($uri);

		// Add an additional request to the queue of requests.
		$this->additionalRequests[] = $request = $this->createRequest($uri, $method, $parameters);

		// If we have a callback, we'll call it now
		if (isset($beforeSend))
		{
			$beforeSend($request);
		}

		// Grab the response
		$response = $this->checkResponse($this->router->dispatch($request))->getContent();

		// Remove the request from the stack of additional requests
		array_pop($this->additionalRequests);

		return $response;
	}

	/**
	 * Returns the current request, if any.
	 *
	 * @return \Cartalyst\Api\Http\Request
	 */
	public function getCurrentRequest()
	{
		if (count($this->additionalRequests)) return end($this->additionalRequests);

		return $this->mainRequest;
	}

	/**
	 * Creates an API requset.
	 *
	 * @param  string   $uri
	 * @param  string   $method
	 * @param  array    $parameters
	 * @return \Cartalyst\Api\Http\Request
	 */
	public function createRequest($uri, $method = 'GET', array $parameters = array())
	{
		return InternalRequest::create(

			// The URI
			$uri,

			// The HTTP method
			$method,

			// The GET parameters
			$parameters,

			// The COOKIE parameters
			$this->mainRequest->cookies->all(),

			// The FILES parameters
			$this->mainRequest->files->all(),

			// The SERVER parameters
			$this->mainRequest->server->all(),

			// The CONTENT
			null
		);
	}

	/**
	 * Return a new API response. This response is very similar to a
	 * normal response, except that (because the API can run internally)
	 * the content is not always transformed to a string or array.
	 *
	 * @todo   Allow for a configuration whether internal requests actaully
	 *         return objects or whether we transform them to arrays (by
	 *         encoding as JSON and then decoding [and calling toArray() on
	 *         applicable interfaces]).
	 *
	 * @param  string  $content
	 * @param  int     $status
	 * @param  array   $headers
	 * @return \Cartalyst\Api\Http\Response
	 */
	public function createResponse($data = array(), $status = 200, $headers = array())
	{
		return new ApiResponse($data, $status, $headers);
	}

	/**
	 * Checks the given response and throws a number of Exceptions
	 * if it is not okay. Used internally to check responses.
	 *
	 * @param  \Cartalyst\Api\Http\Response  $response
	 * @return \Cartalyst\Api\Http\Resposne  $response
	 * @throws Cartalyst\Api\Http\ApiHttpException
	 */
	public function checkResponse(Response $response)
	{
		try
		{
			// Bad response? Let's throw a HttpException
			// for consistency
			if ( ! $response->isSuccessful())
			{
				$message = null;
				if (is_array($content = $response->getContent()) and isset($content['message']))
				{
					$message = $content['message'];
				}

				// Create a new API HTTP exception
				$e = new ApiHttpException(
					$response->getStatusCode(),
					$message,
					null,
					$response->headers->all()
				);

				// SEt the errors
				$e->setErrors($response->getErrors());

				throw $e;
			}
		}

		// Be sure to only catch HTTP Exceptions, and not all
		// Exceptions. We want to provide debugging ability for
		// our users
		catch (HttpException $e)
		{
			// Create up a much more informative message
			if ( ! $message = $e->getMessage())
			{
				$statusCode = $e->getStatusCode();
				$message = sprintf(
					'%d %s',
					$statusCode,
					SymfonyResponse::$statusTexts[$statusCode]
				);
			}

			// Convert to an ApiHttpException
			// so that users debugging can catch
			// just these Exceptions if they want.
			$finalException = new ApiHttpException(
				$e->getStatusCode(),
				$message,
				$e->getPrevious(),
				$e->getHeaders(),
				$e->getCode()
			);

			// If our exception is an instance of an
			// API HTTP Exception, there may be errors
			// attached which we can attach to our newly
			// thrown Exception.
			if ($e instanceof ApiHttpException)
			{
				$finalException->setErrors($e->getErrors());
			}

			throw $finalException;
		}

		return $response;
	}

	public function setRouter(Router $router)
	{
		$this->router = $router;
	}

	public function getMainRequest()
	{
		return $this->mainRequest;
	}

	public function getUriParser()
	{
		return $this->uriParser;
	}

}
