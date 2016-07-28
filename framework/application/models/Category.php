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

                    $translation = $childNode->getTranslation($lang);

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
     * Inserts a temporary node into the category
     *
     * @param $type
     * @return Category
     */
    static function insertTemporary($type)
    {
        //Create the new page
        $node = new self(['temporary' => 1]);
        $node->makeLastChildOf(self::find(1));

        //Create the page's translations
        foreach(Language::all() as $lang){
            $trans = new Translation();
            $trans->parent_id = $node->id;
            $trans->language_id = $lang->id;
            $trans->type = $type;
            $trans->save();
        }

        return $node;
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

    static function updatePage($id, $input)
    {

        //Get the page structure
        $page = static::find($id);
        $page_data = json_decode($page->data);
        $current_widgets = [];
        $database_widgets = [];

        if(!$page) {
            throw new NotFoundResourceException('Este recurso no existe');
        }

        //Get all page modules
        $widgets_in_db = Widget::where('category_id', '=', $id)->get();

        foreach ($widgets_in_db as $widget) {
            $database_widgets[] = $widget->id;
        }

        //Set the structure
        if(isset($page_data->structure)) {

            //Does it have a sructure set?
            if(array_key_exists('fila', $input)) {

                //Re - format the input structure
                $rows = $input['fila'];
                $rows = array_values((array)json_decode(json_encode($rows), FALSE));

                //Set the rows properties
                foreach ($page_data->structure as $row_key => $row) {
                    $page_data->structure[$row_key]->class = $rows[$row_key]->class;
                    $page_data->structure[$row_key]->expanded = isset($rows[$row_key]->expanded) ? 1 : 0;

                    //Set the columns properties
                    foreach ($page_data->structure[$row_key]->columns as $col_key => $col) {
                        $page_data->structure[$row_key]->columns[$col_key]->class = $rows[$row_key]->columns[$col_key]->class;
                        $page_data->structure[$row_key]->columns[$col_key]->span = $rows[$row_key]->columns[$col_key]->span;
                        $page_data->structure[$row_key]->columns[$col_key]->offset = $rows[$row_key]->columns[$col_key]->offset;
                        $page_data->structure[$row_key]->columns[$col_key]->pull = $rows[$row_key]->columns[$col_key]->pull;
                        $page_data->structure[$row_key]->columns[$col_key]->push = $rows[$row_key]->columns[$col_key]->push;

                        $current_widgets = array_merge($current_widgets, $col->widgets);

                    }

                }

                //Remove any modules that are in the database but not in the page's structure
                $delete = array_diff($database_widgets, $current_widgets);

                if($delete) {

                    $widgets_in_db = Widget::whereIn('category_id', $delete)->get();
                    if($widgets_in_db->count()) {
                        $widgets_in_db->delete();
                    }

                }
            }

        } else {
            $page_data->structure = [];
        }

        $input['data'] = json_encode($page_data);

        //Update the page's translations
        $page->setTranslations($input);

        //Update the page's data
        $page->css_class = $input['css_class'];
        $page->enabled = $input['enabled'];
        $page->group_visibility = $input['group_visibility'];
        $page->data = $input['data'];
        $page->save();

    }

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'name' => $input['name'][$lang->id],
                'menu_name' => $input['menu_name'][$lang->id],
                //remove the space if any from the start and end of each keyword, create an array
                'meta_keywords' => array_map('trim', explode(',', $input['meta_keywords'][$lang->id])),
                'meta_description' => $input['meta_description'][$lang->id],
                'meta_title' => $input['meta_title'][$lang->id],
            ];

            $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $this->id, 'type' => 'page']);
            $trans->type = 'page';
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}