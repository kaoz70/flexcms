<?php

namespace App;
use Gajus\Dindent\Exception\RuntimeException;
use Intervention\Image\ImageManagerStatic as Intervention;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 6:14 PM
 */
class BaseModel extends Model {

    protected static $type = 'base';
    protected static $image_folder = 'assets/images/';
    protected static $section_id = null;

    protected $casts = [
        'enabled' => 'boolean',
        'important' => 'boolean',
        'data' => 'json',
    ];

    private $lang;

    /**
     * Translation data for one language
     *
     * @var
     */
    public $translation;

    /**
     * Holds all the item's translations
     *
     * @var
     */
    public $translations;

    protected $appends = ['translation', 'translations', 'images'];

    /**
     * @return array
     */
    public function getImagesAttribute()
    {
        return isset($this->images) ? $this->images : null;
    }

    /**
     * @return array
     */
    public function setImagesAttribute($val)
    {
        return $this->images = $val;
    }

    /**
     * @return object
     */
    public function getTranslationAttribute()
    {
        return $this->translation;
    }

    /**
     * @return array
     */
    public function getTranslationsAttribute()
    {
        return $this->translations;
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return self::$type;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Returns the content's translation as a json decoded object/array
     *
     * @param int $lang_id
     * @return mixed
     * @throws \CMSException
     */
    public function getTranslation($lang_id)
    {

        if(!$this->getType()) {
            throw new \CMSException("Please set the model " . __CLASS__ . "'s protected type variable");
        }

        $trans = $this->hasOne('App\Translation', 'parent_id')
            ->where('language_id', $lang_id)
            ->where('type', static::$type)
            ->first();

        if($trans) {
            $this->translation = $trans->data;
            $this->createProperties($trans->data);
            return $this->translation;
        } else {
            return $this->translation = null;
        }

    }

    /**
     * Add translation properties directly to the model
     *
     * @param $data
     */
    public function createProperties($data){
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * Get all the translations available for the content
     *
     * @return array
     * @throws RuntimeException
     */
    public function getTranslations()
    {

        $arr = [];

        foreach(Language::orderBy('position', 'asc')->get() as $lang) {
            $arr[] = $this->getTranslation($lang->id);
        }

        $this->translations = $arr;

        return $arr;

    }

    /**
     * Save the images
     *
     * @param array $sections
     */
    public function setImages(array $sections)
    {

        //Delete all the files
        //File::where('parent_id', $this->id)->delete();

        if(!$sections) {
            return;
        }
        
        //Create the content folder if it doesn't exist yet
        $path = static::$image_folder . $this->id . '/';
        Utils::createFolder($path);

        // Image sections
        foreach ($sections as $section) {

            $configs = ImageConfig::where('image_section_id', $section['id'])->get();

            // TODO: Delete the file
            foreach ($section['delete'] as $key => $file) {
                $toDelete = File::find($file['id']);
                $toDelete->delete();
            }

            //Every uploaded file
            foreach ($section['files'] as $key => $file) {

                //Save the original image if its the first upload
                if($file['file_path']) {
                    File::move($file['file_path'], $path . $file['file_name'] . '_orig' . $file['file_ext']);
                }

                //Create the file row in the database
                foreach ($configs as $key => $config) {
                    $image = Image::where('parent_id', $this->id)
                        ->where('section_id', $section['id'])
                        ->where('sufix', $config->sufix)
                        ->first();

                    if (!$image) {
                        $image = new Image();
                        $image->parent_id = $this->id;
                        $image->sufix = $config->sufix;
                        $image->section_id = $section['id'];
                    }

                    $image->position = $key + 1;
                    $image->name = isset($file['file_name']) ? $file['file_name'] : $file['name'];
                    $image->data = [
                        'coords' => $file['data']['coords'],
                        'colors' => $section['colors'],
                        'image_alt' => $file['type'],
                    ];
                    $image->type = $file['type'];
                    $image->file_name = $file['file_name'];

                    //Create the image
                    if(isset($configs[$key - 1])) {
                        $prevConfig = $configs[$key - 1];
                        if($prevConfig->force_jpg) {
                            $file['file_ext'] = '.jpg';
                        }
                        $origPath = $path . $image->file_name . $prevConfig->sufix . $file['file_ext'];
                        $img = Image::process($file, $path, $config, $file['data']['coords'], $origPath);
                    } else {
                        $img = Image::process($file, $path, $config, $file['data']['coords']);
                    }

                    $image->mime_type = $img->mime();
                    $image->file_ext = $img->extension;
                    $image->save();

                }

            }
        }
    }

    /**
     * Get a model with all the available translations
     *
     * @param $content_id
     * @return mixed
     */
    static function getForEdit($content_id)
    {

        $content = static::find($content_id);
        $content->images = ImageSection::getImages(static::$type, $content_id, static::$image_folder . $content_id . '/');
        $contentTrans = new EditTranslations();
        $contentTrans->setContent($content);

        foreach (Language::orderBy('position', 'asc')->get() as $lang) {
            $contentTrans->add($lang, $content->getTranslation($lang->id));
        }

        return $contentTrans->getAll();

    }

    /**
     * Reorders the passed items
     *
     * @param $items
     * @param null $section
     */
    public static function reorder($items, $section = null)
    {

        if($section) {
            $count = static::where(static::$section_id, $section)->get()->count();
        } else {
            $count = static::get()->count();
        }

        for($i = 0 ; $i < $count; $i++){
            $row = static::find($items[$i]['id']);
            $row->position = $i + 1;
            $row->save();
        }

    }

}