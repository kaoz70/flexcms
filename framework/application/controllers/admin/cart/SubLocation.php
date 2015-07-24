<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:24 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class SubLocation extends Cart implements \AdminInterface {

	public function index()
	{
		$location_type_id = $this->uri->segment(5);

		// Get an array of all locations filtered by the id in the url.
		$sql_where = array($this->flexi_cart_admin->db_column('locations', 'type') => $location_type_id);
		$data['items'] = $this->flexi_cart_admin->get_db_location_array(FALSE, $sql_where);

		//Data
		$ubicacion = $this->Cart->getUbicacion($location_type_id);
		$padre = $this->Cart->getUbicacion($ubicacion->loc_type_parent_fk);

		$sql_where = array($this->flexi_cart_admin->db_column('locations', 'type') => $ubicacion->loc_type_parent_fk);
		$data['grupos'] = $this->flexi_cart_admin->get_db_location_array(FALSE, $sql_where);

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/subLocation/edit');
		$data['url_eliminar'] = base_url('admin/cart/subLocation/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel6';
		$data['list_id'] = 'sub_ubicaciones';

		$data['txt_titulo'] = 'Sub locaciones';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/subLocation/create/' . $location_type_id), 'crear nueva sub locaci&oacute;n', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		if($padre){
			$data['idx_nombre'] = 'loc_name';
			$data['idx_grupo_id'] = 'loc_id';
			$data['idx_grupo_id_alt'] = 'loc_parent_fk';
			$data['idx_item_id'] = 'loc_id';
			$data['idx_item_nombre'] = 'loc_name';
			$data['txt_grupoNombre'] = $padre->loc_type_name;
			$this->load->view('admin/listadoAgrupado_view', $data);
		} else {
			$data['idx_id'] = 'loc_id';
			$data['idx_nombre'] = 'loc_name';
			$this->load->view('admin/listado_view', $data);
		}


	}

	public function create()
	{

		$location_type_id = $this->uri->segment(4);

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get arrays of all shipping and tax zones.
		$data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
		$data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

		// Get the row array of the location type filtered by the id in the url.
		$sql_where = array($this->flexi_cart_admin->db_column('location_type', 'id') => $location_type_id);
		$data['location_type_data'] = $this->flexi_cart_admin->get_db_location_type_row_array(FALSE, $sql_where);

		$data['padre_id'] = '';
		$data['ship_id'] = '';
		$data['tax_id'] = '';

		$data['titulo'] = 'Crear Sub locaci&oacute;n';
		$data['nuevo'] = 'nuevo';
		$data['ubicacionId'] = $location_type_id;
		$data['loc_id'] = $this->cms_general->generarId('locations');
		$data['nombre'] = '';
		$data['link'] = base_url('admin/cart/subLocation/create/' . $location_type_id);
		$data['txt_boton'] = 'Guardar';
		$data['status'] = 'checked="checked"';

		$this->load->view('admin/cart/subUbicacionCrear_view', $data);
	}

	public function insert()
	{
		$this->Cart->insert_location($this->uri->segment(5));
	}

	public function edit($id)
	{

		$location = $this->Cart->getSubUbicacion($id);
		$location_type_id = $location->loc_type_fk;

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get arrays of all shipping and tax zones.
		$data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
		$data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

		// Get the row array of the location type filtered by the id in the url.
		$sql_where = array($this->flexi_cart_admin->db_column('location_type', 'id') => $location_type_id);
		$data['location_type_data'] = $this->flexi_cart_admin->get_db_location_type_row_array(FALSE, $sql_where);

		$data['titulo'] = 'Crear Sub locaci&oacute;n';
		$data['nuevo'] = '';
		$data['ubicacionId'] = $location_type_id;
		$data['nombre'] = $location->loc_name;
		$data['link'] = base_url('admin/cart/subLocation/update/' . $location->loc_id);
		$data['txt_boton'] = 'Actualizar';
		$data['loc_id'] = $location->loc_id;

		$data['padre_id'] = $location->loc_parent_fk;
		$data['ship_id'] = $location->loc_ship_zone_fk;
		$data['tax_id'] = $location->loc_tax_zone_fk;

		if($location->loc_status)
			$data['status'] = 'checked="checked"';
		else
			$data['status'] = '';

		$this->load->view('admin/cart/subUbicacionCrear_view', $data);
	}

	public function update($id)
	{
		$this->Cart->update_location($id);
	}

	public function delete($id)
	{
		$this->flexi_cart_admin->delete_db_location($id);
		$this->load->view('admin/request/html', array('return' => 'success'));
	}

}