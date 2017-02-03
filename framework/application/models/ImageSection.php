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

    public function getMultipleUploadAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Get all the image configs in a section
     *
     * @param $section
     * @return mixed
     */
    public static function getBySection($section)
    {

        $sections = static::where('section', $section)->get();

        foreach ($sections as $section) {
            $section->items =  $section->imageConfigs();
        }

        return $sections;
    }

    public function imageConfigs()
    {
        return $this->hasMany('App\ImageConfig')->orderBy('position', 'asc')->get();
    }
}