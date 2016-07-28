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

class Address extends BaseModel
{

    protected $table = 'addresses';

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'address' => $input['address'][$lang->id],
            ];

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => 'address'
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}