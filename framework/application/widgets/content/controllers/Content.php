<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/11/15
 * Time: 5:37 PM
 */

namespace App\Widget;

use App\Admin;
use App\View;
use App\Widget;
use Exception;

class Content implements \WidgetInterface {

	static function admin($id){

		try {
			$widget = Widget::find($id);
			$widget->types = Admin::getContentModules();
			$widget->data = json_decode($widget->data);
			return View::blade(APPPATH . 'widgets/content/views/admin.blade.php', $widget)->render();
		} catch (Exception $e) {
			show_error($e->getMessage());
		}

	}

	static function run($method){

		// Logic will be written here.


	}



}