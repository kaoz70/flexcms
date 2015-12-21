<?php

namespace App;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/25/15
 * Time: 10:17 AM
 */
class Requirements {

	/**
	 * @param $php_version
	 */
	static function check($php_version)
	{

		if( ! version_compare($php_version, PHP_VERSION)) {
			$data = [
				"heading" => "Error",
				"message" => "The provided PHP version should be higher than: <strong>" . $php_version . "</strong>. Current version is: <strong>" . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . "." . PHP_RELEASE_VERSION . "</strong>",
			];

			echo View::blade(APPPATH . 'views/errors/html/general.blade.php', $data)->render();
			exit;
		}
	}

}