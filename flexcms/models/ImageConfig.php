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
    protected static $section_id = 'image_section_id';

    protected $appends = ['images'];

    protected $casts = [
        'crop' => 'boolean',
        'force_jpg' => 'boolean',
        'optimize_original' => 'boolean',
    ];

    /**
     * Return this column as boolean
     *
     * @param $value
     * @return bool
     */
    public function getForceJpgAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Return this column as boolean
     *
     * @param $value
     * @return bool
     */
    public function getOptimizeOriginalAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Return this column as boolean
     *
     * @param $value
     * @return bool
     */
    public function getWatermarkAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Return this column as boolean
     *
     * @param $value
     * @return bool
     */
    public function getWatermarkRepeatAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Return this column as boolean
     *
     * @param $value
     * @return bool
     */
    public function getCropAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Return this column as integer
     *
     * @param $value
     * @return int
     */
    public function getQualityAttribute($value)
    {
        return (int)$value;
    }

    /**
     * Return this column as integer
     *
     * @param $value
     * @return int
     */
    public function getWatermarkAlphaAttribute($value)
    {
        return (int)$value;
    }

    /**
     * Stores the ImageConfig data
     *
     * @param ImageConfig $imageConfig
     * @param $imageData
     * @param $watermarkData
     * @return ImageConfig
     */
    public static function store(ImageConfig $imageConfig, $imageData, $watermarkData)
    {

        $imageConfig->image_section_id = $imageData['image_section_id'];
        $imageConfig->sufix = $imageData['sufix'];
        $imageConfig->width = isset($imageData['width']) ? $imageData['width'] : null;
        $imageConfig->height = isset($imageData['height']) ? $imageData['height'] : null;
        $imageConfig->name = $imageData['name'];
        $imageConfig->crop = isset($imageData['crop']) ? $imageData['crop'] : null;
        $imageConfig->force_jpg = isset($imageData['force_jpg']) ? $imageData['force_jpg'] : null;
        $imageConfig->optimize_original = isset($imageData['optimize_original']) ? $imageData['optimize_original'] : null;
        $imageConfig->background_color = isset($imageData['background_color']) ? $imageData['background_color'] : null;
        $imageConfig->quality = isset($imageData['quality']) ? $imageData['quality'] : null;
        $imageConfig->restrict_proportions = isset($imageData['restrict_proportions']) ? $imageData['restrict_proportions'] : '';
        $imageConfig->save();

        if($watermarkData && count($watermarkData['files'])) {

            $imageConfig->watermark = true;

            //No watermark and user uploaded a file
            if(!$imageConfig->watermark_file_id && $watermarkData['uploaded']) {
                $image = new File();
                $image->parent_id = $imageConfig->id;
                $image = static::_storeWatermark($image, $watermarkData);
                $image->position = File::where('parent_id', $image->parent_id)->get()->count();
                $image->save();
            }

            //Image has a watermark already and user uploaded a file
            elseif($imageConfig->watermark_file_id && $watermarkData['uploaded']) {
                $image = static::_storeWatermark(File::find($imageConfig->watermark_file_id), $watermarkData);
            } elseif($imageConfig->watermark_file_id) {
                $image = File::find($imageConfig->watermark_file_id);
            }

            $imageConfig->watermark_file_id = $image->id;
            $imageConfig->watermark_position = isset($imageData['watermark_position']) ? $imageData['watermark_position'] : null;
            $imageConfig->watermark_alpha = $imageData['watermark_alpha'];
            $imageConfig->watermark_repeat = isset($imageData['watermark_repeat']) ? $imageData['watermark_repeat'] : null;

        }

        $imageConfig->save();

        return $imageConfig;
    }

    public static function getForContent($content_id)
    {
        return static::where('section', $content_id)->get();
    }

    /**
     * Stores the watermark data
     *
     * @param File $file
     * @param $data
     * @return File
     */
    private static function _storeWatermark(File $file, $data)
    {

        //Store the file data
        $file->type = $data['type'];
        $file->mime_type =$data['mime_type'];
        $file->file_ext =$data['file_ext'];
        $file->raw_name =$data['raw_name'];
        $file->save();
        $file->setFilePath("./assets/images/watermarks/{$file->id}{$file->file_ext}");

        //Move the file into the watermark folder
        File::move($data['file_path'], $file->getFilePath());

        return $file;
    }

    /**
     * Get the watermark image data
     *
     * @return mixed
     */
    public function watermark()
    {

        //Get the image
        $image = File::find($this->watermark_file_id);

        if($image) {
            //Set the file path
            $image->setFilePath(base_url("assets/images/watermarks/{$image->id}{$image->file_ext}"));
        }

        return $image;

    }

    public function deleteWatermark()
    {
        $image = File::find($this->watermark_file_id);
        if($image) {
            $image->deleteFile('./assets/images/watermarks');
            $image->delete();
        }
    }

    public function getContentFiles($content_id, $image_base_path)
    {
        $images = Image::where('config_id', $this->id)->where('parent_id', $content_id)->orderBy('position')->get();

        foreach ($images as $image) {
            /** @var Image $image */
            $image->setUrlPath(base_url($image_base_path . $image->name . '_orig.' . $image->file_ext));
        }

        return $images;
    }

}
