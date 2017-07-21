<?php
use App\BaseModel;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 9:58 AM
 */
interface AdminInterface {

    /**
     * Gets one or all resources
     *
     * @param null $id
     * @return mixed
     */
    public function index_get($id = null);

    /**
     * Update a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_put($id);

    /**
     * Insert a new resource
     *
     * @return mixed
     */
    public function index_post();

    /**
     * Delete a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_delete($id);

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param BaseModel $model
     * @param $data
     * @return mixed
     */
    public function _store(BaseModel $model, $data);

}