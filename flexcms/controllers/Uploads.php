<?php

/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 7/27/2016
 * Time: 10:33 AM
 */

use Intervention\Image\ImageManagerStatic as Image;

class Uploads extends RESTController
{

    public function index_get($filename)
    {
        $image = Image::make(APPPATH . 'uploads/' . $filename);
        header("Content-Type: {$image->mime}");
        echo $image->encode($image->extension);
    }

}
