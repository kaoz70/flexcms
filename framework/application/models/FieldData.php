<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:13 PM
 */

namespace App;


use Cartalyst\Sentinel\Users\EloquentUser;

class FieldData extends BaseModel {

    protected $table = 'field_data';

    public function field(){
        return $this->belongsTo('App\Field')->first();
    }

    public function input() {
        return $this->field()->input();
    }

    /**
     * Check if the User field data exists for this field and user
     *
     * @param EloquentUser $user
     * @param Field $field
     * @return mixed
     */
    public static function userData(EloquentUser $user, Field $field)
    {
        return static::where('parent_id', $user->id)
            ->where('field_id', $field->id)
            ->where('section', 'user')
            ->first();
    }

}