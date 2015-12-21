<?php

/**
 * Part of the Nested Sets package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Nested Sets
 * @version    3.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\NestedSets\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\NestedSets\Nodes\NodeTrait;
use Cartalyst\NestedSets\Nodes\NodeInterface;

class NodeTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup resources and dependencies.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/stubs/DummyWorker.php';
    }

    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
        Node::unsetPresenter();
    }

    public function testChildrenManipulation()
    {
        $node = new Node;

        $node->setChildren(['foo']);
        $this->assertCount(1, $node->getChildren());
        $this->assertEquals(['foo'], $node->getChildren());

        $node->clearChildren();
        $this->assertEmpty($node->getChildren());

        $node->setChildAtIndex($child1 = new Node, 2);
        $this->assertCount(1, $children = $node->getChildren());
        $this->assertEquals($child1, reset($children));
        $this->assertEquals(2, key($children));
    }

    public function testSettingHelper()
    {
        $node = new Node;
        $this->addMockConnection($node);
        $node->setWorker('DummyWorker');
        $this->assertInstanceOf('DummyWorker', $node->createWorker());
    }

    public function testPresenter()
    {
        $this->assertNull(Node::getPresenter());
        Node::setPresenter($presenter = m::mock('Cartalyst\NestedSets\Presenter'));
        $this->assertEquals($presenter, Node::getPresenter());

        $node = new Node;
        $presenter->shouldReceive('presentAs')->with($node, 'foo', 'bar', 0)->once()->andReturn('success');
        $this->assertEquals('success', $node->presentAs('foo', 'bar'));

        $presenter->shouldReceive('presentAs')->with($node, 'baz', 'qux', 2)->once()->andReturn('success');
        $this->assertEquals('success', $node->presentAsBaz('qux', 2));
    }

    public function testFindingChildrenAlwaysReturnsArray()
    {
        $node = m::mock('Cartalyst\NestedSets\Tests\Node[createWorker]');
        $node->shouldReceive('createWorker')->once()->andReturn($worker = m::mock('Cartalyst\NestedSets\Workers\WorkerInterface'));
        $worker->shouldReceive('tree')->with($node, 0, null)->once()->andReturn($treeNode = new Node);
        $this->assertEquals([$treeNode], $node->findChildren());
    }

    protected function addMockConnection($model)
    {
        $model->setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));

        $resolver->shouldReceive('connection')->andReturn(m::mock('Illuminate\Database\Connection'));
        $model->getConnection()->shouldReceive('getQueryGrammar')->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));
        $model->getConnection()->shouldReceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
    }
}

class Node extends Model implements NodeInterface {
    use NodeTrait;
}
