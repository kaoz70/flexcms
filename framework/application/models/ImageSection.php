<?php

namespace App;

/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 25-Jan-17
 * Time: 06:07 PM
 */
class ImageSection extends BaseModel
{

    private $items;

    protected $appends = ['items'];

    public function setItemsAttribute($value)
    {
        $this->items = $value;
    }

    public function getItemsAttribute()
    {
        return $this->items;
    }

    public function imageConfigs()
    {
        return $this->hasMany('App\ImageConfig')->orderBy('position', 'asc')->get();
    }
}