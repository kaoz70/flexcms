<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 1:36 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Config extends Model {

	protected $table = 'config';

	/**
	 * Formats the site's config values so that we can access them easier
	 *
	 * @return array
	 */
	static function get()
	{

		$result = [];

		foreach (self::all() as $row) {
			$result[$row->key] = $row->value;
		}

		return $result;
	}

	/**
	 * Get the site's index page
	 *
	 * @param $lang
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	static function siteIndex($lang)
	{
		$conf = self::get();
		$page = Category::find($conf['index_page_id']);

		if($page) {
			$page->data = $page->getTranslation($lang);
			return $page;
		} else {
			throw new \Exception('No site index page defined, define one on the backend');
		}

	}

}