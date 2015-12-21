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
use Illuminate\Events\Dispatcher;
use Cartalyst\Sentinel\Addons\Social\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sentinel social manager instance.
     *
     * @var \Cartalyst\Sentinel\Addons\Social\Manager
     */
    protected $manager;

    /**
     * Sentinel instance.
     *
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * Request provider instance.
     *
     * @var \Cartalyst\Sentinel\Addons\Social\RequestProviders\RequestProviderInterface
     */
    protected $requestProvider;

    /**
     * Sentinel session instance.
     *
     * @var \Cartalyst\Sentinel\Sessions\SessionInterface
     */
    protected $session;

    /**
     * Dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * Link repository instance.
     *
     * @var \Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface
     */
    protected $linkRepository;

    /**
     * Setup resources and dependencies.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/stubs/InvalidProvider.php';
        require_once __DIR__.'/stubs/ValidOAuth1Provider.php';
        require_once __DIR__.'/stubs/ValidOAuth2Provider.php';
    }

    /**
     * Setup resources and dependencies.
     *
     * @return void
     */
    public function setUp()
    {
        $this->manager = new Manager(
            $this->sentinel        = m::mock('Cartalyst\Sentinel\Sentinel'),
            $this->linkRepository  = m::mock('Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface'),
            $this->requestProvider = m::mock('Cartalyst\Sentinel\Addons\Social\RequestProviders\RequestProviderInterface'),
            $this->session         = m::mock('Cartalyst\Sentinel\Sessions\SessionInterface'),
            $this->dispatcher      = new Dispatcher
        );
    }

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
    public function it_can_add_connections()
    {
        $connections = [
            'foo' => ['bar' => 'baz'],
            'baz' => ['foo' => 'bar'],
        ];

        $this->manager->addConnections($connections);

        $this->assertCount(2, $this->manager->getConnections());
        $this->assertEquals(['bar' => 'baz'], $this->manager->getConnection('foo'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_an_exception_when_retrieving_non_existent_connections()
    {
        $this->manager->getConnection('foo');
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_an_exception_when_making_non_existent_connections()
    {
        $this->manager->make('foo', 'http://example.com/callback');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Class matching driver is required
     */
    public function it_throws_an_exception_if_driver_is_missing()
    {
        $this->manager->addConnection('foo', []);

        $this->manager->make('foo', 'http://example.com/callback');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Failed to determine OAuth type as [Foo] does not exist.
     */
    public function it_throws_an_exception_if_driver_does_not_exist()
    {
        $this->manager->addConnection('foo', [
            'driver'     => 'Foo',
            'identifier' => 'appid',
            'secret'     => 'appsecret',
        ]);

        $this->manager->make('foo', 'http://example.com/callback');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage App identifier and secret are required
     */
    public function it_throws_an_exception_if_identifier_is_missing()
    {
        $this->manager->addConnection('foo', [
            'driver' => 'Foo',
        ]);

        $this->manager->make('foo', 'http://example.com/callback');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage App identifier and secret are required
     */
    public function it_throws_an_exception_if_secret_is_missing()
    {
        $this->manager->addConnection('foo', [
            'driver'     => 'Foo',
            'identifier' => 'bar',
        ]);

        $this->manager->make('foo', 'http://example.com/callback');
    }

    /** @test */
    public function it_can_make_built_in_oauth1_connection()
    {
        $this->manager->addConnection('twitter', [
            'driver'     => 'Twitter',
            'identifier' => 'appid',
            'secret'     => 'appsecret',
        ]);

        $provider = $this->manager->make('twitter', 'http://example.com/callback');

        $this->assertInstanceOf('League\OAuth1\Client\Server\Twitter', $provider);
        $this->assertEquals('appid', $provider->getClientCredentials()->getIdentifier());
        $this->assertEquals('appsecret', $provider->getClientCredentials()->getSecret());
    }

    /** @test */
    public function it_can_make_built_in_oauth2_connection()
    {
        $this->manager->addConnection('facebook', [
            'driver'     => 'Facebook',
            'identifier' => 'appid',
            'secret'     => 'appsecret',
        ]);

        $provider = $this->manager->make('facebook', 'http://example.com/callback');

        $this->assertInstanceOf('League\OAuth2\Client\Provider\Facebook', $provider);
        $this->assertEquals('appid', $provider->clientId);
        $this->assertEquals('appsecret', $provider->clientSecret);
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage does not inherit from a compatible OAuth provider class
     */
    public function it_throws_an_exception_when_making_custom_invalid_connection()
    {
        $this->manager->addConnection('foo', [
            'driver'     => 'InvalidProvider',
            'identifier' => 'appid',
            'secret'     => 'appsecret',
        ]);

        $provider = $this->manager->make('foo', 'http://example.com/callback');
    }

    /** @test */
    public function it_can_make_oauth1_provider()
    {
        $this->manager->addConnection('foo', [
            'driver'     => 'ValidOAuth1Provider',
            'identifier' => 'appid',
            'secret'     => 'appsecret',
        ]);

        $provider = $this->manager->make('foo', 'http://example.com/callback');

        $this->assertInstanceOf('ValidOAuth1Provider', $provider);
        $this->assertEquals('appid', $provider->getClientCredentials()->getIdentifier());
        $this->assertEquals('appsecret', $provider->getClientCredentials()->getSecret());
    }

    /** @test */
    public function it_can_make_oauth2_provider()
    {
        $this->manager->addConnection('foo', [
            'driver'     => 'ValidOAuth2Provider',
            'identifier' => 'appid',
            'secret'     => 'appsecret',
        ]);

        $provider = $this->manager->make('foo', 'http://example.com/callback');

        $this->assertInstanceOf('ValidOAuth2Provider', $provider);
        $this->assertEquals('appid', $provider->clientId);
        $this->assertEquals('appsecret', $provider->clientSecret);
    }

    /** @test */
    public function it_can_get_oauth1_authorization_url()
    {
        $manager = $this->mockManager('make, oauthVersion');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        $provider->shouldReceive('getTemporaryCredentials')
            ->once()
            ->andReturn('credentials');

        $this->session->shouldReceive('put')
            ->with('credentials')
            ->once();

        $provider->shouldReceive('getAuthorizationUrl')
            ->once()
            ->andReturn('uri');

        $this->assertEquals('uri', $manager->getAuthorizationUrl('foo', 'http://example.com/callback'));
    }

    /** @test */
    public function it_can_get_oauth2_authorization_url()
    {
        $manager = $this->mockManager('make, oauthVersion');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth2\Client\Provider\AbstractProvider'));

        $provider->shouldReceive('getAuthorizationUrl')
            ->once()
            ->andReturn('uri');

        $this->assertEquals('uri', $manager->getAuthorizationUrl('foo', 'http://example.com/callback'));
    }

    /**
     * @test
     * @expectedException Cartalyst\Sentinel\Addons\Social\AccessMissingException
     * @expectedExceptionMessage Missing [oauth_token] parameter
     */
    public function it_throws_an_exception_on_authentication_if_temporary_identitifer_is_missing()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        $this->requestProvider->shouldReceive('getOAuth1TemporaryCredentialsIdentifier')
            ->once();

        $user = $manager->authenticate('foo', 'http://example.com/callback');
    }

    /**
     * @test
     * @expectedException Cartalyst\Sentinel\Addons\Social\AccessMissingException
     * @expectedExceptionMessage Missing [verifier] parameter
     */
    public function it_throws_an_exception_on_authentication_if_verifier_is_missing()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        $this->requestProvider->shouldReceive('getOAuth1TemporaryCredentialsIdentifier')
            ->once()
            ->andReturn('1az');

        $this->requestProvider->shouldReceive('getOAuth1Verifier')
            ->once();

        $manager->authenticate('foo', 'http://example.com/callback');
    }

    /**
     * @test
     * @expectedException Cartalyst\Sentinel\Addons\Social\AccessMissingException
     * @expectedExceptionMessage Missing [code] parameter
     */
    public function it_throws_an_exception_on_oauth2_authentication_when_code_is_missing()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth2\Client\Provider\AbstractProvider'));

        $this->requestProvider->shouldReceive('getOAuth2Code')
            ->once();

        $user = $manager->authenticate('foo', 'http://example.com/callback');
    }

    /** @test */
    public function it_can_create_a_link_repository_if_none_exist()
    {
        $manager = new Manager($this->sentinel);

        $this->sentinel->shouldReceive('getUserRepository')
            ->once();

        $this->assertInstanceOf('Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface', $manager->getLinksRepository());
    }

    /** @test */
    public function it_can_set_and_retrieve_the_link_repository()
    {
        $linkRepository = m::mock('Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface');

        $this->manager->setLinksRepository($linkRepository);

        $this->assertSame($linkRepository, $this->manager->getLinksRepository());
    }

    /** @test */
    public function authenticate_with_linked_user()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        $this->requestProvider->shouldReceive('getOAuth1TemporaryCredentialsIdentifier')
            ->once()
            ->andReturn('identifier');

        $this->requestProvider->shouldReceive('getOAuth1Verifier')
            ->once()
            ->andReturn('verifier');

        // Mock retrieving credentials from the underlying package
        $this->session->shouldReceive('get')
            ->andReturn($temporaryCredentials = m::mock('League\OAuth1\Client\Credentials\TemporaryCredentials'));

        $provider->shouldReceive('getTokenCredentials')
            ->with($temporaryCredentials, 'identifier', 'verifier')
            ->once()
            ->andReturn($tokenCredentials = m::mock('League\OAuth1\Client\Credentials\TokenCredentials'));

        $provider->shouldReceive('getUserUid')
            ->once()
            ->andReturn(789);

        // Finding an appropriate link
        $this->linkRepository->shouldReceive('findLink')
            ->with('foo', 789)
            ->once()
            ->andReturn($link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\LinkInterface'));

        $link->shouldReceive('storeToken')
            ->with($tokenCredentials)
            ->once();

        // Logged in user
        $this->sentinel->shouldReceive('getUser')
            ->once();

        // Retrieving a user from the link
        $link->shouldReceive('getUser')
            ->andReturn($user = m::mock('Cartalyst\Sentinel\Users\UserInterface'));

        $this->sentinel->shouldReceive('authenticate')
            ->with($user, true)
            ->once();

        $manager->existing(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_existing'] = true;
        });

        $user = $manager->authenticate('foo', 'http://example.com/callback', function () {
            $_SERVER['__sentinel_social_linking'] = func_get_args();
        }, true);

        $this->assertTrue(isset($_SERVER['__sentinel_social_existing']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_linking']));

        $eventArgs = $_SERVER['__sentinel_social_linking'];

        unset($_SERVER['__sentinel_social_existing']);
        unset($_SERVER['__sentinel_social_linking']);

        $this->assertCount(4, $eventArgs);

        list($_link, $_provider, $_tokenCredentials, $_slug) = $eventArgs;

        $this->assertEquals($link, $_link);
        $this->assertEquals($provider, $_provider);
        $this->assertEquals($tokenCredentials, $_tokenCredentials);
        $this->assertEquals('foo', $_slug);
    }

    /** @test */
    public function authenticate_with_unlinked_existing_user()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        // Request proxy
        $this->requestProvider->shouldReceive('getOAuth1TemporaryCredentialsIdentifier')
            ->once()
            ->andReturn('identifier');

        $this->requestProvider->shouldReceive('getOAuth1Verifier')
            ->once()
            ->andReturn('verifier');

        // Mock retrieving credentials from the underlying package
        $this->session->shouldReceive('get')
            ->andReturn($temporaryCredentials = m::mock('League\OAuth1\Client\Credentials\TemporaryCredentials'));

        $provider->shouldReceive('getTokenCredentials')
            ->with($temporaryCredentials, 'identifier', 'verifier')
            ->once()
            ->andReturn($tokenCredentials = m::mock('League\OAuth1\Client\Credentials\TokenCredentials'));

        // Unique ID
        $provider->shouldReceive('getUserUid')
            ->once()
            ->andReturn(789);

        // Finding an appropriate link
        $this->linkRepository->shouldReceive('findLink')
            ->with('foo', 789)
            ->once()
            ->andReturn($link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\LinkInterface'));

        $link->shouldReceive('storeToken')
            ->with($tokenCredentials)
            ->once();

        // Logged in user
        $this->sentinel->shouldReceive('getUser')
            ->once();

        // Retrieving a user from the link
        $link->shouldReceive('getUser')
            ->once();

        // Retrieving an existing user
        $provider->shouldReceive('getUserEmail')
            ->once()
            ->andReturn('foo@bar.com');

        $this->sentinel->shouldReceive('findByCredentials')
            ->with(['login'=>'foo@bar.com'])
            ->once()
            ->andReturn($user = m::mock('Cartalyst\Sentinel\Users\UserInterface'));

        $link->shouldReceive('setUser')
            ->with($user)
            ->once();

        $link->shouldReceive('getUser')
            ->once()
            ->andReturn($user);

        // And finally, logging a user in
        $this->sentinel->shouldReceive('authenticate')
            ->with($user, true)
            ->once();

        $manager->existing(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_existing'] = true;
        });

        $user = $manager->authenticate('foo', 'http://example.com/callback', function () {
            $_SERVER['__sentinel_social_linking'] = func_get_args();
        }, true);

        $this->assertTrue(isset($_SERVER['__sentinel_social_existing']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_linking']));

        $eventArgs = $_SERVER['__sentinel_social_linking'];

        unset($_SERVER['__sentinel_social_existing']);
        unset($_SERVER['__sentinel_social_linking']);

        $this->assertCount(4, $eventArgs);

        list($_link, $_provider, $_tokenCredentials, $_slug) = $eventArgs;

        $this->assertEquals($link, $_link);
        $this->assertEquals($provider, $_provider);
        $this->assertEquals($tokenCredentials, $_tokenCredentials);
        $this->assertEquals('foo', $_slug);
    }

    /** @test */
    public function authenticate_with_oauth1_logged_in_user()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        // Request proxy
        $this->requestProvider->shouldReceive('getOAuth1TemporaryCredentialsIdentifier')
            ->once()
            ->andReturn('identifier');

        $this->requestProvider->shouldReceive('getOAuth1Verifier')
            ->once()
            ->andReturn('verifier');

        // Mock retrieving credentials from the underlying package
        $this->session->shouldReceive('get')
            ->andReturn($temporaryCredentials = m::mock('League\OAuth1\Client\Credentials\TemporaryCredentials'));

        $provider->shouldReceive('getTokenCredentials')
            ->with($temporaryCredentials, 'identifier', 'verifier')
            ->once()
            ->andReturn($tokenCredentials = m::mock('League\OAuth1\Client\Credentials\TokenCredentials'));

        // Unique ID
        $provider->shouldReceive('getUserUid')
            ->once()
            ->andReturn(789);

        // Finding an appropriate link
        $this->linkRepository->shouldReceive('findLink')
            ->with('foo', 789)
            ->once()
            ->andReturn($link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\LinkInterface'));

        $link->shouldReceive('storeToken')
            ->with($tokenCredentials)
            ->once();

        // Logged in user
        $this->sentinel->shouldReceive('getUser')
            ->once()
            ->andReturn($user = m::mock('Cartalyst\Sentinel\Users\UserInterface'));

        $link->shouldReceive('setUser')
            ->with($user)
            ->once();

        // Retrieving a user from the link
        $link->shouldReceive('getUser')
            ->andReturn($user);

        $manager->existing(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_existing'] = true;
        });

        $user = $manager->authenticate('foo', 'http://example.com/callback', function () {
            $_SERVER['__sentinel_social_linking'] = func_get_args();
        }, true);

        $this->assertTrue(isset($_SERVER['__sentinel_social_existing']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_linking']));

        $eventArgs = $_SERVER['__sentinel_social_linking'];

        unset($_SERVER['__sentinel_social_existing']);
        unset($_SERVER['__sentinel_social_linking']);

        $this->assertCount(4, $eventArgs);

        list($_link, $_provider, $_tokenCredentials, $_slug) = $eventArgs;

        $this->assertEquals($link, $_link);
        $this->assertEquals($provider, $_provider);
        $this->assertEquals($tokenCredentials, $_tokenCredentials);
        $this->assertEquals('foo', $_slug);
    }

    /** @test */
    public function authenticate_with_oauth2_linked_user()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth2\Client\Provider\AbstractProvider'));

        // Request proxy
        $this->requestProvider->shouldReceive('getOAuth2Code')
            ->once()
            ->andReturn('code');

        // Mock retrieving credentials from the underlying package
        $provider->shouldReceive('getAccessToken')
            ->with('authorization_code', ['code' => 'code'])
            ->once()
            ->andReturn($accessToken = m::mock('League\OAuth2\Client\Token\AccessToken'));

        // Unique ID
        $provider->shouldReceive('getUserUid')
            ->once()
            ->andReturn(789);

        // Finding an appropriate link
        $this->linkRepository->shouldReceive('findLink')
            ->with('foo', 789)
            ->once()
            ->andReturn($link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\LinkInterface'));

        $link->shouldReceive('storeToken')
            ->with($accessToken)
            ->once();

        // Logged in user
        $this->sentinel->shouldReceive('getUser')
            ->once();

        // Retrieving a user from the link
        $link->shouldReceive('getUser')
            ->andReturn($user = m::mock('Cartalyst\Sentinel\Users\UserInterface'));

        // And finally, logging a user in
        $this->sentinel->shouldReceive('authenticate')
            ->with($user, true)
            ->once();

        $manager->existing(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_existing'] = true;
        });

        $user = $manager->authenticate('foo', 'http://example.com/callback', function () {
            $_SERVER['__sentinel_social_linking'] = func_get_args();
        }, true);

        $this->assertTrue(isset($_SERVER['__sentinel_social_existing']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_linking']));

        $eventArgs = $_SERVER['__sentinel_social_linking'];

        unset($_SERVER['__sentinel_social_existing']);
        unset($_SERVER['__sentinel_social_linking']);

        $this->assertCount(4, $eventArgs);

        list($_link, $_provider, $_accessToken, $_slug) = $eventArgs;

        $this->assertEquals($link, $_link);
        $this->assertEquals($provider, $_provider);
        $this->assertEquals($accessToken, $_accessToken);
        $this->assertEquals('foo', $_slug);
    }

    /** @test */
    public function authenticate_with_oauth2_logged_in_user()
    {
        $manager = $this->mockManager('make');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth2\Client\Provider\AbstractProvider'));

        // Request proxy
        $this->requestProvider->shouldReceive('getOAuth2Code')
            ->once()
            ->andReturn('code');

        // Mock retrieving credentials from the underlying package
        $provider->shouldReceive('getAccessToken')
            ->with('authorization_code', ['code' => 'code'])
            ->once()
            ->andReturn($accessToken = m::mock('League\OAuth2\Client\Token\AccessToken'));

        // Unique ID
        $provider->shouldReceive('getUserUid')
            ->once()
            ->andReturn(789);

        // Finding an appropriate link
        $this->linkRepository->shouldReceive('findLink')
            ->with('foo', 789)
            ->once()
            ->andReturn($link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\LinkInterface'));

        $link->shouldReceive('storeToken')
            ->with($accessToken)
            ->once();

        // Logged in user
        $this->sentinel->shouldReceive('getUser')
            ->once()
            ->andReturn($user = m::mock('Cartalyst\Sentinel\Users\UserInterface'));

        $link->shouldReceive('setUser')
            ->with($user)
            ->once();

        // Retrieving a user from the link
        $link->shouldReceive('getUser')
            ->andReturn($user);

        $manager->existing(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_existing'] = true;
        });

        $user = $manager->authenticate('foo', 'http://example.com/callback', function () {
            $_SERVER['__sentinel_social_linking'] = func_get_args();
        }, true);

        $this->assertTrue(isset($_SERVER['__sentinel_social_existing']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_linking']));

        $eventArgs = $_SERVER['__sentinel_social_linking'];

        unset($_SERVER['__sentinel_social_existing']);
        unset($_SERVER['__sentinel_social_linking']);

        $this->assertCount(4, $eventArgs);

        list($_link, $_provider, $_accessToken, $_slug) = $eventArgs;

        $this->assertEquals($link, $_link);
        $this->assertEquals($provider, $_provider);
        $this->assertEquals($accessToken, $_accessToken);
        $this->assertEquals('foo', $_slug);
    }

    /** @test */
    public function authenticate_with_oauth1_with_unlinked_non_existent_user()
    {
        $manager = $this->mockManager('make');

        $user = m::mock('Cartalyst\Sentinel\Users\UserInterface');

        $manager->shouldReceive('make')
            ->with('foo', 'http://example.com/callback')
            ->once()
            ->andReturn($provider = m::mock('League\OAuth1\Client\Server\Server'));

        // Request proxy
        $this->requestProvider->shouldReceive('getOAuth1TemporaryCredentialsIdentifier')
            ->once()
            ->andReturn('identifier');

        $this->requestProvider->shouldReceive('getOAuth1Verifier')
            ->once()
            ->andReturn('verifier');

        // Mock retrieving credentials from the underlying package
        $this->session->shouldReceive('get')
            ->andReturn($temporaryCredentials = m::mock('League\OAuth1\Client\Credentials\TemporaryCredentials'));

        $provider->shouldReceive('getTokenCredentials')
            ->with($temporaryCredentials, 'identifier', 'verifier')
            ->once()
            ->andReturn($tokenCredentials = m::mock('League\OAuth1\Client\Credentials\TokenCredentials'));

        // Unique ID
        $provider->shouldReceive('getUserUid')
            ->once()
            ->andReturn(789);

        // Finding an appropriate link
        $this->linkRepository->shouldReceive('findLink')
            ->with('foo', 789)
            ->once()
            ->andReturn($link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\LinkInterface'));

        $link->shouldReceive('storeToken')
            ->with($tokenCredentials)
            ->once();

        $this->sentinel->shouldReceive('getUser')
            ->once();

        $link->shouldReceive('getUser')
            ->once();

        $link->shouldReceive('getUser')
            ->once()
            ->andReturn($user);

        $link->shouldReceive('setUser')
            ->with($user)
            ->once();

        $provider->shouldReceive('getUserEmail')
            ->once()
            ->andReturn('foo@bar.com');

        $this->sentinel->shouldReceive('findByCredentials')
            ->with(['login'=>'foo@bar.com'])
            ->once();

        $this->sentinel->shouldReceive('getUserRepository')
            ->once()
            ->andReturn($users = m::mock('Cartalyst\Sentinel\Users\UserRepositoryInterface'));

        $users->shouldReceive('createModel')
            ->once()
            ->andReturn($user);

        $provider->shouldReceive('getUserScreenName')
            ->once()
            ->andReturn(['Ben', 'Corlett']);

        $this->sentinel->shouldReceive('registerAndActivate')
            ->once()
            ->andReturn($user);

        $this->sentinel->shouldReceive('authenticate')
            ->with($user, true)
            ->once()
            ->andReturn($user);

        $manager->registering(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_registering'] = true;
        });

        $manager->registered(function ($link, $provider, $token, $slug) {
            $_SERVER['__sentinel_social_registered'] = true;
        });

        $user = $manager->authenticate('foo', 'http://example.com/callback', function () {
            $_SERVER['__sentinel_social_linking'] = func_get_args();
        }, true);

        $this->assertTrue(isset($_SERVER['__sentinel_social_registering']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_registered']));
        $this->assertTrue(isset($_SERVER['__sentinel_social_linking']));

        $eventArgs = $_SERVER['__sentinel_social_linking'];

        unset($_SERVER['__sentinel_social_registering']);
        unset($_SERVER['__sentinel_social_registered']);
        unset($_SERVER['__sentinel_social_linking']);

        $this->assertCount(4, $eventArgs);

        list($_link, $_provider, $_tokenCredentials, $_slug) = $eventArgs;

        $this->assertEquals($link, $_link);
        $this->assertEquals($provider, $_provider);
        $this->assertEquals($tokenCredentials, $_tokenCredentials);
        $this->assertEquals('foo', $_slug);
    }

    /**
     * Creates a manager mock.
     *
     * @param  string  $methods
     * @return \Mockery\MockInterface
     */
    protected function mockManager($methods)
    {
        $manager = m::mock("Cartalyst\Sentinel\Addons\Social\Manager[{$methods}]", [
            $this->sentinel,
            $this->linkRepository,
            $this->requestProvider,
            $this->session,
            $this->dispatcher,
        ]);

        return $manager;
    }
}
