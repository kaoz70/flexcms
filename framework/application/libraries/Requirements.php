<?php

namespace App;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/25/15
 * Time: 10:17 AM
 */
class Requirements {

	static function check()
	{
		self::php("5.4.0");
		self::mod_rewrite();
		self::pdo();
	}

	/**
	 * Check if the provided PHP version is the correct one
	 *
	 * @param $version
	 */
	private static function php($version)
	{
		if( ! version_compare($version, PHP_VERSION, '<=')) {
			self::error("The provided PHP version should be higher than: <strong>" . $version . "</strong>. Current version is: <strong>" . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . "." . PHP_RELEASE_VERSION . "</strong>");
		}
	}

	/**
	 * Check if mod_rewrite is enabled in the server, this provides us with SEO friendly URLs
	 */
	private static function mod_rewrite()
	{
		if( ! array_key_exists('HTTP_MOD_REWRITE', $_SERVER)) {
			self::error("Apache module <strong>mod_rewrite</strong> is not available or is not enabled.");
		}
	}
	
	/**
	 * Check if PDO class is available, we need this because of Laravel's database connection and Models
	 */
	private static function pdo()
	{
		if ( ! extension_loaded('pdo')) {
    		self::error("PHP extension <strong>PDO</strong> is not available or is not enabled.");
		}
	}

	/**
	 * Shows the error message
	 *
	 * @param $message
	 */
	private static function error($message)
	{
		$data = [
			"heading" => "Error",
			"message" => $message,
		];

		echo View::blade(APPPATH . 'views/errors/html/general.blade.php', $data)->render();
		exit;
	}

}