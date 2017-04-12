<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 12-Apr-17
 * Time: 04:16 PM
 */

namespace App;


use Baum\Node;

class TranslationNode extends Node
{

    /**
     * Set of all children & nested children.
     *
     * @param Language $lang
     * @return \Illuminate\Database\Query\Builder
     */
    public function descendantsLang(Language $lang) {
        return $this->descendantsAndSelfLang($lang)->withoutSelf();
    }

    /**
     * Retrieve all of its children & nested children.
     *
     * @param Language $lang
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDescendantsLang(Language $lang, $columns = array('*')) {
        if ( is_array($columns) )
            return $this->descendantsLang($lang)->get($columns);

        $arguments = func_get_args();

        $limit    = intval(array_shift($arguments));
        $columns  = array_shift($arguments) ?: array('*');

        return $this->descendantsLang($lang)->limitDepth($limit)->get($columns);
    }

    /**
     * Scope targeting itself and all of its nested children.
     *
     * @param Language $lang
     * @return \Illuminate\Database\Query\Builder
     */
    public function descendantsAndSelfLang(Language $lang) {
        return $this->newNestedSetQuery()
            ->selectRaw('*, (SELECT data FROM translations WHERE parent_id = `categories`.`id` AND language_id = ' . $lang->id . ') as translation')
            ->where($this->getLeftColumnName(), '>=', $this->getLeft())
            ->where($this->getLeftColumnName(), '<', $this->getRight());
    }

}