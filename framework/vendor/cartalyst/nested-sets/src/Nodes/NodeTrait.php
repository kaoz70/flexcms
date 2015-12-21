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

use Closure;
use Cartalyst\NestedSets\Presenter;
use Illuminate\Database\Eloquent\Model;

trait NodeTrait
{
    /**
     * Children associated with the model.
     *
     * @var array
     */
    protected $children = [];

    /**
     * Boot the node trait for a model.
     *
     * @return void
     */
    public static function bootNodeTrait()
    {
        static::deleting(function($model)
        {
            if ($model->exists) {
                $model->createWorker()->deleteNode($model);
            }
        });
    }

    /**
     * The attribute used to show the depth
     * of a child when in a tree. This attribute
     * cannot be saved to the databse, it's for
     * processing and runtime usage only.
     *
     * @var string
     */
    protected $depthAttribute = 'depth';

    /**
     * The worker class which the model uses.
     *
     * @var string
     */
    protected $worker = 'Cartalyst\NestedSets\Workers\IlluminateWorker';

    /**
     * The presenter instance.
     *
     * @var Cartalyst\NestedSets\Presenter
     */
    protected static $presenter;

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
        $this->children = [];
    }

    /**
     * Sets the child in the children array at
     * the given index.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $child
     * @param  int  $index
     * @return void
     */
    public function setChildAtIndex(NodeInterface $child, $index)
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
        return isset($this->children[$index]) ? $this->children[$index] : null;
    }

    /**
     * Get the table associated with the node.
     *
     * @return string
     */
    public function getTable()
    {
        return parent::getTable();
    }

    /**
     * Get the primary key for the node.
     *
     * @return string
     */
    public function getKeyName()
    {
        return parent::getKeyName();
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return parent::getIncrementing();
    }

    /**
     * Get all of the current attributes on the node.
     *
     * @return array
     */
    public function getAllAttributes()
    {
        return $this->getAttributes();
    }

    /**
     * Set all of the current attributes on the node.
     *
     * @param  array  $attributes
     * @return void
     */
    public function setAllAttributes(array $attributes)
    {
        if ($this->incrementing and array_key_exists($this->primaryKey, $attributes) and $attributes[$this->primaryKey] != null) {
            $this->exists = true;
        }

        static::$unguarded = true;
        $this->fill($attributes);
        static::$unguarded = false;
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return parent::getAttribute($key);
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
        if ($this->incrementing and $key == $this->primaryKey and $value != null) {
            $this->exists = true;
        }

        parent::setAttribute($key, $value);
    }

    /**
     * Get the reserved attributes.
     *
     * @return array
     */
    public function getReservedAttributeNames()
    {
        return $this->reservedAttributes;
    }

    /**
     * Get the name of a reserved attribute.
     *
     * @param  string  $key
     * @return string
     */
    public function getReservedAttributeName($key)
    {
        return $this->reservedAttributes[$key];
    }

    /**
     * Return the "depth" attribute.
     *
     * @return string
     */
    public function getDepthAttributeName()
    {
        return $this->depthAttribute;
    }

    /**
     * Finds all nodes in a flat array.
     *
     * @return array
     */
    public function findAll()
    {
        return static::all()->all();
    }

    /**
     * Creates a new instance of this node.
     *
     * @return Cartalyst\NestedSets\Nodes\NodeInterface
     */
    public function createNode()
    {
        return $this->newInstance();
    }

    /**
     * Callback after the node is created in the
     * database, not necessarily through save().
     *
     * @return void
     */
    public function afterCreate()
    {
        if ($this->timestamps) {
            $this->exists = true;
            $this->setCreatedAt($time = $this->freshTimestamp());
            $this->touch();
        }
    }

    /**
     * Callback after the node is updated in the
     * database, not necessarily through save().
     *
     * @return void
     */
    public function afterUpdate()
    {
        if ($this->timestamps) {
            $this->touch();
        }
    }

    /**
     * Delete the model from the database and also
     * all of it's children.
     *
     * @return void
     */
    public function deleteWithChildren()
    {
        if ($this->exists) {
            $this->createWorker()->deleteNodeWithChildren($this);
        }
    }

    /**
     * Refreshes the node's reserved attributes from the database.
     *
     * @return void
     */
    public function refresh()
    {
        $this->createWorker()->hydrateNode($this);
    }

    /**
     * Returns if the model is a leaf node or not; whether
     * it has children.
     *
     * @return bool
     */
    public function isLeaf()
    {
        return $this->createWorker()->isLeaf($this);
    }

    /**
     * Returns the path of the node.
     *
     * @return array
     */
    public function getPath()
    {
        return $this->createWorker()->path($this);
    }

    /**
     * Returns the depth of the node.
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->createWorker()->depth($this);
    }

    /**
     * Returns the parent for the node.
     *
     * @return Cartalyst\NestedSets\Nodes\NodeInterface
     */
    public function getParent()
    {
        return $this->createWorker()->parentNode($this);
    }

    /**
     * Returns the cound of children for the model.
     *
     * @param  int  $depth
     * @return int
     */
    public function getChildrenCount($depth = 0)
    {
        return $this->createWorker()->childrenCount($this, $depth);
    }

    /**
     * Actually finds the children for the node.
     *
     * @param  int  $depth
     * @return array
     */
    public function findChildren($depth = 0)
    {
        return $this->children = $this->loadTree($depth);
    }

    /**
     * Allows you to pass through a callback when finding children,
     * to manipulate the query. Does not cache the children.
     *
     * @param  Closure  $callback
     * @param  int  $depth
     * @return array
     */
    public function filterChildren(Closure $callback, $depth = 0)
    {
        return $this->loadTree($depth, $callback);
    }

    /**
     * Makes the model a root node.
     *
     * @return void
     */
    public function makeRoot()
    {
        // @todo Allow existing items to become new root items
        if ($this->exists) {
            throw new \RuntimeException("Currently cannot make existing node {$this->getKey()} a root item.");
        }

        $this->createWorker()->insertNodeAsRoot($this);
    }

    /**
     * Makes the model the first child of the given parent.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function makeFirstChildOf(NodeInterface $parent)
    {
        $method = $this->exists ? 'moveNodeAsFirstChild' : 'insertNodeAsFirstChild';
        $this->createWorker()->$method($this, $parent);
    }

    /**
     * Makes the model the last child of the given parent.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $parent
     * @return void
     */
    public function makeLastChildOf(NodeInterface $parent)
    {
        $method = $this->exists ? 'moveNodeAsLastChild' : 'insertNodeAsLastChild';
        $this->createWorker()->$method($this, $parent);
    }

    /**
     * Makes the model the previous sibling of the given sibling.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function makePreviousSiblingOf(NodeInterface $sibling)
    {
        $method = $this->exists ? 'moveNodeAsPreviousSibling' : 'insertNodeAsPreviousSibling';
        $this->createWorker()->$method($this, $sibling);
    }

    /**
     * Makes the model the next sibling of the given sibling.
     *
     * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $sibling
     * @return void
     */
    public function makeNextSiblingOf(NodeInterface $sibling)
    {
        $method = $this->exists ? 'moveNodeAsNextSibling' : 'insertNodeAsNextSibling';
        $this->createWorker()->$method($this, $sibling);
    }

    /**
     * Maps a tree of either nodes, arrays of StdClass objects to
     * the hierarchy array. Children nodes present in the database
     * but not present in this hierarchy will removed.
     *
     * @param  mixed  $nodes
     * @return void
     */
    public function mapTree($nodes)
    {
        $this->createWorker()->mapTree($this, $nodes);
    }

    /**
     * Maps a tree of either nodes, arrays of StdClass objects to
     * the hierarchy array. Children nodes present in the database
     * but not present in this hierarchy will be kept, they
     * become the first items in the tree.
     *
     * @param  mixed  $nodes
     * @return void
     */
    public function mapTreeAndKeep($nodes)
    {
        $this->createWorker()->mapTreeAndKeep($this, $nodes);
    }

    /**
     * Presents the node in the given format. If the attribute
     * provided is a closure, we will call it, providing every
     * single node recursively. You must return a string from
     * your closure which will be used as the output for that
     * node when presenting.
     *
     * @param  string  $format
     * @param  string|Closure  $attribute
     * @param  int  $depth
     * @return mixed
     */
    public function presentAs($format, $attribute, $depth = 0)
    {
        return static::$presenter->presentAs($this, $format, $attribute, $depth);
    }

    /**
     * Presents the children of the given node in the given
     * format. If the attribute provided is a closure, we will
     * call it, providing every single node recursively. You
     * must return a string from your closure which will be
     * used as the output for that node when presenting.
     *
     * @param  string  $format
     * @param  string|Closure  $attribute
     * @param  int  $depth
     * @return mixed
     */
    public function presentChildrenAs($format, $attribute, $depth = 0)
    {
        return static::$presenter->presentChildrenAs($this, $format, $attribute, $depth);
    }

    /**
     * Creates a worker instance for the model.
     *
     * @return Cartalyst\NestedSets\Workers\WorkerInterface
     */
    public function createWorker()
    {
        $class = '\\'.ltrim($this->worker, '\\');

        return new $class($this->getConnection(), $this);
    }

    /**
     * Sets the wroker to be used by the model.
     *
     * @param  string  $helper
     * @return void
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = $this->attributesToArray();
        $attributes['children'] = [];

        foreach ($this->children as $child) {
            $attributes['children'][] = $child->toArray();
        }

        return array_merge($attributes, $this->relationsToArray());
    }

    /**
     * Loads a tree.
     *
     * @param  int  $depth
     * @param  Closure  $callback
     * @return array
     */
    protected function loadTree($depth = 0, Closure $callback = null)
    {
        $tree = $this->createWorker()->tree($this, $depth, $callback);

        // The tree method from the worker is none-the-wiser
        // to whether we are retrieving a root node or not. If
        // we only have one child, it will therefore return a
        // singular object. We'll ensure we're actually returning
        // an array.
        if (! is_array($tree)) {
            $tree = [$tree];
        }

        return $tree;
    }

    /**
     * Returns a collection of all nodes in a flat array.
     *
     * @param  int  $tree
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function allFlat($tree = null)
    {
        $static = new static;
        $nodes  = $static->createWorker()->allFlat($tree);

        return $static->newCollection($nodes);
    }

    /**
     * Returns a collection of all root nodes.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function allRoot()
    {
        $static = new static;
        $root   = $static->createWorker()->allRoot();

        return $static->newCollection($root);
    }

    /**
     * Returns a collection of all leaf nodes.
     *
     * @param  ind  $tree
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function allLeaf($tree = null)
    {
        $static = new static;
        $leaf   = $static->createWorker()->allLeaf($tree);

        return $static->newCollection($leaf);
    }

    /**
     * Sets the presenter to be used by all Eloquent nodes.
     *
     * @param  Cartalyst\NestedSets\Presenter
     * @return void
     */
    public static function setPresenter(Presenter $presenter)
    {
        static::$presenter = $presenter;
    }

    /**
     * Gets the presenter used by all Eloquent nodes.
     *
     * @return Cartalyst\NestedSets\Presenter
     */
    public static function getPresenter()
    {
        return static::$presenter;
    }

    /**
     * Unsets the presenter instance.
     *
     * @return void
     */
    public static function unsetPresenter()
    {
        static::$presenter = null;
    }

    /**
     * Handle dynamic method calls into the method.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Account for dynamic calls to a the presenter instance
        if (starts_with($method, 'presentAs')) {
            array_unshift($parameters, lcfirst(substr($method, 9)));
            return call_user_func_array([$this, 'presentAs'], $parameters);
        }
        if (starts_with($method, 'presentChildrenAs')) {
            array_unshift($parameters, lcfirst(substr($method, 17)));
            return call_user_func_array([$this, 'presentChildrenAs'], $parameters);
        }

        return parent::__call($method, $parameters);
    }
}
