<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/22/15
 * Time: 3:41 PM
 */

namespace catalog;
$_ns = __NAMESPACE__;

class Config extends \Catalog {

	public function index()
	{
		$config = $this->Catalogo->getConfiguration();
		$data['productoMostarProductoInicio'] = $config->productoMostarProductoInicio;
		$data['link'] = base_url('admin/catalog/config/update');
		$data['titulo'] = 'Configuraci&oacute;n';
		$data['txt_boton'] = 'Guardar Configuracion';
		$this->load->view('admin/catalog/configuracion_view', $data);
	}

	public function update()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->updateConfiguration();
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la configuraci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

}