<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:14 AM
 */

namespace contact;
$_ns = __NAMESPACE__;

class Person extends \Contact implements \AdminInterface {
	
	public function index(){
		return $this->Contacto->getContacts('es');
	}

	public function create()
	{

		$data['idiomas'] = $this->Contacto->idiomas();

		$data['contactoId'] = $this->cms_general->generarId('contactos');
		$data['contactoEmail'] = '';

		$data['titulo'] = "Crear Contacto";
		$data['link'] = base_url("admin/contact/person/insert");
		$data['txt_boton'] = "crear";
		$data['nuevo'] = 'nuevo';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->contactoNombre = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/contact/person_view', $data);
	}

	public function insert()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Contacto->addContact();
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el contacto!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function edit($id)
	{
		$contact= $this->Contacto->getContact($id);

		$data['titulo'] = "Modificar Contacto";
		$data['txt_boton'] = "modificar";
		$data['link'] = base_url("admin/contact/person/update/" . $contact->contactoId);
		$data['contactoId'] = $contact->contactoId;
		$data['contactoEmail'] = $contact->contactoEmail;
		$data['nuevo'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$contactoTraduccion = $this->Contacto->getContactoTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			if(isset($contactoTraduccion->contactoNombre)) {
				$traducciones[$idioma['idiomaDiminutivo']]->contactoNombre = $contactoTraduccion->contactoNombre;
			} else {
				$traducciones[$idioma['idiomaDiminutivo']]->contactoNombre = '';
			}

		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/contact/person_view', $data);
	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Contacto->updateContact($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el contacto!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Contacto->deleteContact($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el contacto!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}