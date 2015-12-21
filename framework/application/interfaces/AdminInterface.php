<?php
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface AdminInterface {

	/**
	 * List of items
	 *
	 * @return mixed
	 */
	public function index();

	/**
	 * Create form interface
	 *
	 * @return mixed
	 */
	public function create();

	/**
	 * Edit form interface
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function edit($id);

	/**
	 * Insert the item into database
	 *
	 * @return mixed
	 */
	public function insert();

	/**
	 * Update the item in the database
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function update($id);

	/**
	 * Remove the item from the database
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function delete($id);

	/**
	 * @param $id
	 * @param bool $new
	 *
	 * @return mixed
	 */
	public function _showView($id, $new = FALSE);

}