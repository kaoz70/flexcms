<?php namespace Cartalyst\Sentinel\Hashing;
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

class BcryptHasher implements HasherInterface {

	use Hasher;

	/**
	 * The hash strength.
	 *
	 * @var int
	 */
	public $strength = 8;

	/**
	 * {@inheritDoc}
	 */
	public function hash($value)
	{
		$salt = $this->createSalt();

		// Format the strength
		$strength = str_pad($this->strength, 2, '0', STR_PAD_LEFT);

		// Create prefix - "$2y$"" fixes blowfish weakness
		$prefix = PHP_VERSION_ID < 50307 ? '$2a$' : '$2y$';

		return crypt($value, $prefix.$strength.'$'.$salt.'$');
	}

	/**
	 * {@inheritDoc}
	 */
	public function check($value, $hashedValue)
	{
		return $this->slowEquals(crypt($value, $hashedValue), $hashedValue);
	}

}
