<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/25/15
 * Time: 11:30 AM
 */

namespace App;

use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem as Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container as Container;
use Illuminate\View\Factory;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\View as LaravelView;

class View
{

    /**
     * @param bool $viewPath
     * @param array $data
     * @return LaravelView
     * @throws \Throwable
     */
    static function blade($viewPath = false, $data = array())
    {

        // this path needs to be array
        $FileViewFinder = new FileViewFinder(
            new Filesystem,
            array($viewPath)
        );

        // use blade instead of phpengine
        // pass in filesystem object and cache path
        $compiler = new BladeCompiler(new Filesystem(), APPPATH . 'cache/views');
        $BladeEngine = new CompilerEngine($compiler);

        // create a dispatcher
        $dispatcher = new Dispatcher(new Container);

        // build the factory
        $factory = new Factory(
            new EngineResolver,
            $FileViewFinder,
            $dispatcher
        );

        // this path needs to be string
        $viewObj = new LaravelView(
            $factory,
            $BladeEngine,
            "",
            $viewPath . '.blade.php',
            $data
        );

        echo $viewObj->render();
        return $viewObj;
    }

}
