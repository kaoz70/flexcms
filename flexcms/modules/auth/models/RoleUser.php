<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 3:29 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model{

	function users()
	{
		return $this->hasMany('App\User')->get();
	}

}