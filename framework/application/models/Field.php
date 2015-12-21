<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 4:13 PM
 */

namespace App;


class Field extends BaseModel {

	static function reorder($inputs, $section)
	{

		//Get the ids
		$ids = json_decode($inputs, true);

		for($i = 0 ; $i < static::where('section', $section)->get()->count() ; $i++){

			$row = static::find($ids[$i]);
			$row->position = $i + 1;
			$row->save();

		}

	}

}