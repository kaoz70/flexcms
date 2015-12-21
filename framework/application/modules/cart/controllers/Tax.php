<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:38 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Tax extends Cart implements \AdminInterface {

	function index()
	{
		$data['items'] = $this->flexi_cart_admin->get_db_tax_array();

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/tax/edit');
		$data['url_eliminar'] = base_url('admin/cart/tax/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel5';
		$data['list_id'] = 'impuestos';

		$data['idx_id'] = 'tax_id';
		$data['idx_nombre'] = 'tax_name';

		$data['txt_titulo'] = 'Impuestos';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/tax/create'), 'crear nuevo impuesto', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	function create()
	{
		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get arrays of all shipping and tax zones.
		$data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
		$data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

		$data['location_id'] = '';
		$data['zone_id'] = '';
		$data['rate'] = '';

		$data['titulo'] = 'Crear Impuesto';
		$data['nuevo'] = 'nuevo';
		$data['id'] =  $this->cms_general->generarId('tax');
		$data['nombre'] = '';
		$data['link'] = base_url('admin/cart/tax/insert');
		$data['txt_boton'] = 'Guardar';
		$data['status'] = 'checked="checked"';

		$this->load->view('admin/cart/impuestoCrear_view', $data);
	}

	public function insert()
	{
		$this->Cart->insert_tax();
	}

	public function edit($id)
	{

		$impuesto = $this->Cart->getTax($id);

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get arrays of all shipping and tax zones.
		$data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
		$data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

		$data['location_id'] = $impuesto->tax_location_fk;
		$data['zone_id'] = $impuesto->tax_zone_fk;
		$data['rate'] = $impuesto->tax_rate;

		$data['titulo'] = 'Modificar Impuesto';
		$data['nuevo'] = '';
		$data['id'] =  $impuesto->tax_id;
		$data['nombre'] = $impuesto->tax_name;
		$data['link'] = base_url('admin/cart/tax/update/' . $impuesto->tax_id);
		$data['txt_boton'] = 'Guardar';

		if($impuesto->tax_status)
			$data['status'] = 'checked="checked"';
		else
			$data['status'] = '';

		$this->load->view('admin/cart/impuestoCrear_view', $data);
	}

	public function update($id)
	{
		$this->Cart->update_tax($id);
	}

	public function delete($id)
	{
		if($this->flexi_cart_admin->delete_db_tax($id)) {
			$this->load->view('admin/request/html', array('return' => 'success'));
		} else {
			$this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
		}
	}

}