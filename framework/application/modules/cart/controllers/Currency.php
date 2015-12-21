<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:50 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Currency extends Cart implements \AdminInterface {

	public function index()
	{

		$data['items'] = $this->flexi_cart_admin->get_db_currency_array();

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/currency/edit');
		$data['url_eliminar'] = base_url('admin/cart/currency/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'descuentos';

		$data['idx_id'] = 'curr_id';
		$data['idx_nombre'] = 'curr_name';

		$data['txt_titulo'] = 'Monedas';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/currency/create'), 'nueva moneda', array('class' => $data['nivel'] . ' ajax n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{

		$data['nombre'] = '';
		$data['exchange_rate'] = '';
		$data['symbol'] = '';
		$data['thousand'] = '.';
		$data['decimal'] = ',';

		$data['titulo'] = 'Crear Moneda';
		$data['nuevo'] = 'nuevo';
		$data['id'] =  $this->cms_general->generarId('currency');
		$data['nombre'] = '';
		$data['link'] = base_url('admin/cart/currency/insert');
		$data['txt_boton'] = 'Guardar';
		$data['status'] = 'checked="checked"';

		$this->load->view('admin/cart/monedaCrear_view', $data);
	}

	public function insert()
	{
		$this->Cart->insert_currency();
	}

	public function edit($id)
	{

		$moneda = $this->Cart->getMoneda($id);

		$data['nombre'] = $moneda->curr_name;
		$data['exchange_rate'] = $moneda->curr_exchange_rate;
		$data['symbol'] = $moneda->curr_symbol;
		$data['thousand'] = $moneda->curr_thousand_separator;
		$data['decimal'] = $moneda->curr_decimal_separator;

		$data['titulo'] = 'Crear Moneda';
		$data['nuevo'] = '';
		$data['id'] =  $moneda->curr_id;
		$data['link'] = base_url('admin/cart/currency/update');
		$data['txt_boton'] = 'Guardar';

		if($moneda->curr_status)
			$data['status'] = 'checked="checked"';
		else
			$data['status'] = '';

		$this->load->view('admin/cart/monedaCrear_view', $data);
	}

	public function update($id)
	{
		$this->Cart->update_currency($id);
	}

	public function delete($id)
	{
		if($this->flexi_cart_admin->delete_db_currency($id)) {
			$this->load->view('admin/request/html', array('return' => 'success'));
		} else {
			$this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
		}
	}

}