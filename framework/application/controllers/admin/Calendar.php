<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Calendar extends CI_Controller
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
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();

	}

	public function create()
	{

		$id = $this->Calendar->insert();

		//Default DB data
		$data = array(
			'id' => $id,
			'date' => '',
			'enabled' => 1,
		);

		//View data
		$data['titulo'] = 'Nuevo D&iacute;a';
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/calendar/delete/' . $id);
		$data['class'] = '';
		$data['txt_boton'] = 'Crear D&iacute;a';
        $data['link'] = base_url('admin/calendar/update/' . $id);

		$this->load->view('admin/calendar/day_view', $data);
	}

	public function edit($id)
	{

		$data = $this->Calendar->get($id);

		$data['titulo'] = 'Modificar D&iacute;a';

        $data['nuevo'] = '';
        $data['removeUrl'] = '';

		$data['txt_boton'] = 'Modificar D&iacute;a';
		$data['link'] = base_url('admin/calendar/update/' . $id);

		$this->load->view('admin/calendar/day_view', $data);

	}

	public function update($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Calendar->update($id);
			$response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al modificar el d&iacute;!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Calendar->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el d&iacute;a!', $e);
        }

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function activities($id)
	{
		$data['items'] = $this->Calendar->activities($id);

		$data['url_rel'] = base_url('admin/calendar/activities');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/calendar/edit_activity');
		$data['url_eliminar'] = base_url('admin/calendar/delete_activity');
		$data['url_search'] = base_url("admin/search/activity");

		$data['search'] = true;
		$data['drag'] = false;
		$data['nivel'] = 'nivel2';
		$data['list_id'] = 'calendar';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'time';

		$data['txt_titulo'] = 'Actividades';

		/*
         * Menu
         */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax boton importante n1'
		);
		$data['menu'][] = anchor(base_url('admin/calendar/create_activity/' . $id), 'Crear Actividad', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create_activity($calendar_id)
	{
		$id = $this->Calendar->insert_activity($calendar_id);

		//Default DB data
		$data = array(
			'id' => $id,
			'time' => '',
			'data' => '',
			'enabled' => 1,
		);

		$data['idiomas'] = $this->Idioma->getLanguages();
		$data['places'] = $this->Mapas->getUbicaciones();

		//TODO: finish dynamic fields
		/*$fields = $this->Calendar->fields();

		foreach ($fields as $field) {

		}*/



		//View data
		$data['titulo'] = 'Nueva Actividad';
		$data['nuevo'] = 'nuevo';
		$data['removeUrl'] = base_url('admin/calendar/delete_activity/' . $id);
		$data['class'] = '';
		$data['txt_boton'] = 'Crear Actividad';
		$data['link'] = base_url('admin/calendar/update_activity/' . $id);

		$this->load->view('admin/calendar/activity_view', $data);
	}

	public function edit_activity($id)
	{

		$data = $this->Calendar->activity($id);

		$data['idiomas'] = $this->Idioma->getLanguages();
		$data['places'] = $this->Mapas->getUbicaciones();

		//TODO: finish dynamic fields
		/*$fields = $this->Calendar->fields();

		foreach ($fields as $field) {

		}*/



		//View data
		$data['titulo'] = 'Modificar Actividad';
		$data['nuevo'] = '';
		$data['removeUrl'] = '';
		$data['class'] = '';
		$data['txt_boton'] = 'Modificar Actividad';
		$data['link'] = base_url('admin/calendar/update_activity/' . $id);

		$this->load->view('admin/calendar/activity_view', $data);
	}

	public function update_activity($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Calendar->update_activity($id);
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al modificar la actividad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete_activity($id)
	{
		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Calendar->delete_activity($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la actividad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function fields()
	{
		$data['items'] = $this->Calendar->fields();

		$data['url_rel'] = base_url('admin/calendar/fields');
		$data['url_sort'] = base_url('admin/calendar/reorder_fields');
		$data['url_modificar'] = base_url('admin/calendar/edit_field');
		$data['url_eliminar'] = base_url('admin/calendar/delete_field');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel2';
		$data['list_id'] = 'activity_fields';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'name';

		$data['txt_titulo'] = 'Campos';

		/*
         * Menu
         */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax boton importante n1'
		);
		$data['menu'][] = anchor(base_url('admin/calendar/create_field'), 'Crear Campo', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create_field()
	{
		//Default DB data
		$data = array(
			'name' => '',
			'input_id' => -1,
			'enabled' => 1,
			'class' => ''
		);

		$data['inputs'] = $this->Input->getByComponent('calendario');

		/*
           * TRADUCCIONES
           */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->name = '';
		}

		$data['traducciones'] = $traducciones;

		//View data
		$data['titulo'] = 'Nuevo Campo';
		$data['nuevo'] = 'nuevo';
		$data['class'] = '';
		$data['txt_boton'] = 'Crear Campo';
		$data['link'] = base_url('admin/calendar/insert_field');

		$this->load->view('admin/calendar/field_view', $data);
	}

	public function edit_field($id)
	{
		//Default DB data
		$data =  $this->Calendar->getField($id);

		$data['inputs'] = $this->Input->getByComponent('calendario');

		/*
           * TRADUCCIONES
           */
		$data['idiomas'] = $this->Idioma->getLanguages();
		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$campoTraduccion = $this->Calendar->getFieldTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			if($campoTraduccion)
				$traducciones[$idioma['idiomaDiminutivo']]->name = $campoTraduccion->name;
			else
				$traducciones[$idioma['idiomaDiminutivo']]->name = '';
		}

		$data['traducciones'] = $traducciones;

		//View data
		$data['titulo'] = 'Modificar Campo';
		$data['nuevo'] = '';
		$data['class'] = '';
		$data['txt_boton'] = 'Modificar Campo';
		$data['link'] = base_url('admin/calendar/update_field/' . $id);

		$this->load->view('admin/calendar/field_view', $data);
	}

	public function insert_field()
	{
		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id = $this->Calendar->insert_field();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function update_field($id)
	{
		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Calendar->update_field($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function delete_field($id)
	{
		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Calendar->delete_field($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function reorder_fields()
	{
		$this->Calendar->reorder_fields();
	}

}