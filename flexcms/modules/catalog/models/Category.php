<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 8/1/2016
 * Time: 4:20 PM
 */

namespace catalog\models;


use App\Language;
use App\Translation;

class Category extends \App\Category
{

    protected $section = 'catalog';

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'name' => $input['name'][$lang->id],
                'description' => $input['description'][$lang->id],
            ];

            $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $this->id, 'type' => $this->section]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}