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

namespace Cartalyst\Sentinel\Addons\Social\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Sentinel\Addons\Social\RequestProviders\NativeRequestProvider as Provider;

class NativeRequestProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_retrieve_oauth1_temporary_credentials_identifier()
    {
        $provider = new Provider;

        $_GET['oauth_token'] = 'oauth_token_value';

        $this->assertEquals('oauth_token_value', $provider->getOAuth1TemporaryCredentialsIdentifier());
    }

    /** @test */
    public function it_can_retrieve_oauth1_verifier()
    {
        $provider = new Provider;

        $_GET['oauth_verifier'] = 'verifier_value';

        $this->assertEquals('verifier_value', $provider->getOAuth1Verifier());
    }

    /** @test */
    public function it_can_retrieve_oauth2_code()
    {
        $provider = new Provider;

        $_GET['code'] = 'code_value';

        $this->assertEquals('code_value', $provider->getOAuth2Code());
    }
}
