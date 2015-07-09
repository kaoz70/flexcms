<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->load->library('image_lib');

        $this->load->model('admin/catalogo_model', 'Catalogo');
        $this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
	    $this->load->model('admin/module_model', 'Modulo');

        $this->load->library('Seguridad');
        $this->load->library('CMS_General');

        $this->seguridad->init();

    }

    /*************************************/
    /************ PRODUCTOS **************/
    /*************************************/

    public function create_product()
    {

        $productoId = $this->Catalogo->guardarProducto($this->cms_general);

        //Error
        if($productoId === false)
            return;

        $campos = $this->Catalogo->camposEntradas();

        $data['idiomas'] = $this->Idioma->getLanguages();

        $inputs = array();

        foreach($campos as $row)
        {

            $input = new stdClass();
            $input->productoCampoId = $row->productoCampoId;
            $input->inputTipoContenido = $row->inputTipoContenido;
            $input->inputTipoNombre = $row->inputTipoNombre;
            $input->productoCampoValor = $row->productoCampoValor;
            $input->productoCampoRelContenido = array();

            foreach ($data['idiomas'] as $idioma)
            {
                $input->productoCampoRelContenido[$idioma['idiomaDiminutivo']] = new stdClass();
                $input->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido = '';
            }

            array_push($inputs,  $input);
        }

        $root = CatalogTree::allRoot()->first();
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
        $data['link'] = base_url('admin/catalog/update_product/' . $productoId);
        $data['campoValor'] = '';
        $data['nuevo'] = TRUE;
        $data['removeUrl'] = base_url('admin/catalog/delete_product/'.$productoId);
        $data['cropDimensions'] = $this->General->getCropImage(5);
        $data['productoImagenCoord'] = '';

        /*
           * TRADUCCIONES
           */

        $traducciones = array();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->productoNombre = '';
            $traducciones[$idioma['idiomaDiminutivo']]->productoKeywords = '';
            $traducciones[$idioma['idiomaDiminutivo']]->productoDescripcion = '';
            $traducciones[$idioma['idiomaDiminutivo']]->productoMetaTitulo = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/product_view',$data);
    }

    public function edit_product($id)
    {

        $campos = $this->Catalogo->camposEntradas($id);
        $inputs = array();
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach($campos as $row)
        {
            $input = new stdClass();
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

        $root = CatalogTree::allRoot()->first();
        $root->findChildren(999);

        $data['checkedPD'] = $checkedPD;
        $data['categorias'] = $root->getChildren();
        $data['habilitado'] = $checkedPE;

        $data['txt_boton'] = 'Modificar Producto';
        //obtengo valores de campos
        $data['campos'] = $inputs;
        $data['link'] = base_url('admin/catalog/update_product/' . $data['productoId']);
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
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();

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

    public function update_product()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateProducto($this->cms_general);
            $response->new_id = $this->uri->segment(4);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el producto!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));


    }

    //Miguel
    public function delete_product($id)
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

        $response = new stdClass();
        $response->error_code = 0;

        try{
            //Eliminamos el producto
            $this->Catalogo->deleteProducto();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el producto!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_products($categoryId)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->reorderProducts($categoryId);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los productos!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    /*************************************/
    /************ CATEGORIA **************/
    /*************************************/

    public function categories()
    {
        $root = CatalogTree::allRoot()->first();
        $root->findChildren(999);

        $depth = 0;
        foreach (CatalogTree::allLeaf() as $leaf) {
            if($depth < $leaf->getDepth()) {
                $depth = $leaf->getDepth();
            }
        }

        $data['root_node'] = $root;
        $data['tree_size'] = $depth;

        $data['txt_nuevo'] = 'crear nueva categoría';
        $data['titulo'] = 'Categorías';

        $data['edit_url'] = base_url('admin/catalog/edit_category');
        $data['delete_url'] = base_url('admin/catalog/delete_category');
        $data['name'] = 'productoCategoriaNombre';

        $data['id'] = 'catalogo_tree';

        $data['url_reorganizar'] = base_url('admin/catalog/reorder_categories');
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
        $data['menu'][] = anchor(base_url('admin/catalog/create_category'), 'crear nueva categoría', $atts);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoArbol_view', $data);
    }

    public function create_category()
    {

        $id = $this->Catalogo->insertCategory($this->cms_general);

        $data['titulo'] = 'Nueva Categor&iacute;a';
        $data['label_nombre'] = 'Nombre';
        $data['nombre'] = '';
        $data['txt_boton'] = 'Crear Categoría';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['link'] = base_url('admin/catalog/update_category/' . $id);
        $data['removeUrl'] = base_url('admin/catalog/delete_category/' . $id);
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
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaNombre = '';
            $traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaDescripcion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/category_view',$data);
    }

    public function edit_category()
    {

        $id = $this->uri->segment(4);

        $data['titulo'] = 'Editar Categor&iacute;a';
        $data['label_nombre'] = 'Nombre';

        $categoria = $this->Catalogo->getCategory($id);

        $data['txt_boton'] = 'Modificar Categoría';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['link'] = base_url('admin/catalog/update_category/' . $categoria->id);
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
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
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

    public function update_category()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateCategory($this->cms_general);
            $response->new_id = $this->uri->segment(4);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al modificar la categor&iacute;a!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete_category($id)
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

        $response = new stdClass();
        $response->error_code = 0;

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $node = CatalogTree::find($id);
            $node->deleteWithChildren();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la categor&iacute;a!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_categories()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $pages = CatalogTree::find(1);
            $pages->mapTree(json_decode($this->input->post('posiciones'), true));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar las categor&iacute;as!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }


    /*************************************/
    /************ TEMPLATE ***************/
    /*************************************/

    public function fields()
    {

        $data['items'] = $this->Catalogo->getCampos();

        $data['url_rel'] = base_url('admin/catalog/fields');
        $data['url_sort'] = base_url('admin/catalog/reorder_fields');
        $data['url_modificar'] = base_url('admin/catalog/edit_field');
        $data['url_eliminar'] = base_url('admin/catalog/delete_field');
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
        $data['menu'][] = anchor(base_url('admin/catalog/create_field'), 'Crear Nuevo Elemento', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function create_field()
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
        $data['link']  = base_url('admin/catalog/insert_field');
        $data['nuevo'] = 'nuevo';

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/field_view',$data);
    }

    public function edit_field()
    {
        $id = $this->uri->segment(4);
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
        $data['link']  = base_url('admin/catalog/update_field/' . $id);
        $data['nuevo'] = '';

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $campoTraduccion = $this->Catalogo->getCampoTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($campoTraduccion)
                $traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor = $campoTraduccion->productoCampoValor;
            else
                $traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/field_view',$data);

    }

    public function insert_field()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id =  $this->Catalogo->guardarCampo($this->cms_general);
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function update_field()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateCampo($this->cms_general);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete_field()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->deleteCampo();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_fields()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->reorderTemplateElements();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    /*
      * GALLERY IMAGES
      */
    public function images()
    {

        $productId = $this->uri->segment(4);
        $data['items'] = $this->Catalogo->getProductImages($productId);

        $data['url_rel'] = base_url('admin/catalog/images/'.$productId);
        $data['url_sort'] = base_url('admin/catalog/reorder_images/'.$productId);
        $data['url_modificar'] = base_url('admin/catalog/edit_image/');
        $data['url_eliminar'] = base_url('admin/catalog/delete_image/');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'product_images';

        $data['idx_id'] = 'productoImagenId';
        $data['idx_nombre'] = 'productoImagenNombre';

        $data['txt_titulo'] = 'Imágenes';

	    $data['url_path'] =  base_url() . 'assets/public/images/catalog/gal_' . $productId . '_';
	    $data['url_upload'] =  base_url() . 'admin/imagen/productoGaleria/' . $productId;
	    $data['method'] =  'productoGaleria/' . $productId;

	    $dimensiones = $this->General->getCropImage(6);
	    $data['width'] = $dimensiones->imagenAncho;
	    $data['height'] = $dimensiones->imagenAlto;

	    $data['nivel'] = 'nivel5';
	    $data['list_id'] = 'producto_galeria_images';

	    $data['idx_id'] = 'productoImagenId';
	    $data['idx_nombre'] = 'productoImagenNombre';
	    $data['idx_extension'] = 'productoImagen';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['bottomMargin'] = count($data['menu']) * 34;

	    $this->load->view('admin/listadoGaleria_view', $data);
    }

    public function edit_image()
    {
        $productoImagenId = $this->uri->segment(4);
        $image = $this->Catalogo->getProductImage($productoImagenId);

        $data['productoId'] = $image->productoId;
        $data['titulo'] = 'Modificar Imágen';
        $data['productoImagenNombre'] = $image->productoImagenNombre;
        $data['txt_boton'] = 'Modificar Imágen';
        $data['productoImagen'] = $image->productoImagen;
        $data['nuevo'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(6);
        $data['productoImagenCoord'] = urlencode($image->productoImagenCoord);

        if($image->productoImagen != '')
        {
            $data['txt_botImagen'] = 'Cambiar Imágen';
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/catalog/gal_' . $image->productoId . '_' . $productoImagenId . '_admin.' . $image->productoImagen . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/catalog/gal_' . $image->productoId . '_' . $productoImagenId . '_orig.' . $image->productoImagen;
        }
        else
        {
            $data['txt_botImagen'] = 'Subir Imagen';
            $data['imagen'] = '';
            $data['imagenOrig'] = '';
        }

        $data['productoImagenId'] = $productoImagenId;
        $data['link'] = base_url('admin/catalog/update_image');

        $enabled = '';

        if($image->productoImagenEnabled)
            $enabled = 'checked="checked"';

        $data['productoImagenEnabled'] = $enabled;

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $imagenTraduccion = $this->Catalogo->getImageTranslation($idioma['idiomaDiminutivo'], $productoImagenId);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($imagenTraduccion)
                $traducciones[$idioma['idiomaDiminutivo']]->productoImagenDescripcion = $imagenTraduccion->productoImagenTexto;
            else
                $traducciones[$idioma['idiomaDiminutivo']]->productoImagenDescripcion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/product_image_view', $data);
    }

    public function update_image()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateProductImage();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete_image($image_id)
    {

        $imagen = $this->Catalogo->getProductImage($image_id);

		//Delete the images
	    if($imagen && $imagen->productoImagen != ''){

            //TODO: use this to delete the images in other controllers
		    //Get the images
		    $images = $this->Modulo->getImages(6);
		    $extension = preg_replace('/\?+\d{0,}/', '', $imagen->productoImagen);

		    foreach ($images as $img) {
			    if(file_exists('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . $img->imagenSufijo . '.' . $extension))
				    unlink('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . $img->imagenSufijo . '.' . $extension);
		    }

		    //image
		    if (file_exists('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '.' . $extension))
			    unlink('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '.' . $extension);

		    //Admin image
		    if (file_exists('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '_admin.' . $extension))
			    unlink('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '_admin.' . $extension);

		    //Original image
		    if (file_exists('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '_orig.' . $extension))
			    unlink('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '_orig.' . $extension);

		    //Search image
		    if (file_exists('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '_search.' . $extension))
			    unlink('assets/public/images/catalog/gal_' . $imagen->productoId . '_' . $image_id . '_search.' . $extension);

	    }

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->deleteProductImage($image_id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_images()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->reorderProductImages($this->uri->segment(4));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    /*
     * PRODUCT FILES
     */
    public function files()
    {

        $productId = $this->uri->segment(4);
        $data['items'] = $this->Catalogo->getProductFiles($productId);

        $data['url_rel'] = base_url('catalogo/files/'.$productId);
        $data['url_sort'] = base_url('admin/catalog/reorder_files/'.$productId);
        $data['url_modificar'] = base_url('admin/catalog/edit_file/'.$productId);
        $data['url_eliminar'] = base_url('admin/catalog/delete_file/'.$productId);
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'product_files';

        $data['idx_id'] = 'productoDescargaId';
        $data['idx_nombre'] = 'productoDescargaNombre';

        $data['txt_titulo'] = 'Archivos';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/catalog/create_file/'.$productId), 'subir nuevo archivo', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    //TODO: delete this method when I integrate the new upload method for the files
    public function create_file()
    {
        $productId = $this->uri->segment(4);
        $productoDescargaId = $this->cms_general->generarId('producto_descargas');

        $data['productoId'] = $productId;
        $data['titulo'] = 'Nueva Descarga';
        $data['productoDescargaNombre'] = '';
        $data['txt_botImagen'] = 'Subir nueva descarga';
        $data['txt_boton'] = 'Guardar Descarga';
        $data['archivoUrl'] = '<a href="#"></a>';
        $data['productoDescargaId'] = $productoDescargaId;
        $data['link'] = base_url('admin/catalog/insert_file');
        $data['productoDescargaEnabled'] = 'checked="checked"';
        $data['productoDescarga'] = '';
        $data['productoDescargaArchivo'] = '';
        $data['nuevo'] = 'nuevo';

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/product_file_view', $data);
    }

    public function edit_file()
    {
        $productId = $this->uri->segment(4);
        $productoDescargaId = $this->uri->segment(5);
        $file = $this->Catalogo->getProductFile($productoDescargaId);

        $data['productoId'] = $productId;
        $data['titulo'] = 'Modificar Descarga';
        $data['productoDescargaNombre'] = $file->productoDescargaNombre;
        $data['txt_boton'] = 'Modificar Descarga';
        $data['productoDescargaArchivo'] = $file->productoDescargaArchivo;
        $data['productoDescargaId'] = $productoDescargaId;
        $data['nuevo'] = '';

        if($file->productoDescargaArchivo != '')
        {
            $data['txt_botImagen'] = 'Cambiar Archivo';
            $data['archivoUrl'] = '<a href="' . base_url() . 'docs/catalog/prod_'. $file->productoId . '/' . $file->productoDescargaArchivo . '">' . $file->productoDescargaArchivo . '</a>';
        }
        else
        {
            $data['txt_botImagen'] = 'Subir Archivo';
            $data['archivoUrl'] = '<a href="#"></a>';
        }

        $data['productoDescargaId'] = $productoDescargaId;
        $data['link'] = base_url('admin/catalog/update_file');

        $enabled = '';

        if($file->productoDescargaEnabled)
            $enabled = 'checked="checked"';

        $data['productoDescargaEnabled'] = $enabled;

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $descargaTraduccion = $this->Catalogo->getDescargaTranslation($idioma['idiomaDiminutivo'], $productoDescargaId);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($descargaTraduccion)
                $traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion = $descargaTraduccion->productoDescargaTexto;
            else
                $traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/product_file_view', $data);
    }

    public function insert_file()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Catalogo->insertProductFile();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el archivo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function update_file()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateProductFile();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el archivo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));


    }

    public function delete_file($file_id)
    {
        $descarga = $this->Catalogo->getProductFile($file_id);

        //Eliminamos los archivos
        if($descarga->productoDescargaArchivo != '')
        {

            if(file_exists('./docs/catalog/prod_'. $descarga->productoId . '/' . $descarga->productoDescargaArchivo))
                unlink('./docs/catalog/prod_'. $descarga->productoId . '/' . $descarga->productoDescargaArchivo);

        }

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->deleteProductFile($file_id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el archivo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_files()
    {
        $this->Catalogo->reorderProductFiles($this->uri->segment(4));
    }

    /*
     * PRODUCT VIDEOS
     */
    public function videos()
    {

        $productId = $this->uri->segment(4);
        $data['items'] = $this->Catalogo->getProductVideos($productId);

        $data['url_rel'] = base_url('admin/catalog/videos/'.$productId);
        $data['url_sort'] = base_url('admin/catalog/reorder_videos/'.$productId);
        $data['url_modificar'] = base_url('admin/catalog/edit_video/'.$productId);
        $data['url_eliminar'] = base_url('admin/catalog/delete_video/'.$productId);
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'product_videos';

        $data['idx_id'] = 'productoVideoId';
        $data['idx_nombre'] = 'productoVideoNombre';

        $data['txt_titulo'] = 'Videos';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/catalog/create_video/'.$productId), 'crear nuevo video', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    //TODO: delete this method when I integrate the new upload method for the videos
    public function create_video()
    {
        $productId = $this->uri->segment(4);
        $productoVideoId = $this->cms_general->generarId('producto_videos');

        $data['productoId'] = $productId;
        $data['titulo'] = 'Nuevo Video';
        $data['productoVideoNombre'] = '';
        $data['productoVideoId'] = $productoVideoId;
        $data['link'] = base_url('admin/catalog/insert_video');
        $data['productoVideoEnabled'] = 'checked="checked"';
        $data['productoVideo'] = '';
        $data['nuevo'] = 'nuevo';
        $data['txt_boton'] = 'Nuevo Video';
        $data['productoVideo'] = '';

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->productoVideoDescripcion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/product_video_view', $data);
    }

    public function edit_video($productId, $productoVideoId)
    {
        $video = $this->Catalogo->getProductVideo($productoVideoId);

        $data['productoId'] = $productId;
        $data['titulo'] = 'Modificar Video';
        $data['productoVideoNombre'] = $video->productoVideoNombre;
        $data['txt_boton'] = 'Modificar Video';
        $data['productoVideo'] = $video->productoVideo;
        $data['nuevo'] = '';

        $data['productoVideoId'] = $productoVideoId;
        $data['link'] = base_url('admin/catalog/update_video');

        $enabled = '';

        if($video->productoVideoEnabled)
            $enabled = 'checked="checked"';

        $data['productoVideoEnabled'] = $enabled;

        /*
           * TRADUCCIONES
           */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $videoTraduccion = $this->Catalogo->getVideoTranslation($idioma['idiomaDiminutivo'], $productoVideoId);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($videoTraduccion)
                $traducciones[$idioma['idiomaDiminutivo']]->productoVideoDescripcion = $videoTraduccion->productoVideoTexto;
            else
                $traducciones[$idioma['idiomaDiminutivo']]->productoVideoDescripcion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/catalog/product_video_view', $data);
    }

    public function insert_video()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Catalogo->insertProductVideo();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el video!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function update_video()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateProductVideo();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el video!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete_video($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->deleteProductVideo($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el video!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_videos()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->reorderProductVideos($this->uri->segment(4));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el video!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    /*
      * GALLERY AUDIOS
      */
    public function audios()
    {

        $productId = $this->uri->segment(4);
        $data['items'] = $this->Catalogo->getProductAudios($productId);

        $data['url_rel'] = base_url('admin/catalog/audios/'.$productId);
        $data['url_sort'] = base_url('admin/catalog/reorder_audios/'.$productId);
        $data['url_modificar'] = base_url('admin/catalog/edit_audio/'.$productId);
        $data['url_eliminar'] = base_url('admin/catalog/delete_audio');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'product_audios';

        $data['idx_id'] = 'productoAudioId';
        $data['idx_nombre'] = 'productoAudioNombre';

        $data['txt_titulo'] = 'Audios';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearAudio',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/catalog/create_audio/'.$productId), 'crear nuevo audio', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function create_audio()
    {
        $productId = $this->uri->segment(4);
        $productoAudioId = $this->cms_general->generarId('producto_audios');

        $data['productoId'] = $productId;
        $data['titulo'] = 'Nuevo Audio';
        $data['productoAudioNombre'] = '';
        $data['txt_subir'] = 'Subir nuevo audio';
        $data['txt_boton'] = 'Guardar Audio';
        $data['productoAudioId'] = $productoAudioId;
        $data['link'] = base_url('admin/catalog/insert_audio');
        $data['productoAudioEnabled'] = 'checked="checked"';
        $data['productoAudioExtension'] = '';
        $data['nuevo'] = 'nuevo';
        $data['audio'] = '';

        $this->load->view('admin/catalog/product_audio_view', $data);
    }

    public function edit_audio()
    {
        $productId = $this->uri->segment(4);
        $productoAudioId = $this->uri->segment(5);
        $image = $this->Catalogo->getProductAudio($productoAudioId);

        $data['productoId'] = $productId;
        $data['titulo'] = 'Modificar Audio';
        $data['productoAudioNombre'] = $image->productoAudioNombre;
        $data['txt_boton'] = 'Modificar Audio';
        $data['productoAudioExtension'] = $image->productoAudioExtension;
        $data['nuevo'] = '';
        $data['audio'] = '';

        if($image->productoAudioExtension != '')
        {
            $data['txt_subir'] = 'Cambiar audio';

            $path = base_url('assets/public/audio/catalog/audio_' . $productId . '_' . $productoAudioId . '.' . $image->productoAudioExtension);

            $data['audio'] = '<audio controls>
                <source src="'.$path.'" type="audio/mpeg">
                <embed height="50" width="100" src="'.$path.'">
            </audio>';
        }
        else
        {
            $data['txt_subir'] = 'Subir audio';
        }

        $data['productoAudioId'] = $productoAudioId;
        $data['link'] = base_url('admin/catalog/update_audio');

        $enabled = '';

        if($image->productoAudioEnabled)
            $enabled = 'checked="checked"';

        $data['productoAudioEnabled'] = $enabled;

        $this->load->view('admin/catalog/product_audio_view', $data);
    }

    public function insert_audio()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Catalogo->insertProductAudio();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el audio!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function update_audio()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->updateProductAudio();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el audio!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete_audio($audioId)
    {
        $audio = $this->Catalogo->getProductAudio($audioId);

        //Eliminamos los audios
        if($audio->productoAudioExtension != '')
        {

            $extension = $audio->productoAudioExtension;

            if(file_exists('./assets/public/audio/catalog/audio_' . $audio->productoId . '_' . $audioId . '.' . $extension))
                unlink('./assets/public/audio/catalog/audio_' . $audio->productoId . '_' . $audioId . '.' . $extension);
        }

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->deleteProductAudio($audioId);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el audio!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_audios()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->reorderProductAudios($this->uri->segment(4));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los audios!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    /*
     * PRODUCT LIST
     */
    public function predefined_list(){

        $data['productoId'] = $this->uri->segment(4);
        $data['productoCampoId'] = $this->uri->segment(5);
        $data['titulo'] = 'Listado Predefinido';
        $data['txt_guardar'] = 'Guardar';

        $data['items_todos'] = $this->Catalogo->getItemsPredefinidos($this->uri->segment(5));
        $data['items_seleccionados'] = $this->Catalogo->getItemsPredefinidosProducto($this->uri->segment(4), $this->uri->segment(5));

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

    public function update_predefined_list(){

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->guardarListadoPredefinido();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el listado!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function predefined_list_items($id){

        $data['items'] = $this->Catalogo->getItemsPredefinidos($id);
        $campo_id = $this->uri->segment(4);

        $data['url_rel'] = base_url('admin/catalog/predefined_list_items');
        $data['url_sort'] = base_url('admin/catalog/reorder_predefined_list_items');
        $data['url_modificar'] = base_url('admin/catalog/edit_predefined_list_item');
        $data['url_eliminar'] = base_url('admin/catalog/delete_predefined_list_item');
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

        $data['menu'][] = anchor(base_url('admin/catalog/create_predefined_list_item/' . $campo_id), 'Crear nuevo Item', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);

    }

    public function create_predefined_list_item(){

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
        $data['productoCampoId'] = $this->uri->segment(4);
        $data['txt_boton'] = 'Crear nuevo Item';
        $data['nuevo'] = 'nuevo';
        $data['link'] = base_url('admin/catalog/insert_predefined_list_item');
        $data['productoCamposListadoPredefinidoPublicado'] = 'checked="checked"';
        $data['productoCamposListadoPredefinidoClase'] = '';
        $data['productoCamposListadoPredefinidoId'] = $this->cms_general->generarId('producto_campos_listado_predefinido');

        $this->load->view('admin/catalog/predefined_list_item_view', $data);
    }

    public function insert_predefined_list_item(){

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

    public function edit_predefined_list_item(){

        $data['titulo'] = 'Modificar Item';

        $item = $this->Catalogo->getItemPredefinido($this->uri->segment(4));

        /*
        * TRADUCCIONES
        */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $idioma) {
            /*$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->productoCamposListadoPredefinidoTexto = '';*/

            $campoTraduccion = $this->Catalogo->getCampoListadoPredefinidoTranslation($idioma['idiomaDiminutivo'], $this->uri->segment(4));
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
        $data['link'] = base_url('admin/catalog/update_predefined_list_item');

        $data['productoCamposListadoPredefinidoPublicado'] = '';
        if($item->productoCamposListadoPredefinidoPublicado)
            $data['productoCamposListadoPredefinidoPublicado'] = 'checked="checked"';

        $data['productoCamposListadoPredefinidoClase'] = $item->productoCamposListadoPredefinidoClase;
        $data['productoCamposListadoPredefinidoId'] = $item->productoCamposListadoPredefinidoId;

        $this->load->view('admin/catalog/predefined_list_item_view', $data);
    }

    public function update_predefined_list_item()
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

    public function delete_predefined_list_item()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Catalogo->eliminarListadoPredefinido($this->uri->segment(4));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el elemento predefinido!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder_predefined_list_items()
    {
        $this->Catalogo->reorganizarItemsPredefinidos($this->uri->segment(4));
    }

    public function field_list(){

    }

    /*
     * CONFIGURATION
     */

    public function configuracion()
    {
        $config = $this->Catalogo->getConfiguration();
        $data['productoMostarProductoInicio'] = $config->productoMostarProductoInicio;
        $data['link'] = base_url('admin/catalog/guardarConfiguracion');
        $data['titulo'] = 'ConfiguraciÃ³n';
        $data['txt_boton'] = 'Guardar Configuracion';
        $this->load->view('admin/catalog/configuracion_view', $data);
    }

    public function guardarConfiguracion()
    {
        $this->Catalogo->updateConfiguration();
        $this->configuracion();
    }

}