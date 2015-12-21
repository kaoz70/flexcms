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

namespace Cartalyst\Sentinel\Addons\Social;

use Closure;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Events\Dispatcher;
use Cartalyst\Support\Traits\EventTrait;
use Cartalyst\Sentinel\Users\UserInterface;
use Cartalyst\Sentinel\Sessions\NativeSession;
use Cartalyst\Sentinel\Sessions\SessionInterface;
use Cartalyst\Sentinel\Users\UserNotFoundException;
use League\OAuth1\Client\Server\Server as OAuth1Provider;
use Cartalyst\Sentinel\Addons\Social\Models\LinkInterface;
use Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepository;
use League\OAuth2\Client\Provider\AbstractProvider as OAuth2Provider;
use Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface;
use Cartalyst\Sentinel\Addons\Social\RequestProviders\NativeRequestProvider;
use Cartalyst\Sentinel\Addons\Social\RequestProviders\RequestProviderInterface;

class Manager
{
    use EventTrait;

    /**
     * The shared Sentinel instance.
     *
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * The link provider, used for associating users
     * with OAuth providers.
     *
     * @var \Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface
     */
    protected $links;

    /**
     * The request provider.
     *
     * @var \Cartalyst\Sentinel\Addons\Social\RequestProviders\ProviderInterface
     */
    protected $request;

    /**
     * A Sentinel session driver.
     *
     * @var \Cartalyst\Sentinel\Sessions\SessionInterface
     */
    protected $session;

    /**
     * Array of connections (credentials for creating provider instances).
     *
     * @var array
     */
    protected $connections = [];

    /**
     * Array of initialized providers.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Create a new Sentinel Social Manager instance.
     *
     * @param  \Cartalyst\Sentinel\Sentinel  $sentinel
     * @param  \Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface  $links
     * @param  \Cartalyst\Sentinel\Addons\Social\RequestProviders\RequestProviderInterface  $request
     * @param  \Cartalyst\Sentinel\Sessions\SessionInterface  $session
     * @param  \Illuminate\Events\Dispatcher  $dispatcher
     * @return void
     */
    public function __construct(Sentinel $sentinel, LinkRepositoryInterface $links = null, RequestProviderInterface $request = null, SessionInterface $session = null, Dispatcher $dispatcher = null)
    {
        $this->sentinel = $sentinel;

        if (isset($links)) {
            $this->links = $links;
        }

        if (isset($request)) {
            $this->request = $request;
        }

        if (isset($session)) {
            $this->session = $session;
        }

        if (isset($dispatcher)) {
            $this->dispatcher = $dispatcher;
        }
    }

    /**
     * Create a provider with the given connection slug and with the
     * optional callback URI. Instances of providers are cached.
     *
     * @param  string  $slug
     * @param  string  $callbackUri
     * @return mixed
     */
    public function make($slug, $callbackUri)
    {
        if (! isset($this->providers[$slug])) {
            $this->providers[$slug] = $this->createProvider($slug, $callbackUri);
        }

        return $this->providers[$slug];
    }

    /**
     * Returns the authorize URL for a connection with the given
     * slug. Abstracts away the differences between OAuth1 and
     * OAuth2 for a uniform API.
     *
     * @param  string  $slug
     * @param  string  $callbackUri
     * @return string
     */
    public function getAuthorizationUrl($slug, $callbackUri)
    {
        $provider = $this->make($slug, $callbackUri);

        // OAuth 1 is a three-legged authentication process
        // and thus we need to grab temporary credentials
        // first.
        if ($this->oauthVersion($provider) == 1) {
            $temporaryCredentials = $provider->getTemporaryCredentials();

            $this->session->put($temporaryCredentials);

            return $provider->getAuthorizationUrl($temporaryCredentials);
        }

        return $provider->getAuthorizationUrl();
    }

    /**
     * Authenticate against the current service. A closure may be provided
     * for a callback upon authentication as a shortcut for subscribing
     * to an event.
     *
     * @param  string  $slug
     * @param  string  $callbackUri
     * @param  Closure  $callback
     * @param  bool  $remember
     * @return \Cartalyst\Sentinel\Users\UserInterface
     */
    public function authenticate($slug, $callbackUri, Closure $callback = null, $remember = false)
    {
        // If a callback is supplied, we'll treat it as a global linking
        // callback. Specific callbacks for registering and existing
        // users can be registered outside of this method.
        if ($callback) {
            $this->linking($callback);
        }

        $provider = $this->make($slug, $callbackUri);
        $token    = $this->retrieveToken($provider);

        // We'll check if a user is already logged in. If so
        // Sentinel Social will link the logged in user.
        if ($user = $this->sentinel->getUser()) {
            $link = $this->linkLoggedIn($slug, $provider, $token, $user);
        } else {
            $link = $this->linkLoggedOut($slug, $provider, $token);
            $user = $link->getUser();

            $this->login($user, $remember);
        }

        return $user;
    }

    /**
     * Register a callback for when a new user is registering
     * through an OAuth provider.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function registering(Closure $callback)
    {
        $this->registerEvent('registering', $callback);
    }

    /**
     * Register a callback for when a new user is registered
     * through an OAuth provider.
     *
     * @param  \Closure $callback
     * @return void
     */
    public function registered(Closure $callback)
    {
        $this->registerEvent('registered', $callback);
    }

    /**
     * Register a callback for when an existing user is
     * linked with an OAuth provider.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function existing(Closure $callback)
    {
        $this->registerEvent('existing', $callback);
    }

    /**
     * Register a callback for when a user of any type is
     * linked with an OAuth provider.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function linking(Closure $callback)
    {
        $this->registerEvent('linking', $callback);
    }

    /**
     * Retrieves a link for the given slug, unique ID
     * and token.
     *
     * @param  string  $slug
     * @param  string  $uid
     * @param  mixed  $token
     * @return \Cartalyst\Sentinel\Addons\Social\Models\LinkInterface
     */
    protected function retrieveLink($slug, $uid, $token)
    {
        $link = $this->links->findLink($slug, $uid);
        $link->storeToken($token);
        return $link;
    }

    /**
     * Retrieves a link and associates a user (will lazily
     * create one) for the given slug, provider and token.
     *
     * @param  string  $slug
     * @param  mixed  $provider
     * @param  mixed  $token
     * @return \Cartalyst\Sentinel\Addons\Social\Models\LinkInterface
     */
    protected function linkLoggedIn($slug, $provider, $token, UserInterface $user)
    {
        $uid  = $provider->getUserUid($token);
        $link = $this->retrieveLink($slug, $uid, $token);

        $link->setUser($user);

        $this->fireEvent('sentinel.social.existing', [$link, $provider, $token, $slug]);
        $this->fireEvent('sentinel.social.linking', [$link, $provider, $token, $slug]);

        return $link;
    }

    /**
     * Retrieves a link and associates a user (will lazily
     * create one) for the given slug, provider and token.
     *
     * @param  string  $slug
     * @param  mixed  $provider
     * @param  mixed  $token
     * @return \Cartalyst\Sentinel\Addons\Social\Models\LinkInterface
     */
    protected function linkLoggedOut($slug, $provider, $token)
    {
        $uid  = $provider->getUserUid($token);
        $link = $this->retrieveLink($slug, $uid, $token);

        if (! $user = $link->getUser()) {
            $login = $provider->getUserEmail($token) ?: $uid.'@'.$slug;
            $user = $this->sentinel->findByCredentials(compact('login'));

            if ($user) {
                $link->setUser($user);

                $this->fireEvent('sentinel.social.existing', [$link, $provider, $token, $slug]);
            } else {
                $user = $this
                    ->sentinel
                    ->getUserRepository()
                    ->createModel();

                // Create a dummy password for the user
                $password = [$slug, $login, time(), mt_rand()];
                shuffle($password);
                $password = implode('', $password);

                $credentials = [
                    'login'    => $login,
                    'password' => $password,
                ];

                // Some providers give a first / last name, some don't.
                // If we only have one name, we'll just put it in the
                // "first_name" attribute.
                if (is_array($name = $provider->getUserScreenName($token))) {
                    $credentials['first_name'] = $name[0];
                    $credentials['last_name']  = $name[1];
                } elseif (is_string($name)) {
                    $credentials['first_name'] = $name;
                }

                $this->fireEvent('sentinel.social.registering', [$link, $provider, $token, $slug]);

                $user = $this->sentinel->registerAndActivate($credentials);
                $link->setUser($user);

                $this->fireEvent('sentinel.social.registered', [$link, $provider, $token, $slug]);
            }
        } else {
            $this->fireEvent('sentinel.social.existing', [$link, $provider, $token, $slug]);
        }

        $this->fireEvent('sentinel.social.linking', [$link, $provider, $token, $slug]);

        return $link;
    }

    /**
     * Logs the given user into Sentinel.
     *
     * @param  \Cartalyst\Sentinel\Users\UserInterface  $user
     * @param  bool  $remember
     * @return void
     */
    protected function login(UserInterface $user, $remember = false)
    {
        return $this->sentinel->authenticate($user, $remember);
    }

    /**
     * Retrieves a token (OAuth1 token credentials or OAuth2 access
     * token) for the given provider, abstracting away the
     * differences from the user.
     *
     * @param  mixed  $provider
     * @return mixed
     * @throws \Cartalyst\Sentinel\Addons\Social\AccessMissingException
     */
    protected function retrieveToken($provider)
    {
        if ($this->oauthVersion($provider) == 1) {
            $temporaryIdentifier = $this->request->getOAuth1TemporaryCredentialsIdentifier();

            if (! $temporaryIdentifier) {
                throw new AccessMissingException('Missing [oauth_token] parameter (used for OAuth1 temporary credentials identifier).');
            }

            $verifier = $this->request->getOAuth1Verifier();

            if (! $verifier) {
                throw new AccessMissingException('Missing [verifier] parameter.');
            }

            $temporaryCredentials = $this->session->get();

            $tokenCredentials = $provider->getTokenCredentials($temporaryCredentials, $temporaryIdentifier, $verifier);

            return $tokenCredentials;
        }

        $code = $this->request->getOAuth2Code();

        if (! $code) {
            throw new AccessMissingException("Missing [code] parameter.");
        }

        $accessToken = $provider->getAccessToken('authorization_code', compact('code'));

        return $accessToken;
    }

    /**
     * Add a connection to the manager.
     *
     * @param  string  $slug
     * @param  array   $connection
     * @return void
     */
    public function addConnection($slug, array $connection)
    {
        $this->connections[$slug] = $connection;
    }

    /**
     * Add multple connections to the manager.
     *
     * @param  array  $connections
     * @return void
     */
    public function addConnections(array $connections)
    {
        foreach ($connections as $slug => $connection) {
            $this->addConnection($slug, $connection);
        }
    }

    /**
     * Retrieve a connection with the given slug.
     *
     * @param  string  $slug
     * @return array
     * @throws \RuntimeException
     */
    public function getConnection($slug)
    {
        if (! isset($this->connections[$slug])) {
            throw new \RuntimeException("Cannot retrieve connection [$slug] as it has not been added.");
        }

        return $this->connections[$slug];
    }

    /**
     * Get all connections associated with the manager.
     *
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Get the links repository.
     *
     * @return \Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface
     */
    public function getLinksRepository()
    {
        if (! $this->links) {
            $this->links = $this->createLinksRepository();
        }

        return $this->links;
    }

    /**
     * Set the links repository.
     *
     * @param  \Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepositoryInterface  $links
     * @return void
     */
    public function setLinksRepository(LinkRepositoryInterface $links)
    {
        $this->links = $links;
    }

    /**
     * Creates a default links repository if none has been specified.
     *
     * @return \Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepository
     */
    protected function createLinksRepository()
    {
        $model = 'Cartalyst\Sentinel\Addons\Social\Models\Link';

        $users = $this->sentinel->getUserRepository();

        return new LinkRepository($users, $model);
    }

    /**
     * Creates a provider from a connection with the given slug.
     *
     * @param  string  $slug
     * @param  string  $callbackUri
     * @return mixed
     */
    protected function createProvider($slug, $callbackUri)
    {
        $connection = $this->getConnection($slug);

        $this->validateConnection($slug, $connection);

        list($oauthVersion, $driver) = $this->determineOAuth($connection['driver']);

        if ($oauthVersion == 1) {
            return $this->createOAuth1Provider($driver, $connection, $callbackUri);
        }

        return $this->createOAuth2Provider($driver, $connection, $callbackUri);
    }

    /**
     * Validates the given connection is satisfactory to initiate a driver.
     *
     * @param  string  $slug
     * @param  array   $connection
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateConnection($slug, array $connection)
    {
        if (! isset($connection['driver'])) {
            throw new \InvalidArgumentException("Class matching driver is required for [$slug] connection.");
        }

        if (! isset($connection['identifier']) or ! isset($connection['secret'])) {
            throw new \InvalidArgumentException("App identifier and secret are required for [$slug] connection.");
        }
    }

    /**
     * Determines the OAuth version of the given provider.
     *
     * @param  mixed  $provider
     * @return int
     * @throws \RuntimeException
     */
    protected function oauthVersion($provider)
    {
        if ($provider instanceof OAuth1Provider) {
            return 1;
        }

        if ($provider instanceof OAuth2Provider) {
            return 2;
        }
    }

    /**
     * Determines the OAuth version and class name for a driver with the
     * given name. Allows for built-in and custom drivers. An array is
     * returned, where the first value is the version and the second
     * is the class name to instantiate.
     *
     * @param  mixed  $driver
     * @return array
     * @throws \RuntimeException
     */
    protected function determineOAuth($driver)
    {
        // Built-in OAuth1 server
        if (class_exists($class = 'League\\OAuth1\\Client\\Server\\'.$driver)) {
            return [1, $class];
        }

        // Built-in OAuth2 provider
        if (class_exists($class = 'League\\OAuth2\\Client\\Provider\\'.$driver)) {
            return [2, $class];
        }

        // If the driver is a custom class which doesn't exist
        if (! class_exists($driver)) {
            throw new \RuntimeException("Failed to determine OAuth type as [$driver] does not exist.");
        }

        $parent = $this->getHighestParent($driver);

        if ($parent == 'League\\OAuth1\\Client\\Server\\Server') {
            return [1, $driver];
        }

        if ($parent == 'League\\OAuth2\\Client\\Provider\\AbstractProvider') {
            return [2, $driver];
        }

        throw new \RuntimeException("[$driver] does not inherit from a compatible OAuth provider class.");
    }

    /**
     * Retrieves the highest parent class name for the given child class name.
     *
     * @param  string  $childName
     * @return string  $parentName
     */
    protected function getHighestParent($childName)
    {
        // Find out what interfaces the driver implements
        $childClass = new \ReflectionClass($childName);

        // We'll reference the child name as the default parent name just
        // incase somebody passes through an object which doesn't extend
        // anything, this'll help them get a decent error message.
        $parentName = $childName;

        while ($parentClass = $childClass->getParentClass()) {
            $parentName = $parentClass->getName();
            $childClass = $parentClass;
        }

        return $parentName;
    }

    /**
     * Creates an OAuth1 provider from a connection with an optional
     * callback URI.
     *
     * @param  string $driver
     * @param  array  $connection
     * @param  string $callbackUri
     * @return \League\OAuth1\Client\Server\Server
     */
    protected function createOAuth1Provider($driver, $connection, $callbackUri)
    {
        $credentials = [
            'identifier'   => $connection['identifier'],
            'secret'       => $connection['secret'],
            'callback_uri' => $callbackUri,
        ];

        return new $driver($credentials);
    }

    /**
     * Creates an OAuth2 provider from a connection with an optional
     * callback URI.
     *
     * @param  string $driver
     * @param  array  $connection
     * @param  string $callbackUri
     * @return \League\OAuth2\Client\Provider\AbstractProvider
     */
    protected function createOAuth2Provider($driver, $connection, $callbackUri)
    {
        $options = [
            'clientId'     => $connection['identifier'],
            'clientSecret' => $connection['secret'],
            'redirectUri'  => $callbackUri,
            'scopes'       => isset($connection['scopes']) ? $connection['scopes'] : [],
        ];

        return new $driver($options);
    }

    /**
     * Register an event with Sentinel Social.
     *
     * @param  string   $name
     * @param  \Closure  $callback
     * @return void
     * @throws \RuntimeException
     */
    protected function registerEvent($name, Closure $callback)
    {
        $this->dispatcher->listen("sentinel.social.{$name}", $callback);
    }
}
