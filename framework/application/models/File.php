<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 01/23/17
 * Time: 3:06 PM
 */

namespace App;


use Symfony\Component\HttpFoundation\File\Exception\FileException;

class File extends BaseModel {

    private $file_path = '';

    private $uploaded = false;

    protected $appends = ['file_path', 'uploaded'];

    /**
     * @param bool $uploaded
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;
    }

    /**
     * @return bool
     */
    public function isUploaded()
    {
        return $this->uploaded;
    }

    /**
     * @param string $file_path
     */
    public function setFilePath($file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->file_path;
    }

    public function getFilePathAttribute()
    {

        //Add a cache busting timestamp
        $timestamp = strtotime($this->updated_at);

        $path = '';

        if($this->isUploaded()) {
            $path = $this->getFilePath();
        } else {
            $this->getFilePath() . '?' . $timestamp ?: time();
        }

        return $path;

    }

    public function getUploadedAttribute()
    {
        return $this->isUploaded();
    }

    /**
     * Moves the file form one place to another
     *
     * @param $from
     * @param $to
     */
    public static function move($from, $to)
    {
        if (copy($from, $to)) {
            //Delete the file
            unlink($from);
        } else {
            throw new FileException("No se pudo mover el archivo");
        }
    }

    /**
     * Creates a model from the upload file data
     *
     * @param $uploadData
     * @return static
     */
    public static function fromUpload($uploadData)
    {
        $file = new static();
        $file->type = $uploadData['is_image'] ? 'image' : 'file';
        $file->mime_type = $uploadData['file_type'];
        $file->file_ext = $uploadData['file_ext'];
        $file->raw_name = $uploadData['raw_name'];
        $file->setFilePath($uploadData['full_path']);
        $file->setUploaded(true);
        return $file;
    }

    /**
     * Deletes the file associated in the filesystem
     * @param $path
     * @return bool
     */
    public function deleteFile($path)
    {

        $path .= '/' . $this->id . $this->file_ext;

        if(file_exists($path) && unlink($path)) {
            return true;
        }

        return false;

    }

}