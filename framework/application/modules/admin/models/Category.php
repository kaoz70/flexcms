<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace admin;

use App\Language;
use App\Translation;
use App\Widget;
use Cartalyst\NestedSets\Nodes\NodeTrait;
use Cartalyst\NestedSets\Nodes\NodeInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Category extends \App\Category implements NodeInterface {

    protected $section = 'page';

    use NodeTrait;

    /**
     * Inserts a temporary node into the category
     *
     * @param $type
     * @return Category
     */
    static function insertTemporary($type)
    {
        //Create the new page
        $node = new self([
            'temporary' => 1,
            'type' => 'page',
        ]);
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

            $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $this->id, 'type' => $this->section]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}