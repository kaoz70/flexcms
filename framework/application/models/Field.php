<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:13 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Field extends BaseModel {

    protected $section;

    protected static function reorder($inputs, $section)
    {

        //Get the ids
        $ids = json_decode($inputs, true);

        for($i = 0 ; $i < static::where('section', $section)->get()->count() ; $i++){

            $row = static::find($ids[$i]);
            $row->position = $i + 1;
            $row->save();

        }

    }

    public function input()
    {
        return $this->belongsTo('App\Input')->first();
    }

    /**
     * Creates the fields for any current user, product, slideshow, etc
     *
     * @param $rows
     * @param $section
     */
    public function createChildTableFields($rows, $section)
    {

        foreach ($rows as $row) {
            $fieldData = new FieldData();
            $fieldData->parent_id = $row->id;
            $fieldData->field_id = $this->id;
            $fieldData->section = $section;
            $fieldData->save();
        }

    }

    /**
     * @param Model $model
     * @return FieldData|mixed
     */
    public function fieldData(Model $model)
    {

        $fieldData = FieldData::getData($model, $this, $this->section);

        if(!$fieldData) {
            $fieldData = new FieldData();
        }

        return $fieldData;

    }

}