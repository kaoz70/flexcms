<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:19 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Location extends Cart implements \AdminInterface {

	public function index()
	{
		$data['items'] = $this->flexi_cart_admin->get_db_location_type_array(FALSE, 'loc_type_temporary = 0');

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/location/edit');
		$data['url_eliminar'] = base_url('admin/cart/location/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'ubicaciones';

		$data['idx_id'] = 'loc_type_id';
		$data['idx_nombre'] = 'loc_type_name';

		$data['txt_titulo'] = 'Tipos de Locacion';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/location/create'), 'crear nueva locaci&oacute;n', array('class' => $data['nivel'] . ' ajax importante n2 boton'));
		$data['menu'][] = anchor(base_url('admin/cart/zone/index'), 'zonas de envio', array('class' => $data['nivel'] . ' ajax n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{

		$ubicacionId = $this->insert();

		$data['ubicacionId'] = $ubicacionId;
		$data['titulo'] = 'Nueva Locaci&oacute;n';
		$data['habilitado'] = 'checked="checked"';
		$data['nuevo'] = 'nuevo';
		$data['removeUrl'] = base_url('admin/cart/location/delete/' . $ubicacionId);

		$data['nombre'] = '';
		$data['padre_id'] = '';

		$data['txt_boton'] = 'Crear Ubicaci&oacute;n';
		$data['link'] = base_url('admin/cart/location/update/' . $ubicacionId);
		$data['link_ubicaciones'] = base_url('admin/cart/subLocation/index/' . $ubicacionId);

		$data['ubicaciones'] = $this->flexi_cart_admin->get_db_location_type_array();

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->get_db_location_type_array(FALSE, 'loc_type_temporary = 0');

		$this->load->view('admin/cart/ubicacionCrear_view', $data);
	}

	public function insert()
	{
		return $this->Cart->createUbicacion();
	}

	public function update($id)
	{
		$this->Cart->update_location_types($id);
	}

	public function edit($id)
	{
		$ubicacion = $this->Cart->getUbicacion($id);

		$data['ubicacionId'] = $ubicacion->loc_type_id;
		$data['titulo'] = 'Modificar Ubicaci&oacute;n';
		$data['habilitado'] = 'checked="checked"';
		$data['nuevo'] = '';
		$data['removeUrl'] = '';

		$data['txt_boton'] = 'Actualizar Ubicaci&oacute;n';
		$data['link'] = base_url('admin/cart/location/update/' . $ubicacion->loc_type_id);
		$data['link_ubicaciones'] = base_url('admin/cart/subLocation/index/' . $ubicacion->loc_type_id);
		$data['nombre'] = $ubicacion->loc_type_name;
		$data['padre_id'] = $ubicacion->loc_type_parent_fk;

		$data['ubicaciones'] = $this->flexi_cart_admin->get_db_location_type_array();

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->get_db_location_type_array(FALSE, 'loc_type_temporary = 0');

		$this->load->view('admin/cart/ubicacionCrear_view', $data);
	}

	public function delete($id)
	{
		$this->flexi_cart_admin->delete_db_location_type($id, TRUE);
		$this->load->view('admin/request/html', array('return' => 'success'));
	}

}