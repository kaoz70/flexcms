<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:40 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Discount extends Cart implements \AdminInterface {

	public function index()
	{

		$data['items'] = $this->flexi_cart_admin->get_db_discount_array(FALSE);

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/discount/edit');
		$data['url_eliminar'] = base_url('admin/cart/discount/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'descuentos';

		$data['idx_id'] = 'disc_id';
		$data['idx_nombre'] = 'disc_description';

		$data['txt_titulo'] = 'Descuentos';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/discount/create'), 'crear nuevo descuento', array('class' => $data['nivel'] . ' ajax importante n2 boton'));
		$data['menu'][] = anchor(base_url('admin/cart/discountGroup/index'), 'grupos de descuento', array('class' => $data['nivel'] . ' ajax n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{

		$discount_id = $this->cms_general->generarId('discounts');

		$this->load->model('admin/catalogo_model');

		$this->data['titulo'] = 'Nuevo Descuento';
		$this->data['nuevo'] = 'nuevo';
		$this->data['discount_id'] =  $discount_id;

		$this->data['link'] = base_url('admin/cart/discount/insert/' . $this->uri->segment(4));
		$this->data['txt_boton'] = 'Crear';

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$this->data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get an array of all zones.
		$this->data['zones'] = $this->flexi_cart_admin->location_zones();

		// Get an array of all discount types.
		$this->data['discount_types'] = $this->flexi_cart_admin->get_db_discount_type_array();

		// Get an array of all discount methods.
		$this->data['discount_methods'] = $this->flexi_cart_admin->get_db_discount_method_array();

		// Get an array of all discount tax methods.
		$this->data['discount_tax_methods'] = $this->flexi_cart_admin->get_db_discount_tax_method_array();

		// Get an array of all discount groups.
		$sql_where = array(
			'disc_group_temporary' => 0
		);
		$this->data['discount_groups'] = $this->flexi_cart_admin->get_db_discount_group_array(FALSE, $sql_where);

		// Get an array of all product items.
		$this->data['items'] = $this->catalogo_model->getProductos();

		// Get the row array of the discount filtered by the id in the url.
		//$sql_where = array($this->flexi_cart_admin->db_column('discounts', 'id') => $discount_id);
		//$this->data['discount_data'] = $this->flexi_cart_admin->get_db_discount_row_array(FALSE, $sql_where);

		$this->data['type'] = '';
		$this->data['method'] = '';
		$this->data['tax_method'] = '';
		$this->data['location'] = '';
		$this->data['zone'] = '';
		$this->data['group'] = '';
		$this->data['item'] = '';
		$this->data['code'] = '';
		$this->data['description'] = '';
		$this->data['quantity_required'] = '';
		$this->data['quantity_discounted'] = '';
		$this->data['value_required'] = '';
		$this->data['value_discounted'] = '';
		$this->data['recursive'] = '';
		$this->data['non_combinable'] = '';
		$this->data['void_reward'] = '';
		$this->data['force_shipping'] = '';
		$this->data['custom_status_1'] = '';
		$this->data['custom_status_2'] = '';
		$this->data['custom_status_3'] = '';
		$this->data['usage_limit'] = '';
		$this->data['valid_date'] = '';
		$this->data['expire_date'] = '';
		$this->data['status'] = 1;
		$this->data['order_by'] = '';

		$this->load->view('admin/cart/descuentoCrear_view', $this->data);

	}

	public function insert()
	{
		$this->Cart->insert_discount();
	}

	public function edit($id)
	{

		$descuento = $this->Cart->getDescuento($id);

		$discount_id = $descuento->disc_id;

		$this->load->model('admin/catalogo_model');

		$this->data['titulo'] = 'Modificar Descuento';
		$this->data['nuevo'] = '';
		$this->data['discount_id'] =  $discount_id;

		$this->data['link'] = base_url('admin/cart/discount/update/' . $id);
		$this->data['txt_boton'] = 'Modificar';

		// Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
		// Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
		$this->data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

		// Get an array of all zones.
		$this->data['zones'] = $this->flexi_cart_admin->location_zones();

		// Get an array of all discount types.
		$this->data['discount_types'] = $this->flexi_cart_admin->get_db_discount_type_array();

		// Get an array of all discount methods.
		$this->data['discount_methods'] = $this->flexi_cart_admin->get_db_discount_method_array();

		// Get an array of all discount tax methods.
		$this->data['discount_tax_methods'] = $this->flexi_cart_admin->get_db_discount_tax_method_array();

		// Get an array of all discount groups.
		$sql_where = array(
			'disc_group_temporary' => 0
		);
		$this->data['discount_groups'] = $this->flexi_cart_admin->get_db_discount_group_array(FALSE, $sql_where);

		// Get an array of all product items.
		$this->data['items'] = $this->catalogo_model->getProductos();

		$this->data['type'] = $descuento->disc_type_fk;
		$this->data['method'] = $descuento->disc_method_fk;
		$this->data['tax_method'] = $descuento->disc_tax_method_fk;
		$this->data['location'] = $descuento->disc_location_fk;
		$this->data['zone'] = $descuento->disc_zone_fk;
		$this->data['group'] = $descuento->disc_group_fk;
		$this->data['item'] = $descuento->disc_item_fk;
		$this->data['code'] = $descuento->disc_code;
		$this->data['description'] = $descuento->disc_description;
		$this->data['quantity_required'] = $descuento->disc_quantity_required;
		$this->data['quantity_discounted'] = $descuento->disc_quantity_discounted;
		$this->data['value_required'] = $descuento->disc_value_required;
		$this->data['value_discounted'] = $descuento->disc_value_discounted;
		$this->data['recursive'] = $descuento->disc_recursive;
		$this->data['non_combinable'] = $descuento->disc_non_combinable_discount;
		$this->data['void_reward'] = $descuento->disc_void_reward_points;
		$this->data['force_shipping'] = $descuento->disc_force_ship_discount;
		$this->data['custom_status_1'] = $descuento->disc_custom_status_1;
		$this->data['custom_status_2'] = $descuento->disc_custom_status_2;
		$this->data['custom_status_3'] = $descuento->disc_custom_status_3;
		$this->data['usage_limit'] = $descuento->disc_usage_limit;
		$this->data['valid_date'] = $descuento->disc_valid_date;
		$this->data['expire_date'] = $descuento->disc_expire_date;
		$this->data['status'] = $descuento->disc_status;
		$this->data['order_by'] = $descuento->disc_order_by;

		// Get the row array of the discount filtered by the id in the url.
		//$sql_where = array($this->flexi_cart_admin->db_column('discounts', 'id') => $discount_id);
		//$this->data['discount_data'] = $this->flexi_cart_admin->get_db_discount_row_array(FALSE, $sql_where);

		$this->load->view('admin/cart/descuentoCrear_view', $this->data);
	}

	public function update($id)
	{
		$this->Cart->update_discount($id);
	}

	public function delete($id)
	{
		if($this->flexi_cart_admin->delete_db_discount($id)) {
			$this->load->view('admin/request/html', array('return' => 'success'));
		} else {
			$this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
		}
	}

}