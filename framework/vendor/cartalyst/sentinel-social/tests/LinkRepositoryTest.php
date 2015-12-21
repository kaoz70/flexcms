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
use Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepository;

class LinkRepositoryTest extends PHPUnit_Framework_TestCase
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
    public function it_can_find_existing_links()
    {
        $linkRepository = m::mock('Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepository[createModel]');

        $linkRepository->shouldReceive('createModel')
            ->once()
            ->andReturn($model = m::mock('Cartalyst\Sentinel\Addons\Social\Models\Link'));

        $model->shouldReceive('newQuery')
            ->once()
            ->andReturn($model);

        $model->shouldReceive('with')
            ->with('user')
            ->once()
            ->andReturn($model);

        $model->shouldReceive('where')
            ->with('provider', '=', 'slug')
            ->once()
            ->andReturn($model);

        $model->shouldReceive('where')
            ->with('uid', '=', 789)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('first')
            ->once()
            ->andReturn('success');

        $this->assertEquals('success', $linkRepository->findLink('slug', 789));
    }

    /** @test */
    public function it_will_create_a_link_if_non_existent_found()
    {
        $linkRepository = m::mock('Cartalyst\Sentinel\Addons\Social\Repositories\LinkRepository[createModel]');

        $linkRepository->shouldReceive('createModel')
            ->twice()
            ->andReturn($model = m::mock('Cartalyst\Sentinel\Addons\Social\Models\Link'));

        $model->shouldReceive('newQuery')
            ->once()
            ->andReturn($model);

        $model->shouldReceive('with')
            ->with('user')
            ->once()
            ->andReturn($model);

        $model->shouldReceive('where')
            ->with('provider', '=', 'slug')
            ->once()
            ->andReturn($model);

        $model->shouldReceive('where')
            ->with('uid', '=', 789)
            ->once()
            ->andReturn($model);

        $model->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $model->shouldReceive('fill')->with([
            'provider' => 'slug',
            'uid'      => 789,
        ])->once();

        $model->shouldReceive('save')
            ->once();

        $this->assertEquals($model, $linkRepository->findLink('slug', 789));
    }

    /** @test */
    public function it_can_create_models()
    {
        $provider = new LinkRepository;

        $model = $provider->createModel();

        $this->assertInstanceOf('Cartalyst\Sentinel\Addons\Social\Models\Link', $model);
    }
}
