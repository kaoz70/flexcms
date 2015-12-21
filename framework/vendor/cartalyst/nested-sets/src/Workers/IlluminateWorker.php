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

namespace Cartalyst\NestedSets\Workers;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Expression;
use Cartalyst\NestedSets\Nodes\NodeInterface;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * This class does the MPTT magic which powers nested-sets.
 * A great resource to learn about MPTT can be found at
 * http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/
 */
class IlluminateWorker implements WorkerInterface
{
    /**
     * The database connection instance.
     *
     * @var Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The base node which the worker uses
     * to get information (such as reserved
     * attributes, table name, primary key
     * names etc).
     *
     * @var Cartalyst\NestedSets\Nodes\NodeInterface
     */
    protected $baseNode;

    /**
     * Create a Illuminate worker instance.
     *
     * @param  Illuminate\Database\Connection  $connection
     * @return void
     */
    public function __construct(Connection $connection, NodeInterface $baseNode)
    {
        $this->connection = $connection;
        $this->baseNode   = $baseNode;
    }

    /**
     * Returns all nodes, in a flat array.
     *
     * @param  int  $tree
     * @return array
     */
    public function allFlat($tree = null)
    {
        $all = $this->baseNode->findAll();

        // If no tree was supplied, we will return all items
        if ($tree === null) {
            return $all;
        }

        $me = $this;

        // If a tree was supplied, we will filter the items to
        // ensure the tree matches.
        return array_filter($all, function ($node) use ($me, $tree) {
            return ($node->getAttribute($me->getReservedAttributeName('tree')) == $tree);
        });
    }

    /**
     * Returns all root nodes, in a flat array.
     *
     * @return array
     */
    public function allRoot()
    {
        $me = $this;

        // Root items are those who's left limit is equal to "1".
        return array_filter($this->baseNode->findAll(), function ($node) use ($me) {
            return ($node->getAttribute($me->getReservedAttributeName('left')) == 1);
        });
    }

    /**
     * Finds all leaf nodes, in a flat array.
     * Leaf nodes are nodes which do not have
     * any children.
     *
     * @param  int  $tree
     * @return array
     */
    public function allLeaf($tree = null)
    {
        $me = $this;

        // Leaf nodes are nodes with no children, therefore the
        // right limit will be one greater than the left limit.
        return array_filter($this->baseNode->findAll(), function ($node) use ($me, $tree) {
            $right = $node->getAttribute($me->getReservedAttributeName('right'));
            $left  = $node->getAttribute($me->getReservedAttributeName('left'));
            $size  = $right - $left;

            // If we have no tree, simply check the size
            if ($tree === null) {
                return $size == 1;
            }

            // Otherwise, check our tree constraint matches as well.
            return ($size == 1 and $node->getAttribute($me->getReservedAttributeName('tree')) == $tree);
        });
    }

    /**
     * Returns if the given node is a leaf node (has
     * no children).
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return bool
     */
    public function isLeaf(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();

        return $node->getAttribute($attributes['right']) - $node->getAttribute($attributes['left']) == 1;
    }

    /**
     * Finds the path of the given node. The path is
     * the primary key of the node and all of it's
     * parents up to the root item.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return array
     */
    public function path(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();
        $table      = $this->getTable();
        $keyName    = $this->baseNode->getKeyName();
        $tree       = $node->getAttribute($attributes['tree']);

        // Note, joins currently don't support "between" operators
        // in the query builder, so we will satisfy half of the
        // "betweeen" in the join and the other half in a "where"
        // clause. This will allow us to use the query builder for
        // it's database agnostic compilation
        $results = $this
            ->connection->table("$table as node")
            ->join(
                "$table as parent",
                new Expression($this->wrap("node.{$attributes['left']}")),
                '>=',
                new Expression($this->wrap("parent.{$attributes['left']}"))
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['left']}")),
                '<=',
                new Expression($this->wrap("parent.{$attributes['right']}"))
            )
            ->where(
                new Expression($this->wrap("node.$keyName")),
                '=',
                $node->getAttribute($keyName)
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['tree']}")),
                '=',
                $tree
            )
            ->where(
                new Expression($this->wrap("parent.{$attributes['tree']}")),
                '=',
                $tree
            )
            ->orderBy(new Expression($this->wrap("node.{$attributes['left']}")))
            ->get([new Expression($this->wrap("parent.$keyName"))]);

        // Our results is an array of objects containing the key name
        // only. We will simplify this by simply returning the key
        // name so our array is a simple array of primatives.
        return array_map(function ($result) use ($keyName) {
            return $result->$keyName;
        }, $results);
    }

    /**
     * Returns the depth of a node in a tree, where
     * 0 is a root node, 1 is a root node's direct
     * child and so on.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return int
     */
    public function depth(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();
        $table      = $this->getTable();
        $keyName    = $this->baseNode->getKeyName();
        $tree       = $node->getAttribute($attributes['tree']);

        $result = $this
            ->connection->table("$table as node")
            ->join(
                "$table as parent",
                new Expression($this->wrap("node.{$attributes['left']}")),
                '>=',
                new Expression($this->wrap("parent.{$attributes['left']}"))
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['left']}")),
                '<=',
                new Expression($this->wrap("parent.{$attributes['right']}"))
            )
            ->where(
                new Expression($this->wrap("node.$keyName")),
                '=',
                $node->getAttribute($keyName)
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['tree']}")),
                '=',
                $tree
            )
            ->where(
                new Expression($this->wrap("parent.{$attributes['tree']}")),
                '=',
                $tree
            )
            ->orderBy(new Expression($this->wrap("node.{$attributes['left']}")))
            ->groupBy(new Expression($this->wrap("node.{$attributes['left']}")))
            ->first([new Expression(sprintf(
                '(count(%s) - 1) as %s',
                $this->wrap("parent.$keyName"),
                $this->wrap($this->getDepthAttributeName())
            ))]);

        return $result->depth;
    }

    /**
     * Returns the relative depth of a node in a tree,
     * relative to the parent provided. The parent
     * must in fact be a parent in the path of this
     * item otherwise we cannot find the relative
     * depth.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parentNode
     * @return int
     */
    public function relativeDepth(NodeInterface $node, NodeInterface $parentNode)
    {
        throw new \BadMethodCallException(__METHOD__);
    }

    /**
     * Returns the parnet node for the given node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     */
    public function parentNode(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();
        $table      = $this->getTable();
        $keyName    = $this->baseNode->getKeyName();
        $key        = $node->getAttribute($keyName);
        $left       = $node->getAttribute($attributes['left']);

        // If we are a root node, we obviously won't have a parent.
        // This method should never be called on root nodes.
        if ($left == 1) {
            throw new \RuntimeException("Node [$key] has no parent as it is a root node.");
        }

        // To find the parent, we'll query the database for all
        // nodes who's limits are outside our node. We'll
        // grab the one with the largest left limit as this is
        // our closest parent.
        $result = $this
            ->connection->table($table)
            ->where($attributes['left'], '<', $left)
            ->where($attributes['right'], '>', $node->getAttribute($attributes['right']))
            ->where($attributes['tree'], '=', $node->getAttribute($attributes['tree']))
            ->orderBy($attributes['left'], 'desc')
            ->first();

        return $this->createNode($result);
    }

    /**
     * Returns all children for the given node in a flat
     * array. If the depth is 1 or more, that is how many
     * levels of children we recurse through to put into
     * the flat array.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $depth
     * @param  Closure  $callback
     * @return array
     */
    public function childrenNodes(NodeInterface $node, $depth = 0, Closure $callback = null)
    {
        $attributes = $this->getReservedAttributeNames();
        $table      = $this->getTable();
        $keyName    = $this->baseNode->getKeyName();
        $key        = $node->getAttribute($keyName);
        $tree       = $node->getAttribute($attributes['tree']);
        $nodes      = [];

        // We will store a query builder object that we
        // use throughout the course of this method.
        $query = $this
            ->connection->table("$table as node")
            ->join(
                "$table as parent",
                new Expression($this->wrap("node.{$attributes['left']}")),
                '>=',
                new Expression($this->wrap("parent.{$attributes['left']}"))
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['left']}")),
                '<=',
                new Expression($this->wrap("parent.{$attributes['right']}"))
            )
            ->join(
                "$table as sub_parent",
                new Expression($this->wrap("node.{$attributes['left']}")),
                '>=',
                new Expression($this->wrap("sub_parent.{$attributes['left']}"))
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['left']}")),
                '<=',
                new Expression($this->wrap("sub_parent.{$attributes['right']}"))
            );

        // Create a query to select the sub-tree
        // component of each node. We initialize this
        // here so that we can take its' bindings and
        // merge them in.
        $subQuery = $this->connection->table("$table as node");

        // We now build up the sub-tree component of the
        // query in a closure which is passed as the condition
        // an inner join for the main query.
        $me = $this;

        $query->join('sub_tree', function ($join) use ($me, $node, $subQuery, $attributes, $table, $keyName, $key, $tree) {
            $subQuery
                ->select(
                    new Expression($me->wrap("node.$keyName")),
                    new Expression(sprintf(
                        '(count(%s) - 1) as %s',
                        $me->wrap("parent.$keyName"),
                        $me->wrap($me->getDepthAttributeName())
                    ))
                )
                ->join(
                    "$table as parent",
                    new Expression($me->wrap("node.{$attributes['left']}")),
                    '>=',
                    new Expression($me->wrap("parent.{$attributes['left']}"))
                )
                ->where(
                    new Expression($me->wrap("node.{$attributes['left']}")),
                    '<=',
                    new Expression($me->wrap("parent.{$attributes['right']}"))
                )
                ->where(
                    new Expression($me->wrap("node.$keyName")),
                    '=',
                    $key
                )
                ->where(
                    new Expression($me->wrap("node.{$attributes['tree']}")),
                    '=',
                    $tree
                )
                ->where(
                    new Expression($me->wrap("parent.{$attributes['tree']}")),
                    '=',
                    $tree
                )
                ->orderBy(new Expression($me->wrap("node.{$attributes['left']}")))
                ->groupBy(new Expression($me->wrap("node.$keyName")));

            // Configure the join from the SQL the query
            // builder generates.
            $join->table = new Expression(sprintf(
                '(%s) as %s',
                $subQuery->toSql(),
                $me->wrapTable($join->table)
            ));

            $join->on(
                new Expression($me->wrap("sub_parent.$keyName")),
                '=',
                new Expression($me->wrap("sub_tree.$keyName"))
            );
        });

        // Now we have compiled the SQL for our sub query,
        // we need to merge it's bindings into our main query.
        $query->mergeBindings($subQuery);

        $query
            ->where(
                new Expression($this->wrap("node.{$keyName}")),
                '!=',
                $key
            )
            ->where(
                new Expression($this->wrap("node.{$attributes['tree']}")),
                '=',
                $tree
            )
            ->where(
                new Expression($this->wrap("parent.{$attributes['tree']}")),
                '=',
                $tree
            )
            ->where(
                new Expression($this->wrap("sub_parent.{$attributes['tree']}")),
                '=',
                $tree
            );

        // If a callback was supplied, we'll call it now
        if ($callback) {
            $callback($query);
        }

        $query
            ->orderBy(new Expression($this->wrap("node.{$attributes['left']}")))
            ->groupBy(
                new Expression($this->wrap("node.$keyName")),
                new Expression($this->wrap("sub_tree.{$this->getDepthAttributeName()}"))
            );

        // If we have a depth, we need to supply a "having"
        // clause to the query builder.
        if ($depth > 0) {
            $query->having(
                new Expression(
                    sprintf(
                        'count(%s)',
                        $this->wrap($me->getDepthAttributeName())
                    )
                ),
                '<=', ++$depth
            );
        }

        $results = $query->get([
            new Expression($this->wrap("node.*")),
            new Expression(sprintf(
                '(count(%s) - (%s + 1)) as %s',
                $this->wrap("parent.$keyName"),
                $this->wrap("sub_tree.{$this->getDepthAttributeName()}"),
                $this->wrap($this->getDepthAttributeName())
            ))
        ]);

        foreach ($results as $result) {
            $nodes[] = $this->createNode($result);
        }

        return $nodes;
    }

    /**
     * Returns the count of the children for the given node, with an
     * optional depth limit.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $depth
     * @param  Closure  $callback
     * @return int
     */
    public function childrenCount(NodeInterface $node, $depth = 0, $callback = null)
    {
        // We will only use our complex query if the depth
        // is being limited. Otherwise, we can save on some
        // overhead.
        if ($depth > 0) {
            return count($this->childrenNodes($node, $depth, $callback));
        }

        $attributes = $this->getReservedAttributeNames();
        $right      = $node->getAttribute($attributes['right']);
        $left       = $node->getAttribute($attributes['left']);

        return ($right - $left - 1) / 2;
    }

    /**
     * Returns a tree for the given node. If the depth
     * is 0, we return all children. If the depth is
     * 1 or more, that is how many levels of children
     * nodes we recurse through.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $depth
     * @param  Closure  $callback
     * @return array
     */
    public function tree(NodeInterface $node, $depth = 0, $callback = null)
    {
        $nodes = $this->childrenNodes($node, $depth, $callback);

        return $this->flatNodesToTree($nodes);
    }

    /**
     * Maps a tree to the database. We update each items'
     * values as well if they're provided. This can be used
     * to create a whole new tree structure or simply to re-order
     * a tree.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface   $parent
     * @param  array   $nodes
     * @param  delete  $delete
     * @return void
     */
    public function mapTree(NodeInterface $parent, array $nodes, $delete = true)
    {
        $table      = $this->getTable();
        $attributes = $this->getReservedAttributeNames();
        $keyName    = $this->baseNode->getKeyName();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $parent, $nodes, $table, $attributes, $keyName, $delete) {
            // Grab all exstiging child nodes. We'll reduce these through the
            // recursive mapping process. Whatever children are left-over
            // should then be deleted.
            $existingNodes = $me->childrenNodes($parent);

            // Loop through nodes and recursively map them in the database
            foreach ($nodes as $node) {
                $me->recursivelyMapNode($node, $parent, $existingNodes);
            }

            // If we are keeping children, we'll skip on deleting
            if ($delete === false) {
                return;
            }

            // Now we've recursively mapped the nodes, the leftover
            // nodes were not mapped at all. These should be deleted
            // from the database now.
            foreach ($existingNodes as $existingNode) {
                $me->hydrateNode($existingNode);
                $me->deleteNode($existingNode);
            }
        });
    }

    /**
     * Maps a tree to the database and keep all nodes not present in
     * the passed array. This allows for allowing pushing new items
     * into a tree without affecting the entire tree.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface   $parent
     * @param  array  $nodes
     * @return void
     */
    public function mapTreeAndKeep(NodeInterface $parent, array $nodes)
    {
        $this->mapTree($parent, $nodes, false);
    }

    /**
     * Makes a new node a root node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function insertNodeAsRoot(NodeInterface $node)
    {
        $table      = $this->getTable();
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $table, $attributes) {
            $query = $connection->table($table);

            $node->setAttribute($attributes['left'], 1);
            $node->setAttribute($attributes['right'], 2);
            $node->setAttribute($attributes['tree'], $query->max($attributes['tree']) + 1);

            $me->insertNode($node);

        });
    }

    /**
     * Inserts the given node as the first child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function insertNodeAsFirstChild(NodeInterface $node, NodeInterface $parent)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $parent, $attributes) {
            // We will hydrate our parent node now just in case
            // we're dealing with stale data (i.e. in a mapping
            // process)
            $me->hydrateNode($parent);

            // Our left limit will be one greater than that of the parent
            // node, which will mean we are the first child.
            $left  = $parent->getAttribute($attributes['left']) + 1;
            $right = $left + 1;
            $tree  = $parent->getAttribute($attributes['tree']);

            $me->createGap($left, 2, $tree);

            // Update the node instance with our properties
            $node->setAttribute($attributes['left'], $left);
            $node->setAttribute($attributes['right'], $right);
            $node->setAttribute($attributes['tree'], $tree);

            $me->insertNode($node);

            // We will update the parent instance so that it's
            // limits are accurate and it can be used again.
            $parent->setAttribute($attributes['right'], $parent->getAttribute($attributes['right']) + 2);

        });
    }

    /**
     * Inserts the given node as the last child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function insertNodeAsLastChild(NodeInterface $node, NodeInterface $parent)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $parent, $attributes) {
            // We will hydrate our parent node now just in case
            // we're dealing with stale data (i.e. in a mapping
            // process)
            $me->hydrateNode($parent);

            // Our left limit will be the same as the (current) right limit
            // of the parent node, which will mean we are the last child.
            $left  = $parentRight = $parent->getAttribute($attributes['right']);
            $right = $left + 1;
            $tree  = $parent->getAttribute($attributes['tree']);

            $me->createGap($left, 2, $tree);

            // Update the node instance with our properties
            $node->setAttribute($attributes['left'], $left);
            $node->setAttribute($attributes['right'], $right);
            $node->setAttribute($attributes['tree'], $tree);

            $me->insertNode($node);

            // We will update the parent instance so that it's
            // limits are accurate and it can be used again.
            $parent->setAttribute($attributes['right'], $parentRight + 2);

        });
    }

    /**
     * Inserts the given node as the previous sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function insertNodeAsPreviousSibling(NodeInterface $node, NodeInterface $sibling)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $sibling, $attributes) {
            // We will hydrate our sibling node now just in case
            // we're dealing with stale data (i.e. in a mapping
            // process)
            $me->hydrateNode($sibling);

            // Our left limit will be the same as the (current) left limit
            // of the sibling node, which will mean we are the previous sibling.
            $left  = $siblingLeft = $sibling->getAttribute($attributes['left']);
            $right = $left + 1;
            $tree  = $sibling->getAttribute($attributes['tree']);

            $me->createGap($left, 2, $tree);

            // Update the node instance with our properties
            $node->setAttribute($attributes['left'], $left);
            $node->setAttribute($attributes['right'], $right);
            $node->setAttribute($attributes['tree'], $tree);

            $me->insertNode($node);

            // We will update the parent instance so that it's
            // limits are accurate and it can be used again.
            $sibling->setAttribute($attributes['left'], $siblingLeft + 2);
            $sibling->setAttribute($attributes['right'], $sibling->getAttribute($attributes['right']) + 2);

        });
    }

    /**
     * Inserts the given node as the next sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function insertNodeAsNextSibling(NodeInterface $node, NodeInterface $sibling)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $sibling, $attributes) {
            // We will hydrate our sibling node now just in case
            // we're dealing with stale data (i.e. in a mapping
            // process)
            $me->hydrateNode($sibling);

            // Our left limit will be one more than the (current) right limit
            // of the sibling node, which will mean we are the next sibling.
            // Additionally, because we sit to the right of the child, we do
            // not have to update the child's properties as none of our queries
            // will adjust the record it represents in the database.
            $left  = $sibling->getAttribute($attributes['right']) + 1;
            $right = $left + 1;
            $tree  = $sibling->getAttribute($attributes['tree']);

            $me->createGap($left, 2, $tree);

            // Update the node instance with our properties
            $node->setAttribute($attributes['left'], $left);
            $node->setAttribute($attributes['right'], $right);
            $node->setAttribute($attributes['tree'], $tree);

            $me->insertNode($node);

        });
    }

    /**
     * Makes the given node a root node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function moveNodeAsRoot(NodeInterface $node)
    {
        throw new \BadMethodCallException(__METHOD__);
    }

    /**
     * Moves the given node as the first child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function moveNodeAsFirstChild(NodeInterface $node, NodeInterface $parent)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $parent, $attributes) {
            $me->slideNodeOutOfTree($node);

            // We will hydrate our parent node now just
            // in case the sliding process above messed it's
            // order up.
            $me->hydrateNode($parent);

            $left = $parent->getAttribute($attributes['left']) + 1;
            $me->slideNodeInTree($node, $left);
            $me->afterUpdateNode($node);

            // And once more we will hydrate the parent's
            // attributes again so that the object instance
            // is in sync with the database
            $me->hydrateNode($parent);
            $me->afterUpdateNode($parent);
        });
    }

    /**
     * Moves the given node as the last child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function moveNodeAsLastChild(NodeInterface $node, NodeInterface $parent)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $parent, $attributes) {
            $me->slideNodeOutOfTree($node);

            // We will hydrate our parent node now just
            // in case the sliding process above messed it's
            // order up.
            $me->hydrateNode($parent);

            $left = $parent->getAttribute($attributes['right']);
            $me->slideNodeInTree($node, $left);
            $me->afterUpdateNode($node);

            // And once more we will hydrate the parent's
            // attributes again so that the object instance
            // is in sync with the database
            $me->hydrateNode($parent);
            $me->afterUpdateNode($parent);
        });
    }

    /**
     * Moves the given node as the previous sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function moveNodeAsPreviousSibling(NodeInterface $node, NodeInterface $sibling)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $sibling, $attributes) {
            $me->slideNodeOutOfTree($node);

            // We will hydrate our sibling node now just
            // in case the sliding process above messed it's
            // order up.
            $me->hydrateNode($sibling);

            $left = $sibling->getAttribute($attributes['left']);
            $me->slideNodeInTree($node, $left);
            $me->afterUpdateNode($node);

            // And once more we will hydrate the sibling's
            // attributes again so that the object instance
            // is in sync with the database
            $me->hydrateNode($sibling);
            $me->afterUpdateNode($sibling);
        });
    }

    /**
     * Moves the given node as the next sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function moveNodeAsNextSibling(NodeInterface $node, NodeInterface $sibling)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $sibling, $attributes) {
            $me->slideNodeOutOfTree($node);

            // We will hydrate our sibling node now just
            // in case the sliding process above messed it's
            // order up.
            $me->hydrateNode($sibling);

            $left = $sibling->getAttribute($attributes['right']) + 1;
            $me->slideNodeInTree($node, $left);
            $me->afterUpdateNode($node);

            // And once more we will hydrate the sibling's
            // attributes again so that the object instance
            // is in sync with the database
            $me->hydrateNode($sibling);
            $me->afterUpdateNode($sibling);
        });
    }

    /**
     * Removes a node from the database and orphans
     * it's children.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function deleteNode(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;
        $keyName    = $this->baseNode->getKeyName();
        $key        = $node->getAttribute($keyName);
        $left       = $node->getAttribute($attributes['left']);

        // Firstly, if a node is a "root" node, we cannot
        // orphan it's children, where would they go?
        if ($left == 1) {
            throw new \RuntimeException("Cannot delete node [$key] and orphan it's children as it is root.");
        }

        $this->ensureTransaction(function ($connection) use ($me, $node, $keyName, $key, $left, $attributes) {
            // Firstly, we'll simply delete the node from the database
            $me
                ->getConnection()
                ->table($me->getTable())
                ->where($keyName, '=', $key)
                ->delete();

            $tree = $node->getAttribute($attributes['tree']);

            // Now, we need to make a negative gap of "1"
            // for all items between the parent's left and
            // right limits so that the left limit which
            // the parent held is removed.
            $me->removeGap($left + 1, 1, $tree);

            // And now we'll move every node outside of the
            // tree one more to the left so the right limit
            // which the parent held is also removed. Our
            // hierarchy is now pure.
            $me->removeGap($node->getAttribute($attributes['right']), 1, $tree);
        });
    }

    /**
     * Removes a node from the database and all of
     * it's children.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function deleteNodeWithChildren(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();
        $me         = $this;

        $this->ensureTransaction(function ($connection) use ($me, $node, $attributes) {
            // Firstly, we want to slide our node ouf ot the tree
            // so that the rest of the tree remains intact once we
            // remove our node and it's children.
            $me->slideNodeOutOfTree($node);

            // Now, we want to simply remove all of the nodes
            // who reside between the limits of the node we're
            // deleting.
            $me
                ->getConnection()
                ->table($me->getTable())
                ->where($attributes['left'], '>=', $node->getAttribute($attributes['left']))
                ->where($attributes['right'], '<=', $node->getAttribute($attributes['right']))
                ->where($attributes['tree'], '=', $node->getAttribute($attributes['tree']))
                ->delete();
        });
    }

    /**
     * Creates a gap in a tree, starting from the
     * left limit with the given size (the size can
     * be negative).
     *
     * @param  int  $left
     * @param  int  $size
     * @param  int  $tree
     * @return void
     */
    public function createGap($left, $size, $tree)
    {
        if ($size === 0) {
            throw new \InvalidArgumentException("Cannot create a gap in tree [$tree] starting from [$left] with a size of [0].");
        }

        $attributes = $this->getReservedAttributeNames();

        $this
            ->connection->table($this->getTable())
            ->where($attributes['left'], '>=', $left)
            ->where($attributes['tree'], '=', $tree)
            ->update([
                $attributes['left'] => new Expression(sprintf(
                    '%s + %d',
                    $this->wrap($attributes['left']),
                    $size
                )),
            ]);

        $this
            ->connection->table($this->getTable())
            ->where($attributes['right'], '>=', $left)
            ->where($attributes['tree'], '=', $tree)
            ->update([
                $attributes['right'] => new Expression(sprintf(
                    '%s + %d',
                    $this->wrap($attributes['right']),
                    $size
                )),
            ]);
    }

    /**
     * Alias to create a negative gap.
     *
     * @param  int  $start
     * @param  int  $size
     * @param  int  $tree
     * @return void
     */
    public function removeGap($start, $size, $tree)
    {
        if ($size < 0) {
            throw new \InvalidArgumentException("Cannot provide a negative size of [$size] remove a gap. Instead, provide the positive size.");
        }

        return $this->createGap($start, $size * -1, $tree);
    }

    /**
     * Slides a node out of the tree hierarchy (so it's
     * right limit sits on '0'; it will not render in any
     * hierarchical data).
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function slideNodeOutOfTree(NodeInterface $node)
    {
        $attributes = $this->getReservedAttributeNames();
        $size       = $this->getNodeSize($node);
        $delta      = 0 - $node->getAttribute($attributes['right']);
        $grammar    = $this->connection->getQueryGrammar();

        // There are two steps to this method. We are firstly going
        // to adjust our node and every child so that our right limit
        // is 0, which removes the node from the hierarchical tree.
        $this
            ->connection->table($this->getTable())
            ->where($attributes['left'], '>=', $node->getAttribute($attributes['left']))
            ->where($attributes['right'], '<=', $node->getAttribute($attributes['right']))
            ->where($attributes['tree'], '=', $node->getAttribute($attributes['tree']))
            ->update([
                $attributes['left'] => new Expression(sprintf(
                    '%s + %d',
                    $grammar->wrap($attributes['left']),
                    $delta
                )),
                $attributes['right'] => new Expression(sprintf(
                    '%s + %d',
                    $grammar->wrap($attributes['right']),
                    $delta
                )),
            ]);

        // Now, we will close the gap created by shifting the node
        $this->removeGap($node->getAttribute($attributes['left']), $size + 1, $node->getAttribute($attributes['tree']));

        // We will calculate and update the node's properties now
        // so that the person does not have to re-hydrate them from
        // the database, as that will add overhead.
        $node->setAttribute($attributes['left'], $node->getAttribute($attributes['left']) + $delta);
        $node->setAttribute($attributes['right'], $node->getAttribute($attributes['right']) + $delta);
    }

    /**
     * Slides a node back into it's tree so it can be used
     * in hierarchical data. This is the reverse of sliding out
     * of a tree and can be used to reposition a node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $left
     * @return void
     */
    public function slideNodeInTree(NodeInterface $node, $left)
    {
        $attributes = $this->getReservedAttributeNames();
        $size       = $this->getNodeSize($node);
        $delta      = $size + $left;
        $grammar    = $this->connection->getQueryGrammar();

        // Reversing the proces of sliding out of a tree, we will
        // now create a gap for our node to enter at.
        $this->createGap($left, $size + 1, $node->getAttribute($attributes['tree']));

        // We will now adjust the left and right limits of our node and
        // all it's children to be within the hierachical data in the
        // gap we just created above.
        $this
            ->connection->table($this->getTable())
            ->where($attributes['left'], '>=', 0 - $size)
            ->where($attributes['right'], '<=', 0)
            ->where($attributes['tree'], '=', $node->getAttribute($attributes['tree']))
            ->update([
                $attributes['left'] => new Expression(sprintf(
                    '%s + %d',
                    $grammar->wrap($attributes['left']),
                    $delta
                )),
                $attributes['right'] => new Expression(sprintf(
                    '%s + %d',
                    $grammar->wrap($attributes['right']),
                    $delta
                )),
            ]);

        // Like sliding out of a tree, we will now update the node's
        // attributes so they don't have to be hydrated.
        $node->setAttribute($attributes['left'], $node->getAttribute($attributes['left']) + $delta);
        $node->setAttribute($attributes['right'], $node->getAttribute($attributes['right']) + $delta);
    }

    /**
     * Takes a flat array of nodes and trasnforms them to a hierachical tree.
     *
     * If the tree generated from the results array has a root node,
     * the results is an instance of the root node (where the children
     * are accessible through getChildren(). Otherwise, an array of
     * results are returned).
     *
     * @param  array   $nodes
     * @return mixed
     */
    public function flatNodesToTree(array $nodes)
    {
        if (count($nodes) === 0) {
            return [];
        }

        // Tree to return
        $tree = [];

        // Current stack, used to determine relative
        // depth to a parent.
        $stack = [];

        // Variable used to check the size of the current
        // stack. We use it to determine where to put children
        // in the hierarchy array.
        $stackCounter = 0;

        $depthAttribute = $this->getDepthAttributeName();

        // Loop through the results
        foreach ($nodes as $node) {
            $stackCounter = count($stack);

            // Adjust our stack counter down every time we
            // find a non-matching parent node.
            while ($stackCounter > 0 and $stack[$stackCounter - 1]->getAttribute($depthAttribute) >= $node->getAttribute($depthAttribute)) {
                array_pop($stack);
                $stackCounter--;
            }

            // If we've crossed off all the non-matching parent
            // nodes above, we are dealing with a root node and
            // it should be appended to the main tree.
            if ($stackCounter === 0) {
                $i = count($tree);
                $tree[$i] = $node;
                $stack[] = &$tree[$i];
            }

            // Otherwise, we will assign it as a child to the
            // parent node which is in the main tree by reference.
            else {
                $i = count($stack[$stackCounter - 1]->getChildren());
                $stack[$stackCounter - 1]->setChildAtIndex($node, $i);
                $stack[] = $node;
            }
        }

        return (count($tree) > 1) ? $tree : reset($tree);
    }

    /**
     * Recursively maps a node by making the node the last child of the
     * given parent. It is recursive by looping through the node's children
     * and invoking this same method for each one of the node's children,
     * presenting the node as the parent.
     *
     * @param  mixed  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @param  array  $existingNodes
     * @return void
     */
    public function recursivelyMapNode($node, NodeInterface $parent, array &$existingNodes = [])
    {
        // We will accept arrays and StdClass objects
        // as parameters, as this method is typically
        // used for mapping arrays into the tree.
        if (! $node instanceof NodeInterface) {
            $node = $this->createNode($node);
        }

        $keyName = $this->baseNode->getKeyName();
        $key     = $node->getAttribute($keyName);

        $matched = false;

        foreach ($existingNodes as $index => $existingNode) {
            // We will need to determine if we're dealing with an existing node
            // or creating a new one. We do this by filtering the existing nodes
            // for the target node.
            if ($existingNode->getAttribute($keyName) != $key) {
                continue;
            }

            // Either the person will not have passed through the reserved
            // attributes or if they have, we want to make sure we reset
            // them here so our queries work nicely.
            $this->hydrateNode($node);

            // We will move the node to be the last child of the parent (which
            // will keep it's order consistent to the order which it was
            // in the array).
            $this->moveNodeAsLastChild($node, $parent);

            // We will update the node. Moving nodes around only updates the
            // appropriate attributes on the database table. This will ensure
            // that all of the attributes passed to the mapping operation have
            // been updated.
            $this->updateNode($node);

            // We will unset the existing key now as this will allow
            // us to compare what keys are left at the end of the recursive
            // operation and those keys will be for the nodes which do
            // not exist now.
            unset($existingNodes[$index]);

            // Break our loop so that we don't continually test other nodes.
            $matched = true;
            break;
        }

        // If we have not matched any existing nodes, we'll create one
        if (! $matched) {
            // When inserting new nodes as children, all of their attributes
            // are mapped to the database (thus saving multiple queries on that
            // operation, where all attributes should be saved [as opposed to
            // sliding existing nodes around]). This makes our operation here
            // extremely simple.
            $this->insertNodeAsLastChild($node, $parent);
        }

        // Now, we will look to see if the node
        // has children, and if it does, we will loop
        // through each child and call this same method
        // recursively, presenting each child as the
        // node and this node as the parent.
        foreach ($node->getChildren() as $child) {
            $this->recursivelyMapNode($child, $node, $existingNodes);
        }
    }

    /**
     * Returns the connection associated with the worker.
     *
     * @return Illumimate\Database\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns the table associated with the worker.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->baseNode->getTable();
    }

    /**
     * Get the reserved attributes.
     *
     * @return array
     */
    public function getReservedAttributeNames()
    {
        return $this->baseNode->getReservedAttributeNames();
    }

    /**
     * Get the name of a reserved attribute.
     *
     * @param  string  $key
     * @return string
     */
    public function getReservedAttributeName($key)
    {
        return $this->baseNode->getReservedAttributeName($key);
    }

    /**
     * Return the "depth" attribute.
     *
     * @return string
     */
    public function getDepthAttributeName()
    {
        return $this->baseNode->getDepthAttributeName();
    }

    /**
     * Calculate's the "size" of a node in the hierachical
     * structure, based off it's left and right limits.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return int
     */
    public function getNodeSize(NodeInterface $node)
    {
        return $node->getAttribute($node->getReservedAttributeName('right')) - $node->getAttribute($node->getReservedAttributeName('left'));
    }

    /**
     * Enures the given closur is executed within a PDO transaction.
     *
     * @param  Closure  $callback
     * @return void
     */
    public function ensureTransaction(Closure $callback)
    {
        if (! $this->connection->getPdo()->inTransaction()) {
            $this->connection->transaction($callback);
        } else {
            $callback($this->connection);
        }
    }

    /**
     * Inserts a node in the database and upates the node's
     * properties if applicable.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Illuminate\Database\Query\Builder  $query
     * @return void
     */
    public function insertNode(NodeInterface $node)
    {
        $query         = $this->connection->table($this->getTable());
        $keyName       = $this->baseNode->getKeyName();
        $allAttributes = $node->getAllAttributes();

        $attributes = array_except(
            $allAttributes,
            [$this->getDepthAttributeName(), $keyName]
        );

        if ($node->getIncrementing()) {
            $node->setAttribute($keyName, $query->insertGetId($attributes));
        } else {
            $attributes[$keyName] = array_get($allAttributes, $keyName);

            $query->insert($attributes);
        }

        $this->afterCreateNode($node);
    }

    /**
     * Updates the given node in the database.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function updateNode(NodeInterface $node)
    {
        $keyName    = $this->baseNode->getKeyName();
        $key        = $node->getAttribute($keyName);
        $attributes = array_except($node->getAllAttributes(), [$this->getDepthAttributeName()]);

        $this
            ->connection->table($this->getTable())
            ->where($keyName, '=', $key)
            ->update($attributes);

        $this->afterUpdateNode($node);
    }

    /**
     * Creates a node with the given attributes.
     *
     * @param  mixed  $attributes
     * @return Cartalyst\NestedSets\Nodes\NodeInterface  $node
     */
    public function createNode($attributes = [])
    {
        $attributes = (array) $attributes;

        // Prepare the node's children
        $children   = [];
        if (isset($attributes['children'])) {
            $children = $attributes['children'];
            unset($attributes['children']);
        }

        // Create a new node which we will hydrate
        // with results.
        $node = $this->baseNode->createNode();
        $node->setAllAttributes($attributes);
        $node->setChildren($children);

        return $node;
    }

    /**
     * Hydrates a node by querying the database for it
     * and updating it's reserved attributes from the
     * queried record.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function hydrateNode(NodeInterface $node)
    {
        $table      = $this->getTable();
        $attributes = $this->getReservedAttributeNames();
        $keyName    = $this->baseNode->getKeyName();

        $result = $this
            ->connection->table("$table")
            ->where($keyName, '=', $key = $node->getAttribute($keyName))
            ->first(array_values($attributes));

        if ($result === null) {
            throw new \RuntimeException("Attempting to hydrate non-existent node [$key].");
        }

        // We only want to update the attributes which
        // affect nested sets.
        $attributes = array_intersect_key((array) $result, array_flip($attributes));

        foreach ($attributes as $key => $value) {
            $node->setAttribute($key, $value);
        }
    }

    /**
     * Fires the "after create" trigger for a node.*
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function afterCreateNode(NodeInterface $node)
    {
        $node->syncOriginal();

        $node->afterCreate();
    }

    /**
     * Fires the "after update" trigger for a node.*
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function afterUpdateNode(NodeInterface $node)
    {
        $node->syncOriginal();

        $node->afterUpdate();
    }

    /**
     * Wraps a table from the current database connection.
     *
     * @param  string  $value
     * @return string
     */
    public function wrapTable($value)
    {
        return $this->connection->getQueryGrammar()->wrapTable($value);
    }

    /**
     * Wraps a value from the current database connection.
     *
     * @param  string  $value
     * @return string
     */
    public function wrap($value)
    {
        return $this->connection->getQueryGrammar()->wrap($value);
    }
}
