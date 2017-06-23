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
    private $files;

    protected $appends = ['items', 'files'];

    public function setItemsAttribute($value)
    {
        $this->items = $value;
    }

    public function getItemsAttribute()
    {
        return $this->items;
    }

    public function setFilesAttribute($value)
    {
        $this->files = $value;
    }

    public function getFilesAttribute()
    {
        return $this->files;
    }

    public function getMultipleUploadAttribute($value)
    {
        return (boolean)$value;
    }

    public static function getImages($section, $content_id, $image_base_path)
    {
        $sections = static::getBySection($section);

        foreach ($sections as $section) {
            $section->files = static::getFiles($section->id, $content_id, $image_base_path);
        }

        return $sections;

    }

    public static function getFiles($section_id, $content_id, $image_base_path)
    {
        $images = Image::where('section_id', $section_id)->where('parent_id', $content_id)->get();

        foreach ($images as $image) {
            $image->setUrlPath(base_url($image_base_path . $image->name . '_orig' . $image->file_ext));
        }

        return $images;
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
            // TODO: find a better way to not duplicate this (transformers maybe)
            $section->configs =  $section->imageConfigs(); //this is for the content detail
            $section->items =  $section->imageConfigs(); //this is for the list
        }

        return $sections;
    }

    public function imageConfigs()
    {
        return $this->hasMany('App\ImageConfig')->orderBy('position', 'asc')->get();
    }
}