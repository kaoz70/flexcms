<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:19 PM
 */

namespace slider;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Field extends \Slider implements \AdminInterface {

	public function index()
	{

		$data['items'] = \App\Field::where('section', 'slider')
			->orderBy('position')
			->get();

		$data['url_rel'] = base_url('admin/slider/field');
		$data['url_sort'] = base_url('admin/slider/field/reorder/' . 1); // NO param necessary (1)
		$data['url_modificar'] = base_url('admin/slider/field/edit');
		$data['url_eliminar'] = base_url('admin/slider/field/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'banner_campos';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'name';

		$data['txt_titulo'] = 'Editar Template';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax boton n1'
		);
		$data['menu'][] = anchor(base_url('admin/slider/field/create'), 'Crear Nuevo Elemento', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{
		try {
			$this->_showView();
		} catch (Exception $e) {
			$this->error('Error', $e);
		}

	}

	public function edit($id)
	{

		try {
			$this->_showView($id, false);
		} catch (Exception $e) {
			$this->error('Error', $e);
		}

	}

	public function _showView( $id = null, $new = true ) {

		$field = \App\Field::findOrNew($id);
		$data = $field->toArray();

		$data['titulo'] = 'Elemento';
		$data['nuevo'] = $new;

		$data['inputs'] = \App\Input::where('section', 'slider')->get();
		$data['translations'] = $field->getTranslations();

		$data['txt_boton'] = 'Guardar';
		$data['link'] = $new ? base_url('admin/slider/field/insert') : base_url('admin/slider/field/update/' . $id);

		$this->load->view('field_view',$data);
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$field = $this->_store(new \Slider\Models\Field());
			$field->position = \App\Field::where('section', 'slider')->get()->count();
			$field->save();
			$response->new_id = $field->id;
		} catch (Exception $e) {
			$response = $this->error('Ocurri&oacute; un problema al crear el campo!', $e);
		}

		$this->load->view('admin/request/json', [ 'return' => $response ] );

	}
	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id = $this->_store(\Slider\Models\Field::find($id))->id;
		} catch (Exception $e) {
			$response = $this->error('Ocurri&oacute; un problema al modificar el campo!', $e);
			var_dump($response);
		}

		$this->load->view('admin/request/json', [ 'return' => $response ] );

	}

	private function _store(\Slider\Models\Field $model) {

		$input = $this->input->post();

		$model->css_class = $this->input->post('css_class');
		$model->section = 'slider';
		$model->name = $this->input->post('name');
		$model->input_id = $this->input->post('input_id');
		$model->label_enabled = $this->input->post('label_enabled');
		$model->required = $this->input->post('required');
		$model->save();

		//Update the content's translations
		$model->setTranslations($input);

		return $model;
	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$field = \App\Field::find($id);
			$field->delete();
		} catch (Exception $e) {
			$response = $this->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
		}

		$this->load->view('admin/request/json', [ 'return' => $response ] );

	}

	public function reorder($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			\App\Field::reorder($this->input->post('posiciones'), 'slider');
		} catch (Exception $e) {
			$response = $this->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
		}

		$this->load->view('admin/request/json', [ 'return' => $response ] );

	}

}