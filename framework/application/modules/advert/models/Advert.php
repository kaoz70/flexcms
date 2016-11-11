<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 03-Sep-16
 * Time: 04:33 PM
 */

namespace App;


class Advert extends BaseModel
{

    static function generateTag($file){

        $extension = mb_strtolower(pathinfo('./assets/public/files/publicidad/' . $file, PATHINFO_EXTENSION));

        if(!$extension) {
            $extension = $file;
        }

        switch($extension) {

            //Images
            case 'jpg':
            case 'gif':
            case 'png':
            case 'jpeg':
                $tag = '<img src="' . base_url() . 'assets/public/files/publicidad/' . $file . '?' . time() . '" />';
                break;

            //Audio
            case 'mp3':
            case 'ogg':
            case 'mwa':
            case 'wav':
                $tag = '<audio src="' . base_url() . 'assets/public/files/publicidad/' . $file . '" controls ></audio>';
                break;

            //Flash
            case 'swf':
                $path = base_url() . 'assets/public/files/publicidad/' . $file;
                $tag = "<object width=\"100\" height=\"100\">
                    <param name=\"movie\" value=\"$path\">
                    <embed src=\"$path\" width=\"100\" height=\"100\">
                    </embed>
                </object>";
                break;

            //Video
            case 'avi':
            case 'wmv':
            case 'mov':
                $tag = '<video src="' . base_url() . 'assets/public/files/publicidad/' . $file . '" controls ></video>';
                break;

            //Others
            default:
                $tag = '<a href="' . base_url() . 'assets/public/files/publicidad/' . $file . '">' . $file . '</a>';

        }

        return $tag;

    }

}