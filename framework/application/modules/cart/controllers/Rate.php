<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:34 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Rate extends Cart implements \AdminInterface {

	function index()
	{

		$shipping_id = $this->uri->segment(5);

		// Get an array of all shipping rates filtered by the id in the url.
		$sql_where = array($this->flexi_cart_admin->db_column('shipping_rates', 'parent') => $shipping_id);
		$data['items'] = $this->flexi_cart_admin->get_db_shipping_rate_array(FALSE, $sql_where);

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/rate/edit');
		$data['url_eliminar'] = base_url('admin/cart/rate/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel6';
		$data['list_id'] = 'tarifa_envio';

		$data['idx_id'] = 'ship_rate_id';
		$data['idx_nombre'] = 'ship_rate_value';

		$data['txt_titulo'] = 'Tarifas de envio';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/rate/create/' . $this->uri->segment(4)), 'crear nueva tarifa de envio', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);

	}

	function create()
	{
		$id = $this->cms_general->generarId('shipping_rates');

		$data['titulo'] = 'Nueva Tarifa de Envio';
		$data['nuevo'] = 'nuevo';
		$data['opcionTarifaId'] = $id;

		$data['valor'] = '0.00';
		$data['tare_weight'] = '0';
		$data['min_weight'] = '0';
		$data['max_weight'] = '9999';
		$data['min_value'] = '0.00';
		$data['max_value'] = '9999.00';
		$data['status'] = 'checked="checked"';

		$data['link'] = base_url('admin/cart/rate/insert/' . $this->uri->segment(5));
		$data['txt_boton'] = 'Guardar';

		$this->load->view('admin/cart/envioTarifaCrear_view', $data);
	}

	function insert()
	{
		$this->Cart->insert_shipping_rate($this->uri->segment(5));
	}

	function edit($id)
	{

		$tarifa = $this->Cart->getTarifaEnvio($id);

		$data['titulo'] = 'Nueva Tarifa de Envio';
		$data['nuevo'] = '';
		$data['opcionTarifaId'] = $tarifa->ship_rate_id;

		$data['valor'] = $tarifa->ship_rate_value;
		$data['tare_weight'] = $tarifa->ship_rate_tare_wgt;
		$data['min_weight'] = $tarifa->ship_rate_min_wgt;
		$data['max_weight'] = $tarifa->ship_rate_max_wgt;
		$data['min_value'] = $tarifa->ship_rate_min_value;
		$data['max_value'] = $tarifa->ship_rate_max_value;

		if($tarifa->ship_rate_status)
			$data['status'] = 'checked="checked"';
		else
			$data['status'] = '';

		$data['link'] = base_url('admin/cart/rate/update/' . $tarifa->ship_rate_id);
		$data['txt_boton'] = 'Guardar';

		$this->load->view('admin/cart/envioTarifaCrear_view', $data);
	}

	function update($id)
	{
		$this->Cart->update_shipping_rate($id);
	}

	function delete($id)
	{

	}

}