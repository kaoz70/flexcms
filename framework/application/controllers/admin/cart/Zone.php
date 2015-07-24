<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:27 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Zone extends Cart implements \AdminInterface {

	public function index()
	{
		$data['items'] = $this->flexi_cart_admin->get_db_location_zone_array();

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/zone/edit');
		$data['url_eliminar'] = base_url('admin/cart/zone/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'zonas';

		$data['idx_id'] = 'lzone_id';
		$data['idx_nombre'] = 'lzone_name';

		$data['txt_titulo'] = 'Zonas';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/zone/create'), 'crear nueva zona', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{
		$data['titulo'] = 'Nueva Zona';
		$data['nuevo'] = 'nuevo';
		$data['zonaId'] = $this->cms_general->generarId('location_zones');
		$data['nombre'] = '';
		$data['checked'] = 'checked="checked"';
		$data['desc'] = '';
		$data['link'] = base_url('admin/cart/zone/insert');
		$data['txt_boton'] = 'Guardar';
		$data['status'] = 'checked="checked"';

		$this->load->view('admin/cart/zonaCrear_view', $data);
	}

	public function insert()
	{
		$this->Cart->insert_zone();
	}

	public function edit($id)
	{

		$zona = $this->Cart->getZone($id);

		$data['titulo'] = 'Modificar Zona';
		$data['nuevo'] = '';
		$data['zonaId'] = $zona->lzone_id;
		$data['nombre'] = $zona->lzone_name;
		if($zona->lzone_status)
			$data['checked'] = 'checked="checked"';
		else
			$data['checked'] = '';
		$data['desc'] = $zona->lzone_description;
		$data['link'] = base_url('admin/cart/zone/update/' . $zona->lzone_id);
		$data['txt_boton'] = 'Modificar';
		$data['status'] = 'checked="checked"';

		$this->load->view('admin/cart/zonaCrear_view', $data);
	}

	public function update($id)
	{
		$this->Cart->update_zone($id);
	}

	public function delete($id)
	{
		$this->flexi_cart_admin->delete_db_location_zone($id);
		$this->load->view('admin/request/html', array('return' => 'success'));
	}

}