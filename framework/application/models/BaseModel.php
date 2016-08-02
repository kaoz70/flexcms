<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 6:14 PM
 */
class BaseModel extends Model {

    private $lang;

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
     * @param $lang
     * @return mixed
     * @throws \TranslationException
     */
    public function getTranslation($lang)
    {

        if(!$this->type) {
            throw new \TranslationException("Please set the model's type");
        }

        $translation = $this->hasOne('App\Translation', 'parent_id')
            ->where('language_id', $lang)
            ->where('type', $this->type)
            ->first();

        if($translation) {
            $this->translation = json_decode($translation->data);
            return $this->translation;
        } else {
            throw new \TranslationException("Content translation does not exist");
        }

    }

    /**
     * Get all the translations available for the content
     *
     * @param $type
     * @return array
     */
    public function getTranslations($type)
    {

        $languages = Language::all();
        $arr = [];

        foreach($languages as $lang) {
            try {
                $arr[$lang->id] = $this->getTranslation($lang->id, $type);
            } catch (\TranslationException $e) {
                //No translation available
                $arr[$lang->id] = '';
            }
        }

        return $arr;

    }

    protected static function reorder($inputs, $section)
    {

        //Get the ids
        $ids = json_decode($inputs, true);

        for($i = 0 ; $i < static::get()->count() ; $i++){
            $row = static::find($ids[$i]);
            $row->position = $i + 1;
            $row->save();
        }

    }

}