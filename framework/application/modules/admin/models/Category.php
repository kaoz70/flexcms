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
use Cartalyst\NestedSets\Nodes\NodeInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Category extends \App\Category implements NodeInterface {

    protected $section = 'page';

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
        $page->popup = (bool) $input['popup'];
        $page->save();

    }

    public function setTranslations($input)
    {

        $input = json_decode($input, true);

        foreach(Language::all() as $lang){

            $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $this->id, 'type' => $this->section]);
            $trans_data = json_decode($trans->data);

            $trans_data->name = $input[$lang->id]['translation']['name'];
            $trans_data->menu_name = $input[$lang->id]['translation']['menu_name'];

            //We update this form two different places so we have to check if they are sending the data

            if(isset($input[$lang->id]['translation']['meta_keywords'])){
                //remove the space if any from the start and end of each keyword, create an array
                $trans_data->meta_keywords = $input[$lang->id]['translation']['meta_keywords'];
            }

            if(isset($input[$lang->id]['translation']['meta_description'])){
                //remove the space if any from the start and end of each keyword, create an array
                $trans_data->meta_description = $input[$lang->id]['translation']['meta_description'];
            }

            if(isset($input[$lang->id]['translation']['meta_title'])){
                //remove the space if any from the start and end of each keyword, create an array
                $trans_data->meta_title = $input[$lang->id]['translation']['meta_title'];
            }

            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}