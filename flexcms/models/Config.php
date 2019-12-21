<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 1:36 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Config extends Model {

    protected $table = 'config';

    /**
     * Formats the site's config values so that we can access them easier
     *
     * @return array
     */
    static function get($group = 'general')
    {

        $result = [];

        foreach (static::where('group', $group)->get() as $row) {

            //Check if string is a boolean
            if($row->value == 'true' || $row->value == 'false') {
                $value = filter_var($row->value, FILTER_VALIDATE_BOOLEAN);
            }

            //Check if string is float
            elseif(is_numeric($row->value) && strpos($row->value, ".") !== false) {
                $value = (float) $row->value;
            }

            //Check if string is int
            elseif(is_numeric($row->value) && strpos($row->value, ".") === false) {
                $value = (int) $row->value;
            }

            //Its string
            else {
                $value = $row->value;
            }

            $result[$row->key] = $value;

        }

        return $result;
    }

    static function saveData(array $data)
    {

        foreach ($data as $key => $value) {
            $row = static::where('key', $key)->first();

            //Check if string is a boolean
            if($value === true || $value === false) {
                $row->value =  $value ? 'true' : 'false';
            } else {
                $row->value = $value;
            }

            $row->save();
        }
    }

    /**
     * Get the site's index page
     *
     * @param $lang
     *
     * @return mixed
     * @throws \Exception
     */
    static function siteIndex($lang)
    {
        $conf = static::get();
        /** @var Page $page */
        $page = Page::find($conf['index_page_id']);

        if($page) {
            $page->translation = $page->getTranslation($lang);
            return $page;
        } else {
            throw new \Exception('No site index found, define one on the backend');
        }
    }

    static function theme()
    {
        $conf = static::get();
        return $conf['theme'];
    }

}
