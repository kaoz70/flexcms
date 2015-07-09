<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    private $m_config;

    function __construct(){

        parent::__construct();
        $this->load->model('module_model', 'Modulos');
        $this->load->model('noticias_model', 'Noticias');
        $this->load->model('enlaces_model', 'Enlaces');
        $this->load->model('idiomas_model', 'Idiomas');
        $this->load->model('catalogo_model', 'Catalog');
        $this->load->model('configuracion_model', 'Config');
        $this->load->model('faq_model', 'Faq');
        $this->load->model('galeria_model', 'Galeria');
        $this->load->model('imagenes_model', 'Imagenes');
        $this->load->model('servicios_model', 'Servicios');
        $this->load->model('pedido_model', 'Pedido');

        $this->load->library('pagination');
        $this->load->library('ion_auth');

        $this->load->helper('text');

        $this->flexi = new stdClass;

        $this->m_config = $this->Config->get();

        //Set theme
        $this->load->set_theme($this->m_config->theme);

		header("X-Robots-Tag: noindex", true);

	}

	//URI: ajax/es/enlaces/252/1
    public function module($method = '', $language = 'es', $id = 0, $page = '')
    {

        $idioma = $this->Idiomas->get($language);

		if(!$idioma) {
			show_my_404(current_url(), $this->m_config->theme);
		}

        $this->lang->load('ui', $idioma->idiomaNombre);

        switch($method)
        {
            case '':
                break;
            case 'enlaces':
                $this->enlaces($language, $id, $page);
                break;
            case 'publicaciones':
                $this->publicaciones($language, $id, $page);
                break;
            case 'catalogoCategoria':
                $this->catalogoCategoria($language, $id, $page);
                break;
            case 'productosDestacados':
                $this->productosDestacados($language, $id, $page);
                break;
            case 'faq':
                $this->faq($language, $id, $page);
                break;
            case 'galeria':
                $this->galeria($language, $id, $page);
                break;
            case 'servicios':
                $this->servicios($language, $id, $page);
                break;
        }
    }

    private function enlaces($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);

        if(count($module) > 0)
        {
            $enlacesCant = count($this->Enlaces->getByPage($module->moduloParam1, $idioma));
            $enlacesCol = $this->Modulos->getItemsForEnlaces($module->moduloParam1, $module->moduloParam2, $paginacionPaginaActual, $idioma);
            $enlacesPagina = $this->Modulos->getEnlaces($module->moduloParam1, $idioma);

            $moduleData['pagination'] = '';

            if($module->moduloVerPaginacion)
            {
                $pag_config['base_url'] = base_url('ajax/module/enlaces/'.$idioma.'/'.$id);
                $pag_config['total_rows'] =$enlacesCant;
                $pag_config['per_page'] = $module->moduloParam2;
                $pag_config['uri_segment'] = 6;

                $this->pagination->initialize($pag_config);
                $pagination = $this->pagination->create_links();

                $moduleData['pagination'] = $pagination;
            }

            $moduleData['enlaces'] = $enlacesCol;
            $moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

            $moduleData['viewTitle'] = $module->moduloMostrarTitulo;
            $moduleData['class'] = 'mod_enlaces ' . $module->moduloClase;

            $pagina = $this->Modulos->getPageByType(10, $idioma);

            if(count($enlacesPagina) != 0)
                $moduleData['paginaEnlacesUrl'] = $pagina->paginaNombreURL;
            else
                $moduleData['paginaEnlacesUrl'] = '';

            $imagen = $this->Imagenes->get($module->moduloParam3);
            $moduleData['imageSize'] = $imagen->imagenSufijo;
            $moduleData['diminutivo'] = $idioma;

            $this->load->view('modulos/enlaces/' . $module->moduloVista, $moduleData);
        }
    }

    public function publicaciones($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);

        if(count($module) > 0)
        {

            //Obenenemos la pagina de la que proviene la publicacion
            $noticiaPagina = $this->Modulos->getPublicaciones($module->moduloParam1, $idioma);

            $cantidad = 1;

            if($module->moduloParam2 != '')
                $cantidad = $module->moduloParam2;

            $noticiasCant = count($this->Noticias->getByPage($module->moduloParam1, $idioma));
            $noticiasCol = $this->Modulos->getItemsForPublicaciones($module->moduloParam1, $cantidad, $paginacionPaginaActual, $idioma);

            $moduleData['pagination'] = '';

            if($module->moduloVerPaginacion)
            {
                $pag_config['base_url'] = base_url('ajax/module/publicaciones/'.$idioma.'/'.$id);
                $pag_config['total_rows'] =$noticiasCant;
                $pag_config['per_page'] = $module->moduloParam2;
                $pag_config['uri_segment'] = 6;

                $this->pagination->initialize($pag_config);
                $pagination = $this->pagination->create_links();

                $moduleData['pagination'] = $pagination;
            }

            $moduleData['noticias'] = $noticiasCol;
            $moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

            if(count($noticiaPagina) != 0)
                $moduleData['paginaNoticiaUrl'] = $noticiaPagina[0]['paginaNombreURL'];
            else
                $moduleData['paginaNoticiaUrl'] = '';

            $moduleData['viewTitle'] = $module->moduloMostrarTitulo;
            $moduleData['class'] = 'mod_publicacion ' . $module->moduloClase;

            $imagen = $this->Imagenes->get($module->moduloParam3);
            $moduleData['imageSize'] = $imagen->imagenSufijo;
            $moduleData['diminutivo'] = $idioma;

            $this->load->view('modulos/publicaciones/' . $module->moduloVista, $moduleData);
        }


    }

    public function catalogoCategoria($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);

        if(count($module) > 0)
        {

            //Obtenemos la pagina de catalogo
            $catalogoPagina = $this->Modulos->getPageByType(4, $idioma);

            if(count($catalogoPagina) > 0)
            {
                $productosCant = count($this->Catalog->getProductsByCategory($module->moduloParam1, $idioma));
                $productosModPag = $this->Modulos->getItemsForCatalog($module->moduloParam1, $module->moduloParam2, $paginacionPaginaActual, $idioma);

                $moduleData['pagination'] = '';

                if($module->moduloVerPaginacion)
                {
                    $pag_config['base_url'] = base_url('ajax/module/catalogoCategoria/'.$idioma.'/'.$id);
                    $pag_config['total_rows'] =$productosCant;
                    $pag_config['per_page'] = $module->moduloParam2;
                    $pag_config['uri_segment'] = 6;

                    $this->pagination->initialize($pag_config);
                    $pagination = $this->pagination->create_links();

                    $moduleData['pagination'] = $pagination;
                }

                $moduleData['productos'] = $productosModPag;
                $moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

                $moduleData['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
                $moduleData['viewTitle'] = $module->moduloMostrarTitulo;
                $moduleData['class'] = 'mod_catalogoCategoria ' . $module->moduloClase;

                $imagen = $this->Imagenes->get($module->moduloParam3);
                $moduleData['imageSize'] = $imagen->imagenSufijo;
                $moduleData['diminutivo'] = $idioma;

				$this->load->view('modulos/catalogo/product/' . $module->moduloVista, $moduleData);
            }
            else {
                show_error('Cree una pagina de cat&aacute;logo');
            }

        }


    }
    
    public function productosDestacados($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);

        if(count($module) > 0)
        {
            $catalogoPagina = $this->Modulos->getPageByType(4, $idioma);

            if(count($catalogoPagina) == 0)
            {
                show_error('Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Cat&aacute;logo</strong>');
            }

            $productosCant = count($this->Catalog->getProductosDestacados($module->moduloParam1, $paginacionPaginaActual, $idioma));

            $productosModPag = $this->Modulos->getItemsForProductosDestacados($module->moduloParam1, $module->moduloParam2, $paginacionPaginaActual, $idioma);

            $moduleData['pagination'] = '';

            if($module->moduloVerPaginacion)
            {

                $pag_config['base_url'] = base_url('ajax/module/productosDestacados/'.$idioma.'/'.$id);
                $pag_config['total_rows'] = $productosCant;
                $pag_config['per_page'] = $module->moduloParam2;
                $pag_config['uri_segment'] = 6;

                $this->pagination->initialize($pag_config);
                $pagination = $this->pagination->create_links();

                $moduleData['pagination'] = $pagination;

            }

            $imagen = $this->Imagenes->get($module->moduloParam3);
            $moduleData['imageSize'] = $imagen->imagenSufijo;

            $moduleData['productos'] = $productosModPag;
            $moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

            $moduleData['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
            $moduleData['viewTitle'] = $module->moduloMostrarTitulo;
            $moduleData['class'] = 'mod_catalogoProductosDestacados ' . $module->moduloClase;

            $moduleData['diminutivo'] = $idioma;

			$this->load->view('modulos/catalogo/product/' . $module->moduloVista, $moduleData);
        }

    }
    
    public function faq($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);

        if(count($module) > 0)
        {

            $faqPagina = $this->Modulos->getFAQ($module->moduloParam1, $idioma);
            $pagina = $this->Modulos->getPageByType(2, $idioma);

            $faqCant = count($this->Faq->getByPage($module->moduloParam1, $idioma));
            $faqCol = $this->Modulos->getItemsForFAQ($module->moduloParam1, $module->moduloParam2, $paginacionPaginaActual, $idioma);

            $moduleData['pagination'] = '';

            if($module->moduloVerPaginacion)
            {
                $pag_config['base_url'] = base_url('ajax/module/faq/'.$idioma.'/'.$id);
                $pag_config['total_rows'] =$faqCant;
                $pag_config['per_page'] = $module->moduloParam2;
                $pag_config['uri_segment'] = 6;

                $this->pagination->initialize($pag_config);
                $pagination = $this->pagination->create_links();

                $moduleData['pagination'] = $pagination;
            }

            $moduleData['faq'] = $faqCol;
            $moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

            if(count($faqPagina) != 0)
                $moduleData['paginaFaqUrl'] = $pagina->paginaNombreURL;
            else
                $moduleData['paginaFaqUrl'] = '';
            $moduleData['viewTitle'] = $module->moduloMostrarTitulo;
            $moduleData['class'] = 'mod_faq ' . $module->moduloClase;

            $moduleData['diminutivo'] = $idioma;

            $this->load->view('modulos/faq/' .  $module->moduloVista, $moduleData);
        }
        

    }
    
    public function galeria($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);
        $this->load->library('ion_auth');

        if(!empty($module))
        {
            $galeriaPagina = $this->Modulos->getGaleria($module->moduloParam1);
            $categoriaGaleria = $this->Galeria->getCategoria($module->moduloParam1);


            if($categoriaGaleria->descargaCategoriaPrivada && !$this->ion_auth->logged_in())
                return;

            $pagina = $this->Modulos->getPageByType(6, $idioma);

            $cantidad = 1;

            if($module->moduloParam2 != '')
                $cantidad = $module->moduloParam2;

            $galeriaCant = count($this->Galeria->getByCategory($module->moduloParam1));
            $galeriaCol = $this->Modulos->getItemsForGaleria($module->moduloParam1, $cantidad, $paginacionPaginaActual, $idioma);

            $moduleData['pagination'] = '';

            if($module->moduloVerPaginacion)
            {
                $pag_config['base_url'] = base_url('ajax/module/galeria/'.$idioma.'/'.$module->moduloId);
                $pag_config['total_rows'] =$galeriaCant;
                $pag_config['per_page'] = $module->moduloParam2;
                $pag_config['uri_segment'] = 6;

                $this->pagination->initialize($pag_config);
                $pagination = $this->pagination->create_links();

                $moduleData['pagination'] = $pagination;
            }

            $moduleData['galeria'] = $galeriaCol;

            if(count($galeriaPagina) != 0)
            {
                if(count($pagina) > 0)
                    $moduleData['paginaGaleriaUrl'] = $pagina->paginaNombreURL;
                else
                {
                    show_error('Cree una p&aacute;gina de Galeria');
                }

            }
            else
                $moduleData['paginaGaleriaUrl'] = '';
            $moduleData['viewTitle'] = $module->moduloMostrarTitulo;
            $moduleData['class'] = 'mod_galeria ' . $module->moduloClase;

            $imagen = $this->Imagenes->get($module->moduloParam3);
            $moduleData['imageSize'] = $imagen->imagenSufijo;

            $moduleData['imagenes'] = array();
            $moduleData['videos'] = array();
            $moduleData['archivos'] = array();

            foreach($galeriaCol as $descarga){

                $esImagen = preg_match('/jpg|jpeg|png|gif/',mb_strtolower($descarga->descargaArchivo));

                if($esImagen) {
                    $moduleData['imagenes'][] = $descarga;
                } else if (!$esImagen && strpos($descarga->descargaArchivo, '.') === false) {
                    $moduleData['videos'][] = $descarga;
                } else if (!$esImagen && strpos($descarga->descargaArchivo, '.')) {
                    $moduleData['archivos'][] = $descarga;
                }

            }

            if(!empty($moduleData['imagenes']))
                $this->load->view('modulos/galeria/imagenes_view', $moduleData);

            if(!empty($moduleData['archivos']))
                $this->load->view('modulos/galeria/documentos_view', $moduleData);

            if(!empty($moduleData['videos']))
                $this->load->view('modulos/galeria/videos_view', $moduleData);


        }


    }

    public function cart()
    {

        $this->load->model('cart_model');
        $this->load->library('flexi_cart');

        $return = new stdClass();

        $method = $this->uri->segment(3);

        // ! Important note when updating the cart via ajax !
        // CodeIgniters sessions need the page to be refreshed before data that is set to a session is available to be retrieved again.
        //
        // As flexi cart stores the cart data within CI's sessions, whenever the cart is updated via ajax, the cart will not be updated until after
        // the page has been refreshed/reloaded.
        //
        // Therefore, this example redirects back to the item ajax example page, where the updated mini cart drop menu will be displayed to the
        // user to notify them of the update.

        switch($method){

            /**
             * insert_ajax_link_item_to_cart
             * Inserts an item to the cart via a link from the 'Add Item to Cart via Ajax' page.
             * The settings for each item are defined via the custom demo function 'demo_insert_ajax_link_item_to_cart()'.
             */
            case 'add':
                $this->cart_model->insert_item_to_cart();
                break;

            /**
             * delete_item
             * Deletes and item from the cart using the '$row_id' supplied via the url link.
             * This function is accessed from the 'View Cart' page via an items 'Remove' link.
             */
            case 'delete':
                // The 'delete_items()' function can accept an array of row_ids to delete more than one row at a time.
                $this->flexi_cart->delete_items($this->uri->segment(4));
                break;
        }

        //Print any messages
        $return->message = $this->flexi_cart->get_messages();

        //Get the cart items
        $data['mini_cart_items'] = $this->flexi_cart->cart_items();
        $return->mini_cart = $this->load->view('paginas/cart/mini_cart_view', $data, true);

	    $data['return'] = $return;

        $this->load->set_admin_theme();
	    $this->load->view('admin/request/json', $data);

    }

    public function servicios($idioma, $id, $paginacionPaginaActual)
    {

        $module = $this->Modulos->getModule($id);

        if(count($module) > 0)
        {

            $servicioPagina = $this->Modulos->getPageByType(12, $idioma);

            if(count($servicioPagina) == 0)
            {
                show_error('Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Servicios</strong>');
            }

            $serviciosCant = count($this->Servicios->getAll($idioma, $module->moduloParam1));

            $serviciosModPag = $this->Modulos->getItemsForServicios($module->moduloParam2, $paginacionPaginaActual, $idioma, $module->moduloParam1);

            $moduleData['pagination'] = '';

            if($module->moduloVerPaginacion)
            {

                $pag_config['base_url'] = base_url('ajax/module/servicios/'.$idioma.'/'.$module->moduloId);
                $pag_config['total_rows'] =$serviciosCant;
                $pag_config['per_page'] = $module->moduloParam2;
                $pag_config['uri_segment'] = 6;

                $this->pagination->initialize($pag_config);
                $pagination = $this->pagination->create_links();

                $moduleData['pagination'] = $pagination;

            }

            $imagen = $this->Imagenes->get($module->moduloParam3);
            $moduleData['imageSize'] = $imagen->imagenSufijo;

            $moduleData['servicios'] = $serviciosModPag;
            $moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

            $moduleData['paginaServiciosUrl'] = $servicioPagina->paginaNombreURL;
            $moduleData['diminutivo'] = $idioma;

            $this->load->view('modulos/servicios/' . $module->moduloVista, $moduleData);
        }


    }

    /**
     * Get the users location based on the IP
     */
    function location(){
        $this->load->library('Geolocator');
	    $data['return'] = $this->geolocator->get_city_and_parents();
	    $this->load->view('admin/request/json', $data);
    }

    /**
     * Get the country list
     */
    function countries(){
        $this->load->library('Geolocator');
	    $data['return'] = $this->geolocator->get_countries();
	    $this->load->view('admin/request/json', $data);
    }

}