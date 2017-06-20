<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 8/1/2016
 * Time: 12:46 PM
 */

namespace catalog\models;


use App\BaseModel;
use App\FieldData;
use App\Language;
use App\Translation;

class Product extends BaseModel
{

    protected static $type = 'product';

    static function get($page_id)
    {
        return static::where('category_id', $page_id)->get();
    }

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'name' => $input['name'][$lang->id],
                'meta_keywords' => array_map('trim', explode(',', $input['meta_keywords'][$lang->id])),
                'meta_description' => $input['meta_description'][$lang->id],
                'meta_title' => $input['meta_title'][$lang->id],
            ];

            $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $this->id, 'type' => $this->type]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

    public function delete()
    {

        Translation::where('parent_id', $this->id)
            ->where('type', $this->type)
            ->delete();

        Translation::where('parent_id', $this->id)
            ->where('type', 'product_field_data')
            ->delete();

        FieldData::where('parent_id', $this->id)
            ->where('section', $this->type)
            ->delete();

        parent::delete();

    }

}