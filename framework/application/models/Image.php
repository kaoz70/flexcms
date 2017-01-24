<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:37 PM
 */

namespace App;


class Image extends File {

    public static function getPlaceholderData()
    {

        $fields = new \stdClass();
        $fields->extension = '';
        $fields->coords = new \stdClass();
        $fields->coords->top = 0;
        $fields->coords->left = 0;
        $fields->coords->width = 0;
        $fields->coords->height = 0;
        $fields->coords->scale = 0;

        return $fields;

    }

}