<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:34 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;

class Field extends \Catalog implements \AdminInterface {

	public function index()
	{

		$data['items'] = $this->Catalogo->getCampos();

		$data['url_rel'] = base_url('admin/catalog/field');
		$data['url_sort'] = base_url('admin/catalog/field/reorder');
		$data['url_modificar'] = base_url('admin/catalog/field/edit');
		$data['url_eliminar'] = base_url('admin/catalog/field/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'elementos';

		$data['idx_id'] = 'productoCampoId';
		$data['idx_nombre'] = 'productoCampoValor';

		$data['txt_titulo'] = 'Editar Template';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax boton importante n1'
		);
		$data['menu'][] = anchor(base_url('admin/catalog/field/create'), 'Crear Nuevo Elemento', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{
		$data['titulo'] = 'Nuevo Elemento';
		$data['habilitado']	= 'checked="checked"';

		$data['campoId'] = $this->cms_general->generarId('producto_campos');
		$data['inputId'] = '';
		$checked = 'checked="checked"';
		$data['checkedVerNombre'] = $checked;
		$data['checkedVerModulo'] = $checked;
		$data['checkedVerListado'] = $checked;
		$data['checkedVerPedido'] = $checked;
		$data['checkedHabilitado'] = $checked;
		$data['checkedVerFiltro'] = $checked;
		$data['inputs'] = $this->Catalogo->getInputs();
		$data['productoCampoClase'] = '';
		$data['txt_boton'] = 'Guardar Elemento';
		$data['link']  = base_url('admin/catalog/field/insert');
		$data['nuevo'] = 'nuevo';

		/*
		   * TRADUCCIONES
		   */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/field_view',$data);
	}
	
	public function edit($id)
	{
		$campo = $this->Catalogo->getDatosCampo($id);

		$data['titulo'] = 'Modificar Elemento';
		$data['campoId'] = $id;
		$data['habilitado']	= 'checked="checked"';

		$data['inputId'] = $campo->inputId;;
		$checked = 'checked="checked"';

		$data['checkedVerNombre'] = '';
		$data['checkedVerModulo'] = '';
		$data['checkedVerFiltro'] = '';
		$data['checkedVerModulo'] = '';
		$data['checkedVerPedido'] = '';
		$data['checkedHabilitado'] = '';
		$data['checkedVerListado'] = '';

		if($campo->productoCampoMostrarNombre)
			$data['checkedVerNombre'] = $checked;

		if($campo->productoCampoVerModulo)
			$data['checkedVerModulo'] = $checked;

		if($campo->productoCampoVerListado)
			$data['checkedVerListado'] = $checked;

		if($campo->productoCampoVerPedido)
			$data['checkedVerPedido'] = $checked;

		if($campo->productoCampoHabilitado)
			$data['checkedHabilitado'] = $checked;

		if($campo->productoCampoVerFiltro)
			$data['checkedVerFiltro'] = $checked;

		$data['inputs'] = $this->Catalogo->getInputs();
		$data['productoCampoClase'] = $campo->productoCampoClase;
		$data['txt_boton'] = 'Modificar Elemento';
		$data['link']  = base_url('admin/catalog/field/update/' . $id);
		$data['nuevo'] = '';

		/*
		   * TRADUCCIONES
		   */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$campoTraduccion = $this->Catalogo->getCampoTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			if($campoTraduccion)
				$traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor = $campoTraduccion->productoCampoValor;
			else
				$traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/field_view',$data);

	}

	public function insert()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id =  $this->Catalogo->guardarCampo($this->cms_general);
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->updateCampo($this->cms_general, $id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->deleteCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->reorderTemplateElements();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}