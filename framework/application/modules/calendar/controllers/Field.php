<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:16 AM
 */

namespace calendar;
$_ns = __NAMESPACE__;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once ('Day.php');

class Field extends \Day implements \AdminInterface {

	public function index()
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

	public function create()
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
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();;;
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

	public function edit($id)
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
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
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

	public function insert()
	{
		$response = new \stdClass();;;
		$response->error_code = 0;

		try{
			$response->new_id = $this->Calendar->insert_field();
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function update($id)
	{
		$response = new \stdClass();;;
		$response->error_code = 0;

		try{
			$this->Calendar->update_field($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function delete($id)
	{
		$response = new \stdClass();;;
		$response->error_code = 0;

		try{
			$this->Calendar->delete_field($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function reorder($id)
	{
		$this->Calendar->reorder_fields();
	}
}