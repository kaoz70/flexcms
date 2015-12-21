<?php namespace Cartalyst\Dependencies;
/**
 * Part of the Dependencies package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Dependencies
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

interface DependentInterface {

	/**
	 * Get the dependent's slug (unique identifier).
	 *
	 * @return string
	 */
	public function getSlug();

	/**
	 * Get an array of dependencies' slugs.
	 *
	 * @return string|array
	 */
	public function getDependencies();

}
