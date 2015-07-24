<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ajax extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('admin/general_model', 'General');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

        //TODO, if I enable this, I cant poll the CSRF from the admin login page
		//$this->seguridad->init();

	}

	public function index()
	{

	}
	
	//Miguel
	public function unique_name()
	{
        $nombre = $this->cms_general->generateSafeUrl($this->input->post('nombre'));
        $seccion = $this->input->post('seccion');
        $columna = $this->input->post('columna');
        $id = $this->input->post('id');
        $columna_id = $this->input->post('columna_id');

        $unique = $this->General->unique_name($nombre, $seccion, $columna, $id, $columna_id);

        if(count($unique) == 0) {
            $data['return'] = 1;
        } else {
            $data['return'] = 0;
        }

        $this->load->view('admin/request/json', $data);

	}

    public function unlockTable()
    {
        $tabla = $this->input->post('tabla');
        $columna = $this->input->post('columna');
        $id = $this->input->post('id');
        $this->General->lockTable($tabla, $columna, $id, NULL);
    }

    public function get_csrf()
    {
	    $data['return'] = $this->security->get_csrf_hash();
        $this->load->view('admin/request/html', $data);
    }

}