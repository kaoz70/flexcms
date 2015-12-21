<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:43 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class DiscountGroup extends  Cart implements \AdminInterface {

	public function index()
	{
		$sql_where = array(
			'disc_group_temporary' => 0
		);
		$data['items'] = $this->flexi_cart_admin->get_db_discount_group_array(FALSE, $sql_where);

		$data['url_rel'] = base_url('admin/cart');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/cart/discountGroup/edit');
		$data['url_eliminar'] = base_url('admin/cart/discountGroup/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel5';
		$data['list_id'] = 'grupo_descuentos';

		$data['idx_id'] = 'disc_group_id';
		$data['idx_nombre'] = 'disc_group';

		$data['txt_titulo'] = 'Grupos de Descuento';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['menu'][] = anchor(base_url('admin/cart/discountGroup/create'), 'nuevo grupo de descuento', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{

		$data['titulo'] = 'Crear Grupo Descuento';
		$data['nuevo'] = 'nuevo';
		$data['id'] =  $this->insert();
		$data['nombre'] = '';
		$data['removeUrl'] = base_url('admin/cart/discountGroup/delete/' . $data['id']);
		$data['link'] = base_url('admin/cart/discountGroup/update/' . $data['id']);
		$data['txt_boton'] = 'Guardar';
		$data['status'] = 'checked="checked"';

		$this->load->view('admin/cart/grupoDescuentoCrear_view', $data);
	}

	public function insert()
	{
		return $this->Cart->insert_discount_group();
	}

	public function edit($id)
	{

		$grupo = $this->Cart->getGrupoDescuento($id);

		$data['titulo'] = 'Grupo Descuento';
		$data['nuevo'] = '';
		$data['id'] =  $grupo->disc_group_id;
		$data['nombre'] = $grupo->disc_group;
		$data['removeUrl'] = '';
		$data['link'] = base_url('admin/cart/discountGroup/update/' . $data['id']);
		$data['txt_boton'] = 'Guardar';

		if($grupo->disc_group_status)
			$data['status'] = 'checked="checked"';
		else
			$data['status'] = '';

		$this->load->view('admin/cart/grupoDescuentoCrear_view', $data);
	}

	public function update($id)
	{
		$this->Cart->update_discount_groups($id);
	}

	public function delete($id)
	{
		if($this->flexi_cart_admin->delete_db_discount_group($id)) {
			$this->load->view('admin/request/html', array('return' => 'success'));
		} else {
			$this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
		}
	}

}