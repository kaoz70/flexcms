<?php

/**
 * Part of the Themes package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Themes
 * @version    3.0.6
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Themes\Laravel;

use Cartalyst\Themes\ThemeBag;
use Cartalyst\Themes\ThemePublisher;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Cartalyst\Themes\Assets\AssetManager;
use Cartalyst\Themes\Views\IlluminateViewFinder;
use Cartalyst\Themes\Console\ThemePublishCommand;
use Cartalyst\Themes\Locations\IlluminateGenerator as IlluminateLocationGenerator;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $config = $this->app['config']->get('cartalyst.themes');

        if ($active = array_get($config, 'active')) {
            $this->app['themes']->setActive($active);
        }

        if ($fallback = array_get($config, 'fallback')) {
            $this->app['themes']->setFallback($fallback);
        }

        $this->app['themes']->setViewFactory($this->app['view']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->prepareResources();
        $this->registerThemeBag();
        $this->registerLocationGenerator();
        $this->registerAssetManager();
        $this->registerThemePublisher();
        $this->overrideViewFinder();

        $this->commands('command.theme.publish');
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../config/config.php');

        $this->mergeConfigFrom($config, 'cartalyst.themes');

        $this->publishes([
            $config => config_path('cartalyst.themes.php'),
        ], 'config');
    }

    /**
     * Attempts to guess whether the application is in debug mode
     * or not, which will affect the assets compilation.
     *
     * @return bool
     */
    public function guessDebug()
    {
        $debug = $this->app['config']->get('cartalyst.themes.debug', null);

        if (is_null($debug) or ! $debug or $this->app['env'] === 'production') {
            return false;
        }

        return true;
    }

    /**
     * Register the theme bag which holds all the themes.
     *
     * @return void
     */
    protected function registerThemeBag()
    {
        $this->app['themes'] = $this->app->share(function ($app) {
            $config = $app['config']->get('cartalyst.themes');

            $themeBag = new ThemeBag($app['files'], array_get($config, 'paths'));

            $themeBag->setPackagesPath(array_get($config, 'packages_path'));
            $themeBag->setNamespacesPath(array_get($config, 'namespaces_path'));
            $themeBag->setViewsPath(array_get($config, 'views_path'));
            $themeBag->setAssetsPath(array_get($config, 'assets_path'));

            return $themeBag;
        });
    }

    /**
     * Register the location generator which returns a bunch of URLs and
     * paths to assets.
     *
     * @return void
     */
    protected function registerLocationGenerator()
    {
        $this->app['theme.locations'] = $this->app->share(function ($app) {
            return new IlluminateLocationGenerator($app['url'], $app['path.public']);
        });
    }

    /**
     * Register the asset manager itself, which is responsible for holding all
     * assets and compiling them.
     *
     * @return void
     */
    protected function registerAssetManager()
    {
        // The assets and views parts actually take the theme bag as
        // their dependencies. They do this so that all themes in
        // the bag may be used when finding views and assets.
        $me = $this;
        $this->app['theme.assets'] = $this->app->share(function ($app) use ($me) {
            $publicPath = $app['path.public'];

            $config = $app['config']->get('cartalyst.themes');

            $manager = new AssetManager(
                $app['themes'],
                $app['view.finder'],
                $app['theme.locations']
            );

            $manager->setDebug($me->guessDebug());

            $manager->setCachePath(array_get($config, 'cache_path', null));

            foreach (array_get($config, 'filters', array()) as $extension => $filters) {
                foreach ($filters as $filter) {
                    $manager->addFilter($extension, $filter);
                }
            }

            $manager->setForceRecompile(array_get($config, 'force_recompile', false));
            $manager->clearAssets(array_get($config, 'auto_clear', false));

            return $manager;
        });
    }

    /**
     * Override the view finder used by Laravel to be our Theme view finder.
     *
     * @return void
     */
    protected function overrideViewFinder()
    {
        $originalViewFinder = $this->app['view.finder'];

        $this->app['view.finder'] = $this->app->share(function ($app) use ($originalViewFinder) {
            $paths = array_merge(
                $app['config']['view.paths'],
                $originalViewFinder->getPaths()
            );

            $viewFinder = new IlluminateViewFinder($app['files'], $paths, $originalViewFinder->getExtensions());

            $viewFinder->setThemeBag($app['themes']);

            foreach ($originalViewFinder->getPaths() as $location) {
                $viewFinder->addLocation($location);
            }

            foreach ($originalViewFinder->getHints() as $namespace => $hints) {
                $viewFinder->addNamespace($namespace, $hints);
            }

            return $viewFinder;
        });

        // Now that we have overridden the "view.finder" IoC offest, we
        // need to re-register the factory as we cannot reset it
        // on the Factory at runtime, yet.
        $viewServiceProvider = new ViewServiceProvider($this->app);
        $viewServiceProvider->registerFactory();
    }

    /**
     * Registers the theme publisher that will be used.
     *
     * @return void
     */
    protected function registerThemePublisher()
    {
        $this->app['theme.publisher'] = $this->app->share(function ($app) {
            $publisher = new ThemePublisher($app['themes']);

            $publisher->setPackagePath($app['path.base'].'/vendor');

            $publisher->setDispatcher($app['events']);

            return $publisher;
        });

        $this->app['command.theme.publish'] = $this->app->share(function ($app) {
            return new ThemePublishCommand($app['theme.publisher']);
        });
    }
}
