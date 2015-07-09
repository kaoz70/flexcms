<?php namespace Cartalyst\Sentinel\Checkpoints;
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
use RuntimeException;

class NotActivatedException extends RuntimeException {

	/**
	 * The user which caused the exception.
	 *
	 * @var \Cartalyst\Sentinel\Users\UserInterface
	 */
	protected $user;

	/**
	 * Returns the user.
	 *
	 * @return \Cartalyst\Sentinel\Users\UserInterface
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Sets the user associated with Sentinel (does not log in).
	 *
	 * @param  \Cartalyst\Sentinel\Users\UserInterface
	 * @return void
	 */
	public function setUser(UserInterface $user)
	{
		$this->user = $user;
	}

}
