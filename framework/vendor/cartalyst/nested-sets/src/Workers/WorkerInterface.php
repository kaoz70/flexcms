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

use Cartalyst\NestedSets\Nodes\NodeInterface;

interface WorkerInterface
{
    /**
     * Returns all nodes, in a flat array.
     *
     * @param  int  $tree
     * @return array
     */
    public function allFlat($tree = null);

    /**
     * Returns all root nodes, in a flat array.
     *
     * @return array
     */
    public function allRoot();

    /**
     * Finds all leaf nodes, in a flat array.
     * Leaf nodes are nodes which do not have
     * any children.
     *
     * @param  int  $tree
     * @return array
     */
    public function allLeaf($tree = null);

    /**
     * Returns if the given node is a leaf node (has
     * no children).
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return bool
     */
    public function isLeaf(NodeInterface $node);

    /**
     * Finds the path of the given node. The path is
     * the primary key of the node and all of it's
     * parents up to the root item.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return array
     */
    public function path(NodeInterface $node);

    /**
     * Returns the depth of a node in a tree, where
     * 0 is a root node, 1 is a root node's direct
     * child and so on.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return int
     */
    public function depth(NodeInterface $node);

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
    public function relativeDepth(NodeInterface $node, NodeInterface $parentNode);

    /**
     * Returns the parnet node for the given node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     */
    public function parentNode(NodeInterface $node);

    /**
     * Returns all children for the given node in a flat
     * array. If the depth is 1 or more, that is how many
     * levels of children we recurse through to put into
     * the flat array.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $depth
     * @return array
     */
    public function childrenNodes(NodeInterface $node, $depth = 0);

    /**
     * Returns the count of the children for the given node, with an
     * optional depth limit.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $depth
     * @return int
     */
    public function childrenCount(NodeInterface $node, $depth = 0);

    /**
     * Returns a tree for the given node. If the depth
     * is 0, we return all children. If the depth is
     * 1 or more, that is how many levels of children
     * nodes we recurse through.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  int  $depth
     * @return array
     */
    public function tree(NodeInterface $node, $depth = 0);

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
    public function mapTree(NodeInterface $parent, array $nodes, $delete = true);

    /**
     * Maps a tree to the database and keep all nodes not present in
     * the passed array. This allows for allowing pushing new items
     * into a tree without affecting the entire tree.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface   $parent
     * @param  array  $nodes
     * @return void
     */
    public function mapTreeAndKeep(NodeInterface $parent, array $nodes);

    /**
     * Makes a new node a root node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function insertNodeAsRoot(NodeInterface $node);

    /**
     * Inserts the given node as the first child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function insertNodeAsFirstChild(NodeInterface $node, NodeInterface $parent);

    /**
     * Inserts the given node as the last child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function insertNodeAsLastChild(NodeInterface $node, NodeInterface $parent);

    /**
     * Inserts the given node as the previous sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function insertNodeAsPreviousSibling(NodeInterface $node, NodeInterface $sibling);

    /**
     * Inserts the given node as the next sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function insertNodeAsNextSibling(NodeInterface $node, NodeInterface $sibling);

    /**
     * Makes the given node a root node.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function moveNodeAsRoot(NodeInterface $node);

    /**
     * Moves the given node as the first child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function moveNodeAsFirstChild(NodeInterface $node, NodeInterface $parent);

    /**
     * Moves the given node as the last child of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function moveNodeAsLastChild(NodeInterface $node, NodeInterface $parent);

    /**
     * Moves the given node as the previous sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function moveNodeAsPreviousSibling(NodeInterface $node, NodeInterface $sibling);

    /**
     * Moves the given node as the next sibling of
     * the parent node. Updates node attributes as well.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function moveNodeAsNextSibling(NodeInterface $node, NodeInterface $sibling);

    /**
     * Removes a node from the database and orphans
     * it's children.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function deleteNode(NodeInterface $node);

    /**
     * Removes a node from the database and all of
     * it's children.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
     * @return void
     */
    public function deleteNodeWithChildren(NodeInterface $node);
}
