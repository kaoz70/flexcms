<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace App;

use Cartalyst\NestedSets\Nodes\NodeTrait;
use Cartalyst\NestedSets\Nodes\NodeInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Category extends BaseModel implements NodeInterface {

    use NodeTrait;

    private $type;

    protected $reservedAttributes = array(
        'left'  => 'lft',
        'right' => 'rgt',
        'tree'  => 'tree',
    );

    /**
     * Translation data for one language
     *
     * @var
     */
    public $translation;

    /**
     * Holds all the item's translations
     *
     * @var
     */
    public $translations;

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

        if(array_search(FALSE, $unique)) {
            return FALSE;
        } else {
            return TRUE;
        }

    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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

                    $translation = $childNode->getTranslation($lang, 'page');

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

}