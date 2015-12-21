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
use Cartalyst\Sentinel\Addons\Social\Models\Link;
use League\OAuth2\Client\Token\AccessToken as OAuth2AccessToken;
use League\OAuth1\Client\Credentials\TokenCredentials as OAuth1TokenCredentials;

class LinkTest extends PHPUnit_Framework_TestCase
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

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_when_passed_an_invalid_token()
    {
        $link = new Link;

        $token = new \stdClass;

        $link->storeToken($token);
    }

    /** @test */
    public function it_has_a_user_relationship()
    {
        $link = new Link;

        $this->addMockConnection($link);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $link->user());
    }

    /** @test */
    public function it_can_set_and_retrieve_the_user()
    {
        $user = m::mock('Cartalyst\Sentinel\Users\EloquentUser');

        $link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\Link[user]');

        $link->getConnection()
            ->getQueryGrammar()
            ->shouldReceive('getDateFormat')
            ->andReturn('Y-m-d H:i:s');

        $link->getConnection()
            ->getPostProcessor()
            ->shouldReceive('processInsertGetId');

        $link->getConnection()
            ->shouldReceive('getQueryGrammar')
            ->andReturn($grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar'));

        $link->getConnection()
            ->getQueryGrammar()
            ->shouldReceive('compileInsertGetId');

        $link->shouldReceive('user')
            ->twice()
            ->andReturn($relation = m::mock('Illuminate\Database\Eloquent\Relations\BelongsTo'));

        $relation->shouldReceive('associate')
            ->with($user)
            ->once();

        $relation->shouldReceive('getResults')
            ->once()
            ->andReturn($user);

        $link->setUser($user);

        $this->assertSame($user, $link->getUser());
    }

    /** @test */
    public function it_can_store_oauth1_tokens()
    {
        $link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\Link[save]');

        $tokenCredentials = new OAuth1TokenCredentials;
        $tokenCredentials->setIdentifier('foo');
        $tokenCredentials->setSecret('bar');

        $link->shouldReceive('save')
            ->once();

        $link->storeToken($tokenCredentials);

        $this->assertEquals('foo', $link->oauth1_token_identifier);
        $this->assertEquals('bar', $link->oauth1_token_secret);
    }

    /** @test */
    public function it_can_store_oauth2_tokens()
    {
        $link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\Link[save]');

        $this->addMockConnection($link);

        $link->getConnection()
            ->getQueryGrammar()
            ->shouldReceive('getDateFormat')
            ->andReturn('Y-m-d H:i:s');

        $accessToken = new OAuth2AccessToken([
            'access_token' => 'foo',
            'expires_in' => 10,
            'refresh_token' => 'bar',
        ]);

        $link->shouldReceive('save')
            ->once();

        $link->storeToken($accessToken);

        $this->assertEquals('foo', $link->oauth2_access_token);
        $this->assertEquals('bar', $link->oauth2_refresh_token);
        $this->assertInstanceOf('DateTime', $link->oauth2_expires);
        $this->assertEquals(time() + 10, $link->oauth2_expires->getTimestamp());
    }

    /** @test */
    public function it_can_set_and_retrieve_the_users_model()
    {
        Link::setUsersModel('foo');

        $this->assertEquals('foo', Link::getUsersModel());
    }

    /** @test */
    public function it_does_not_replace_refresh_tokens_with_null()
    {
        $link = m::mock('Cartalyst\Sentinel\Addons\Social\Models\Link[save]');

        $link->oauth2_refresh_token = 'bar';

        $this->addMockConnection($link);

        $link->getConnection()
            ->getQueryGrammar()
            ->shouldReceive('getDateFormat')
            ->andReturn('Y-m-d H:i:s');

        $accessToken = new OAuth2AccessToken([
            'access_token' => 'foo',
            'expires_in' => 10,
            'refresh_token' => null,
        ]);

        $link->shouldReceive('save')
            ->once();

        $link->storeToken($accessToken);

        $this->assertEquals('foo', $link->oauth2_access_token);
        $this->assertEquals('bar', $link->oauth2_refresh_token);
        $this->assertInstanceOf('DateTime', $link->oauth2_expires);
        $this->assertEquals(time() + 10, $link->oauth2_expires->getTimestamp());
    }

    /**
     * Adds a mock connection to the object.
     *
     * @param  mixed  $model
     * @return void
     */
    protected function addMockConnection($model)
    {
        $model->setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));

        $resolver->shouldReceive('connection')
            ->andReturn(m::mock('Illuminate\Database\Connection'));

        $model->getConnection()
            ->shouldReceive('getQueryGrammar')
            ->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));

        $model->getConnection()
            ->shouldReceive('getPostProcessor')
            ->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
    }
}
