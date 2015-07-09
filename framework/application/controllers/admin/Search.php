<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

    function __construct(){

        parent::__construct();

        $this->load->helper('text');

        $this->load->model('search_model', 'Search');

	    $this->load->library('Seguridad');
	    $this->seguridad->init();

    }

    public function usuarios()
    {
        $query = $this->input->get('query');
        $result = $this->Search->getUsers($query);

        $this->load->view('admin/request/json', array('return' => $result));
    }

    public function productos()
    {
        $query = $this->input->get('query');
        $result = $this->Search->getProducts($query);

        $this->load->view('admin/request/json', array('return' => $result));
    }

    public function publicaciones($page_id)
    {
        $query = $this->input->get('query');
        $result = $this->Search->publicaciones($query, 'es', $page_id);

        $this->load->view('admin/request/json', array('return' => $result));
    }

    public function articles()
    {
        $query = $this->input->get('query');
        $result = $this->Search->articulos($query);

        $this->load->view('admin/request/json', array('return' => $result));
    }

    public function galeria()
    {
        $query = $this->input->get('query');
        $result = $this->Search->descargas($query);

        $this->load->view('admin/request/json', array('return' => $result));
    }

    public function servicios()
    {
        $query = $this->input->get('query');
        $result = $this->Search->servicios($query);

        $this->load->view('admin/request/json', array('return' => $result));
    }

}