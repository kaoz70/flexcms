<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:29 AM
 */

namespace gallery;
$_ns = __NAMESPACE__;

use GalleryTree;
use stdClass;
use Exception;

include_once ('File.php');

class Category extends File implements \AdminInterface {

	public function index()
	{

		$root = GalleryTree::allRoot()->first();
		$root->findChildren(999);

		$depth = 0;
		foreach (GalleryTree::allLeaf() as $leaf) {
			if($depth < $leaf->getDepth()) {
				$depth = $leaf->getDepth();
			}
		}

		$data['root_node'] = $root;
		$data['tree_size'] = $depth;

		$data['titulo'] = 'Categorías';

		$data['id'] = 'galeria_tree';

		$data['url_reorganizar'] = base_url('admin/gallery/category/reorder');
		$data['url_rel'] = base_url('admin/gallery/category');
		$data['edit_url'] = base_url('admin/gallery/category/edit');
		$data['delete_url'] = base_url('admin/gallery/category/delete');
		$data['name'] = 'descargaCategoriaNombre';

		$data['nivel'] = 'nivel3';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crear',
			'class' => $data['nivel'] . ' ajax boton importante n1'
		);
		$data['menu'][] = anchor(base_url('admin/gallery/category/create'), 'crear nueva categoría', $atts);

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listadoArbol_view', $data);

	}

	public function create()
	{
		$data['id'] = $this->Descargas->addCategory($this->cms_general);
		$data['descargaCategoriaTipo'] = '';
		$data['descargaCategoriaNombre'] = '';
		$data['descargaCategoriaClase'] = '';
		$data['descargaCategoriaEnlace'] = '';
		$data['titulo'] = "Crear Categoría";
		$data['link'] = base_url("admin/gallery/category/update/" . $data['id']);
		$data['txt_boton'] = "crear";
		$data['descargaCategoriaPublicado'] = 'checked="checked"';
		$data['nuevo'] = 'nuevo';

		$data['imagen'] = '';
		$data['removeUrl'] = base_url('admin/gallery/category/delete/' . $data['id']);
		$data['imagenOrig'] = '';
		$data['imagenExtension'] = '';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['cropDimensions'] = $this->General->getCropImage(12);
		$data['descargaCategoriaImagenCoord'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/gallery/category_view', $data);
	}

	public function edit($id)
	{

		$data = $this->Descargas->getCategory((int)$id, 'es');
		$data['nuevo'] = '';
		$data['titulo'] = "Modificar Categoría";
		$data['link'] = base_url("admin/gallery/category/update/" . $id);
		$data['txt_boton'] = "modificar";

		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['removeUrl'] = '';
		$data['imagenExtension'] = '';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['cropDimensions'] = $this->General->getCropImage(12);
		$data['descargaCategoriaImagenCoord'] = urlencode($data['descargaCategoriaImagenCoord']);

		if($data['descargaCategoriaImagen'] != '')
		{
			//Eliminamos el cache del navegador
			$extension = $data['descargaCategoriaImagen'];
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/downloads/cat_' . $data['id'] . '_admin.' . $extension . '?' . time() . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/downloads/cat_' . $data['id'] . '_orig.' . $extension;
			$data['imagenExtension'] = $data['descargaCategoriaImagen'];
		}

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$descargaTraduccion = $this->Descargas->getCategoriaTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			if($descargaTraduccion)
				$traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre = $descargaTraduccion->descargaCategoriaNombre;
			else
				$traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/gallery/category_view', $data);
	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Descargas->updateCategory($this->cms_general);
			$response->new_id = $this->input->post('descargaCategoriaId');
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la categor&iacute;a!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Descargas->addCategory($this->cms_general);
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear la categor&iacute;a!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$node = GalleryTree::find($id);
			$node->deleteWithChildren();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la categor&iacute;a!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$pages = GalleryTree::find(1);
			$pages->mapTree(json_decode($this->input->post('posiciones'), true));
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar la categor&iacute;a!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}