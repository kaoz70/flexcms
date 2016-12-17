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

    public static function exception($message)
    {
        $data = [
            "heading" => "Error",
            "message" => $message,
            "success" => false,
            "notify" => true,
        ];

        log_message('Error', $message);

        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            echo View::blade(APPPATH . 'views/errors/html/general.blade.php', $data)->render();
        } else {
            echo View::blade(APPPATH . 'views/errors/json/error_general.php', $data)->render();
        }

        exit;
    }

}