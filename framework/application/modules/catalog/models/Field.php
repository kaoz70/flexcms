<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:14 PM
 */

namespace Catalog\Models;


use App\FieldData;
use App\Language;
use App\Translation;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Field extends \App\Field {

    protected $section = 'product';

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'label' => $input['label'][$lang->id],
            ];

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => 'product_field'
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

    public function fieldData(Model $model)
    {

        $fieldData = parent::fieldData($model);
        $translation = Translation::where('parent_id', $fieldData->id)
            ->where('language_id', $this->getLang())
            ->where('type', 'product_field_data')
            ->first();

        return $translation ?: (object) [
            'data' => ''
        ];

    }

}