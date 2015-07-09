<?php namespace Cartalyst\Api;
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

use Illuminate\Http\Request;

class UriParser {

	protected $mainRequest;

	protected $defaultVersion;

	protected $uriNamespace = 'api';

	/**
	 * Create a new URI Parser instance.
	 *
	 * @param  \Illuminate\Http\Request  $mainRequest
	 * @param  int  $defaultVersion
	 * @param  string  $uriNamespace
	 * @return void
	 */
	public function __construct(Request $mainRequest, $defaultVersion, $uriNamespace = null)
	{
		$this->mainRequest    = $mainRequest;
		$this->defaultVersion = $defaultVersion;

		if (isset($uriNamespace))
		{
			$this->uriNamespace = $uriNamespace;
		}
	}

	/**
	 * Parses the relative URI provided, looking for versioning and
	 * any other relevant information inside it to pass
	 * back to the user.
	 *
	 * @param  string  $uri
	 * @return array
	 */
	public function parseRelativeUri($uri)
	{
		if ($result = filter_var($uri, FILTER_VALIDATE_URL) !== false)
		{
			throw new \InvalidArgumentException("Method should only be used for parsing relative URIs, not entire ones such as [$uri].");
		}

		$uri      = ltrim($uri, '/');
		$uriParts = explode('/', $uri);

		// Let's attempt to extract the version from the URI parts.
		preg_match('/v(\d+)/', $uriParts[0], $matches);

		if (empty($matches))
		{
			$version = $this->defaultVersion;
		}
		else
		{
			$version = (int) $matches[1];
			array_shift($uriParts);
		}

		return array($version, implode('/', $uriParts));
	}

	public function convertParsedUriArrayToString(array $uri)
	{
		list($version, $relativeUri) = $uri;

		return sprintf('%s/v%d/%s',
			$this->uriNamespace,
			$version,
			$relativeUri
		);
	}

	public function parseRoutePattern($pattern)
	{
		if ($result = filter_var($pattern, FILTER_VALIDATE_URL) !== false)
		{
			throw new \InvalidArgumentException("Method should only be used for parsing relative route patterns, not entire ones such as [$pattern].");
		}

		if (starts_with($pattern, '/') and strlen($pattern) > 1)
		{
			$pattern = substr($pattern, 1);
		}

		if ( ! starts_with($pattern, '{api}'))
		{
			return false;
		}

		preg_match('/^(?:(\\{api\}\/)(v(\d+)\/)?)?/', $pattern, $matches);

		if (count($matches) === 4)
		{
			$version = (int) $matches[3];
		}
		elseif (count($matches) === 2)
		{
			$version = $this->defaultVersion;
		}
		else
		{
			throw new \InvalidArgumentException("Could not extract version from route pattern [$pattern].");
		}

		$uri = str_replace($matches[0], '', $pattern);

		return array($version, $uri);
	}

	public function createUri($uri)
	{
		if ( ! is_array($uri))
		{
			$uri = $this->parseRelativeUri($uri);
		}

		return sprintf('%s/%s',
			$this->mainRequest->root(),
			$this->convertParsedUriArrayToString($uri)
		);
	}

	public function getMainRequest()
	{
		return $this->mainRequest;
	}

	public function getDefaultVersion()
	{
		return $this->defaultVersion;
	}

	public function setUriNamespace($uriNamespace)
	{
		$this->uriNamespace = $uriNamespace;
	}

	public function getUriNamespace()
	{
		return $this->uriNamespace;
	}

}
