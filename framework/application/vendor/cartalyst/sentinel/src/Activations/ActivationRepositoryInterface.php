<?php namespace Cartalyst\Sentinel\Activations;
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

use Cartalyst\Sentinel\Users\UserInterface;

interface ActivationRepositoryInterface {

	/**
	 * Create a new activation record and code.
	 *
	 * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
	 * @return string
	 */
	public function create(UserInterface $user);

	/**
	 * Checks if a valid activation for the given user exists.
	 *
	 * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
	 * @return bool
	 */
	public function exists(UserInterface $user);

	/**
	 * Completes the activation for the given user.
	 *
	 * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
	 * @param  string  $code
	 * @return bool
	 */
	public function complete(UserInterface $user, $code);

	/**
	 * Checks if a valid activation has been completed.
	 *
	 * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
	 * @return bool
	 */
	public function completed(UserInterface $user);

	/**
	 * Remove an existing activation (deactivate).
	 *
	 * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
	 * @return bool|null
	 */
	public function remove(UserInterface $user);

	/**
	 * Remove expired activation codes.
	 *
	 * @return int
	 */
	public function removeExpired();

}
