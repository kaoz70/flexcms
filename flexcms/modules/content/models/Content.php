<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Content extends BaseModel {

    protected $table = 'content';
    protected static $image_folder = 'assets/images/content/';

    protected static $section_id = 'category_id';
    protected static $type = 'content';

    /**
     * Format the publications start time
     *
     * @param  string  $value
     * @return string
     */
    public function getPublicationStartAttribute($value)
    {

        $dt = Carbon::parse($value);

        if($value == null || ! $dt->getTimestamp()) {
            return null;
        }

        return $dt->tz($this->timezone)->toAtomString();

    }

    /**
     * Format the publications end time
     *
     * @param  string  $value
     * @return string
     */
    public function getPublicationEndAttribute($value)
    {

        $dt = Carbon::parse($value);

        if($value == null || ! $dt->getTimestamp()) {
            return null;
        }

        return $dt->tz($this->timezone)->toAtomString();

    }

    /**
     * Get all the page's contents
     *
     * @param $page_id
     * @param $lang
     * @param $order
     * @return Collection
     */
    static function getByPage($page_id, $lang, $order = 'manual')
    {

        switch ($order) {
            case 'date_asc':
                $orderCol = 'created_at';
                $orderDirection = 'asc';
                break;
            case 'date_desc':
                $orderCol = 'created_at';
                $orderDirection = 'desc';
                break;
            default: //manual
                $orderCol = 'position';
                $orderDirection = 'asc';
                break;
        }

        $content = static::where(static::$section_id, $page_id)
            ->orderBy($orderCol, $orderDirection)
            ->get();

        foreach ($content as &$c){
            if(!$c->getTranslation($lang)) {
                $c->translation = new \stdClass();
                $c->translation->name = "{Missing translation}";
            }
        }

        return $content;

    }

    /**
     * Get one content detail by language
     *
     * @param $content_id
     * @param $lang
     * @return Content
     */
    static function getTranslated($content_id, $lang)
    {
        $content = static::find($content_id)->getTranslation($lang);
        return $content;
    }

    /**
     * Get all content pages (the ones that can be edited)
     *
     * @param bool|false $only_ids
     *
     * @return array
     */
    static function getEditable($only_ids = FALSE)
    {

        //Get any content widget
        $content = Widget::where('widgets.type', 'content')
            ->join('categories', 'categories.id', '=', static::$section_id)
            ->get();

        //Do we want to return only page ids?
        if($only_ids) {

            $ids = [];

            foreach($content as $page){
                $ids[] = $page->id;
            }

            return $ids;

        }

        return $content;

    }

    /**
     * Stores the translation data
     *
     * @param $inputs
     * @return array
     */
    public function setTranslations($inputs)
    {

        $translations = [];

        foreach(Language::all() as $lang){

            foreach ($inputs as $input) {

                if($input['id'] === $lang->id) {

                    $translation = $input['translation'];

                    $trans_data = [
                        'name' => isset($translation['name']) ? $translation['name'] : '',
                        'content' => isset($translation['content']) ? $translation['content'] : '',
                        'meta_keywords' => isset($translation['meta_keywords']) ? $translation['meta_keywords'] : [],
                        'meta_description' => isset($translation['meta_description']) ? $translation['meta_description'] : '',
                        'meta_title' => isset($translation['meta_title']) ? $translation['meta_title'] : '',
                    ];

                    $trans = Translation::firstOrNew([
                        'language_id' => $lang->id,
                        'parent_id' => $this->id,
                        'type' => static::$type
                    ]);

                    $trans->data = json_encode($trans_data);
                    $trans->save();

                    $translations[$lang->id] = $trans_data;

                }

            }

        }

        return $translations;

    }

}