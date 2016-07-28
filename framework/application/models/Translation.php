<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 3:06 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model {

    protected $fillable = [ 'parent_id', 'language_id', 'type' ];

}