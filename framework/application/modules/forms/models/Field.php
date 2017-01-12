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

    public function setTranslations($input)
    {

        foreach(Language::all() as $key => $lang){

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => $this->type,
            ]);
            $trans->data = json_encode($input[$key]['translation']);
            $trans->save();

        }
    }

    /**
     * Deletes the form fields and its translations
     *
     * @param array $form_ids
     */
    public static function deleteWithTranslations(array $form_ids)
    {

        foreach ($form_ids as $id) {

            //Find the field
            $field = static::where('parent_id', $id)->where('section', 'form')->first();

            if($field) {
                //Delete any translations
                Translation::where('parent_id', $field->id)->where('type', static::$type)->delete();

                //Delete the field
                $field->delete();

            }

        }

    }

}