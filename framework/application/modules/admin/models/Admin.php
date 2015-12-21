<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 12/1/15
 * Time: 1:48 PM
 */

namespace App;


class Admin {

	static private function getFolders()
	{
		$path = APPPATH . 'modules/';
		$folders = [];
		foreach (new \DirectoryIterator($path) as $file) {
			if ($file->isDot()) continue;
			if ($file->isDir()) {
				$folders[] = $file->getFilename();
			}
		}

		return $folders;

	}

	static function getModules()
	{
		$path = APPPATH . 'modules/';
		$items = [];

		//Get menu item data
		foreach (self::getFolders() as $folder) {
			$config = json_decode(file_get_contents($path . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config.json'));
			$config->menu->controller = 'admin/'  . $folder;
			if($config->menu->show) {
				$items[$folder] = $config->menu;
			}
		}

		return $items;

	}

	static function getContentModules()
	{

		$path = APPPATH . 'modules/';
		$items = [];

		//Get menu item data
		foreach (self::getFolders() as $folder) {
			$config = json_decode(file_get_contents($path . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config.json'));
			if(isset($config->content) && $config->content) {
				$items[$folder] = $config;
			}
		}

		return $items;
	}

}