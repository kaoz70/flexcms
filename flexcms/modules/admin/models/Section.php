<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/10/15
 * Time: 5:09 PM
 */

namespace App;


use Cartalyst\Sentinel\Roles\RoleInterface;
use Illuminate\Database\Eloquent\Model;

class Section extends Model {

	static function get($group_id = NULL) {

		$query = self::where('view_menu', '=', 1)
		             ->orderBy('position', 'asc');

		if($group_id) {
			$query->join('user_sections', 'user_sections.id', '=', 'user_group_id')
			      ->where('user_sections.user_group_id', $group_id);
		}

		return $query->get();

	}

	static function getByRole(RoleInterface $role) {

	}

}