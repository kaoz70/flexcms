<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 19-Jan-17
 * Time: 03:59 PM
 */

namespace App;


class ImageConfig extends BaseModel
{

    protected $table = 'images_config';

    public function getCropAttribute($value)
    {
        return (boolean)$value;
    }

}