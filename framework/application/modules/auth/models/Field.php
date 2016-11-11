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

    protected $section = 'user';
    protected $type = 'user_field';

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
                'type' => $this->type,
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}