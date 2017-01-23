<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/7/15
 * Time: 3:15 PM
 */
class Upload extends RESTController {

    public function index_post()
    {

        $response = new \App\Response();

        $config['allowed_types'] = 'gif|jpg|png|pdf|xls|doc';
        $config['upload_path'] = sys_get_temp_dir();

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file')) {
            $response->setError($this->upload->display_errors(), new Exception($this->upload->display_errors()));
        } else {
            $response->setData($this->upload->data());
        }

        $this->response($response);

    }
    
}