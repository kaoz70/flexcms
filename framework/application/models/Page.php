<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 06-Dec-16
 * Time: 05:01 PM
 */

namespace App;


class Page extends Category
{

    protected static $type = 'page';
    protected $table = 'categories';

    /**
     * Get the flat list of pages
     *
     * @param $lang_id
     * @return mixed
     */
    public static function getList($lang_id)
    {
        
        $pages = static::where('type', static::$type)->get();
        
        foreach ($pages as &$page) {
            $page->getTranslation($lang_id);
        }

        return $pages;
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

    public function setTranslations($inputs)
    {

        foreach(Language::all() as $lang){

            foreach ($inputs as $input) {

                if($input['id'] === $lang->id) {

                    $trans = Translation::firstOrNew(['language_id' => $lang->id, 'parent_id' => $this->id, 'type' => self::getType()]);
                    $trans_data = $trans->data ?: new \stdClass();

                    $trans_data->name = $input['translation']['name'];
                    $trans_data->menu_name = $input['translation']['menu_name'];

                    //We update this form two different places so we have to check if they are sending the data

                    if(isset($input['translation']['meta_keywords'])){
                        //remove the space if any from the start and end of each keyword, create an array
                        $trans_data->meta_keywords = $input['translation']['meta_keywords'];
                    } else {
                        $trans_data->meta_keywords = [];
                    }

                    if(isset($input['translation']['meta_description'])){
                        //remove the space if any from the start and end of each keyword, create an array
                        $trans_data->meta_description = $input['translation']['meta_description'];
                    }

                    if(isset($input['translation']['meta_title'])){
                        //remove the space if any from the start and end of each keyword, create an array
                        $trans_data->meta_title = $input['translation']['meta_title'];
                    }

                    $trans->data = json_encode($trans_data);
                    $trans->save();

                }

            }

        }
    }

}