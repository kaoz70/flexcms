<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 06-Dec-16
 * Time: 05:01 PM
 */

namespace App;


use Cartalyst\Sentinel\Sentinel;

class Page extends Category
{

    protected static $type = 'page';
    protected $table = 'categories';
    protected $appends = ['translations', 'icon'];

    /**
     * Return enabled attribute as boolean instead of int
     *
     * @param $value
     * @return bool
     */
    public function getDataAttribute($value)
    {
        $data = new \stdClass();
        $data->structure = [];
        return json_decode($value) ?: $data;
    }

    public function getIconAttribute()
    {

        $icon = '';

        //Only get this info in the admin: that would be an Angular ajax request
        //TODO check if user is admin
        if(Utils::isAjaxRequest() && $contentWidget = \App\Widget::getMainWidget($this)) {
            $path = APPPATH . 'modules' . DIRECTORY_SEPARATOR . $contentWidget->data['content_type'] . DIRECTORY_SEPARATOR . 'config.json';
            $config = json_decode(file_get_contents($path));
            $icon = $config->menu->page_icon;
        }

        return $icon;
    }

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