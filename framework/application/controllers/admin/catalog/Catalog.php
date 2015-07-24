<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->load->library('image_lib');

        $this->load->model('admin/catalogo_model', 'Catalogo');
        $this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
	    $this->load->model('admin/module_model', 'Modulo');

        $this->load->library('CMS_General');

    }

}