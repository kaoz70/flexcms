<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 22-Apr-17
 * Time: 10:15 PM
 */

namespace App\Widget\Content\Model;



use App\Admin;

class Widget extends \App\Widget
{

    /**
     * View path to where the admin view is located
     *
     * @var string
     */
    private static $admin_view = 'content/views/Admin.php';

    /**
     * @return string
     */
    public static function getAdminView()
    {
        return self::$admin_view;
    }

    /**
     * Admin data
     *
     * @param null $id
     * @return mixed
     */
    static function admin($id){
        $widget = Widget::getForEdit($id);
        $widget['types'] = Admin::getContentModules();
        $widget['config'] =  json_decode(file_get_contents(APPPATH . 'widgets/content/config.json'));
        //TODO: what is this for??
        //$widget['content']->data = json_decode($widget['content']->data);
        return $widget;
    }

    public function store($data)
    {
        $this->data = $data['data'];
        $this->save();

        //THe page may not exist yet if the widget is created before a new page save
        if($page = $this->getCategory()) {
            //Update the page content type
            $page->content_type = $this->data['content_type'];
            $page->save();
        }

    }

}