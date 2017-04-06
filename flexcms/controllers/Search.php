<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

    function __construct(){

        parent::__construct();

        $this->load->helper('text');

        $this->load->model('catalogo_model', 'Catalog');
        $this->load->model('search_model', 'Search');
        $this->load->model('idiomas_model', 'Idiomas');
        $this->load->model('module_model', 'Module');
        $this->load->model('configuracion_model', 'Config');

        $this->m_config = $this->Config->get();

        //Set theme
        $this->load->set_theme($this->m_config->theme);

    }

    public function index()
    {
        $query = $this->input->get('query');
        $language = $this->input->get('language');

        $idioma = $this->Idiomas->get($language);
        $this->lang->load('ui', $idioma->idiomaNombre);

        /*
		 * ARTICULOS
		 */
        $data['articulos'] = $this->Search->articulos($query, $language);

        /*
         * FAQ
         */
        $faqs = array();
        $faqPagina = $this->Module->getPageByType(2,$language);

        foreach ($this->Search->faq($query, $language) as $key => $value) {
            $faq = new stdClass();
            $faq->id = $value->faqId;
            $faq->pregunta = $value->faqPregunta;
            $faq->respuesta = $value->faqRespuesta;
            $faq->pagina = $faqPagina->paginaNombreURL;
            array_push($faqs, $faq);
        }

        $data['faqs'] = $faqs;

        /*
         * PUBLICACIONES
         */
        $publicaciones = array();
        $publicacionPaginas = $this->Module->getPagesByType(5,$language);

        foreach ($publicacionPaginas as $pagina)
        {
            $pagina->publicaciones = $this->Search->publicaciones($query, $language, $pagina->id);

            if(count($pagina->publicaciones) > 0)
                array_push($publicaciones, $pagina);
        }

        $data['publicaciones'] = $publicaciones;

        /*
         * DESCARGAS
         */
        $data['descargas'] = $this->Search->descargas($query, $language);

        /*
         * PRODUCTOS
         */
        $productos = array();
        $catalogoPagina = $this->Module->getPageByType(4, $language);

        foreach ($this->Search->productos($query, $language) as $key => $value) {
            $producto = new stdClass();
            $producto->id = $value->productoId;
            $producto->productoUrl = $value->productoUrl;
            $producto->nombre = $value->productoNombre;
            $producto->categoriaUrl = $value->productoCategoriaUrl;
            $producto->categoriaId = $value->categoriaId;
            $producto->extension = $value->productoImagenExtension;
            $producto->pagina = $catalogoPagina->paginaNombreURL;
            array_push($productos, $producto);
        }

        $data['productos'] = $productos;

        $data['lang'] = $language;

        if(!$data['articulos'] AND !$faqs AND !$publicaciones AND !$data['descargas'] AND !$productos) {
            $this->load->view('search/no_results_view', $data);
        } else {
            $this->load->view('search/results_view', $data);
        }

    }

    public function productFilters()
    {
        $filters = $this->input->get('filters');
        $language = $this->input->get('language');
        $page = $this->input->get('page');
        $soloDestacados = $this->input->get('destacados');

        $filters = json_decode($filters);

        if($filters == '')
            $filters = new stdClass;

        $productosIds = $this->Search->catalogFilters($filters, $language);

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

        $data['products'] = $this->Catalog->getProductsByIds($duplicates, $language);
        $data['link_base'] = base_url() . $language . '/' . $page . '/';
        $this->load->view('paginas/catalogo/producto_busqueda_view', $data);
    }

    public function log_activity($pageId = 0) {

        date_default_timezone_set('America/Guayaquil');

        $this->load->library('user_agent');
        if($this->agent->is_robot())
        {
            return FALSE;
        }
        else
        {
            // Start off with the session stuff we know
            $data = array();
            $data['estadisticaUserIP'] =  $this->input->ip_address();
            $data['paginaId'] = $pageId;

            // Lastly, we need to know when this is happening
            $data['estadisticaFecha'] = time();

            // We don't need it, but we'll log the URI just in case
            $data['estadisticaUrl'] = uri_string();

            // And write it to the database
            $this->db->insert('estadisticas', $data);
        }
    }


}