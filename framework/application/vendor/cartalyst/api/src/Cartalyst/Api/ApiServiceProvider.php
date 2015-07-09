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

use Illuminate\Support\ServiceProvider;
use Cartalyst\Api\Auth\SentryAuth;
use Cartalyst\Api\Routing\Router;

class ApiServiceProvider extends ServiceProvider {

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('cartalyst/api', 'cartalyst/api');

		$this->overrideRouter();

		$this->app['router']->setUriParser($this->app['api.parser']);
		$this->app['api']->setRouter($this->app['router']);

		$this->app['api.filters']->registerAuthorizeFilters();
		$this->app['api.filters']->registerResponseFilters();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->setupUriParser();
		$this->setupAuth();
		$this->setupFilterHandler();
		$this->setupRequest();

		$this->registerApi();
	}

	/**
	 * Setup the URI parser for the API.
	 *
	 * @return void
	 */
	protected function setupUriParser()
	{
		$this->app['api.parser'] = $this->app->share(function($app)
		{
			$config         = $app['config'];
			$defaultVersion = $config['cartalyst/api::default_version'];
			$uriNamespace   = $config['cartalyst/api::parser_namespace'];

			return new UriParser($app['request'], $defaultVersion, $uriNamespace);
		});
	}

	/**
	 * Sets up the authentication service to be used with the API.
	 *
	 * @return void
	 */
	protected function setupAuth()
	{
		$this->app['api.auth'] = $this->app->share(function($app)
		{
			return new SentryAuth($app['sentry']);
		});
	}

	/**
	 * Sets up the filter handler.
	 *
	 * @return void
	 */
	protected function setupFilterHandler()
	{
		$this->app['api.filters'] = $this->app->share(function($app)
		{
			$jsonManipulation = $app['config']['cartalyst/api::json_manipulation'];

			return new FilterHandler(
				$app['router'],
				$app['redirect'],
				$app['api.parser'],
				$app['api.auth'],
				$jsonManipulation
			);
		});
	}

	/**
	 * Setup the request shortcut.
	 *
	 * @return void
	 */
	protected function setupRequest()
	{
		$this->app['api.request'] = function($app)
		{
			return $app['api']->getCurrentRequest();
		};
	}

	/**
	 * Register the API instance.
	 *
	 * @return void
	 */
	protected function registerApi()
	{
		$this->app['api'] = $this->app->share(function($app)
		{
			return new Dispatcher(
				$app['router'],
				$app['request'],
				$app['api.parser']
			);
		});
	}

	/**
	 * Overrides the normal router service provider. We do
	 * this so that people may route to the API regardless
	 * of what namespaces / configurations are provided in
	 * the config.
	 *
	 * @return void
	 */
	protected function overrideRouter()
	{
		$originalRouter = $this->app['router'];

		$this->app['router'] = $this->app->share(function($app) use ($originalRouter)
		{
			// We need to essentially clone the existing router with any
			// and all routes, filters and runtime settings that were applied
			// to it.
			$apiRouter = new Router($app['events'], $app);

			$reflected = new \ReflectionObject($originalRouter);
			$reflectedFilters = $reflected->getProperty('patternFilters');
			$reflectedFilters->setAccessible(true);
			$filters = $reflectedFilters->getValue($originalRouter);

			foreach ($filters as $pattern => $_filters)
			{
				foreach ($_filters as $filter)
				{
					$apiRouter->when($pattern, $filter['name'], $filter['methods']);
				}
			}

			$reflected = new \ReflectionObject($originalRouter);
			$reflectedFiltering = $reflected->getProperty('filtering');
			$reflectedFiltering->setAccessible(true);
			$filtering = $reflectedFiltering->getValue($originalRouter);

			// Enable / disable filters
			if ($filtering)
			{
				$apiRouter->enableFilters();
			}
			else
			{
				$apiRouter->disableFilters();
			}

			$reflected = new \ReflectionObject($originalRouter);
			$reflectedRoutes = $reflected->getProperty('routes');
			$reflectedRoutes->setAccessible(true);
			$routes = $reflectedRoutes->getValue($originalRouter);

			$reflected = new \ReflectionObject($apiRouter);
			$reflectedRoutes = $reflected->getProperty('routes');
			$reflectedRoutes->setAccessible(true);
			$reflectedRoutes->setValue($apiRouter, $routes);

			$apiRouter->setUriParser($app['api.parser']);

			$apiRouter->parseRoutes();

			return $apiRouter;
		});
	}

}
