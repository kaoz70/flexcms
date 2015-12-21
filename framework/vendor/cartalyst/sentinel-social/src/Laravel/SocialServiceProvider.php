<?php

/**
 * Part of the Sentinel Social package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel Social
 * @version    2.0.4
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Sentinel\Addons\Social\Laravel;

use Cartalyst\Sentinel\Addons\Social\Manager;
use Cartalyst\Sentinel\Sessions\IlluminateSession;
use Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepository;
use Cartalyst\Sentinel\Addons\Social\RequestProviders\IlluminateRequestProvider;

class SocialServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $defer = true;

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->prepareResources();
        $this->registerLinkRepository();
        $this->registerRequestProvider();
        $this->registerSession();
        $this->registerSentinelSocial();
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'sentinel.addons.social.repository',
            'sentinel.addons.social.request',
            'sentinel.addons.social.session',
            'sentinel.addons.social',
        ];
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        // Publish config
        $config = realpath(__DIR__.'/../config/config.php');

        $this->mergeConfigFrom($config, 'cartalyst.sentinel-addons.social');

        $this->publishes([
            $config => config_path('cartalyst.sentinel-addons.social.php'),
        ], 'config');

        // Publish migrations
        $migrations = realpath(__DIR__.'/../migrations');

        $this->publishes([
            $migrations => $this->app->databasePath().'/migrations',
        ], 'migrations');
    }

    /**
     * Registers the link repository.
     *
     * @return void
     */
    protected function registerLinkRepository()
    {
        $this->app['sentinel.addons.social.repository'] = $this->app->share(function ($app) {
            $model = $app['config']->get('cartalyst.sentinel-addons.social.link');

            $users = $app['config']->get('cartalyst.sentinel.users.model');

            if (class_exists($model) and method_exists($model, 'setUsersModel')) {
                forward_static_call_array([$model, 'setUsersModel'], [$users]);
            }

            return new LinkRepository($model);
        });
    }

    /**
     * Registers the request provider.
     *
     * @return void
     */
    protected function registerRequestProvider()
    {
        $this->app['sentinel.addons.social.request'] = $this->app->share(function ($app) {
            return new IlluminateRequestProvider($app['request']);
        });
    }

    /**
     * Registers the session.
     *
     * @return void
     */
    protected function registerSession()
    {
        $this->app['sentinel.addons.social.session'] = $this->app->share(function ($app) {
            $key = $app['config']->get('cartalyst.sentinel.cookie.key').'_social';

            return new IlluminateSession($app['session.store'], $key);
        });
    }

    /**
     * Registers Sentinel Social.
     *
     * @return void
     */
    protected function registerSentinelSocial()
    {
        $this->app['sentinel.addons.social'] = $this->app->share(function ($app) {
            $manager = new Manager(
                $app['sentinel'],
                $app['sentinel.addons.social.repository'],
                $app['sentinel.addons.social.request'],
                $app['sentinel.addons.social.session'],
                $app['events']
            );

            $connections = $app['config']->get('cartalyst.sentinel-addons.social.connections');

            $manager->addConnections($connections);

            return $manager;
        });

        $this->app->alias('sentinel.addons.social', 'Cartalyst\Sentinel\Addons\Social\Manager');
    }
}
