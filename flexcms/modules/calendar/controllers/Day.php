<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Day extends MY_Controller implements \AdminInterface
{

	public function __construct()
	{
		parent::__construct();

		$this->load->database();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');
		
		$this->load->model('idiomas_model', 'Idioma');
		$this->load->model('calendar_model', 'Calendar');
		$this->load->model('input_model', 'Input');
		$this->load->model('mapas_model', 'Mapas');

        $this->load->library('ion_auth');
        $this->load->library('CMS_General');

	}
	
	public function index(){}

	public function create()
	{

		$id = $this->insert();

		//Default DB data
		$data = array(
			'id' => $id,
			'date' => '',
			'enabled' => 1,
		);

		//View data
		$data['titulo'] = 'Nuevo D&iacute;a';
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/calendar/day/delete/' . $id);
		$data['class'] = '';
		$data['txt_boton'] = 'Crear D&iacute;a';
        $data['link'] = base_url('admin/calendar/day/update/' . $id);

		$this->load->view('admin/calendar/day_view', $data);
	}

	public function insert()
	{
		return $this->Calendar->insert();
	}

	public function edit($id)
	{

		$data = $this->Calendar->get($id);

		$data['titulo'] = 'Modificar D&iacute;a';

        $data['nuevo'] = '';
        $data['removeUrl'] = '';

		$data['txt_boton'] = 'Modificar D&iacute;a';
		$data['link'] = base_url('admin/calendar/day/update/' . $id);

		$this->load->view('admin/calendar/day_view', $data);

	}

	public function update($id)
	{

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $this->Calendar->update($id);
			$response->new_id = $id;
        } catch (\Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al modificar el d&iacute;!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $this->Calendar->delete($id);
        } catch (\Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el d&iacute;a!', $e);
        }

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function reorder($id)
	{

	}

}