<?php

namespace App;
use Gajus\Dindent\Exception\RuntimeException;
use Herrera\Json\Exception\Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 6:14 PM
 */
class BaseModel extends Model {

    protected $casts = [
        'enabled' => 'boolean',
        'important' => 'boolean',
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
     * Change the enabled attribute to boolean
     *
     * @param $value
     * @return bool
     */
    public function getEnabledAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Returns the content's translation as a json decoded object/array
     *
     * @param int $lang_id
     * @return mixed
     * @throws \TranslationException
     */
    public function getTranslation($lang_id)
    {

        if(!$this->type) {
            throw new \RuntimeException("Please set the model " . __CLASS__ . "'s protected type variable");
        }

        $translation = $this->hasOne('App\Translation', 'parent_id')
            ->where('language_id', $lang_id)
            ->where('type', $this->type)
            ->first();

        if($translation) {
            $this->translation = json_decode($translation->data);
            return $this->translation;
        } else {
            return $this->translation = null;
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
     * Get one content with all the available translations
     *
     * @param $content_id
     * @return mixed
     */
    static function getForEdit($content_id)
    {

        $content = static::find($content_id);
        $contentTrans = new EditTranslations();
        $contentTrans->setContent($content);

        foreach (Language::orderBy('position', 'asc')->get() as $lang) {
            $contentTrans->add($lang, $content->getTranslation($lang->id));
        }

        return $contentTrans->getAll();

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