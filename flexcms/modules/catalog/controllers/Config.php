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
		$data['config'] = \App\Config::get('catalog');
		$this->load->view('catalog/config_view', $data);
	}

	public function save()
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