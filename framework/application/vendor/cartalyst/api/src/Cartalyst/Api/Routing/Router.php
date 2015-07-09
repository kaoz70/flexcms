<?php namespace Cartalyst\Api\Routing;
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

use Cartalyst\Api\UriParser;
use Illuminate\Container\Container;
use Illuminate\Routing\Router as BaseRouter;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class Router extends BaseRouter {

	/**
	 * The URI parser used for adjust route paths which
	 * are designated for the API.
	 *
	 * @var Cartlayst\Api\UriParser
	 */
	public $uriParser;

	/**
	 * Create a new route instance.
	 *
	 * @param  string  $method
	 * @param  string  $pattern
	 * @param  mixed   $action
	 * @return \Illuminate\Routing\Route
	 */
	protected function createRoute($method, $pattern, $action)
	{
		// We'll firstly let our parent create the route for us
		$route = parent::createRoute($method, $pattern, $action);

		// And then we'll parse the route pattern
		$this->parseRoute($route);

		return $route;
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
	 * Parses all routes registered to check for patterns.
	 *
	 * @return void
	 */
	public function parseRoutes()
	{
		foreach ($this->routes as $route)
		{
			$this->parseRoute($route);
		}
	}

	/**
	 * Parses a route to check for an API compatible
	 * pattern. If it exists, we update the route.
	 *
	 * @param  \Illuminate\Routing\Route  $route
	 * @return void
	 */
	public function parseRoute(Route $route)
	{
		// Now we'll parse the route pattern to get a URI array
		$uriArray = $this->uriParser->parseRoutePattern($route->uri());

		if ($uriArray !== false)
		{
			// If we've been given a URI array from our parser,
			// we'll convert it to a string
			$reflected = new \ReflectionObject($route);
			$reflectedRoute = $reflected->getProperty('uri');
			$reflectedRoute->setAccessible(true);
			$reflectedRoute->setValue($route, $this->uriParser->convertParsedUriArrayToString($uriArray));
		}
	}

	/**
	 * Set the routes on the router.
	 *
	 * @param  \Illuminate\Routing\RouteCollection  $routes
	 * @return void
	 */
	public function setRoutes(RouteCollection $routes)
	{
		$this->routes = $routes;
	}

	/**
	 * Set the URI parser to be used on the router.
	 *
	 * @param  \Cartalyst\Api\UriParser  $uriParser
	 * @return void
	 */
	public function setUriParser(UriParser $uriParser)
	{
		$this->uriParser = $uriParser;
	}

}
