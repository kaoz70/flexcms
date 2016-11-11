<?php
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface AdminParentInterface {

    /**
     * List of items
     *
     * @param $parent_id
     * @return mixed
     */
    public function index($parent_id);

    /**
     * Create form interface
     *
     * @param $parent_id
     * @return mixed
     */
    public function create($parent_id);

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
     * @param $parent_id
     * @return mixed
     */
    public function insert($parent_id);

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
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Shows the editor view
     *
     * @param Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $model, $new = FALSE);

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model);

}