<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/11/15
 * Time: 5:37 PM
 */

namespace App\Widget;

use App\Admin;
use App\Error;
use App\Language;
use App\View;
use App\Widget;
use Exception;

class Content implements \WidgetInterface {

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
        //TODO: what is this??
        //$widget['content']->data = json_decode($widget['content']->data);
        return $widget;
    }

    static function run($method){

        // Logic will be written here.


    }

}