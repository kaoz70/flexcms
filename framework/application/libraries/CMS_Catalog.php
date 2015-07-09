<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Catalog {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('catalogo_model', 'Catalog');
        $CI->load->model('module_model', 'Modulos');
        $CI->load->model('search_model', 'Search');
	    $CI->load->model('imagenes_model', 'Images');
    }

    public function create($page, $data, $idioma, $currentPage, $module)
    {

        $data['paginaCatalogoUrl'] = $page->paginaNombreURL;

        $CI =& get_instance();
        $catalogConfig = $CI->Catalog->getConfiguration();

        $catalog_id = (int)$CI->uri->segment(3);
        $catalog_name = (int)$CI->uri->segment(4);
		$producto_id = $CI->uri->segment(5);
        //$catId = str_replace("::", "/", urldecode($catId));
        //$catId = json_decode($catId);


        $pagina = is_numeric($producto_id);

        $html = '';
        $ret = new stdClass();
        $ret->html = '';
        $ret->data = $data;

        /****************************************
         * PAGINA PRINCIPAL DEL CATALOGO
         ***************************************/
        //if(gettype($catId) == 'integer' && $catId == 0)
        if(!$catalog_id)
        {

            $tree = CatalogTree::allRoot()->first();
            $tree->lang = $idioma;
            $tree->findChildren(2);

            $categorias = $tree->getChildren();
            
            //Add any products to the category
            foreach ($categorias as $cat) {
                $cat->productos = $CI->Catalog->getProductsByCategory($cat->id, $idioma);
            }

            /*
             * SI HAY UNA SOLA CATEGORIA MOSTRAMOS EL LISTADO DE PRODUCTOS
             */
            if(count($categorias) == 2){

                if(!$catalogConfig->productoMostarProductoInicio)
                {
                    $html .= $this->renderProductList($categorias[1], $data, $idioma, $currentPage, $module);
                }

                /*
                 * VISTA DEL DETALLE DE UN PRODUCTO (primer producto de primera categoria)
                */
                else
                {
                    $producto =  $CI->Catalog->getFirsProductOfFirstCategory($idioma);
                    $categoria = $CI->Catalog->getCategory((int)$producto->categoriaId, $idioma);
                    $catData = $this->renderProductDetail($producto, $categoria,$idioma, $currentPage, $data);
                    $html .= $catData->html;
                    $ret->data = $catData->data;
                }

            }
            else
            {

                /*
                 * LISTADO DE CATEGORIAS
                */
                if(!$catalogConfig->productoMostarProductoInicio)
                {

                    $data['categorias'] = $categorias;

                    $headerData['clase'] = 'class="catalogo content"';
                    $headerData['titulo'] = $page->paginaNombre;
                    $headerData['categoria'] = NULL;

                    $html = $CI->load->view('paginas/catalogo/header_view', $headerData, true);
                    $html .= $CI->load->view('paginas/catalogo/categorias_view', $data, true);
                    $html .= $CI->load->view('paginas/catalogo/footer_view', '', true);
                }

                /*
                 * VISTA DEL DETALLE DE UN PRODUCTO (primer producto de primera categoria)
                */
                else
                {
                    $producto =  $CI->Catalog->getFirsProductOfFirstCategory($idioma);
                    $categoria = $CI->Catalog->getCategory((int)$producto->categoriaId, $idioma);
                    $catData = $this->renderProductDetail($producto, $categoria,$idioma, $currentPage, $data);
                    $html .= $catData->html;
                    $ret->data = $catData->data;
                }

            }

        }
        //else if(gettype($catId) == 'integer' && $catId != 0)

		//Vista de los productos y subcategorias de una categoria
        else if($catalog_id || !$pagina)
        {

            /****************************************
             * LISTADOS DE PRODUCTOS EN LA CATEGORIA
             ***************************************/

            $categoria = $CI->Catalog->getCategory($catalog_id, $idioma);

	        if(!$categoria) {
		        //TODO: category not found, not 404
		        show_my_404(base_url($idioma . '/' . $currentPage), $CI->m_config->theme);
	        }

            if(!$producto_id || $pagina)
            {
                $html .= $this->renderProductList($categoria, $data, $idioma, $currentPage, $module);
            }
            /*****************************************
             * DETALLE DEL PRODUCTO
             ****************************************/
            else
            {
                $producto = $CI->Catalog->getProduct($producto_id, $idioma);

	            if (! $producto) {
		            //TODO: product not found, not 404
		            show_my_404(base_url($idioma . '/' . $currentPage), $CI->m_config->theme);
	            }

                $catData = $this->renderProductDetail($producto, $categoria,$idioma, $currentPage, $data);
                $html .= $catData->html;
                $ret->data = $catData->data;
            }

        }

        /****************************************
         * PAGINA RESULTADO BUSQUEDA POR FILTROS
         ***************************************/
        else if (gettype($catalog_id) == 'object')
        {

            $filters = $catalog_id;
            $headerData['clase'] = 'class="catalogo busqueda content"';
            $headerData['titulo'] = 'Busqueda';

            $paginaCatalogo = $CI->Modulos->getPageByType(4, $idioma);
            $productosIds = $CI->Search->catalogFilters($filters, $idioma);

            //Get all the common values in a multidimentional array
            $arrays = count($productosIds);
            $match = array();
            $duplicates = array();
            foreach($productosIds as $one){
                foreach($one as $single){
                    $var = (array)$single;
                    if(!isset($match[$var['productoId']])) { $match[$var['productoId']] = 0; }
                    $match[$var['productoId']]++;
                    if($match[$var['productoId']] == $arrays){
                        $duplicates[] = (int)$var['productoId'];
                    }
                }
            }

            $data['products'] = $CI->Catalog->getProductsByIds($duplicates, $idioma);
            $data['link_base'] = base_url() . $idioma . '/' . $paginaCatalogo->paginaNombreURL . '/';

            $html = $CI->load->view('paginas/catalogo/header_view', $headerData, true);
            $html .= $CI->load->view('paginas/catalogo/producto_busqueda_view', $data, true);
            $html .= $CI->load->view('paginas/catalogo/footer_view', '', true);
        }

        $ret->html = $html;

        return $ret;

    }

    private function renderProductList($categoria, $data,$idioma, $currentPage, $module)
    {
        $CI =& get_instance();

        if($module->moduloVerPaginacion) {

            $noticiasCant = count($CI->Catalog->getProductsByCategory($categoria->id, $idioma));

            $pag_config = array();
            $pag_config['base_url'] = base_url($idioma.'/'.$CI->uri->segment(2).'/' . $categoria->productoCategoriaUrl . '/');
            $pag_config['total_rows'] =$noticiasCant;
            $pag_config['per_page'] = $module->moduloParam4;
            $pag_config['uri_segment'] = 4;

            $CI->pagination->initialize($pag_config);
            $pagination = $CI->pagination->create_links();

            $data['pagination'] = $pagination;

            $products = $CI->Modulos->getItemsForCatalog($categoria->id, $module->moduloParam4, (int)$CI->uri->segment(4), $idioma);

            $ids = array();

            foreach ($products as $product) {
                $ids[] = $product->productoId;
            }

            $data['products'] = $CI->Catalog->getProductsByIds($ids, $idioma, true);
        } else {
            $data['pagination'] = '';
            $data['products'] = $CI->Catalog->getProductsByCategory($categoria->id, $idioma, true);
        }

        $data['link_base'] = base_url() . $idioma . '/' . $currentPage . '/'. $categoria->id . '/'. $categoria->productoCategoriaUrl . '/';;

        $headerData['clase'] = 'class="catalogo listado content cat_' . $categoria->id . '"';
        $headerData['titulo'] = $categoria->productoCategoriaNombre;

        $tree = CatalogTree::find($categoria->id);
        $tree->lang = $idioma;
        $tree->findChildren(9999);

        $dataCat['categorias'] = $tree->getChildren();
        $dataCat['categoria'] = $data['categoria'] = $headerData['categoria'] = $categoria;

        $html = $CI->load->view('paginas/catalogo/header_view', $headerData, true);
        $html .= $CI->load->view('paginas/catalogo/producto_listado_view', $data, true);
        $html .= $CI->load->view('paginas/catalogo/categorias_view', $dataCat, true);
        $html .= $CI->load->view('paginas/catalogo/footer_view', $data, true);

        return $html;
    }

    private function renderProductDetail($producto, $categoria, $idioma, $currentPage, $data)
    {
        $CI =& get_instance();

	    $ret = new stdClass();

        $headerData['clase'] = 'class="catalogo detalle cat_' . $categoria->id . '"';
        $headerData['titulo'] = $categoria->productoCategoriaNombre;
        $headerData['categoria'] = $categoria;

        $data['link_base'] = base_url() . $idioma . '/' . $currentPage . '/'. $categoria->id . '/'. $categoria->productoCategoriaUrl . '/';;
	    $data['og_description'] = $data['meta_description'] = $producto->productoDescripcion;

		$data['meta_title'] = $producto->productoMetaTitulo;
		$data['meta_keywords'] = $producto->productoKeywords;

        $campos = $CI->Catalog->getProductFields($producto->productoId, $idioma);

        //Related product by keywords
        $palabras = explode(',', $producto->productoKeywords);
        $productosRelacionados = $CI->Catalog->getProductosRelacionados($palabras, $idioma);

        //If above result is empty get the related products form the same category
        if (empty($productosRelacionados)) {
            $productosRelacionados = $CI->Catalog->getProductsByCategory($producto->categoriaId, $idioma, true);
        }

        //Remove this product from related list
        foreach ($productosRelacionados as $key => $rel) {
            if ($rel->productoId === $producto->productoId) {
                unset($productosRelacionados[$key]);
            }
        }

        $producto = $CI->Catalog->consolidateProduct($producto, $campos, $idioma, $categoria);
        $producto->productosRelacionados = $productosRelacionados;

        $data['producto'] = $producto;
        $data['regresarCatalogo'] = $data['link_base'];

        $html = $CI->load->view('paginas/catalogo/header_view', $headerData, true);
        $html .= $CI->load->view('paginas/catalogo/producto_detalle_view', $data, true);
        $html .= $CI->load->view('paginas/catalogo/footer_view', '', true);

	    //Facebook ObjectGraph
	    if($producto->productoImagenExtension) {
		    $image_conf = $CI->Images->getImages(5);
		    $data['og_image'] = base_url('assets/public/images/catalog/prod_' . $producto->productoId . $image_conf[0]->imagenSufijo . '.' . $producto->productoImagenExtension);
	    } else if (
            $producto->imagenes AND
		    array_key_exists(0, $producto->imagenes) AND
            $producto->imagenes[0]->contenido
	    ) {
		    $image_conf = $CI->Images->getImages(6);
		    $image = $producto->imagenes[0]->imagenes[0];
		    $data['og_image'] = base_url('assets/public/images/catalog/gal_' . $producto->productoId . '_' . $image->productoImagenId . $image_conf[0]->imagenSufijo . '.' . $image->productoImagen);
	    }
	    $data['og_title'] = $producto->productoNombre;

	    $ret->html = $html;
	    $ret->data = $data;

        return $ret;
    }

}

/* End of file CMS_Catalog.php */