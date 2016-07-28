<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:14 PM
 */

namespace Auth\Models;


use App\FieldData;
use App\Language;
use App\Translation;
use App\User;

class Field extends \App\Field {

    protected $table = 'fields';

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'label' => $input['label'][$lang->id],
                'placeholder' => $input['placeholder'][$lang->id],
            ];

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => 'user_field'
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

    public function createUserField()
    {

        $users = User::where('temporary', 0)->get();

        foreach ($users as $user) {
            $fieldData = new FieldData();
            $fieldData->parent_id = $user->id;
            $fieldData->field_id = $this->id;
            $fieldData->section = 'user';
            $fieldData->save();
        }

    }

}