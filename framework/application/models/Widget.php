<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/10/15
 * Time: 4:58 PM
 */

namespace App;



class Widget extends BaseModel implements \WidgetInterface {

    protected $CI;
    protected $groups;
    protected static $type = 'widget';

    public function __construct() {
        parent::__construct();
        $this->CI = get_instance();
    }

    static function admin( $id ) {
        // TODO: Implement admin() method.
    }

    static function run( $method ) {
        // TODO: Implement run() method.
    }

    /*static function run($widget, $method, $lang, $view, $admin = FALSE)
    {
        $widget::run($method);
    }*/

    /**
     * Gets all the installed widgets
     * @return array
     */
    static function getInstalled()
    {
        $widgets = [];

        foreach (new \DirectoryIterator(APPPATH . 'widgets/') as $file) {
            if ($file->isDot()) continue;
            if ($file->isDir()) {
                $widgets[] = json_decode(file_get_contents(APPPATH . 'widgets/' . $file->getFilename() . '/config.json'));
            }
        }

        return $widgets;

    }


    /**
     * Gets all the groups
     *
     * @param array $widgets
     *
     * @return array
     */
    static function getGroups(array $widgets)
    {

        $groups = [];

        foreach($widgets as $widget) {
            $groups = array_merge($groups, $widget->groups);
        }

        return array_unique($groups);

    }

    /**
     * Returns the widget's stored jason data as an Object
     *
     * @return mixed
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    /**
     * @param $page_id
     * @return Widget
     */
    public static function getContentWidget($page_id)
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
        $data = $this->getData();
        return $data && isset($data->settings) ? $data->settings : [
            'list_view' => '',
            'detail_view' => '',
            'order' => 'manual',
            'pagination' => false,
            'quantity' => 6,
        ];
    }

    public function setConfig($input)
    {

        $input = json_decode($input);

        $data = $this->getData();

        $data->settings->list_view = $input->list_view;
        $data->settings->detail_view = $input->detail_view;
        $data->settings->order = $input->order;
        $data->settings->pagination = (boolean)$input->pagination;
        $data->settings->quantity = (int)$input->quantity;

        $this->data = json_encode($data);
        $this->save();

    }

}