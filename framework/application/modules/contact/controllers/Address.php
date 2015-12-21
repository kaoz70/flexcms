<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:16 AM
 */

namespace contact;
$_ns = __NAMESPACE__;

class Address extends \Contact implements \AdminInterface {

	public function index(){
		return $this->Contacto->getDirecciones('es');
	}
	
	public function create()
	{

		$data['idiomas'] = $this->Contacto->idiomas();

		$data['direccionId'] = $this->cms_general->generarId('contacto_direcciones');
		$data['contactoDireccionNombre'] = '';

		$data['titulo'] = "Crear Direcci&oacute;n";
		$data['link'] = base_url("admin/contact/address/insert");
		$data['txt_boton'] = "crear";
		$data['nuevo'] = 'nuevo';

		$data['imagenExtension'] = '';
		$data['contactoDireccionCoord'] = '';
		$data['imagenOrig'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(14);
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['imagen'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/contact/address_view', $data);
	}

	public function insert()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Contacto->addDireccion();
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear la direcci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function edit($id)
	{

		$direccion = $this->Contacto->getDireccion($id);
		$data['idiomas'] = $this->Contacto->idiomas();

		$data['direccionId'] = $direccion->contactoDireccionId;
		$data['contactoDireccionNombre'] = $direccion->contactoDireccionNombre;

		$data['titulo'] = "Modificar Direcci&oacute;n";
		$data['link'] = base_url("admin/contact/address/update/" . $id);
		$data['txt_boton'] = "modificar";
		$data['nuevo'] = '';

		$data['imagenExtension'] = $direccion->contactoDireccionImagen;
		$data['contactoDireccionCoord'] = urlencode($direccion->contactoDireccionCoord);
		$data['imagenOrig'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(14);
		$data['imagen'] = '';

		if($direccion->contactoDireccionImagen != '')
		{
			$data['txt_botImagen'] = 'Cambiar Imagen';
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/contacto/dir_' . $direccion->contactoDireccionId . '_admin.' . $direccion->contactoDireccionImagen . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/contacto/dir_' . $direccion->contactoDireccionId . '_orig.' . $direccion->contactoDireccionImagen;
			$data['imagenExtension'] = $direccion->contactoDireccionImagen;
		}
		else
		{
			$data['txt_botImagen'] = 'Subir Imagen';
			$data['imagen'] = '';
			$data['imagenExtension'] = '';
			$data['imagenOrig'] = '';
		}

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$direccionTraduccion = $this->Contacto->getDireccionTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();

			if($direccionTraduccion) {
				$traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion = $direccionTraduccion->contactoDireccion;
			}
			else {
				$traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion = '';
			}

		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/contact/address_view', $data);

	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id = $this->input->post('direccionId');
			$this->Contacto->updateDireccion($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la direcci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Contacto->deleteDireccion($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la direcci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder()
	{
		$this->Contacto->reorderDirecciones();
	}
	
}