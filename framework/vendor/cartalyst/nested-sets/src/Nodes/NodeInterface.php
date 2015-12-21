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

namespace Cartalyst\NestedSets\Nodes;

interface NodeInterface
{
    /**
     * Actually finds the children for the node.
     *
     * @param  int  $depth
     * @return array
     */
    public function findChildren($depth = 0);

    /**
     * Returns the loaded children for the node.
     *
     * @return array
     */
    public function getChildren();

    /**
     * Sets the children for the model.
     *
     * @param  array  $children
     * @return void
     */
    public function setChildren(array $children);

    /**
     * Clears the children for the model.
     *
     * @return void
     */
    public function clearChildren();

    /**
     * Sets the child in the children array at
     * the given index.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $child
     * @param  int  $index
     * @return void
     */
    public function setChildAtIndex(NodeInterface $child, $index);

    /**
     * Returns the child at the given index. If
     * the index does not exist, we return "null"
     *
     * @param  int  $index
     * @return Cartalyst\NestedSets\Nodes\NodeInterface  $child
     */
    public function getChildAtIndex($index);

    /**
     * Get the table associated with the node.
     *
     * @return string
     */
    public function getTable();

    /**
     * Get the primary key for the node.
     *
     * @return string
     */
    public function getKeyName();

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing();

    /**
     * Get all of the current attributes on the node.
     *
     * @return array
     */
    public function getAllAttributes();

    /**
     * Set all of the current attributes on the node.
     *
     * @param  array  $attributes
     * @return void
     */
    public function setAllAttributes(array $attributes);

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value);

    /**
     * Get the reserved attributes.
     *
     * @return array
     */
    public function getReservedAttributeNames();

    /**
     * Get the name of a reserved attribute.
     *
     * @param  string  $key
     * @return string
     */
    public function getReservedAttributeName($key);

    /**
     * Return the "depth" attribute.
     *
     * @return string
     */
    public function getDepthAttributeName();

    /**
     * Finds all nodes in a flat array.
     *
     * @return array
     */
    public function findAll();

    /**
     * Creates a new instance of this node.
     *
     * @return Cartalyst\NestedSets\Nodes\NodeInterface
     */
    public function createNode();

    /**
     * Callback after the node is created in the
     * database, not necessarily through save().
     *
     * @return void
     */
    public function afterCreate();

    /**
     * Callback after the node is updated in the
     * database, not necessarily through save().
     *
     * @return void
     */
    public function afterUpdate();
}
