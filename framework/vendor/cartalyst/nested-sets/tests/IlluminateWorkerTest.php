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

use Closure;
use NodeStub;
use stdClass;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Grammars\Grammar;
use Cartalyst\NestedSets\Workers\IlluminateWorker as Worker;

class IlluminateWorkerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup resources and dependencies.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/stubs/NodeStub.php';
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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreatingZeroGap()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $worker->createGap(1, 0, 1);
    }

    public function testCreatingGap()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $query->shouldReceive('where')->with('lft', '>=', 1)->once()->andReturn($query);
        $query->shouldReceive('where')->with('tree', '=', 3)->once()->andReturn($query);
        $query->shouldReceive('update')->with(['lft' => '"lft" + 2'])->once();

        $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $query->shouldReceive('where')->with('rgt', '>=', 1)->once()->andReturn($query);
        $query->shouldReceive('where')->with('tree', '=', 3)->once()->andReturn($query);
        $query->shouldReceive('update')->with(['rgt' => '"rgt" + 2'])->once();

        $worker->createGap(1, 2, 3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemovingNegativeGap()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $worker->removeGap(1, -2, 3);
    }

    public function testRemovingGap()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[createGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);
        $worker->shouldReceive('createGap')->with(1, -2, 3);
        $worker->removeGap(1, 2, 3);
    }

    public function testSlidingNodeOutOfTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[getNodeSize,removeGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $worker->shouldReceive('getNodeSize')->with($node)->once()->andReturn(1);
        $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $node->shouldReceive('getAttribute')->with('lft')->times(3)->andReturn(2);
        $query->shouldReceive('where')->with('lft', '>=', 2)->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('rgt')->times(3)->andReturn(3);
        $query->shouldReceive('where')->with('rgt', '<=', 3)->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('tree')->twice()->andReturn(1);
        $query->shouldReceive('where')->with('tree', '=', 1)->once()->andReturn($query);
        $query->shouldReceive('update')->with(['lft' => '"lft" + -3', 'rgt' => '"rgt" + -3'])->once();

        $worker->shouldReceive('removeGap')->with(2, 2, 1)->once();

        $node->shouldReceive('setAttribute')->with('lft', -1)->once();
        $node->shouldReceive('setAttribute')->with('rgt', 0)->once();

        $worker->slideNodeOutOfTree($node);
    }

    public function testSlidingNodeInTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[getNodeSize,createGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $worker->shouldReceive('getNodeSize')->with($node)->once()->andReturn(1);
        $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $node->shouldReceive('getAttribute')->with('lft')->once()->andReturn(-1);
        $query->shouldReceive('where')->with('lft', '>=', -1)->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(0);
        $query->shouldReceive('where')->with('rgt', '<=', 0)->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('tree')->twice()->andReturn(1);
        $query->shouldReceive('where')->with('tree', '=', 1)->once()->andReturn($query);
        $query->shouldReceive('update')->with(['lft' => '"lft" + 3', 'rgt' => '"rgt" + 3'])->once();

        $worker->shouldReceive('createGap')->with(2, 2, 1)->once();

        $node->shouldReceive('setAttribute')->with('lft', 2)->once();
        $node->shouldReceive('setAttribute')->with('rgt', 3)->once();

        $worker->slideNodeInTree($node, 2);
    }

    public function testTransaction()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $connection->getPdo()->shouldReceive('inTransaction')->andReturn(false);

        $callback = function (Connection $connection) {
            $_SERVER['__nested_sets.dynamic_query'] = true;
        };

        $connection->shouldReceive('transaction')->with(m::on(function ($actualCallback) use ($connection, $callback) {
            if ($actualCallback == $callback) {
                $actualCallback($connection);
            }

            return ($actualCallback instanceof Closure);
        }))->once();

        $worker->ensureTransaction($callback);
        $this->assertTrue(isset($_SERVER['__nested_sets.dynamic_query']));
        unset($_SERVER['__nested_sets.dynamic_query']);
    }

    public function testInsertNode()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $connection->shouldReceive('table')->with('categories')->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));

        $node1 = $this->getMockNode();
        $node1->shouldReceive('getIncrementing')->once()->andReturn(true);
        $node1->shouldReceive('getAllAttributes')->once()->andReturn($attributes = ['foo' => 'baz', 'depth' => 2]);
        $query->shouldReceive('insertGetId')->with(['foo' => 'baz'])->once()->andReturn('bar');
        $node1->shouldReceive('setAttribute')->with('id', 'bar')->once();
        $node1->shouldReceive('syncOriginal')->once();
        $node1->shouldReceive('afterCreate')->once();
        $worker->insertNode($node1);

        $node2 = $this->getMockNode();
        $node2->shouldReceive('getIncrementing')->once()->andReturn(false);
        $node2->shouldReceive('getAllAttributes')->once()->andReturn($attributes);
        $query->shouldReceive('insert')->with(['foo' => 'baz', 'id' => null])->once();
        $node2->shouldReceive('syncOriginal')->once();
        $node2->shouldReceive('afterCreate')->once();
        $worker->insertNode($node2);
    }

    public function testUpdateNode()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $connection->shouldReceive('table')->with('categories')->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));

        $node->shouldReceive('getAllAttributes')->once()->andReturn($attributes = ['foo' => 'baz', 'depth' => 2]);
        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(2);

        $query->shouldReceive('where')->with('id', '=', 2)->once()->andReturn($query);
        $query->shouldReceive('update')->with(['foo' => 'baz'])->once()->andReturn('bar');
        $node->shouldReceive('syncOriginal')->once();
        $node->shouldReceive('afterUpdate')->once();
        $worker->updateNode($node);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testHydrateNodeForNonExistentNode()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $connection->shouldReceive('table')->with('categories')->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));

        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $query->shouldReceive('where')->with('id', '=', 1)->once()->andReturn($query);
        $query->shouldReceive('first')->with(['lft', 'rgt', 'tree'])->once()->andReturn(null);

        $worker->hydrateNode($node);
    }

    public function testHydrateNode()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $connection->shouldReceive('table')->with('categories')->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));

        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $query->shouldReceive('where')->with('id', '=', 1)->once()->andReturn($query);

        $result = new stdClass;
        $result->lft  = 2;
        $result->rgt  = 3;
        $result->tree = 4;

        $query->shouldReceive('first')->with(['lft', 'rgt', 'tree'])->once()->andReturn($result);

        $node->shouldReceive('setAttribute')->with('lft', 2)->once();
        $node->shouldReceive('setAttribute')->with('rgt', 3)->once();
        $node->shouldReceive('setAttribute')->with('tree', 4)->once();

        $worker->hydrateNode($node);
    }

    public function testAllFlatWithNoTree()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $node->shouldReceive('findAll')->once()->andReturn($allFlat = ['foo', 'bar']);
        $this->assertEquals($allFlat, $worker->allFlat());
    }

    public function testAllFlatWithTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[getReservedAttributeName]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $node->shouldReceive('findAll')->once()->andReturn([
            $node1 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node2 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node3 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
        ]);

        $worker->shouldReceive('getReservedAttributeName')->with('tree')->times(3)->andReturn('tree');

        $node1->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);
        $node2->shouldReceive('getAttribute')->with('tree')->once()->andReturn(2);
        $node3->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

        // For some reason the array_filter appears to not be returning
        // the same instances of the nodes declated above. Either that,
        // or somethign else wacky is happening.
        // @todo, Check this out
        $this->assertCount(2, $allFlat = $worker->allFlat(1));
        // $this->assertEquals(array($node1, $node3), $allFlat);
    }

    public function testAllRoot()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[getReservedAttributeName]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $node->shouldReceive('findAll')->once()->andReturn([
            $node1 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node2 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node3 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
        ]);

        $worker->shouldReceive('getReservedAttributeName')->with('left')->times(3)->andReturn('lft');

        $node1->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);
        $node2->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);
        $node3->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);

        $this->assertCount(2, $worker->allRoot());
    }

    public function testAllLeafWithNoTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[getReservedAttributeName]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $node->shouldReceive('findAll')->once()->andReturn([
            $node1 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node2 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node3 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
        ]);

        $worker->shouldReceive('getReservedAttributeName')->with('right')->times(3)->andReturn('rgt');
        $worker->shouldReceive('getReservedAttributeName')->with('left')->times(3)->andReturn('lft');

        $node1->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(2);
        $node1->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);

        $node2->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(4);
        $node2->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);

        $node3->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
        $node3->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);

        $this->assertCount(2, $worker->allLeaf());
    }

    public function testAllLeafWithTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[getReservedAttributeName]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $node->shouldReceive('findAll')->once()->andReturn([
            $node1 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node2 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
            $node3 = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface'),
        ]);

        $worker->shouldReceive('getReservedAttributeName')->with('right')->times(3)->andReturn('rgt');
        $worker->shouldReceive('getReservedAttributeName')->with('left')->times(3)->andReturn('lft');
        $worker->shouldReceive('getReservedAttributeName')->with('tree')->twice()->andReturn('tree');

        $node1->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(2);
        $node1->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);
        $node1->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

        $node2->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(4);
        $node2->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);

        $node3->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
        $node3->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);
        $node3->shouldReceive('getAttribute')->with('tree')->once()->andReturn(3);

        $this->assertCount(1, $worker->allLeaf(1));
    }

    public function testIsLeaf()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $node1 = $this->getMockNode();
        $node1->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(20);
        $node1->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);
        $this->assertFalse($worker->isLeaf($node1));

        $node2 = $this->getMockNode();
        $node2->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(2);
        $node2->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);
        $this->assertTrue($worker->isLeaf($node2));
    }

    public function testPath()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $connection->shouldReceive('table')->with('categories as node')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $query->shouldReceive('join')->with('categories as parent', m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '>=', m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."lft"';
        }))->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '<=', m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."rgt"';
        }))->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(3);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."id"';
        }), '=', 3)->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."tree"';
        }), '=', 1)->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."tree"';
        }), '=', 1)->once()->andReturn($query);
        $query->shouldReceive('orderBy')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }))->once()->andReturn($query);

        $result1 = new stdClass;
        $result1->id = 3;
        $result2 = new stdClass;
        $result2->id = 2;
        $result3 = new stdClass;
        $result3->id = 1;

        $query->shouldReceive('get')->with(m::on(function ($select) {
                $this->assertCount(1, $select);

                return (string) $select[0] == '"prefix_parent"."id"';
            }))->once()->andReturn([$result3, $result2, $result1]);

        $this->assertCount(3, $path = $worker->path($node));
        $this->assertEquals('1,2,3', implode(',', $path));
    }

    public function testDepth()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $connection->shouldReceive('table')->with('categories as node')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $query->shouldReceive('join')->with('categories as parent', m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '>=', m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."lft"';
        }))->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '<=', m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."rgt"';
        }))->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(3);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."id"';
        }), '=', 3)->once()->andReturn($query);
        $node->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."tree"';
        }), '=', 1)->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."tree"';
        }), '=', 1)->once()->andReturn($query);
        $query->shouldReceive('orderBy')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }))->once()->andReturn($query);
        $query->shouldReceive('groupBy')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }))->once()->andReturn($query);

        $result = new stdClass;
        $result->depth = 4;

        // For some reason, unlike other tests, we have to actually ensure the
        // expression is cast as a string when used in the "select" clause. When
        // used in "where" clauses, the query builder must cast it as a string
        // and hence remove the need for us to do it here in our tests.
        $me = $this;
        $query->shouldReceive('first')->with(m::on(function ($select) use ($me) {
            $me->assertCount(1, $select);
            list($expression) = $select;
            $me->assertInstanceOf('Illuminate\Database\Query\Expression', $expression);

            return (string) $expression == '(count("prefix_parent"."id") - 1) as "depth"';
        }))->andReturn($result);

        $this->assertEquals(4, $worker->depth($node));
    }

    public function testParentNode()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(3);
        $node->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);
        $node->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
        $node->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

        $node->shouldReceive('createNode')->andReturnUsing(function () {
            return new NodeStub;
        });

        $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $query->shouldReceive('where')->with('lft', '<', 2)->once()->andReturn($query);
        $query->shouldReceive('where')->with('rgt', '>', 3)->once()->andReturn($query);
        $query->shouldReceive('where')->with('tree', '=', 1)->once()->andReturn($query);
        $query->shouldReceive('orderBy')->with('lft', 'desc')->once()->andReturn($query);
        $query->shouldReceive('first')->once()->andReturn(['foo' => 'bar']);

        $this->assertInstanceOf('NodeStub', $parentNode = $worker->parentNode($node));
        $this->assertEquals(['foo' => 'bar'], $parentNode->getAllAttributes());
    }

    public function testChildrenNodes()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $node->shouldReceive('getAttribute')->with('tree')->once()->andReturn(3);

        $connection->shouldReceive('table')->with('categories as node')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
        $query->shouldReceive('join')->with('categories as parent', m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '>=', m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."lft"';
        }))->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '<=', m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."rgt"';
        }))->once()->andReturn($query);
        $query->shouldReceive('join')->with('categories as sub_parent', m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '>=', m::on(function ($expression) {
            return (string) $expression == '"prefix_sub_parent"."lft"';
        }))->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }), '<=', m::on(function ($expression) {
            return (string) $expression == '"prefix_sub_parent"."rgt"';
        }))->once()->andReturn($query);
        $connection->shouldReceive('table')->with('categories as node')->once()->andReturn($subQuery = m::mock('Illuminate\Database\Query\Builder'));

        // We need to mock our sub-query that we put in our join
        $me = $this;
        $query->shouldReceive('join')->with('sub_tree', m::on(function ($closure) use ($me, $subQuery, $connection) {
            $join = m::mock('Illuminate\Database\Query\JoinClause');


            $subQuery->shouldReceive('select')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."id"';
            }), m::on(function ($expression) {
                return (string) $expression == '(count("prefix_parent"."id") - 1) as "depth"';
            }))->once()->andReturn($subQuery);

            $subQuery->shouldReceive('join')->with('categories as parent', m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."lft"';
            }), '>=', m::on(function ($expression) {
                    return (string) $expression == '"prefix_parent"."lft"';
                }))->once()->andReturn($subQuery);
            $subQuery->shouldReceive('where')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."lft"';
            }), '<=', m::on(function ($expression) {
                return (string) $expression == '"prefix_parent"."rgt"';
            }))->once()->andReturn($subQuery);
            $subQuery->shouldReceive('where')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."id"';
            }), '=', 1)->once()->andReturn($subQuery);
            $subQuery->shouldReceive('where')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."tree"';
            }), '=', 3)->once()->andReturn($subQuery);
            $subQuery->shouldReceive('where')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_parent"."tree"';
            }), '=', 3)->once()->andReturn($subQuery);
            $subQuery->shouldReceive('orderBy')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."lft"';
            }))->once()->andReturn($subQuery);
            $subQuery->shouldReceive('groupBy')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_node"."id"';
            }))->once()->andReturn($subQuery);

            $subQuery->shouldReceive('toSql')->once()->andReturn('foo');

            $join->table = 'categories';

            $join->shouldReceive('on')->with(m::on(function ($expression) {
                return (string) $expression == '"prefix_sub_parent"."id"';
            }), '=', m::on(function ($expression) {
                    return (string) $expression == '"prefix_sub_tree"."id"';
                }))->once();

            // Call our closure
            $closure($join);

            $me->assertEquals('(foo) as "prefix_categories"', $join->table);

            // Our assertions will ensure we catch any errors, safe to
            // return true here.
            return true;
        }))->once();

        $query->shouldReceive('mergeBindings')->with(m::type('Illuminate\Database\Query\Builder'))->once();

        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."id"';
        }), '!=', 1)->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."tree"';
        }), '=', 3)->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_parent"."tree"';
        }), '=', 3)->once()->andReturn($query);
        $query->shouldReceive('where')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_sub_parent"."tree"';
        }), '=', 3)->once()->andReturn($query);
        $query->shouldReceive('orderBy')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."lft"';
        }))->once()->andReturn($query);
        $query->shouldReceive('groupBy')->with(m::on(function ($expression) {
            return (string) $expression == '"prefix_node"."id"';
        }), m::on(function ($expression) {
            return (string) $expression == '"prefix_sub_tree"."depth"';
        }))->once()->andReturn($query);
        $query->shouldReceive('having')->with(m::on(function ($expression) {
            return (string) $expression == 'count("depth")';
        }), '<=', 3)->once()->andReturn($query);

        $query->shouldReceive('get')->with(m::on(function ($select) use ($me) {
            $me->assertCount(2, $select);
            list($first, $expression) = $select;

            $me->assertInstanceOf('Illuminate\Database\Query\Expression', $first);
            $me->assertInstanceOf('Illuminate\Database\Query\Expression', $expression);

            return ((string) $first == '"prefix_node".*' and (string) $expression == '(count("prefix_parent"."id") - ("prefix_sub_tree"."depth" + 1)) as "depth"');
        }))->once()->andReturn(['foo']);

        $node->shouldReceive('createNode')->andReturnUsing(function () {
            return new NodeStub;
        });

        $this->assertCount(1, $results = $worker->childrenNodes($node, 2));
        $this->assertInstanceOf('NodeStub', reset($results));
    }

    public function testChildrenCount()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[childrenNodes]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $worker->shouldReceive('childrenNodes')->with($node1 = $this->getMockNode(), 1, null)->once()->andReturn(['foo', 'bar']);
        $node1->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(8);
        $node1->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);

        $this->assertEquals(2, $worker->childrenCount($node1, 1));
        $this->assertEquals(3, $worker->childrenCount($node1));

        $node2 = $this->getMockNode();
        $node2->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
        $node2->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);
        $this->assertEquals(0, $worker->childrenCount($node2));

        $node3 = $this->getMockNode();
        $node3->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(20);
        $node3->shouldReceive('getAttribute')->with('lft')->once()->andReturn(3);
        $this->assertEquals(8, $worker->childrenCount($node3));

        $node4 = $this->getMockNode();
        $node4->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(8);
        $node4->shouldReceive('getAttribute')->with('lft')->once()->andReturn(5);
        $this->assertEquals(1, $worker->childrenCount($node4));
    }

    public function testTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[childrenNodes,flatNodesToTree]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $worker->shouldReceive('childrenNodes')->with($node, $depth = 2, null)->andReturn($results = ['foo']);
        $worker->shouldReceive('flatNodesToTree')->with($results)->andReturn('success');

        $this->assertEquals('success', $worker->tree($node, $depth));
    }

    public function testFlatNodesToTree()
    {
        $resultsArray = [
            ['id' => 1, 'name' => 'Admin',     'lft' => 1, 'rgt' => 14,  'tree' => 1, 'depth' => 0],
            ['id' => 2, 'name' => 'TVs',       'lft' => 2, 'rgt' => 3,   'tree' => 1, 'depth' => 1],
            ['id' => 3, 'name' => 'Computers', 'lft' => 4, 'rgt' => 13,  'tree' => 1, 'depth' => 1],
            ['id' => 4, 'name' => 'Mac',       'lft' => 5, 'rgt' => 10,  'tree' => 1, 'depth' => 2],
            ['id' => 5, 'name' => 'iMac',      'lft' => 6, 'rgt' => 7,   'tree' => 1, 'depth' => 3],
            ['id' => 5, 'name' => 'MacBook',   'lft' => 8, 'rgt' => 9,   'tree' => 1, 'depth' => 3],
            ['id' => 6, 'name' => 'PC',        'lft' => 11, 'rgt' => 12, 'tree' => 1, 'depth' => 2],
        ];

        $nodes = [];

        foreach ($resultsArray as $result) {
            $node = new NodeStub;
            $node->setAllAttributes((array) $result);
            $nodes[] = $node;
        }

        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());

        // We cannot simple use "andReturn(new NodeStub);" because
        // that returns the same instance every time, so, instead, we will
        // return a new instance by taking advantage of "andReturnUsing".
        $me = $this;

        $tree = $worker->flatNodesToTree($nodes);
        $this->assertInstanceOf('NodeStub', $tree);

        // This is a big nesting loop which manually checks that our
        // nodes are structured as expected.
        foreach ($tree->getChildren() as $child) {
            switch ($child->id) {
                // TVs
                case 2:
                    $this->assertEquals('TVs', $child->name);

                    $expected = '';
                    $actual = implode(',', array_map(function ($grandChild) {
                        return $grandChild->name;
                    }, $child->getChildren()));

                    $this->assertEquals($expected, $actual);
                    break;

                // Computers
                case 3:
                    $this->assertEquals('Computers', $child->name);

                    $expected = 'Mac,PC';
                    $actual = implode(',', array_map(function ($grandChild) use ($me) {
                        // Inspecting mac, we'll go one level deeper
                        // again and ass
                        if ($grandChild->id == 4) {
                            $expected = 'iMac,MacBook';

                            $actual = implode(',', array_map(function ($greatGrandChild) {
                                return $greatGrandChild->name;
                            }, $grandChild->getChildren()));

                            $me->assertEquals($expected, $actual);
                        }

                        return $grandChild->name;

                    }, $child->getChildren()));

                    $this->assertEquals($expected, $actual);
                    break;

                default:
                    $this->fail("Missing analyzing flat result [{$child->id}].");
                    break;
            }
        }
    }

    public function testMapTree()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[childrenNodes,ensureTransaction,recursivelyMapNode,hydrateNode,deleteNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $parentNode = $this->getMockNode();

        $nodes = [
            ['id' => 1, 'name' => 'Foo'],
            ['name' => 'Bar'],
        ];

        $existingNodes = [
            $existingNode1 = $this->getMockNode(),
            $existingNode2 = $this->getMockNode(),
        ];

        $me = $this;
        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $parentNode, $nodes, $existingNodes) {
            $worker->shouldReceive('childrenNodes')->andReturn($existingNodes);

            $worker->shouldReceive('recursivelyMapNode')->with($nodes[0], $parentNode, $existingNodes)->once();
            $worker->shouldReceive('recursivelyMapNode')->with($nodes[1], $parentNode, $existingNodes)->once();

            $worker->shouldReceive('hydrateNode')->with($existingNodes[0])->once();
            $worker->shouldReceive('deleteNode')->with($existingNodes[0])->once();
            $worker->shouldReceive('hydrateNode')->with($existingNodes[1])->once();
            $worker->shouldReceive('deleteNode')->with($existingNodes[1])->once();

            $callback($connection);

            return true;

        }))->once();

        $worker->mapTree($parentNode, $nodes);
    }

    public function testMapTreeKeepingMissingChildren()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[childrenNodes,ensureTransaction,recursivelyMapNode,hydrateNode,deleteNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $parentNode = $this->getMockNode();

        $nodes = [
            ['id' => 1, 'name' => 'Foo'],
            ['name' => 'Bar'],
        ];

        $existingNodes = [
            $existingNode1 = $this->getMockNode(),
            $existingNode2 = $this->getMockNode(),
        ];

        $me = $this;
        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $parentNode, $nodes, $existingNodes) {
            $worker->shouldReceive('childrenNodes')->andReturn($existingNodes);

            $worker->shouldReceive('recursivelyMapNode')->with($nodes[0], $parentNode, $existingNodes)->once();
            $worker->shouldReceive('recursivelyMapNode')->with($nodes[1], $parentNode, $existingNodes)->once();

            $callback($connection);

            return true;

        }))->once();

        $worker->mapTreeAndKeep($parentNode, $nodes);
    }

    public function testRecursivelyMapNode()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[hydrateNode,insertNodeAsLastChild,moveNodeAsLastChild,insertNode,updateNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);
        $parentNode    = $this->getMockNode();
        $existingNodes = [$childNode1 = $this->getMockNode()];

        // Existing node
        $childNode1->shouldReceive('getChildren')->once()->andReturn([]);
        $childNode1->shouldReceive('getAttribute')->with('id')->twice()->andReturn(2);
        $worker->shouldReceive('hydrateNode')->with($childNode1)->once();
        $worker->shouldReceive('moveNodeAsLastChild')->with($childNode1, $parentNode)->once();
        $worker->shouldReceive('updateNode')->with($childNode1)->once();
        $worker->recursivelyMapNode($childNode1, $parentNode, $existingNodes);
        $this->assertEmpty($existingNodes);

        // New node with key
        $childNode2 = $this->getMockNode();
        $childNode2->shouldReceive('getChildren')->once()->andReturn([]);
        $childNode2->shouldReceive('getAttribute')->with('id')->once()->andReturn(4);
        $worker->shouldReceive('insertNodeAsLastChild')->with($childNode2, $parentNode)->once();
        $worker->recursivelyMapNode($childNode2, $parentNode, $existingNodes);

        // New node without key
        $childNode3 = $this->getMockNode();
        $childNode3->shouldReceive('getChildren')->once()->andReturn([]);
        $childNode3->shouldReceive('getAttribute')->with('id')->once();
        $worker->shouldReceive('insertNodeAsLastChild')->with($childNode3, $parentNode)->once();
        $worker->recursivelyMapNode($childNode3, $parentNode, $existingNodes);

        $existingNodes = [$existingNode = $this->getMockNode()];
        $existingNode->shouldReceive('getAttribute')->with('id')->once()->andReturn(3);

        // Array-based node, also tests "recursive-ness"
        $nodeArray = ['id' => 3, 'name' => 'Foo', 'children' => [['name' => 'Bar']]];
        $node->shouldReceive('createNode')->twice()->andReturnUsing(function () {
            return new NodeStub;
        });

        $worker->shouldReceive('hydrateNode')->with($nodeStubCheck = m::on(function ($node) {
            return $node->getAllAttributes() == ['id' => 3, 'name' => 'Foo'];
        }))->once();
        $worker->shouldReceive('moveNodeAsLastChild')->with($nodeStubCheck, $parentNode)->once();
        $worker->shouldReceive('updateNode')->with($nodeStubCheck)->once();

        $worker->shouldReceive('insertNodeAsLastChild')->with(m::on(function ($node) {
            return $node->getAllAttributes() == ['name' => 'Bar'];
        }), $nodeStubCheck)->once();
        $worker->recursivelyMapNode($nodeArray, $parentNode, $existingNodes);
    }

    public function testInsertNodeAsRoot()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,insertNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node) {
            $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));

            $query->shouldReceive('max')->with('tree')->once()->andReturn(3);

            $node->shouldReceive('setAttribute')->with('lft', 1)->once();
            $node->shouldReceive('setAttribute')->with('rgt', 2)->once();
            $node->shouldReceive('setAttribute')->with('tree', 4)->once();

            $worker->shouldReceive('insertNode')->with($node)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->insertNodeAsRoot($node);
    }

    public function testInsertNodeAsFirstChild()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,hydrateNode,insertNode,createGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $childNode  = $this->getMockNode();
        $parentNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $childNode, $parentNode) {
            $worker->shouldReceive('hydrateNode')->with($parentNode)->once();

            $parentNode->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);
            $parentNode->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

            $worker->shouldReceive('createGap')->with(2, 2, 1)->once();

            $childNode->shouldReceive('setAttribute')->with('lft', 2)->once();
            $childNode->shouldReceive('setAttribute')->with('rgt', 3)->once();
            $childNode->shouldReceive('setAttribute')->with('tree', 1)->once();

            $worker->shouldReceive('insertNode')->with($childNode)->once();

            $parentNode->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(4);
            $parentNode->shouldReceive('setAttribute')->with('rgt', 6)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->insertNodeAsFirstChild($childNode, $parentNode);
    }

    public function testInsertNodeAsLastChild()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,hydrateNode,insertNode,createGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $childNode  = $this->getMockNode();
        $parentNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $childNode, $parentNode) {
            $worker->shouldReceive('hydrateNode')->with($parentNode)->once();

            $parentNode->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(4);
            $parentNode->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

            $worker->shouldReceive('createGap')->with(4, 2, 1)->once();

            $childNode->shouldReceive('setAttribute')->with('lft', 4)->once();
            $childNode->shouldReceive('setAttribute')->with('rgt', 5)->once();
            $childNode->shouldReceive('setAttribute')->with('tree', 1)->once();

            $worker->shouldReceive('insertNode')->with($childNode)->once();

            $parentNode->shouldReceive('setAttribute')->with('rgt', 6)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->insertNodeAsLastChild($childNode, $parentNode);
    }

    public function testInsertNodeAsPreviousSibling()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,hydrateNode,insertNode,createGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $siblingNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node, $siblingNode) {
            $worker->shouldReceive('hydrateNode')->with($siblingNode)->once();

            $siblingNode->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);
            $siblingNode->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

            $worker->shouldReceive('createGap')->with(2, 2, 1)->once();

            $node->shouldReceive('setAttribute')->with('lft', 2)->once();
            $node->shouldReceive('setAttribute')->with('rgt', 3)->once();
            $node->shouldReceive('setAttribute')->with('tree', 1)->once();

            $worker->shouldReceive('insertNode')->with($node)->once();

            $siblingNode->shouldReceive('setAttribute')->with('lft', 4)->once();
            $siblingNode->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
            $siblingNode->shouldReceive('setAttribute')->with('rgt', 5)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->insertNodeAsPreviousSibling($node, $siblingNode);
    }

    public function testInsertNodeAsNextSibling()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,hydrateNode,insertNode,createGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $siblingNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node, $siblingNode) {
            $worker->shouldReceive('hydrateNode')->with($siblingNode)->once();

            $siblingNode->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(4);
            $siblingNode->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

            $worker->shouldReceive('createGap')->with(5, 2, 1)->once();

            $node->shouldReceive('setAttribute')->with('lft', 5)->once();
            $node->shouldReceive('setAttribute')->with('rgt', 6)->once();
            $node->shouldReceive('setAttribute')->with('tree', 1)->once();

            $worker->shouldReceive('insertNode')->with($node)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->insertNodeAsNextSibling($node, $siblingNode);
    }

    public function testMoveNodeAsFirstChild()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,slideNodeOutOfTree,hydrateNode,slideNodeInTree,afterUpdateNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $childNode  = $this->getMockNode();
        $parentNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $childNode, $parentNode) {
            $worker->shouldReceive('slideNodeOutOfTree')->with($childNode)->once();
            $worker->shouldReceive('hydrateNode')->with($parentNode)->twice();

            $parentNode->shouldReceive('getAttribute')->with('lft')->once()->andReturn(1);
            $worker->shouldReceive('slideNodeInTree')->with($childNode, 2)->once();
            $worker->shouldReceive('afterUpdateNode')->with($childNode)->once();

            $worker->shouldReceive('afterUpdateNode')->with($parentNode)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->moveNodeAsFirstChild($childNode, $parentNode);
    }

    public function testMoveNodeAsLastChild()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,slideNodeOutOfTree,hydrateNode,slideNodeInTree,afterUpdateNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $childNode  = $this->getMockNode();
        $parentNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $childNode, $parentNode) {
            $worker->shouldReceive('slideNodeOutOfTree')->with($childNode)->once();
            $worker->shouldReceive('hydrateNode')->with($parentNode)->twice();

            $parentNode->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
            $worker->shouldReceive('slideNodeInTree')->with($childNode, 3)->once();
            $worker->shouldReceive('afterUpdateNode')->with($childNode)->once();

            $worker->shouldReceive('afterUpdateNode')->with($parentNode)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->moveNodeAsLastChild($childNode, $parentNode);
    }

    public function testMoveNodeAsPreviousSibling()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,slideNodeOutOfTree,hydrateNode,slideNodeInTree,afterUpdateNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $siblingNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node, $siblingNode) {
            $worker->shouldReceive('slideNodeOutOfTree')->with($node)->once();
            $worker->shouldReceive('hydrateNode')->with($siblingNode)->twice();

            $siblingNode->shouldReceive('getAttribute')->with('lft')->once()->andReturn(3);
            $worker->shouldReceive('slideNodeInTree')->with($node, 3)->once();
            $worker->shouldReceive('afterUpdateNode')->with($node)->once();

            $worker->shouldReceive('afterUpdateNode')->with($siblingNode)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->moveNodeAsPreviousSibling($node, $siblingNode);
    }

    public function testMoveNodeAsNextSibling()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,slideNodeOutOfTree,hydrateNode,slideNodeInTree,afterUpdateNode]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $siblingNode = $this->getMockNode();

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node, $siblingNode) {
            $worker->shouldReceive('slideNodeOutOfTree')->with($node)->once();
            $worker->shouldReceive('hydrateNode')->with($siblingNode)->twice();

            $siblingNode->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(3);
            $worker->shouldReceive('slideNodeInTree')->with($node, 4)->once();
            $worker->shouldReceive('afterUpdateNode')->with($node)->once();

            $worker->shouldReceive('afterUpdateNode')->with($siblingNode)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->moveNodeAsNextSibling($node, $siblingNode);
    }

    public function testDeleteNode()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,removeGap]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn(3);
        $node->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node) {
            $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));

            $query->shouldReceive('where')->with('id', '=', 3)->once()->andReturn($query);
            $query->shouldReceive('delete')->once();

            $node->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);
            $worker->shouldReceive('removeGap')->with(3, 1, 1)->once();

            $node->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(5);
            $worker->shouldReceive('removeGap')->with(5, 1, 1)->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->deleteNode($node);
    }

    public function testDeleteNodeWithChildren()
    {
        $worker = m::mock('Cartalyst\NestedSets\Workers\IlluminateWorker[ensureTransaction,slideNodeOutOfTree]', [$connection = $this->getMockConnection(), $node = $this->getMockNode()]);

        $worker->shouldReceive('ensureTransaction')->with(m::on(function ($callback) use ($worker, $connection, $node) {
            $worker->shouldReceive('slideNodeOutOfTree')->with($node)->once();

            $node->shouldReceive('getAttribute')->with('lft')->once()->andReturn(2);
            $node->shouldReceive('getAttribute')->with('rgt')->once()->andReturn(5);
            $node->shouldReceive('getAttribute')->with('tree')->once()->andReturn(1);

            $connection->shouldReceive('table')->with('categories')->once()->andReturn($query = m::mock('Illuminate\Database\Query\Builder'));
            $query->shouldReceive('where')->with('lft', '>=', 2)->once()->andReturn($query);
            $query->shouldReceive('where')->with('rgt', '<=', 5)->once()->andReturn($query);
            $query->shouldReceive('where')->with('tree', '=', 1)->once()->andReturn($query);
            $query->shouldReceive('delete')->once();

            $callback($connection);

            return true;
        }))->once();

        $worker->deleteNodeWithChildren($node);
    }

    public function testWrap()
    {
        $worker = new Worker($connection = $this->getMockConnection(), $node = $this->getMockNode());
        $this->assertEquals('"prefix_foo"."bar"', $worker->wrap('foo.bar'));
    }

    protected function getMockConnection()
    {
        $connection = m::mock('Illuminate\Database\Connection');
        $connection->shouldReceive('getQueryGrammar')->andReturn($grammar = new Grammar);
        $grammar->setTablePrefix('prefix_');
        $connection->shouldReceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));

        $connection->shouldReceive('getPdo')->andReturn($pdo = m::mock('stdClass'));

        return $connection;
    }

    protected function getMockNode()
    {
        $node = m::mock('Cartalyst\NestedSets\Nodes\NodeInterface');

        $node->shouldReceive('getKeyName')->andReturn('id');
        $node->shouldReceive('getTable')->andReturn('categories');
        $node->shouldReceive('getReservedAttributeNames')->andReturn([
            'left'  => 'lft',
            'right' => 'rgt',
            'tree'  => 'tree',
        ]);
        $node->shouldReceive('getReservedAttributeName')->with('left')->andReturn('lft');
        $node->shouldReceive('getReservedAttributeName')->with('right')->andReturn('rgt');
        $node->shouldReceive('getReservedAttributeName')->with('tree')->andReturn('tree');
        $node->shouldReceive('getDepthAttributeName')->andReturn('depth');

        return $node;
    }
}
