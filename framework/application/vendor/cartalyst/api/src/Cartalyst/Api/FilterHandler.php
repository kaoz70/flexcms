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

use Cartalyst\Api\Auth\AuthInterface;
use Cartalyst\Api\Http\Response as ApiResponse;
use Cartalyst\Api\Http\InternalRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FilterHandler {

	/**
	 * The router which we use to register filters on.
	 *
	 * @var Illuminate\Routing\Router
	 */
	protected $router;

	/**
	 * The redirector which we use for any redirects on filters.
	 *
	 * @var Illuminate\Routing\Redirector
	 */
	protected $redirector;

	/**
	 * URI parser used for filters.
	 *
	 * @var Cartalyst\Api\UriParser
	 */
	protected $uriParser;

	/**
	 * Auth interface used in filters.
	 *
	 * @var Cartalyst\Api\Auth\AuthInterface
	 */
	protected $auth;

	/**
	 * Array of JSON flags for response manipulation.
	 *
	 * @var int
	 */
	protected $jsonManipulation = 0;

	/**
	 * Create a new Filter Handler.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @param  \Illuminate\Routing\Redirector  $redirector
	 * @param  \Cartalyst\Api\UriParser  $uriParser
	 * @param  \Cartalyst\Api\Auth\AuthInterface  $auth
	 * @return void
	 */
	public function __construct(Router $router, Redirector $redirector, UriParser $uriParser, AuthInterface $auth, $jsonManipulation = null)
	{
		$this->router     = $router;
		$this->redirector = $redirector;
		$this->uriParser  = $uriParser;
		$this->auth       = $auth;

		if (isset($jsonManipulation))
		{
			$this->jsonManipulation = $jsonManipulation;
		}
	}

	/**
	 * Registers authorize filters on the router.
	 *
	 * @return void
	 */
	public function registerAuthorizeFilters()
	{
		$this->router->filter('auth.api', array($this, 'handleAuthorizeFilter'));
	}

	/**
	 * Registers response filters on the router.
	 *
	 * @return void
	 */
	public function registerResponseFilters()
	{
		$this->router->after(array($this, 'handleResponseFilter'));
	}

	/**
	 * Callback used in the authorize filter.
	 *
	 * @param  \Illuminate\Routing\Route  $route
	 * @param  \Illuminate\Http\Request   $requset
	 * @return mixed
	 */
	public function handleAuthorizeFilter(Route $route, Request $request)
	{
		// Check we're dealing with API
		if ( ! starts_with($request->path(), $this->uriParser->getUriNamespace())) return;

		// If we're running internally (this happens
		// when the API dispatcher has created a
		// "HMVC"-like request), we will not apply any
		// authorize filters as the filters should be
		// applied to the route which is calling this
		// internal request.
		if ($request instanceof InternalRequest)
		{
			return;
		}

		if ( ! $this->auth->authenticate($request))
		{
			return new ApiResponse(array(
				'message' => 'Unauthorized',
			), 401);
		}
	}

	/**
	 * Handles responses from the API. Manipulates
	 * content depending on if we're on an internal
	 * or external request.
	 *
	 * @return void
	 */
	public function handleResponseFilter(Request $request, Response $response)
	{
		// Check we're dealing with API
		if ( ! starts_with($request->path(), $this->uriParser->getUriNamespace())) return;

		// If we're not dealing with an internal request, it's
		// time to manipulate our response
		if ( ! $request instanceof InternalRequest and $response instanceof ApiResponse)
		{
			$response->encodeContent($this->jsonManipulation);
		}

		// Great, our external response now has content that
		// is a JSON string.
		return $response;
	}

	/**
	 * Creates a JSON response from a mixed range of content types.
	 *
	 * @param  string  $content
	 * @param  int     $status
	 * @param  array   $headers
	 */
	public function createJsonResponse($content = '', $status = 200, array $headers = array())
	{
		return new JsonResponse($content, $status, $headers);
	}

	/**
	 * Set a new JSON manipulation strategy.
	 *
	 * @param  int  $jsonManipulation
	 * @return void
	 */
	public function setJsonManipulation($jsonManipulation)
	{
		$this->jsonManipulation = $jsonManipulation;
	}

	/**
	 * Set the authentication handler for this filter
	 * handler.
	 *
	 * @param  \Cartalyst\Api\Auth\AuthInterface  $auth
	 * @return void
	 */
	public function setAuth(AuthInterface $auth)
	{
		$this->auth = $auth;
	}

}
