<?php

/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 06-Jan-17
 * Time: 03:48 PM
 */
class Test extends REST_Controller
{

    public function index_get($id)
    {
        $this->response([
            'restuned:' => $id,
        ]);
    }

}