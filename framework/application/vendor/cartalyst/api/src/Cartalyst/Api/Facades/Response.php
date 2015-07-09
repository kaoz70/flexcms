<?php namespace Cartalyst\Api\Facades;
/**
 * Part of the API package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    API
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Cartalyst\Api\Http\Response as ApiResponse;

class Response extends \Illuminate\Support\Facades\Response {

	/**
	 * Return a new API response from the application.
	 *
	 * @param  string|array  $data
	 * @param  int     $status
	 * @param  array   $headers
	 * @return \Cartalyst\Api\Http\Response
	 */
	public static function api($data = array(), $status = 200, array $headers = array())
	{
		return new ApiResponse($data, $status, $headers);
	}

}
