<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:10 AM
 */

namespace calendar;
$_ns = __NAMESPACE__;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once ('Day.php');

class Activity extends \Day implements \AdminInterface {

	public function index()
	{
		$id = $this->uri->segment(5);
		$data['items'] = $this->Calendar->activities($id);

		$data['url_rel'] = base_url('admin/calendar/activities');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/calendar/activity/edit');
		$data['url_eliminar'] = base_url('admin/calendar/activity/delete');
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
		$data['menu'][] = anchor(base_url('admin/calendar/activity/create/' . $id), 'Crear Actividad', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function insert(){}

	public function create()
	{

		$calendar_id = $this->uri->segment(5);
		$id = $this->Calendar->insert_activity($calendar_id);

		//Default DB data
		$data = array(
			'id' => $id,
			'time' => '',
			'data' => '',
			'enabled' => 1,
			'place_id' => 1,
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
		$data['removeUrl'] = base_url('admin/calendar/activity/delete/' . $id);
		$data['class'] = '';
		$data['txt_boton'] = 'Crear Actividad';
		$data['link'] = base_url('admin/calendar/activity/update/' . $id);

		$this->load->view('admin/calendar/activity_view', $data);
	}

	public function edit($id)
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
		$data['link'] = base_url('admin/calendar/activity/update/' . $id);

		$this->load->view('admin/calendar/activity_view', $data);
	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Calendar->update_activity($id);
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al modificar la actividad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{
		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Calendar->delete_activity($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la actividad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function reorder($id)
	{

	}

}