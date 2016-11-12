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
            return null;
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

        $languages = Language::all();
        $arr = [];

        foreach($languages as $lang) {
            try {
                $arr[$lang->id] = $this->getTranslation($lang->id);
            } catch (\TranslationException $e) {
                //No translation available
                $arr[$lang->id] = '';
            } catch (RuntimeException $e) {
                throw new RuntimeException($e->getMessage());
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