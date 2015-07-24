<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:53 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Status extends Cart implements \AdminInterface {

	public function index()
	{
		$data['items'] = $this->flexi_cart_admin->get_db_order_status_array();

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/status/edit');
		$data['url_eliminar'] = base_url('admin/cart/status/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'descuentos';

		$data['idx_id'] = 'ord_status_id';
		$data['idx_nombre'] = 'ord_status_description';

		$data['txt_titulo'] = 'Estados';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/status/create'), 'nuevo estado', array('class' => $data['nivel'] . ' ajax n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{
		$data['nombre'] = '';
		$data['cancelled'] = '';
		$data['save_default'] = '';
		$data['resave_default'] = '.';

		$data['titulo'] = 'Crear Estado';
		$data['nuevo'] = 'nuevo';
		$data['id'] =  $this->cms_general->generarId('order_status');
		$data['nombre'] = '';
		$data['link'] = base_url('admin/cart/status/insert');
		$data['txt_boton'] = 'Guardar';

		$this->load->view('admin/cart/estadoCrear_view', $data);
	}

	public function insert()
	{
		$this->Cart->insert_order_status();
	}

	public function edit($id)
	{

		$estado = $this->Cart->getEstado($id);

		$data['nombre'] = $estado->ord_status_description;
		$data['cancelled'] = $estado->ord_status_cancelled ? 'checked="checked"' : '';
		$data['save_default'] = $estado->ord_status_save_default ? 'checked="checked"' : '';
		$data['resave_default'] = $estado->ord_status_resave_default ? 'checked="checked"' : '';

		$data['titulo'] = 'Modificar Estado';
		$data['nuevo'] = '';
		$data['id'] =   $estado->ord_status_id;
		$data['link'] = base_url('admin/cart/status/update/' . $estado->ord_status_id);
		$data['txt_boton'] = 'Guardar';

		$this->load->view('admin/cart/estadoCrear_view', $data);
	}

	public function update($id)
	{
		$this->Cart->update_order_status($id);
	}

	public function delete($id)
	{
		if($this->flexi_cart_admin->delete_db_order_status($id)) {
			$this->load->view('admin/request/html', array('return' => 'success'));
		} else {
			$this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
		}
	}

}