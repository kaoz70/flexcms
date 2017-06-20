<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:59 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;

use stdClass;

class PredefinedListItem extends PredefinedList implements \AdminInterface {

	public function index(){

		$campo_id = $this->uri->segment(5);
		$data['items'] = $this->Catalogo->getItemsPredefinidos($campo_id);
		$data['url_rel'] = base_url('admin/catalog/predefinedListItem');
		$data['url_sort'] = base_url('admin/catalog/predefinedListItem/reorder/' . $campo_id);
		$data['url_modificar'] = base_url('admin/catalog/predefinedListItem/edit');
		$data['url_eliminar'] = base_url('admin/catalog/predefinedListItem/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel5';
		$data['list_id'] = 'items_predefinidos';

		$data['idx_id'] = 'productoCamposListadoPredefinidoId';
		$data['idx_nombre'] = 'productoCamposListadoPredefinidoTexto';

		$data['txt_titulo'] = 'Items Predefinidos';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crear',
			'class' => $data['nivel'] . ' ajax importante n1 boton'
		);

		$data['menu'][] = anchor(base_url('admin/catalog/predefinedListItem/create/' . $campo_id), 'Crear nuevo Item', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);

	}

	public function create(){

		$data['titulo'] = 'Nuevo Item';

		/*
		* TRADUCCIONES
		*/
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();
		foreach ($data['idiomas'] as $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->productoCamposListadoPredefinidoTexto = '';
		}

		$data['traducciones'] = $traducciones;
		$data['productoCampoId'] = $this->uri->segment(5);
		$data['txt_boton'] = 'Crear nuevo Item';
		$data['nuevo'] = 'nuevo';
		$data['link'] = base_url('admin/catalog/predefinedListItem/insert');
		$data['productoCamposListadoPredefinidoPublicado'] = 'checked="checked"';
		$data['productoCamposListadoPredefinidoClase'] = '';
		$data['productoCamposListadoPredefinidoId'] = "";

		$this->load->view('admin/catalog/predefined_list_item_view', $data);
	}

	public function insert(){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Catalogo->insertarItemPredefinido();
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el elemento predefinido!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function edit($id){

		$data['titulo'] = 'Modificar Item';

		$item = $this->Catalogo->getItemPredefinido($id);

		/*
		* TRADUCCIONES
		*/
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $idioma) {
			/*$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->productoCamposListadoPredefinidoTexto = '';*/

			$campoTraduccion = $this->Catalogo->getCampoListadoPredefinidoTranslation($idioma['idiomaDiminutivo'], $this->uri->segment(5));
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			if($campoTraduccion)
				$traducciones[$idioma['idiomaDiminutivo']]->productoCamposListadoPredefinidoTexto = $campoTraduccion->productoCamposListadoPredefinidoTexto;
			else
				$traducciones[$idioma['idiomaDiminutivo']]->productoCamposListadoPredefinidoTexto = '';

		}

		$data['traducciones'] = $traducciones;
		$data['productoCampoId'] = $this->uri->segment(4);
		$data['txt_boton'] = 'Modificar Item';
		$data['nuevo'] = '';
		$data['link'] = base_url('admin/catalog/predefinedListItem/update/' . $id);

		$data['productoCamposListadoPredefinidoPublicado'] = '';
		if($item->productoCamposListadoPredefinidoPublicado)
			$data['productoCamposListadoPredefinidoPublicado'] = 'checked="checked"';

		$data['productoCamposListadoPredefinidoClase'] = $item->productoCamposListadoPredefinidoClase;
		$data['productoCamposListadoPredefinidoId'] = $item->productoCamposListadoPredefinidoId;

		$this->load->view('admin/catalog/predefined_list_item_view', $data);
	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->actualizarItemPredefinido();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el elemento predefinido!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->eliminarListadoPredefinido($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el elemento predefinido!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{
		$this->Catalogo->reorganizarItemsPredefinidos($id);
	}

}