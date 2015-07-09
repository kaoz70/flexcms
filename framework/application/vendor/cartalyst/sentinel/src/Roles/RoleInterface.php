<?php namespace Cartalyst\Sentinel\Roles;
/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the license.txt file.
 *
 * @package    Sentinel
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

interface RoleInterface {

	/**
	 * Returns the role's primary key.
	 *
	 * @return int
	 */
	public function getRoleId();

	/**
	 * Returns the role's slug.
	 *
	 * @return string
	 */
	public function getRoleSlug();

	/**
	 * Returns all users for the role.
	 *
	 * @return \IteratorAggregate
	 */
	public function getUsers();

	/**
	 * Returns the users model.
	 *
	 * @return string
	 */
	public static function getUsersModel();

	/**
	 * Set the users model.
	 *
	 * @param  string  $usersModel
	 * @return void
	 */
	public static function setUsersModel($usersModel);

}
