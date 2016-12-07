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

}