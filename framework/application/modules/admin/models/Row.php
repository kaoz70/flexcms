<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/11/15
 * Time: 3:51 PM
 */

namespace App;

use stdClass;

class Row {

	/**
	 * Adds a row to the page
	 *
	 * @param $page_id
	 * @param $col_quantity
	 *
	 * @return mixed
	 */
	static function add($page_id, $col_quantity)
	{
		$page = Category::find($page_id);

		/*
		 * Estructura basica
		 */
		$row = new stdClass();
		$row->class = '';
		$row->expanded = FALSE;

		$spans = new stdClass();
		$spans->large = 12;
		$spans->medium = 12;
		$spans->small = 12;

		$offset = new stdClass();
		$offset->large = 0;
		$offset->medium = 0;
		$offset->small = 0;

		$push = new stdClass();
		$push->large = 0;
		$push->medium = 0;
		$push->small = 0;

		$pull = new stdClass();
		$pull->large = 0;
		$pull->medium = 0;
		$pull->small = 0;

		$columna = new stdClass();
		$columna->class = '';
		$columna->span = $spans;
		$columna->offset = $offset;
		$columna->push = $push;
		$columna->pull = $pull;
		$columna->widgets = array();

		for ($i = 0; $i < $col_quantity; $i++){
			$row->columns[] = $columna;
		}

		//Does the page have the structure set? and is it valid?
		if($page_data = json_decode($page->data)) {
			$data['key'] = count($page_data->structure);

			//Append the row
			$page_data->structure[] = $row;

		} else {
			$data['key'] = 0;

			//Add the row
			$page_data = new stdClass();
			$page_data->structure = array($row);

		}

		$page->data = json_encode($page_data);
		$page->save();

		$data['row'] = $row;
		$data['page_id'] = $page_id;

		return $data;

	}

	/**
	 * Removes a row from a page
	 *
	 * @param $page_id
	 * @param $row_id
	 */
	static function remove($page_id, $row_id)
	{

		$page = Category::find($page_id);
		$page_data = json_decode($page->data);

		//Get all the row's widgets and delete them
		$widgets = array();
		foreach ($page_data->structure[$row_id]->columns as $column) {
			$widgets = array_merge($widgets, $column->widgets);
		}
		if($widgets) {
			Widget::whereIn('category_id', $widgets)->get()->delete();
		}

		unset($page_data->structure[$row_id]);
		$page_data->structure = array_values($page_data->structure);

		//Save the structure to DB
		$page->data = json_encode($page_data);
		$page->save();

	}

	/**
	 * Sorts a page rows
	 *
	 * @param $page_id
	 * @param $input
	 */
	static function sort($page_id, $input)
	{

		$from_index = $input['from_index'];
		$to_index = $input['to_index'];

		$page = Category::find($page_id);
		$page_data = json_decode($page->data);

		$row = $page_data->structure[$from_index];

		unset($page_data->structure[$from_index]); //remove the element
		array_splice($page_data->structure, $to_index, 0, array($row)); //Place the a new array in the correct place of array

		//Remove any empty values
		foreach($page_data->structure as $key=>$val){
			if(empty($val)) unset($page_data->structure[$key]);
		}
		$page_data->structure = array_values($page_data->structure);

		//Save the structure to DB
		$page->data = json_encode($page_data);
		$page->save();

	}



	/**
	 * Copies the structure from one page to another, and duplicates each widget
	 *
	 * @param $input
	 *
	 * @return bool
	 * @throws \Exception
	 */
	static function copyStructure($input)
	{
		$from_page = $input['from_page'];
		$to_page = $input['to_page'];
		$page = Category::find($from_page);
		$target_page = Category::find($to_page);
		$page_data = json_decode($page->data);
		$new_widget_ids = array();

		if(isset($page_data->structure)) {

			//Loop through the rows
			foreach ($page_data->structure as $row_key => $row) {

				//Loop through the columns
				foreach ($row->columns as $col_key => $col) {

					$new_ids = array();

					if($col->widgets) {

						//Create the new widgets
						foreach(Widget::whereIn('category_id', $col->widgets)->get() as $widget) {
							$new_widget = $widget->replicate();
							$new_widget->category_id = $to_page;
							$new_widget->save();

							$new_ids[] = $new_widget_ids[] = $new_widget->id;

							//Get the translations
							foreach(Translation::where('parent_id', '=', $from_page) as $trans) {
								$new_trans = $trans->replicate();
								$new_trans->parent_id = $new_widget->id;
								$new_trans->save();
							}

						}

					}

					$col->modules = $new_ids;

				}

			}

			//Delete any old/unlinked modules from the target page if any
			Widget::where('category_id', '=', $to_page)
				->whereNotIn('category_id', $new_widget_ids) //don't delete the new ones
				->delete();

			//Save the structure to DB
			$target_page->data = json_encode($page_data);
			$target_page->save();

			return TRUE;

		} else {
			throw new \Exception('Source page has no structure set');
		}

	}

}