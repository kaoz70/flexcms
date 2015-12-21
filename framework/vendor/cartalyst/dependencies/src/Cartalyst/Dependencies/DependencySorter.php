<?php namespace Cartalyst\Dependencies;
/**
 * Part of the Dependencies package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Dependencies
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

class DependencySorter {

	/**
	 * Holds all the items and their dependencies.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Array of dependent instances associated with the
	 * items.
	 *
	 * @var array
	 */
	protected $dependents = array();

	/**
	 * Create a new sorter.
	 *
	 * @param  array  $items
	 * @return void
	 */
	public function __construct(array $items = array())
	{
		foreach ($items as $item)
		{
			$this->add($item);
		}
	}

	/**
	 * Adds a new item to the sorter.
	 *
	 * @param  string  $item
	 * @param  string|array  $dependencies
	 * @return void
	 */
	public function add($item, $dependencies = array())
	{
		if ($item instanceof DependentInterface)
		{
			$this->addDependent($item);
		}
		else
		{
			$this->addItem($item, $dependencies);
		}
	}

	/**
	 * Sorts the items in this object and returns an array
	 * items in order of their dependencies, where the first
	 * item has the least dependencies and can be used first
	 * and the last item can't be used until all the other
	 * items have been used.
	 *
	 * @return array
	 */
	public function sort()
	{
		$items = $this->items;
		list($original, $sorted) = array($items, array());

		while (count($items) > 0)
		{
			foreach ($items as $item => $dependencies)
			{
				$this->evaluateItem($item, $dependencies, $original, $sorted, $items);
			}
		}

		return $this->retrieve($sorted);
	}

	/**
	 * Get the items for the sorter.
	 *
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Get the dependents for the sorter.
	 *
	 * @return array
	 */
	public function getDependents()
	{
		return $this->dependents;
	}

	/**
	 * Adds a new item to the sorter.
	 *
	 * @param  string  $item
	 * @param  string|array  $dependencies
	 * @return void
	 */
	protected function addItem($item, $dependencies = array())
	{
		$this->items[$item] = (array) $dependencies;
	}

	/**
	 * Adds a new dependent instance to the sorter.
	 *
	 * @param  Cartalyst\Dependencies\DependentInterface  $dependent
	 * @return void
	 */
	protected function addDependent(DependentInterface $dependent)
	{
		$slug = $dependent->getSlug();

		$this->dependents[$slug] = $dependent;

		$this->addItem($slug, $dependent->getDependencies());
	}

	/**
	 * Evaluates an item by looking at it's dependenciese and
	 * adds it to the sorted array when the dependencies have been
	 * satisfied.
	 *
	 * @param  string  $item
	 * @param  array   $dependencies
	 * @param  array   $original
	 * @param  array   $items
	 * @return void
	 */
	protected function evaluateItem($item, $dependencies, $original, &$sorted, &$items)
	{
		// If the item has no more dependencies, we can add it to the sorted list
		// and remove it from the array of items. Otherwise, we will not verify
		// the item's dependencies and determine if they've been sorted.
		if (count($items[$item]) == 0)
		{
			$sorted[$item] = $item;
			unset($items[$item]);
		}
		else
		{
			foreach ($items[$item] as $key => $dependency)
			{
				if ( ! $this->dependencyIsValid($item, $dependency, $original, $items))
				{
					unset($items[$item][$key]);
					continue;
				}

				// If the dependency has not yet been added to the sorted list, we can not
				// remove it from this asset's array of dependencies. We'll try again on
				// the next trip through the loop.
				if ( ! isset($sorted[$dependency])) continue;

				unset($items[$item][$key]);
			}
		}
	}

	/**
	 * Verify that an item's dependency is valid.
	 *
	 * A dependency is considered valid if it exists, is not a circular reference
	 * and is not a reference to the owning asset itself. If the dependency does
	 * not exist, no errors or warnings are given. For all other cases, an
	 * Exception is thrown.
	 *
	 * @param  string  $item
	 * @param  string  $dependency
	 * @param  array   $original
	 * @param  array   $items
	 * @return bool
	 */
	protected function dependencyIsValid($item, $dependency, $original, $items)
	{
		if ( ! isset($original[$dependency]))
		{
			return false;
		}
		elseif ($dependency == $item)
		{
			throw new \UnexpectedValueException("Item [$item] is dependent on itself.");
		}
		elseif (isset($items[$dependency]) and in_array($item, $items[$dependency]))
		{
			throw new \UnexpectedValueException("Item [$item] and [$dependency] have a circular dependency.");
		}

		return true;
	}

	/**
	 * Takes an array of sorted items and returns the
	 * associated dependent if it exists, or simply
	 * the item if not.
	 *
	 * @param  array $sorted
	 * @return array $retrieved
	 */
	protected function retrieve(array $sorted)
	{
		$retrieved = array();

		foreach ($sorted as $slug)
		{
			if (array_key_exists($slug, $this->dependents))
			{
				$retrieved[] = $this->dependents[$slug];
			}
			else
			{
				$retrieved[] = $slug;
			}
		}

		return $retrieved;
	}

}
