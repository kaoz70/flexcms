<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 12:54 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	function users() {
		return RoleUser::where('role_id', $this->id)->join('users', 'users.id', '=', 'role_users.user_id')->get();
	}

}