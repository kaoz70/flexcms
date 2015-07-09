<?php namespace Cartalyst\Sentinel\Sessions;
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

use CI_Session as Session;

class CISession implements SessionInterface {

	/**
	 * The CodeIgniter session driver.
	 *
	 * @var \CI_Session
	 */
	protected $store;

	/**
	 * The session key.
	 *
	 * @var string
	 */
	protected $key = 'cartalyst_sentinel';

	/**
	 * Create a new CodeIgniter Session driver.
	 *
	 * @param  \CI_Session  $store
	 * @param  string  $key
	 * @return void
	 */
	public function __construct(Session $store, $key = null)
	{
		$this->store = $store;

		if (isset($key))
		{
			$this->key = $key;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function put($value)
	{
		$this->store->set_userdata($this->key, serialize($value));
	}

	/**
	 * {@inheritDoc}
	 */
	public function get()
	{
		$value = $this->store->userdata($this->key);

		if ($value)
		{
			return unserialize($value);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function forget()
	{
		$this->store->unset_userdata($this->key);
	}

}
