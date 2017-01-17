<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:14 PM
 */

namespace Contact\Models;


use App\Language;
use App\Translation;

class Field extends \App\Field {

    const FIELD_SECTION = 'form';

    protected static $type = 'form_field';

    public static function getByForm($id)
    {
        return Field::where('section', static::FIELD_SECTION)
            ->where('parent_id', $id)
            ->orderBy('position')
            ->get();
    }

    public function getTranslationsAttribute()
    {
        return Translation::where([
            'parent_id' => $this->id,
            'type' => static::$type,
        ])
            ->join('languages', 'languages.id', '=', 'translations.language_id')
            ->get();
    }

    public function setTranslations($translations)
    {

        foreach($translations as $lang){

            $trans = Translation::firstOrNew([
                'language_id' => $lang['language_id'],
                'parent_id' => $this->id,
                'type' => static::$type,
            ]);
            $trans->data = json_encode($lang['data']);
            $trans->save();

        }
    }

    /**
     * Deletes the form fields and its translations
     *
     * @param $form_id
     */
    public static function deleteWithTranslations($form_id)
    {

        //Find the field
        $field = static::where('parent_id', $form_id)->where('section', 'form')->first();

        if($field) {

            //Delete any translations
            Translation::where('parent_id', $field->id)->where('type', static::$type)->delete();

            //Delete the field
            $field->delete();

        }

    }

}