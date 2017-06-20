<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:53 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;

class Youtube extends \Catalog implements \AdminInterface {

	public function index(){}

	public function create()
	{

		$productId = $this->uri->segment(5);
		$fieldId = $this->uri->segment(6);

		$data['productoId'] = $productId;
		$data['titulo'] = 'Nuevo Video';
		$data['productoArchivoNombre'] = '';
		$data['productoArchivoId'] = '';
		$data['link'] = base_url('admin/catalog/youtube/insert/' . $productId . '/' . $fieldId);
		$data['productoArchivoEnabled'] = 'checked="checked"';
		$data['productoArchivoExtension'] = '';
		$data['nuevo'] = 'nuevo';
		$data['txt_boton'] = 'Nuevo Video';

		/*
	     * TRADUCCIONES
	     */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/youtube_view', $data);
	}

	public function edit($productoArchivoId){}

	public function insert()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Catalogo->insertProductVideo();
			$response->new_id = $id;
			$response->videoId = $this->input->post("productoArchivoExtension");
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el video!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->updateProductVideo();
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el video!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->deleteProductVideo($id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el video!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}