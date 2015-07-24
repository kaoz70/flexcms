<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:30 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Shipping extends Cart implements \AdminInterface {

	public function index(){

		$sql_where = array('ship_temporal' => 0);
		$data['items'] = $this->flexi_cart_admin->get_db_shipping_array(FALSE, $sql_where);

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/shipping/edit');
		$data['url_eliminar'] = base_url('admin/cart/shipping/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'zonas';

		$data['idx_id'] = 'ship_id';
		$data['idx_nombre'] = 'ship_name';

		$data['txt_titulo'] = 'Opciones de envio';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/shipping/create'), 'crear nueva opci&oacute;n de envio', array('class' => $data['nivel'] . ' ajax importante n2 boton'));
		$data['menu'][] = anchor(base_url('admin/cart/tax'), 'impuestos', array('class' => $data['nivel'] . ' ajax n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{

		$id = $this->insert();

		// Get an array of location data formatted with all sub-locations displayed 'tiered' into the location type groups, so locations can be listed
		// over multiple html select menus.
		//$data['locations_tiered'] = $this->flexi_cart_admin->locations_tiered();
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get an array of all shipping zones.
		$data['shipping_zones'] = $this->flexi_cart_admin->location_zones('shipping');

		$data['titulo'] = 'Nueva Opci&oacute;n de Envio';
		$data['removeUrl'] = base_url('admin/cart/shipping/delete/' . $id);
		$data['nuevo'] = 'nuevo';
		$data['opcionEnvioId'] = $id;
		$data['nombre'] = '';
		$data['desc'] = '';
		$data['link'] = base_url('admin/cart/shipping/update/' . $id);
		$data['link_tarifas'] = base_url('admin/cart/rate/index/' . $id);
		$data['txt_boton'] = 'Guardar';
		$data['status'] = 'checked="checked"';
		$data['padre_id'] = '';

		$this->load->view('admin/cart/envioOpcionCrear_view', $data);
	}

	public function insert()
	{
		return $this->Cart->createShippingOption();
	}

	public function update($id)
	{
		$this->Cart->update_shipping($id);
	}

	public function edit($id)
	{

		$shipping = $this->Cart->getShippingOption($id);

		// Get an array of location data formatted with all sub-locations displayed 'tiered' into the location type groups, so locations can be listed
		// over multiple html select menus.
		//$data['locations_tiered'] = $this->flexi_cart_admin->locations_tiered();
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get an array of all shipping zones.
		$data['shipping_zones'] = $this->flexi_cart_admin->location_zones('shipping');

		$data['titulo'] = 'Opci&oacute;n de Envio';
		$data['removeUrl'] = '';
		$data['nuevo'] = '';
		$data['opcionEnvioId'] = $shipping->ship_id;
		$data['nombre'] = $shipping->ship_name;
		$data['desc'] = $shipping->ship_description;
		$data['link'] = base_url('admin/cart/shipping/update/' . $shipping->ship_id);
		$data['link_tarifas'] = base_url('admin/cart/rate/index/' . $shipping->ship_id);
		$data['txt_boton'] = 'Modificar';
		$data['padre_id'] = $shipping->ship_location_fk;

		if($shipping->ship_status)
			$data['status'] = 'checked="checked"';
		else
			$data['status'] = '';

		$this->load->view('admin/cart/envioOpcionCrear_view', $data);
	}

	public function delete($id)
	{
		if($this->flexi_cart_admin->delete_db_shipping($id, TRUE)) {
			$this->load->view('admin/request/html', array('return' => 'success'));
		} else {
			$this->load->view('admin/request/html', array('return' => 'no se pudo eliminar'));
		}
	}

}