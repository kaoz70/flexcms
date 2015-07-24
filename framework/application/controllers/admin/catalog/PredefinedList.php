<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:57 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;

class PredefinedList extends \Catalog implements \AdminInterface {

	/**
	 * List the items in the product section
	 */
	public function index(){

		$data['productoId'] = $this->uri->segment(5);
		$data['productoCampoId'] = $this->uri->segment(6);
		$data['titulo'] = 'Listado Predefinido';
		$data['txt_guardar'] = 'Guardar';

		$data['items_todos'] = $this->Catalogo->getItemsPredefinidos($this->uri->segment(6));
		$data['items_seleccionados'] = $this->Catalogo->getItemsPredefinidosProducto($this->uri->segment(5), $this->uri->segment(6));

		//Remove the duplicate ones. TODO: optimize this code
		foreach ($data['items_todos'] as $key => $seccion_toda)
		{
			foreach ($data['items_seleccionados'] as $seccion_cliente)
			{
				if($seccion_toda['productoCamposListadoPredefinidoId'] === $seccion_cliente->productoCamposListadoPredefinidoId)
				{
					unset($data['items_todos'][$key]);
				}
			}
		}

		$seccionesAdmin = $data['items_seleccionados'];
		$seccionesAdminArr = array();

		foreach($seccionesAdmin as $sec)
		{
			array_push($seccionesAdminArr, $sec->productoCamposListadoPredefinidoId);
		}

		$data['seccionesAdmin'] = htmlspecialchars(json_encode($seccionesAdminArr));

		$this->load->view('admin/catalog/predefined_list_view', $data);
	}

	public function create(){}

	public function edit($id){}

	public function delete($id){}

	public function insert(){}

	public function update($id){

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->guardarListadoPredefinido();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el listado!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}


}