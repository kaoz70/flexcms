<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:37 PM
 */

namespace App;

use Intervention\Image\Image as Intervention;

class Image extends File {

    /**
     * Process the images, resize, crop, etc based on the config
     *
     * @param $file
     * @param $path
     * @param ImageConfig $config
     * @param $crop
     * @return Intervention
     */
    public static function process($file, $path, ImageConfig $config, $crop)
    {

        $newPath = $path . '/' . $file['file_name'] . '_orig' . $file['file_ext'];

        // open the image file
        $img = Intervention::make($newPath);

        //If we are going to force the image to JPG, encode it as JPG
        if($config->force_jpg) {
            $newPath = $path . '/' . $file['file_name'] . $config->suffix . '.jpg';
            $img->encode('jpg', $config->quality);
        }

        //Crop the image
        if($config->restrict_proportions && $config->crop) {
            $img->crop($crop['cropWidth'], $crop['cropHeight'], $crop['cropImageTop'], $crop['cropLeft']);
        }

        //Resize the image
        else if($config->restrict_proportions && !$config->crop) {

            if($config->width > $config->height) {
                $img->widen($config->width);
            } else {
                $img->heighten($config->height);
            }

        }

        //Does it have a watermark
        if($config->watermark) {

            $watermark = $config->watermark();
            $wImg = Intervention::make($watermark->getFilePath());
            $wImg->opacity($config->watermark_alpha);

            if($config->watermark_repeat) {
                $img->fill($wImg);
            } else {
                $img->insert($wImg, $config->watermark_position);
            }

        }

        $img->save($newPath, $config->quality);

        return $img;

    }

}