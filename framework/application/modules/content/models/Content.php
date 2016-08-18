<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace App;

use Illuminate\Support\Collection;

class Content extends BaseModel {

    protected $table = 'content';

    const PAGE_ID = 'category_id';
    protected $type = 'content';

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

        $content = static::where(static::PAGE_ID, $page_id)
            ->orderBy($orderCol, $orderDirection)
            ->get();

        foreach ($content as &$c){
            $c->getTranslation($lang);
        }

        return $content;

    }

    /**
     * Get one content detail
     *
     * @param $content_id
     * @param $lang
     * @return Content
     */
    static function get($content_id, $lang)
    {
        $content = static::find($content_id);
        $content->getTranslation($lang);
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
            ->join('categories', 'categories.id', '=', static::PAGE_ID)
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

    public function setTranslations($input)
    {
        foreach(Language::all() as $lang){

            $trans_data = [
                'name' => $input['name'][$lang->id],
                'content' => $input['content'][$lang->id],
                'meta_keywords' => array_map('trim', explode(',', $input['meta_keywords'][$lang->id])),
                'meta_description' => $input['meta_description'][$lang->id],
                'meta_title' => $input['meta_title'][$lang->id],
            ];

            $trans = Translation::firstOrNew([
                'language_id' => $lang->id,
                'parent_id' => $this->id,
                'type' => static::TYPE
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

    protected static function reorder($inputs, $page_id)
    {

        //Get the ids
        $ids = json_decode($inputs, true);

        for($i = 0 ; $i < static::where(static::PAGE_ID, $page_id)->get()->count() ; $i++){

            $row = static::find($ids[$i]);
            $row->position = $i + 1;
            $row->save();

        }

    }

}