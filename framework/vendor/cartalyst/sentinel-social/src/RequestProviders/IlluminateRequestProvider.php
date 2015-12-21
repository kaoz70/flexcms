<?php

/**
 * Part of the Sentinel Social package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel Social
 * @version    2.0.4
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Sentinel\Addons\Social\RequestProviders;

use Illuminate\Http\Request;

class IlluminateRequestProvider implements RequestProviderInterface
{
    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Creates a new Illuminate request provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function getOAuth1TemporaryCredentialsIdentifier()
    {
        return $this->request->input('oauth_token');
    }

    /**
     * {@inheritDoc}
     */
    public function getOAuth1Verifier()
    {
        return $this->request->input('oauth_verifier');
    }

    /**
     * {@inheritDoc}
     */
    public function getOAuth2Code()
    {
        return $this->request->input('code');
    }
}
