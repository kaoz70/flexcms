<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 15-Dec-16
 * Time: 06:26 PM
 */

namespace App;


class Error
{

    public static function initHandlers()
    {

        if(ENVIRONMENT === 'development') {

            // Set some nice error handlers
            $whoops = new \Whoops\Run;

            if (self::isAjaxRequest()) {
                $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
            } else {
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            }

            $whoops->register();

        } else {

            set_error_handler(function ($errno, $errstr) {
                self::exception($errstr);
            }, E_WARNING);

        }

    }

    public static function exception($message)
    {

        $data = [
            "heading" => "Error",
            "message" => $message,
            "success" => false,
            "notify" => true,
        ];

        log_message('Error', $message);

        if (self::isAjaxRequest()) {
            View::blade(APPPATH . 'views/errors/json/general', $data);
        } else {
            View::blade(APPPATH . 'views/errors/html/general', $data);
        }

        exit;

    }

    /**
     * Checks if the request is an ajax request
     *
     * @return bool
     */
    private static function isAjaxRequest()
    {

        $isAngular = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
        $acceptsJsonResponse = (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        return $isAngular || $acceptsJsonResponse;

    }

}