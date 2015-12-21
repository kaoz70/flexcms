<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 6:14 PM
 */
class BaseModel extends Model {

	/**
	 * Returns the content's translation as a json decoded object/array
	 *
	 * @param $lang
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function getTranslation($lang)
	{

		try {

			$translation = $this->hasOne('App\Translation', 'parent_id')
			                    ->where('language_id', $lang)
			                    ->first();

			if($translation) {
				$this->translation = json_decode($translation->data);
				return $this->translation;
			} else {
				throw new \Exception("Content translation does not exist");
			}

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

	}

	/**
	 * Get all the translations available for the content
	 *
	 * @return array
	 */
	public function getTranslations()
	{

		$languages = Language::all();
		$arr = [];

		foreach($languages as $lang) {
			try {
				$arr[$lang->id] = $this->getTranslation($lang->id);
			} catch (\Exception $e) {
				//No translation available
				$arr[$lang->id] = '';
			}
		}

		return $arr;

	}

}