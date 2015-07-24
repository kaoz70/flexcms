<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:26 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends \Catalog implements \AdminInterface {

	public function index(){}

	public function create()
	{

		$productoId = $this->insert();

		//Error
		if($productoId === false)
			return;

		$campos = $this->Catalogo->camposEntradas();

		$data['idiomas'] = $this->Idioma->getLanguages();

		$inputs = array();

		foreach($campos as $row)
		{

			$input = new \stdClass();
			$input->productoCampoId = $row->productoCampoId;
			$input->inputTipoContenido = $row->inputTipoContenido;
			$input->inputTipoNombre = $row->inputTipoNombre;
			$input->productoCampoValor = $row->productoCampoValor;
			$input->productoCampoRelContenido = array();

			foreach ($data['idiomas'] as $idioma)
			{
				$input->productoCampoRelContenido[$idioma['idiomaDiminutivo']] = new \stdClass();
				$input->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido = '';
			}

			array_push($inputs,  $input);
		}

		$root = \CatalogTree::allRoot()->first();
		$root->findChildren(999);

		$data['titulo'] = 'Nuevo Producto';
		$data['habilitado']	= 'checked="checked"';
		$data['productoId'] = $productoId;
		$data['productoNombre'] = '';
		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['imagenExtension'] = '';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['nuevo1'] = 'nuevo';

		$data['productoPrioridad'] = '';
		$data['checkedPD'] = '';
		$data['checkedPE'] = 'checked="checked"';
		$data['categorias'] = $root->getChildren();
		$data['categoriaId'] = '';
		$data['campos'] = $inputs;

		$data['txt_boton'] = 'Crear Producto';
		$data['link'] = base_url('admin/catalog/product/update/' . $productoId);
		$data['campoValor'] = '';
		$data['nuevo'] = TRUE;
		$data['removeUrl'] = base_url('admin/catalog/product/delete/'.$productoId);
		$data['cropDimensions'] = $this->General->getCropImage(5);
		$data['productoImagenCoord'] = '';

		/*
		   * TRADUCCIONES
		   */

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->productoNombre = '';
			$traducciones[$idioma['idiomaDiminutivo']]->productoKeywords = '';
			$traducciones[$idioma['idiomaDiminutivo']]->productoDescripcion = '';
			$traducciones[$idioma['idiomaDiminutivo']]->productoMetaTitulo = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/product_view',$data);
	}
	
	public function insert(){
		return $this->Catalogo->guardarProducto($this->cms_general);
	}

	public function edit($id)
	{

		$campos = $this->Catalogo->camposEntradas($id);
		$inputs = array();
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach($campos as $row)
		{
			$input = new \stdClass();
			$input->productoCampoId = $row->productoCampoId;
			$input->inputTipoContenido = $row->inputTipoContenido;
			$input->inputTipoNombre = $row->inputTipoNombre;
			$input->productoCampoValor = $row->productoCampoValor;
			$input->productoCampoRelContenido = array();

			foreach ($data['idiomas'] as $key => $idioma)
			{
				$input->productoCampoRelContenido[$idioma['idiomaDiminutivo']] = $this->Catalogo->camposEntradaValor($id, $row->productoCampoId, $idioma['idiomaDiminutivo']);
			}

			array_push($inputs,  $input);

		}

		$productos = $this->Catalogo->getDatosProducto($id); //Datos del link
		$data['titulo'] = 'Modificar Producto';

		$data['productoId'] = $productos->productoId;
		$data['productoPrioridad'] = $productos->productoPrioridad;
		$data['stock_quantity'] = $productos->stock_quantity;
		$data['weight'] = $productos->weight;

		if($productos->productoImagenExtension != '')
		{
			$data['txt_botImagen'] = 'Cambiar Imagen';
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/catalog/prod_' . $productos->productoId . '_admin.' . $productos->productoImagenExtension . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/catalog/prod_' . $productos->productoId . '_orig.' . $productos->productoImagenExtension;
			$data['imagenExtension'] = $productos->productoImagenExtension;
		}
		else
		{
			$data['txt_botImagen'] = 'Subir Imagen';
			$data['imagen'] = '';
			$data['imagenExtension'] = '';
			$data['imagenOrig'] = '';
		}


		$productoDeldia = $productos->productoDeldia;
		if($productoDeldia != 's')
		{
			$checkedPD = '';
		}else
		{
			$checkedPD = 'checked="checked"';
		}

		$data['categoriaId'] = $productos->categoriaId;
		$productoEnable = $productos->productoEnable;

		if($productoEnable != 's')
		{
			$checkedPE = '';
		}else
		{
			$checkedPE = 'checked="checked"';
		}

		$root = \CatalogTree::allRoot()->first();
		$root->findChildren(999);

		$data['checkedPD'] = $checkedPD;
		$data['categorias'] = $root->getChildren();
		$data['habilitado'] = $checkedPE;

		$data['txt_boton'] = 'Modificar Producto';
		//obtengo valores de campos
		$data['campos'] = $inputs;
		$data['link'] = base_url('admin/catalog/product/update/' . $data['productoId']);
		$data['nuevo'] = FALSE;
		$data['nuevo1'] = '';
		$data['removeUrl'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(5);
		$data['productoImagenCoord'] = urlencode($productos->productoImagenCoord);

		/*
		   * TRADUCCIONES
		   */

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$productoTraduccion = $this->Catalogo->getProductoTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();

			if($productoTraduccion) {
				$traducciones[$idioma['idiomaDiminutivo']]->productoNombre = $productoTraduccion->productoNombre;
				$traducciones[$idioma['idiomaDiminutivo']]->productoKeywords = $productoTraduccion->productoKeywords;
				$traducciones[$idioma['idiomaDiminutivo']]->productoDescripcion = $productoTraduccion->productoDescripcion;
				$traducciones[$idioma['idiomaDiminutivo']]->productoMetaTitulo = $productoTraduccion->productoMetaTitulo;
			}
			else {
				$traducciones[$idioma['idiomaDiminutivo']]->productoNombre = '';
				$traducciones[$idioma['idiomaDiminutivo']]->productoKeywords = '';
				$traducciones[$idioma['idiomaDiminutivo']]->productoDescripcion = '';
				$traducciones[$idioma['idiomaDiminutivo']]->productoMetaTitulo = '';
			}

		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/product_view', $data);

	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->updateProducto($this->cms_general);
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el producto!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));


	}

	//Miguel
	public function delete($id)
	{

		$producto = $this->Catalogo->getDatosProducto($id);

		//Eliminamos las imagenes del producto
		//TODO: delete the correct images geting info from DB
		if($producto->productoImagenExtension != '')
		{
			$extension = preg_replace('/\?+\d{0,}/', '', $producto->productoImagenExtension);

			if(file_exists('./assets/public/images/catalog/prod_' . $id . '_small.' . $extension))
				unlink('./assets/public/images/catalog/prod_' . $id . '_small.' . $extension);

			if(file_exists('./assets/public/images/catalog/prod_' . $id . '_medium.' . $extension))
				unlink('./assets/public/images/catalog/prod_' . $id . '_medium.' . $extension);

			if(file_exists('./assets/public/images/catalog/prod_' . $id . '_big.' . $extension))
				unlink('./assets/public/images/catalog/prod_' . $id . '_big.' . $extension);

			if(file_exists('./assets/public/images/catalog/prod_' . $id . '_huge.' . $extension))
				unlink('./assets/public/images/catalog/prod_' . $id . '_huge.' . $extension);
		}

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			//Eliminamos el producto
			$this->Catalogo->deleteProducto();
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el producto!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($categoryId)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->reorderProducts($categoryId);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los productos!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}