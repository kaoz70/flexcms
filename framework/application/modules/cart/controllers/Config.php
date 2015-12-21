<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 5:04 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Config extends Cart {

	public function edit()
	{

		$data['titulo'] = 'Configuraci&oacute;n';
		$data['link'] = base_url('admin/cart/config/update');
		$data['txt_boton'] = 'Actualizar';

		// Get the row array of the config table.
		$data['config'] = $this->flexi_cart_admin->get_db_config_row_array();

		$this->load->view('admin/cart/configuracion_view', $data);
	}

	public function update()
	{
		$this->Cart->update_config();
	}

}