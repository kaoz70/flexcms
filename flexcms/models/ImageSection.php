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

    protected $appends = [];

    public function getMultipleUploadAttribute($value)
    {
        return (boolean)$value;
    }

    public static function getImages($section, $content_id, $image_base_path)
    {
        return static::getBySection($section, $content_id, $image_base_path);
    }

    public static function getFiles($section_id, $content_id, $image_base_path)
    {
        $images = Image::where('section_id', $section_id)->where('parent_id', $content_id)->orderBy('position')->get();

        foreach ($images as $image) {
            /** @var Image $image */
            $image->setUrlPath(base_url($image_base_path . $image->name . '_orig.' . $image->file_ext));
        }

        return $images;
    }

    /**
     * Get all the image configs in a section
     *
     * @param $section
     * @param $content_id
     * @param $image_base_path
     * @return mixed
     */
    public static function getBySection($section, $content_id, $image_base_path)
    {

        $sections = static::where('section', $section)->get();

        /** @var ImageSection $section */
        foreach ($sections as $section) {
            $section->configs =  $section->imageConfigs(); //this is for the content detail

            // Add the files
            /** @var ImageConfig $config */
            foreach ($section->configs as $config) {
                $config->images = $config->getContentFiles($content_id, $image_base_path);
            }
        }

        return $sections;
    }

    public function imageConfigs()
    {
        return $this->hasMany('App\ImageConfig')->orderBy('position', 'asc')->get();
    }
}
