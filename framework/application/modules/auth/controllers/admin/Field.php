<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:33 PM
 */

namespace admin\auth;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Field extends User implements \AdminInterface {

	public function index()
	{
		$data['campos'] = $this->Usuarios->getTemplate();
		$data['titulo'] = 'Campos';
		$this->load->view('admin/users/fields_view', $data);
	}

	public function create()
	{

		$data['userFieldId'] = $this->cms_general->generarId('user_fields');
		$data['titulo'] = "Crear Campo";
		$data['link'] = base_url("admin/users/field/insert");
		$data['txt_boton'] = "crear";
		$data['nuevo'] = 'nuevo';

		/*
		 * TRADUCCIONES
		 */
		$traducciones = array();
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel = '';
			$traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder = '';
		}

		$data['traducciones'] = $traducciones;

		//obtengo entradas
		$data['inputId'] = '';
		$data['habilitado']	= 'checked="checked"';
		$data['userFieldRequired']	= '';
		$data['userFieldValidation'] = '';
		$data['inputs'] = $this->Usuarios->getInputs();
		$data['userFieldClass'] = '';
		$this->load->view('admin/users/field_view', $data);
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id = $this->Usuarios->guardarCampo();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function edit($campoId)
	{
		$campo = $this->Usuarios->getCampo($campoId);

		$data['titulo'] = 'Editar Campo';

		$data['habilitado']	= '';
		if($campo->userFieldActive)
			$data['habilitado']	= 'checked="checked"';

		$data['userFieldRequired']	= '';
		if($campo->userFieldRequired)
			$data['userFieldRequired']	= 'checked="checked"';


		$data['userFieldId'] = $campo->userFieldId;
		$data['inputId'] = $campo->inputId;
		$data['inputs'] = $this->Usuarios->getInputs();
		$data['userFieldClass'] = $campo->userFieldClass;
		$data['userFieldValidation'] = $campo->userFieldValidation;
		$data['txt_boton'] = 'Modificar Campo';
		$data['link']  = base_url('admin/users/field/update/' . $campoId);
		$data['nuevo'] = '';

		/*
		 * TRADUCCIONES
		 */
		$traducciones = array();
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$campoTraduccion = $this->Usuarios->getCampoTranslation($idioma['idiomaDiminutivo'], $campoId);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();

			if((bool)$campoTraduccion) {
				$traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel = $campoTraduccion->userFieldLabel;
				$traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder = $campoTraduccion->userFieldPlaceholder;
			} else {
				$traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel = '';
				$traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder = '';
			}

		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/users/field_view', $data);
	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Usuarios->actualizarCampo();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Usuarios->eliminarCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder()
	{
		$this->Usuarios->reorganizarCampos();
	}

}