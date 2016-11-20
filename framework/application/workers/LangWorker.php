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
     * TODO: find a way to not need to override this method because of the translations on line 193
     *
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
            $newNode = $this->createNode($result);
            $newNode->getTranslation($node->getLang());
            $nodes[] = $newNode;
        }

        return $nodes;
    }

}