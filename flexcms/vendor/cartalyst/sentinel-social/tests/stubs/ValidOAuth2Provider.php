<?php

use League\OAuth1\Client\Credentials\TokenCredentials;

class ValidOAuth2Provider extends League\OAuth2\Client\Provider\AbstractProvider
{

    public function urlAuthorize()
    {
    }

    public function urlAccessToken()
    {
    }

    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token)
    {
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
    }
}
