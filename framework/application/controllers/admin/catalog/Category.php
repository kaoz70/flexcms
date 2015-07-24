<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:30 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;


class Category extends \Catalog implements \AdminInterface {

	/**
	 * List all the categories
	 */
	public function index()
	{
		$root = \CatalogTree::allRoot()->first();
		$root->findChildren(999);

		$depth = 0;
		foreach (\CatalogTree::allLeaf() as $leaf) {
			if($depth < $leaf->getDepth()) {
				$depth = $leaf->getDepth();
			}
		}

		$data['root_node'] = $root;
		$data['tree_size'] = $depth;

		$data['txt_nuevo'] = 'crear nueva categoría';
		$data['titulo'] = 'Categorías';

		$data['edit_url'] = base_url('admin/catalog/category/edit');
		$data['delete_url'] = base_url('admin/catalog/category/delete');
		$data['name'] = 'productoCategoriaNombre';

		$data['id'] = 'catalogo_tree';

		$data['url_reorganizar'] = base_url('admin/catalog/category/reorder');
		$data['url_rel'] = base_url('admin/catalog/categories');

		$data['nivel'] = 'nivel3';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crear',
			'class' => $data['nivel'] . ' ajax boton importante n1'
		);
		$data['menu'][] = anchor(base_url('admin/catalog/category/create'), 'crear nueva categoría', $atts);

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listadoArbol_view', $data);
	}

	/**
	 * Create a new category
	 */
	public function create()
	{

		$id = $this->insert();

		$data['titulo'] = 'Nueva Categor&iacute;a';
		$data['label_nombre'] = 'Nombre';
		$data['nombre'] = '';
		$data['txt_boton'] = 'Crear Categoría';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/catalog/category/update/' . $id);
		$data['removeUrl'] = base_url('admin/catalog/category/delete/' . $id);
		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['imagenExtension'] = '';
		$data['categoriaId'] = $id;
		$data['nuevo'] = 'nuevo';
		$data['cropDimensions'] = $this->General->getCropImage(7);
		$data['categoriaImagenCoord'] = '';

		/*
		   * TRADUCCIONES
		   */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaNombre = '';
			$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaDescripcion = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/category_view',$data);
	}

	/**
	 * Insert the category in the DB
	 * @return mixed
	 */
	public function insert()
	{
		return $this->Catalogo->insertCategory($this->cms_general);
	}

	/**
	 * Edit the category data
	 * @param $id
	 * @return string
	 */
	public function edit($id)
	{

		$data['titulo'] = 'Editar Categor&iacute;a';
		$data['label_nombre'] = 'Nombre';

		$categoria = $this->Catalogo->getCategory($id);

		$data['txt_boton'] = 'Modificar Categoría';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/catalog/category/update/' . $categoria->id);
		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['imagenExtension'] = '';
		$data['removeUrl'] = '';
		$data['categoriaId'] = $categoria->id;
		$data['nuevo'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(7);
		$data['categoriaImagenCoord'] = urlencode($categoria->categoriaImagenCoord);

		if($categoria->categoriaImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $categoria->categoriaImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/catalog/cat_' . $categoria->id . '_admin.' . $extension . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/catalog/cat_' . $categoria->id . '_orig.' . $extension;
			$data['imagenExtension'] = $categoria->categoriaImagen;
		}

		/*
		   * TRADUCCIONES
		   */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$categoriaTraduccion = $this->Catalogo->getCategoriaTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			if($categoriaTraduccion) {
				$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaNombre = $categoriaTraduccion->productoCategoriaNombre;
				$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaDescripcion = $categoriaTraduccion->productoCategoriaDescripcion;
			}
			else {
				$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaNombre = '';
				$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaDescripcion = '';
			}

		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/catalog/category_view',$data);
	}

	/**
	 * Update the category in the DB
	 * @param $id
	 * @return string
	 */
	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->updateCategory($this->cms_general);
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al modificar la categor&iacute;a!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	/**
	 * Delete the category from the DB
	 * @param $id
	 * @return string
	 */
	public function delete($id)
	{

		$category = $this->Catalogo->getCategory($id);

		//Eliminamos las imagenes del producto
		if($category AND $category->categoriaImagen != '')
		{
			$extension = $category->categoriaImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);

			if(file_exists('./assets/public/images/catalog/cat_' . $id . '.' . $extension))
				unlink('./assets/public/images/catalog/cat_' . $id . '.' . $extension);
		}

		$response = new \stdClass();
		$response->error_code = 0;

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$node = \CatalogTree::find($id);
			$node->deleteWithChildren();
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la categor&iacute;a!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	/**
	 * Reorder the categories
	 * @return string
	 */
	public function reorder()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$pages = \CatalogTree::find(1);
			$pages->mapTree(json_decode($this->input->post('posiciones'), true));
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar las categor&iacute;as!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}