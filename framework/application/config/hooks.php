<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = function() {

    //Check if the server meets the App's requirements
    \App\Requirements::check();

    //Load the database config
    $dotenv = new Dotenv\Dotenv(FCPATH);
    $dotenv->load();

    //Config the Eloquent ORM
    $capsule = new Capsule;
    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => getenv('DB_HOST'),
        'database'  => getenv('DB_NAME'),
        'username'  => getenv('DB_USERNAME'),
        'password'  => getenv('DB_PASSWORD'),
        'charset'   => getenv('DB_CHARSET'),
        'collation' => getenv('DB_COLLATION'),
        'prefix'    => getenv('DB_PREFIX'),
    ]);

    $capsule->setEventDispatcher(new Dispatcher(new Container));

    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();

    $events = new Illuminate\Events\Dispatcher;

    $events->listen('illuminate.query', function($query, $bindings, $time, $name)
    {
        // Format binding data for sql insertion
        foreach ($bindings as $i => $binding)
        {
            if ($binding instanceof \DateTime)
            {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            }
            else if (is_string($binding))
            {
                $bindings[$i] = "'$binding'";
            }
        }

        // Insert bindings into query
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
        $query = vsprintf($query, $bindings);

        // Add it into CodeIgniter
        $db =& get_instance()->db;

        $db->query_times[] = $time;
        $db->queries[] = $query;

    });

    $capsule->setEventDispatcher($events);

};

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */