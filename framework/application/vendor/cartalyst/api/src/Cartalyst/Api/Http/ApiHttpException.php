<?php namespace Cartalyst\Api\Http;
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

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiHttpException extends HttpException {

	/**
	 * Array of errors.
	 *
	 * @var array
	 */
	protected $errors = array();

	// http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	/**
	 * Is response invalid?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isInvalid()
	{
		return $this->getStatusCode() < 100 || $this->getStatusCode() >= 600;
	}

	/**
	 * Is response informative?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isInformational()
	{
		return $this->getStatusCode() >= 100 && $this->getStatusCode() < 200;
	}

	/**
	 * Is response successful?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isSuccessful()
	{
		return $this->getStatusCode() >= 200 && $this->getStatusCode() < 300;
	}

	/**
	 * Is the response a redirect?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isRedirection()
	{
		return $this->getStatusCode() >= 300 && $this->getStatusCode() < 400;
	}

	/**
	 * Is there a client error?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isClientError()
	{
		return $this->getStatusCode() >= 400 && $this->getStatusCode() < 500;
	}

	/**
	 * Was there a server side error?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isServerError()
	{
		return $this->getStatusCode() >= 500 && $this->getStatusCode() < 600;
	}

	/**
	 * Is the response OK?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isOk()
	{
		return 200 === $this->getStatusCode();
	}

	/**
	 * Is the response forbidden?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isForbidden()
	{
		return 403 === $this->getStatusCode();
	}

	/**
	 * Is the response a not found error?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isNotFound()
	{
		return 404 === $this->getStatusCode();
	}

	/**
	 * Is the response a redirect of some form?
	 *
	 * @param string $location
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isRedirect($location = null)
	{
		return in_array($this->getStatusCode(), array(201, 301, 302, 303, 307, 308)) && (null === $location ?: $location == $this->headers->get('Location'));
	}

	/**
	 * Is the response empty?
	 *
	 * @return Boolean
	 *
	 * @api
	 */
	public function isEmpty()
	{
		return in_array($this->getStatusCode(), array(201, 204, 304));
	}

	/**
	 * Set the errors for the API HTTP Exception.
	 *
	 * @param  array  $errors
	 * @return array
	 */
	public function setErrors($errors)
	{
		$this->errors = $errors;
	}

	/**
	 * Returns the errors for the API HTTP Exception.
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

}
