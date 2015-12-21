<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace App;

class Content extends BaseModel {

	protected $table = 'content';

	/**
	 * Get all content pages (the ones that can be edited)
	 *
	 * @param bool|false $only_ids
	 *
	 * @return array
	 */
	static function getEditable($only_ids = FALSE)
	{

		if($only_ids) {

			$content = Widget::where('type', '=', 'content')
			             ->join('categories', 'categories.id', '=', 'category_id')
			             ->get();

			$ids = [];

			foreach($content as $page){
				$ids[] = $page->id;
			}

			return $ids;

		}

		return Widget::where('type', '=', 'content')
		             ->join('categories', 'categories.id', '=', 'category_id')
		             ->get();

	}



}