<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 2:53 PM
 */

namespace App;

class Content extends BaseModel {

    protected $table = 'content';

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
        $content = Widget::where('type', 'content')
            ->join('categories', 'categories.id', '=', 'category_id')
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
                'type' => 'content'
            ]);
            $trans->data = json_encode($trans_data);
            $trans->save();

        }
    }

}