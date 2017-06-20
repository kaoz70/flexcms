<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface WidgetInterface {

    /**
     * Shows the Widget's admin view
     *
     * @param null $id
     *
     * @return string
     */
    static function admin($id);

    /**
     * Runs the widget in the frontend
     *
     * @param $method
     * @return mixed
     */
    static function run($method);

}