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

use Cartalyst\NestedSets\Nodes\NodeInterface;

class NodeStub implements NodeInterface
{
    protected $attributes = [];

    protected $children = [];

    /**
     * Actually finds the children for the node.
     *
     * @param  int  $depth
     * @return array
     */
    public function findChildren($depth = 0)
    {
    }

    /**
     * Returns the loaded children for the node.
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the children for the model.
     *
     * @param  array  $children
     * @return void
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * Clears the children for the model.
     *
     * @return void
     */
    public function clearChildren()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Sets the child in the children array at
     * the given index.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $child
     * @param  int  $index
     * @return void
     */
    public function setChildAtIndex(Cartalyst\NestedSets\Nodes\NodeInterface $child, $index)
    {
        $this->children[$index] = $child;
    }

    /**
     * Returns the child at the given index. If
     * the index does not exist, we return "null"
     *
     * @param  int  $index
     * @return Cartalyst\NestedSets\Nodes\NodeInterface  $child
     */
    public function getChildAtIndex($index)
    {
        return (isset($this->children[$index])) ? $this->children[$index] : null;
    }

    /**
     * Get the table associated with the node.
     *
     * @return string
     */
    public function getTable()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Get the primary key for the node.
     *
     * @return string
     */
    public function getKeyName()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Get all of the current attributes on the node.
     *
     * @return array
     */
    public function getAllAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set all of the current attributes on the node.
     *
     * @param  array  $attributes
     * @return void
     */
    public function setAllAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function getAttribute($key, $default = null)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return value($default);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get the reserved attributes.
     *
     * @return array
     */
    public function getReservedAttributeNames()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Get the name of a reserved attribute.
     *
     * @param  string  $key
     * @return string
     */
    public function getReservedAttributeName($key)
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Return the "depth" attribute.
     *
     * @return string
     */
    public function getDepthAttributeName()
    {
        return 'depth';
    }

    /**
     * Finds all nodes in a flat array.
     *
     * @return array
     */
    public function findAll()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Creates a new instance of this node.
     *
     * @return Cartalyst\NestedSets\Nodes\NodeInterface
     */
    public function createNode()
    {
        throw new BadMethodCallException('Stub method '.__METHOD__.' not implemented.');
    }

    /**
     * Callback after the node is created in the
     * database, not necessarily through save().
     *
     * @return void
     */
    public function afterUpdate()
    {
    }

    /**
     * Callback after the node is updated in the
     * database, not necessarily through save().
     *
     * @return void
     */
    public function afterCreate()
    {
    }

    /**
     * Dynamically retrieve attributes on the object.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the object.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if an attribute exists on the object.
     *
     * @param  string  $key
     * @return void
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Unset an attribute on the object.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }
}
