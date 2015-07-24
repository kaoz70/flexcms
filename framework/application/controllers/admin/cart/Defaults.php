<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 5:06 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Defaults extends Cart {

	public function edit()
	{

		$data['titulo'] = 'Valores por Defecto';
		$data['link'] = base_url('admin/cart/defaults/update');
		$data['txt_boton'] = 'Actualizar';

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get an array of all currencies.
		$data['currency_data'] = $this->flexi_cart_admin->get_db_currency_array();

		// Get an array of all shipping options.
		$sql_where = array('ship_temporal' => 0);
		$data['shipping_data'] = $this->flexi_cart_admin->get_db_shipping_array(FALSE, $sql_where);

		// Get an array of all tax rate.
		$data['tax_data'] = $this->flexi_cart_admin->get_db_tax_array();

		// Get current cart defaults.
		$data['default_currency'] = $this->flexi_cart_admin->get_db_currency_row_array(FALSE, array('curr_default' => 1));
		$data['default_ship_location'] = $this->flexi_cart_admin->get_db_location_row_array(FALSE, array('loc_ship_default' => 1));
		$data['default_tax_location'] = $this->flexi_cart_admin->get_db_location_row_array(FALSE, array('loc_tax_default' => 1));
		$data['default_ship_option'] = $this->flexi_cart_admin->get_db_shipping_row_array(FALSE, array('ship_default' => 1));
		$data['default_tax_rate'] = $this->flexi_cart_admin->get_db_tax_row_array(FALSE, array('tax_default' => 1));

		$this->load->view('admin/cart/valoresDefecto_view', $data);
	}

	public function update()
	{
		$this->Cart->update_defaults();
	}

}