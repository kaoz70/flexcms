<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/10/15
 * Time: 4:58 PM
 */

namespace App;



class Widget extends BaseModel {

    protected $groups;
    protected static $type = 'widget';

    protected $appends = ['admin_view'];

    /**
     * View path to where the admin view is located
     *
     * @var string
     */
    protected static $admin_view;

    /**
     * @return string
     */
    public function getAdminViewAttribute()
    {
        return static::$admin_view;
    }

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
     * Check if there is any content widget, and updates the widgets to the page's ID
     *
     * @param Page $page
     * @return null|\App\Widget\Content\Model\Widget
     */
    public static function getMainWidget(Page $page)
    {

        $mainWidget = null;

        foreach ($page->data->structure as $row) {
            foreach ($row->columns as $column) {
                if(isset($column->widgets)) {
                    foreach ($column->widgets as $widget) {

                        $w = static::find($widget);
                        $w->category_id = $page->id;
                        $w->save();

                        if($w->type === 'Content') {
                            $mainWidget = $w;
                        }

                    }
                }
            }
        }

        return $mainWidget;

    }

    public function getCategory()
    {
        return Category::find($this->category_id);
    }

}