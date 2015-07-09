<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 16/12/2014
 * Time: 14:12
 */

use Cartalyst\NestedSets\Nodes\NodeInterface;
use Cartalyst\NestedSets\Workers\IlluminateWorker;
use Illuminate\Database\Query\Expression;

class LangWorker extends IlluminateWorker {

	/**
	 * Returns the parnet node for the given node.
	 *
	 * @param  Cartalyst\NestedSets\Nodes\NodeInterface  $node
	 * @return Cartalyst\NestedSets\Nodes\NodeInterface  $parent
	 */
	public function parentNodeOverride(NodeInterface $node)
	{
		$attributes = $this->getReservedAttributeNames();
		$table      = $this->getTable();
		$keyName    = $this->baseNode->getKeyName();
		$key        = $node->getAttribute($keyName);
		$left       = $node->getAttribute($attributes['left']);

		// If we are a root node, we obviously won't have a parent.
		// This method should never be called on root nodes.
		if ($left == 1) {
			//throw new \RuntimeException("Node [$key] has no parent as it is a root node.");
			return FALSE;
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
	 * Returns a tree for the given node. If the depth
	 * is 0, we return all children. If the depth is
	 * 1 or more, that is how many levels of children
	 * nodes we recurse through.
	 *
	 * @param NodeInterface $node
	 * @param int $depth
	 * @param $join
	 * @param null $callback
	 * @return mixed
	 */
	public function treeLang(NodeInterface $node, $depth = 0, $join, $callback = null)
	{
		$nodes = $this->childrenNodesLang($node, $depth, $join, $callback);

		return $this->flatNodesToTree($nodes);
	}

	/**
	 * Returns all children for the given node in a flat
	 * array. If the depth is 1 or more, that is how many
	 * levels of children we recurse through to put into
	 * the flat array.
	 *
	 * @param NodeInterface $node
	 * @param int $depth
	 * @param $join
	 * @param callable $callback
	 * @return array
	 */
	public function childrenNodesLang(NodeInterface $node, $depth = 0, $join, Closure $callback = null)
	{
		$attributes = $this->getReservedAttributeNames();
		$table      = $this->getTable();
		$keyName    = $this->baseNode->getKeyName();
		$key        = $node->getAttribute($keyName);
		$tree       = $node->getAttribute($attributes['tree']);
		$nodes      = array();

		// We will store a query builder object that we
		// use throughout the course of this method.
		$query = $this
			->connection->table("$table as node")
			->join(
				"$table as parent",
				new Expression($this->wrapColumn("node.{$attributes['left']}")),
				'>=',
				new Expression($this->wrapColumn("parent.{$attributes['left']}"))
			)
			->where(
				new Expression($this->wrapColumn("node.{$attributes['left']}")),
				'<=',
				new Expression($this->wrapColumn("parent.{$attributes['right']}"))
			)
			->join(
				"$table as sub_parent",
				new Expression($this->wrapColumn("node.{$attributes['left']}")),
				'>=',
				new Expression($this->wrapColumn("sub_parent.{$attributes['left']}"))
			)
			->where(
				new Expression($this->wrapColumn("node.{$attributes['left']}")),
				'<=',
				new Expression($this->wrapColumn("sub_parent.{$attributes['right']}"))
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

		$query->join('sub_tree', function($join) use ($me, $node, $subQuery, $attributes, $table, $keyName, $key, $tree)
		{
			$subQuery
				->select(
					new Expression($me->wrapColumn("node.$keyName")),
					new Expression(sprintf(
						'(count(%s) - 1) as %s',
						$me->wrapColumn("parent.$keyName"),
						$me->wrap($me->getDepthAttributeName())
					))
				)
				->join(
					"$table as parent",
					new Expression($me->wrapColumn("node.{$attributes['left']}")),
					'>=',
					new Expression($me->wrapColumn("parent.{$attributes['left']}"))
				)
				->where(
					new Expression($me->wrapColumn("node.{$attributes['left']}")),
					'<=',
					new Expression($me->wrapColumn("parent.{$attributes['right']}"))
				)
				->where(
					new Expression($me->wrapColumn("node.$keyName")),
					'=',
					$key
				)
				->where(
					new Expression($me->wrapColumn("node.{$attributes['tree']}")),
					'=',
					$tree
				)
				->where(
					new Expression($me->wrapColumn("parent.{$attributes['tree']}")),
					'=',
					$tree
				)
				->orderBy(new Expression($me->wrapColumn("node.{$attributes['left']}")))
				->groupBy(new Expression($me->wrapColumn("node.$keyName")));

			// Configure the join from the SQL the query
			// builder generates.
			$join->table = new Expression(sprintf(
				'(%s) as %s',
				$subQuery->toSql(),
				$me->wrap($join->table)
			));

			$join->on(
				new Expression($me->wrapColumn("sub_parent.$keyName")),
				'=',
				new Expression($me->wrapColumn("sub_tree.$keyName"))
			);
		});

		// Now we have compiled the SQL for our sub query,
		// we need to merge it's bindings into our main query.
		$query->mergeBindings($subQuery);

		$query
			->where(
				new Expression($this->wrapColumn("node.{$keyName}")),
				'!=',
				$key
			)
			->where(
				new Expression($this->wrapColumn("node.{$attributes['tree']}")),
				'=',
				$tree
			)
			->where(
				new Expression($this->wrapColumn("parent.{$attributes['tree']}")),
				'=',
				$tree
			)
			->where(
				new Expression($this->wrapColumn("sub_parent.{$attributes['tree']}")),
				'=',
				$tree
			);

		// If a callback was supplied, we'll call it now
		if ($callback)
		{
			$callback($query);
		}

		$query
			->orderBy(new Expression($this->wrapColumn("node.{$attributes['left']}")))
			->groupBy(
				new Expression($this->wrapColumn("node.$keyName")),
				new Expression($this->wrapColumn("sub_tree.{$this->getDepthAttributeName()}"))
			);

		// If we have a depth, we need to supply a "having"
		// clause to the query builder.
		if ($depth > 0)
		{
			$query->having(
				new Expression(
					sprintf(
						'count(%s)',
						$this->wrapColumn($me->getDepthAttributeName())
					)
				),
				'<=', ++$depth
			);
		}

		$query->leftJoin($join['table'], $join['table'] . '.' . $join['column'], '=', 'node.id');

		$exp = array();
		foreach ($join['trans_columns'] as $column) {
			$exp[] = new Expression($this->wrapColumn("{$join['table']}.{$column}"));
		}
		$exp[] = new Expression($this->wrapColumn("node.*"));
		$exp[] = new Expression(sprintf(
			'(count(%s) - (%s + 1)) as %s',
			$this->wrapColumn("parent.$keyName"),
			$this->wrapColumn("sub_tree.{$this->getDepthAttributeName()}"),
			$this->wrap($this->getDepthAttributeName())
		));

		$results = $query->get($exp);

		foreach ($results as $result)
		{
			$nodes[] = $this->createNode($result);
		}

		return $nodes;
	}

}