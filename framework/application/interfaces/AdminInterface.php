<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface AdminInterface {

	/**
	 * @return mixed
	 */
	public function index();

	/**
	 * @return mixed
	 */
	public function create();

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function edit($id);

	/**
	 * @return mixed
	 */
	public function insert();

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function update($id);

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function delete($id);

}