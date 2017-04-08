<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace App;

use Baum\Node;
use Closure;

class Category extends Node {

    protected static $type;

    /**
     * Return popup attribute as boolean instead of int
     *
     * @param $value
     * @return bool
     */
    public function getPopupAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * Return popup attribute as boolean instead of int
     *
     * @param $value
     * @return bool
     */
    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Calls the recursive function to check for unique names on each nodes
     *
     * @param $name
     * @param $lang
     *
     * @return bool
     */
    public function isUniqueName($name, $lang)
    {

        $depth = $this->getDepth();
        $root = self::find(1);
        $root->findChildren($depth);

        $unique = $this->recursiveFindName($root->getChildren(), $lang, $name);

        return array_search(FALSE, $unique) ? FALSE : TRUE;

    }

    protected function loadTree($depth = 0, Closure $callback = null)
    {

        $this->setWorker('LangWorker');
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Loops through each child nodes to see if the passed name is unique or not
     *
     * @param $children
     * @param $lang
     * @param $name
     *
     * @return array
     */
    private function recursiveFindName($children, $lang, $name)
    {

        $unique = [];

        foreach ($children as $childNode)
        {

            if($childNode->id != $this->id) {
                try {

                    $translation = $childNode->getTranslation($lang, self::getType());

                    if($translation->name === $name) {
                        $unique[] = FALSE;
                    } else {
                        $unique[] = TRUE;
                    }

                } catch (\Exception $e) {
                    //No translation here so no problem, its unique
                    $unique[] = TRUE;
                }

                if (count($childNode->getChildren()) > 0) {
                    $unique = array_merge($unique, $this->recursiveFindName($childNode->getChildren(), $lang, $name));
                }
            }

        }

        return $unique;

    }

    /**
     * Creates the menu and the path to the active node
     * @return array
     */
    static function createMenu($lang)
    {
        $CI =& get_instance();
        //$path = $CI->m_breadcrumbs['page']['path'];

        $tree = self::_getCachedTree($lang . '_pages_', $lang);

        return compact('tree', 'path');
    }

    /**
     * Checks if the Tree node is cached, and returns it, or else query again
     *
     * @param $key
     * @param $lang
     *
     * @return mixed
     */
    static function _getCachedTree($key, $lang)
    {

        $CI =& get_instance();

        if ( ! $tree = $CI->cache->get($key)) {
            $tree = self::first();
            $tree->lang = $lang;
            $tree->findChildren(1);
            if(ENVIRONMENT !== 'development') {
                $CI->cache->save($key, $tree, 9999);
            }
        }

        return $tree;

    }

    /**
     * Deletes a node
     *
     * @param $id
     */
    static function remove($id)
    {

        $node = Category::find($id);
        $node->deleteWithChildren();

        //Delete the page's translations
        foreach(Language::all() as $lang){
            Translation::where('parent_id', '=', $id)
                ->where('language_id', '=', $lang->id)
                ->delete();
        }

    }

    /**
     * @return string
     */
    public static function getType()
    {
        return static::$type;
    }

    /**
     * Returns the content's translation as a json decoded object/array
     *
     * @param int $lang_id
     * @return mixed
     * @throws \CMSException
     */
    public function getTranslation($lang_id)
    {

        if(!$this->getType()) {
            throw new \CMSException("Please set the model " . __CLASS__ . "'s protected type variable");
        }

        $trans = $this->hasOne('App\Translation', 'parent_id')
            ->where('language_id', $lang_id)
            ->where('type', static::$type)
            ->first();

        if($trans) {
            $this->translation = $trans->data;
            $this->createProperties($trans->data);
            return $this->translation;
        } else {
            return $this->translation = null;
        }

    }

    /**
     * Add translation properties directly to the model
     *
     * @param $data
     */
    public function createProperties($data){
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }

}