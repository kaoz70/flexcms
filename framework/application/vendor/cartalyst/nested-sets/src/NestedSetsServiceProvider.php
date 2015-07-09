<?php

/**
 * Part of the Nested Sets package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Nested Sets
 * @version    2.0.3
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\NestedSets;

use Illuminate\Support\ServiceProvider;
use Cartalyst\NestedSets\Nodes\EloquentNode;

class NestedSetsServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        EloquentNode::setPresenter($this->app['nested.sets.presenter']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPresenter();
    }

    /**
     * Register the presenter.
     *
     * @return void
     */
    protected function registerPresenter()
    {
        $this->app['nested.sets.presenter'] = $this->app->share(function ($app) {
            return new Presenter;
        });
    }
}
