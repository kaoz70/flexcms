<?php
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface ContentInterface {

    /**
     * List of content items by page
     *
     * @param $page_id
     * @return mixed
     */
    public static function index($page_id);

    /**
     * Create form interface
     *
     * @return mixed
     */
    public function create();

    /**
     * Edit form interface
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id);

    /**
     * Insert the item into database
     *
     * @return mixed
     */
    public function insert();

    /**
     * Update the item in the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($id);

    /**
     * Remove the item from the database
     *
     * @param $page_id
     * @return mixed
     */
    public function delete($page_id);

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model);

    /**
     * Widget's settings for the content
     *
     * @param $widget_id
     * @return mixed
     */
    public function config($widget_id);

    /**
     * Save the widget's settings for the content
     *
     * @param $widget_id
     * @return mixed
     */
    public function config_save($widget_id);

}