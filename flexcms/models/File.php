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

    //Filesystem path to file
    private $file_path = '';

    //Web path to file
    private $url_path = '';

    protected $base_path = '';

    private $uploaded = false;

    protected $appends = ['file_path', 'url_path', 'uploaded'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

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
     * @param string $path
     */
    public function setFilePath($path)
    {
        $this->file_path = $path;
    }

    /**
     * @param string $path
     */
    public function setUrlPath($path)
    {
        $this->url_path = $path;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->file_path;
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        return $this->url_path;
    }

    /**
     * Add a timestamp to the end of the file to remove the browsers cache when uploading files with the same name
     *
     * @param $method
     * @return string
     */
    private function bustCache($method)
    {
        //Add a cache busting timestamp
        $timestamp = strtotime($this->updated_at);

        if($this->isUploaded()) {
            $path = $this->$method();
        } else {
            if($path = $this->$method()) {
                $path .= '?' . $timestamp ?: time();
            }
        }

        return $path;
    }

    public function getFilePathAttribute()
    {
        return $this->bustCache('getFilePath');
    }

    public function getUrlPathAttribute()
    {
        return $this->bustCache('getUrlPath');
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
     * @param bool $delete
     */
    public static function move($from, $to, $delete = true)
    {
        if (file_exists($from) && copy($from, $to)) {
            if($delete){
                //Delete the file
                unlink($from);
            }
        } else {
            throw new FileException("No se pudo mover el archivo [FROM: $from] [TO: $to]");
        }
    }

    /**
     * Created a copy of a file
     *
     * @param $from
     * @param $to
     */
    public static function copy($from, $to)
    {
        static::move($from, $to, false);
    }

    /**
     * Creates a model from the upload file data
     *
     * @param $uploadData
     * @return array
     */
    public static function fromUpload($uploadData)
    {

        $files = [];

        foreach ($uploadData as $fileData) {

            //Create a SEO friendly file name
            $name = str_replace($fileData['file_ext'], '', $fileData['client_name']);
            $name = trim($name);
            $name = str_replace('_', '-', $name);
            $fileName = str_replace('.', '-', $name);
            $name = str_replace('-', ' ', $fileName);

            $file = new static();
            $file->type = $fileData['is_image'] ? 'image' : 'file';
            $file->mime_type = $fileData['file_type'];
            $file->file_ext = $fileData['file_ext'];
            $file->file_name = $fileName;
            $file->name = $fileData['raw_name'];
            $file->image_alt = $name;
            $file->name = $name;
            $file->setUrlPath(base_url('uploads/' . $fileData['file_name']));
            $file->setFilePath($fileData['full_path']);
            $file->setUploaded(true);
            $files[] = $file;
        }

        return $files;

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