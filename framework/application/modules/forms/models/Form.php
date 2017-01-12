<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 7/28/2016
 * Time: 1:17 PM
 */

namespace Contact\Models;


use App\BaseModel;
use App\Language;
use App\Translation;

class Form extends BaseModel
{

    protected $appends = array('fields');

    public function getFieldsAttribute()
    {
        return Field::getByForm($this->id);
    }

    /*public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'name' => $input['name'][$lang->id],
            ];

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => 'form'
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }*/

}