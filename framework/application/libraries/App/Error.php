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
        ];

        log_message('Error', $message);

        echo View::blade(APPPATH . 'views/errors/html/general.blade.php', $data)->render();
        exit;
    }

}