<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:19 AM
 */

namespace contact;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Field extends \Contact implements \AdminInterface {

	public function index(){
		return $this->Contacto->getFormElements();
	}

	public function create()
	{

		$data['contactoCampoId'] = '';
		$data['titulo'] = "Crear Elemento";
		$data['link'] = base_url("admin/contact/field/insert");
		$data['txt_boton'] = "crear";
		$data['nuevo'] = 'nuevo';
		$data['contactoCampoRequerido'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor = '';
			$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder = '';
		}

		$data['traducciones'] = $traducciones;

		//obtengo entradas
		$data['inputId'] = '';
		$data['inputs'] = $this->Contacto->getContactoInputs('es');
		$data['contactoCampoClase'] = '';
		$data['contactoCampoValidacion'] = '';
		$this->load->view('admin/contact/field_view', $data);
	}

	public function edit($contactoCampoId)
	{

		$data['titulo'] = 'Editar Elemento';
		$data['habilitado']	= 'checked="checked"';
		$data['result'] = array();
		$infoElemento = $this->Contacto->getDatosInForm($contactoCampoId);
		$data['contactoCampoId'] = $infoElemento->contactoCampoId;
		//ontengo entradas
		$data['inputId'] = $infoElemento->inputId;
		$data['inputs'] = $this->Contacto->getContactoInputs('es');
		$data['contactoCampoClase'] = $infoElemento->contactoCampoClase;
		$data['contactoCampoValidacion'] = $infoElemento->contactoCampoValidacion;
		$data['txt_boton'] = 'Modificar Elemento';
		$data['link']  = base_url('admin/contact/field/update/' . $contactoCampoId);
		$data['nuevo'] = '';
		$data['contactoCampoRequerido'] = '';

		if($infoElemento->contactoCampoRequerido)
			$data['contactoCampoRequerido'] = 'checked="checked"';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$campoTraduccion = $this->Contacto->getCampoTranslation($idioma['idiomaDiminutivo'], $contactoCampoId);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			if(isset($campoTraduccion->contactoCampoValor)) {
				$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor = $campoTraduccion->contactoCampoValor;
				$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder = $campoTraduccion->contactoCampoPlaceholder;
			}
			else {
				$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor = '';
				$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder = '';
			}


		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/contact/field_view',$data);
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Contacto->guardarInForm();
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear la el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Contacto->updateForm();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	//elimino campos en DB
	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Contacto->deleteForm($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder()
	{
		$this->Contacto->reorderInputs();
	}

}