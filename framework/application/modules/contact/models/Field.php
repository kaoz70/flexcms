<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:14 PM
 */

namespace Contact\Models;


use App\FieldData;
use App\Language;
use App\Translation;
use App\User;

class Field extends \App\Field {

    public function setTranslations($input, $section)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'label' => $input['label'][$lang->id],
                'placeholder' => $input['placeholder'][$lang->id],
            ];

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => $section
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}