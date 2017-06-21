<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/7/15
 * Time: 3:15 PM
 */
class Upload extends RESTController {

    /**
     * Upload a file
     */
    public function index_post()
    {

        $response = new \App\Response();

        $config['allowed_types'] = 'gif|jpg|png|pdf|xls|doc';
        $config['upload_path'] = APPPATH . 'uploads';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if ($fileData =  $this->upload->do_multi_upload('files')) {
            $response->setData(\App\File::fromUpload($fileData));
        } else {
            $response->setError($this->upload->display_errors(), new Exception($this->upload->display_errors()));
        }

        $this->response($response, $response->getStatusHeader());

    }

}