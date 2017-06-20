<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface AdminGalleryInterface {

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function edit($id);

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

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function reorder($id);

}