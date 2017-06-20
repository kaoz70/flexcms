<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 22-Apr-17
 * Time: 10:15 PM
 */

namespace App\Widget\Content\Model;



use App\Admin;

class Widget extends \App\Widget implements \WidgetInterface
{

    protected static $admin_view = 'content/views/Admin.php';

    /**
     * Shows the Widget's admin view
     *
     * @param null $id
     * @return mixed
     */
    static function admin($id){
        $widget = static::getForEdit($id);
        $widget['types'] = Admin::getContentModules();
        $widget['config'] = (new Widget())->readConfigFile();
        return $widget;
    }

    /**
     * Runs the widget in the frontend
     *
     * @param $method
     * @return mixed
     */
    static function run($method)
    {
        // TODO: Implement run() method.
    }

    public function store($data)
    {
        $this->data = $data['data'];
        $this->save();

        //The page may not exist yet if the widget is created before a new page save
        if($page = $this->getCategory()) {
            //Update the page content type
            $page->is_content = true;
            $page->content_type = $this->data['content_type'];
            $page->save();
        }

    }

    /**
     * Update the page data before deleting the widget
     */
    public function delete()
    {

        //Get the page
        if($page = $this->getCategory()) {

            //Update the content types
            $page->is_content = false;
            $page->content_type = null;
            $data = $page->data;

            //Find the widget in the structure and delete it from there
            foreach ($data['structure'] as &$rows) {
                foreach ($rows['columns'] as &$column) {
                    if (false !== $key = array_search($this->id, $column['widgets'])) {
                        unset($column['widgets'][$key]);
                    }
                }
            }
            $page->data = $data;
            $page->save();
        }

        parent::delete();

    }

    /**
     * @param $page_id
     * @return Widget
     */
    public static function findByPage($page_id)
    {
        return static::where('category_id', $page_id)
            ->where('type', 'Content')
            ->first();
    }

    /**
     * Returns the widget's config for Content widgets
     *
     * @return mixed
     */
    public function getConfig()
    {
        $data = $this->data;
        return $data && isset($data['settings']) ? $data['settings'] : [
            'list_view' => '',
            'detail_view' => '',
            'order' => 'manual',
            'pagination' => false,
            'quantity' => 6,
        ];
    }

    public function setConfig($input)
    {
        $data = $this->data;
        $data['settings']['list_view'] = $input['list_view'];
        $data['settings']['detail_view'] = $input['detail_view'];
        $data['settings']['order'] = $input['order'];
        $data['settings']['pagination'] = (boolean)$input['pagination'];
        $data['settings']['quantity'] = (int)$input['quantity'];
        $this->data = $data;
        $this->save();
    }

}