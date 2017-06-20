<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:13 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class FieldData extends BaseModel {

    protected $table = 'field_data';

    public function field(){
        return $this->belongsTo('App\Field')->first();
    }

    public function input() {
        return $this->field()->input();
    }

    /**
     * Check if data exists for this field
     *
     * @param Model $model
     * @param Field $field
     * @param $section
     * @return mixed
     */
    public static function getData(Model $model, Field $field, $section)
    {
        return static::where('parent_id', $model->id)
            ->where('field_id', $field->id)
            ->where('section', $section)
            ->first();
    }

    public static function setData($model, $section, $post)
    {

        //Set the user fields
        $fields = Field::where('section', $section)->get();

        foreach ($fields as $field) {

            $fieldData = FieldData::getData($model, $field, $section);
            if(!$fieldData) {
                $fieldData = new FieldData();
                $fieldData->parent_id = $model->id;
                $fieldData->field_id = $field->id;
                $fieldData->section = $section;
            }

            $fieldData->data = $post['field'][$field->id];
            $fieldData->save();

        }

    }

    public static function setTranslations($model, $section, $post)
    {

        //Set the user fields
        $fields = Field::where('section', 'product')->get();

        foreach ($fields as $field) {

            $fieldData = static::getData($model, $field, 'product');
            if(!$fieldData) {
                $fieldData = new static();
                $fieldData->parent_id = $model->id;
                $fieldData->field_id = $field->id;
                $fieldData->section = 'product';
            }

            $fieldData->save();

            foreach(Language::all() as $lang){
                $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $fieldData->id, 'type' => $section]);
                $trans->data = $post['field'][$field->id][$lang->id];
                $trans->save();
            }

        }

    }

}