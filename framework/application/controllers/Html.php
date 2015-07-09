<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Html extends CI_Controller {

    public $m_config;
    public $theme_config;
    public $m_idioma;
    public $m_currentPage;
    public $m_views_html;

    private $page;
    private $m_user;
    private $m_idioma_object;
    private $m_cache_time = 3000;
    public $m_breadcrumbs;

    function __construct(){

        parent::__construct();

        $this->load->model('admin/page_model', 'Page');
        $this->load->model('banners_model', 'Banners');
        $this->load->model('configuracion_model', 'Config');
        $this->load->model('estadisticas_model', 'Stats');
        $this->load->model('idiomas_model', 'Idiomas');
        $this->load->model('module_model', 'Modulos');
        $this->load->model('pedido_model', 'Pedido');
        $this->load->model('contact_model', 'Contact');
        $this->load->model('publicidad_model', 'Publicidad');
        $this->load->model('usuarios_model', 'Usuarios');

        $this->m_config = $this->Config->get();

        /*
         * Load these for the language menu
         */
        $this->load->model('catalogo_model', 'Catalog');
        $this->load->model('noticias_model', 'Noticias');
        $this->load->model('descargas_model', 'Downloads');
        $this->load->model('servicios_model', 'Servicios');

        /*
         * Custom Libraries
         */
        $this->load->library('CMS_Modules');
        $this->load->library('CMS_General');
        $this->load->library('CMS_Authenticate', array('config' => $this->m_config));
        $this->load->library('MyConsole');

        $this->load->helper('cache_expire');
        if (!is_cache_valid('cache_expire',$this->m_cache_time)){
            $this->db->cache_delete_all();
        }

        //Check if the theme's directory exists, if not use the default one and show an error message
        if ( ! file_exists(FCPATH . 'themes' . DIRECTORY_SEPARATOR . $this->m_config->theme)) {
            $this->session->set_flashdata(
                'error',
                '<div data-alert class="alert-box">La carpeta: "themes' . DIRECTORY_SEPARATOR . $this->m_config->theme . '" no existe para el tema: <strong>' . $this->m_config->theme . '</strong></br>Se va a utilizar la carpeta <strong>"themes ' . DIRECTORY_SEPARATOR . 'default"</strong> por ahora...</div>'
            );
            $this->m_config->theme = 'default';
        }

        //Set theme
        $this->theme_config = $this->load->set_theme($this->m_config->theme);

        $this->m_idioma_object = $this->Idiomas->check($this->uri->segment(1, 'es'));
        $this->m_idioma = $this->m_idioma_object->idiomaDiminutivo;

        $this->m_user = $this->ion_auth->user()->row();
        if($this->m_user) {
            $this->m_user->campos = $this->Usuarios->getCamposUser($this->m_user->id, $this->m_idioma);
        }

        // IMPORTANT! This global must be defined BEFORE the flexi cart library is loaded!
        // It is used as a global that is accessible via both models and both libraries, without it, flexi cart will not work.
        $this->flexi = new stdClass;

        // Load 'standard' and 'lite' flexi cart libraries.
        $this->load->library('flexi_cart');

        // Load cart data to be displayed via 'Mini Cart' menu.
        $this->mini_cart_data();

        $this->m_currentPage = urldecode($this->uri->segment(2, ''));

        //Set any date related configurations
        setlocale(LC_ALL, $this->m_idioma);
        //date_default_timezone_set('America/Guayaquil');

        //Load the language file for the Interface Translations
        $this->lang->load('ui', $this->m_idioma_object->idiomaNombre);
        $this->lang->load('errors', $this->m_idioma_object->idiomaNombre);

        //Checks if the site is offline
        $this->is_offline();

        //Load the cache driver with a fallback option
        $this->load->driver('cache', array(
            'adapter' => 'memcached',
            'backup' => 'file',
            'key_prefix' => url_title(base_url() . uri_string())
        ));

    }

    /**
     * Remove the need to call the page() method in the URL we can use / instead of html/page/
     */
    public function _remap()
    {

        //If the URL is a page
        if($this->uri->segment(1) AND $this->uri->segment(2)) {
            $this->page($this->uri->segment(2));
        }

        //If no URL, its the index page
        else {
            $this->index();
        }

    }

    /**
     * Index method, called when no URI segment is present, it guesses the users preferred language and redirects to
     * the home page in the correct language or defaults to spanish home page
     */
    private function index()
    {

        $idiomas = $this->Idiomas->getLanguages();

        if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {

            $languageArray = array();

            foreach ($idiomas as $value) {
                $languageArray[] = $value['idiomaDiminutivo'];
            }

            $this->m_idioma = $this->cms_general->prefered_language($languageArray);

        }

        else {
            $this->m_idioma = 'es';
        }

        $index = $this->Config->siteIndex($this->m_idioma);

        if($index) {
            $this->m_currentPage = $index->paginaNombreURL;
            $this->page($index->paginaNombreURL);
        }
        else
            show_error('Cree una p&aacute;gina y config&uacute;rela para que sea "p&aacute;gina inicial".');

    }

    /**
     * Main method where all the pages pass through,
     * it constructs the page with all the bits and pieces of info around
     *
     * @param $name
     * @throws \Gajus\Dindent\Exception\RuntimeException
     */
    private function page($name)
    {

        //Merge the Cart library's data with my data
        $data = $this->data;

        //Show 404 if we cant find the page
        if(! $page = $this->Page->getPage($name, $this->m_idioma)) {
            show_my_404(base_url($this->m_idioma.'/'.$name), $this->m_config->theme);
        }

        //Enable Profiler only in testing
        if((bool)$this->m_config->debug_bar) {
            $this->load->add_package_path(APPPATH.'third_party/codeigniter-forensics');
            $this->output->enable_profiler(TRUE);
        }

        $data['page'] = $this->page = $page;

        //Get any special pages
        $data['pagPedidos'] = $this->Modulos->getPageByType(9, $this->m_idioma);
        $data['pagAutenticacion'] = $this->Modulos->getPageByType(11, $this->m_idioma);

        //Enable full page cache only in production and if no special pages (auth, cart)
        if(ENVIRONMENT === 'production' AND ! $data['pagPedidos'] AND ! $data['pagAutenticacion']){
            $this->output->cache($this->m_cache_time);
        }

        $data['pagina_url'] = $name;
        $data['config'] = $this->m_config;
        $data['usuario'] = $this->m_user;
        $data['menu_idiomas'] = array();
        $data['diminutivo'] = $this->m_idioma;
        $data['nombre_sitio'] = $this->m_config->site_name;
        $data['theme'] = $this->m_config->theme;
        $data['theme_asset'] = base_url('themes/' . $this->m_config->theme);
        $data['direcciones'] = $this->Contact->getDirecciones($this->m_idioma);

        //Meta tags
        $data['meta_description'] = $page->paginaDescripcion;
        $data['meta_keywords'] = implode(',',array_map('trim', explode(',', $page->paginaKeywords))); //Format the keywords (remove any whitespaces)

        //OpenGraph data
        $data['og_image'] = '';
        $data['og_title'] = '';
        $data['og_description'] = '';

        //Get any popup "publicity" banners on the page
        $data['popup_banner'] = $this->popup_banners($page);

        //Get all the banner assets
        $banner_assets = $this->get_banner_assets();
        $data['assets_banner_js'] = $banner_assets->js;
        $data['assets_banner_css'] = $banner_assets->css;
        $data['assets_banner_cdn'] = $banner_assets->cdn;

        $data['error_message'] = $this->session->flashdata('error');

        //Generate the page structure
        $data = $this->generate_structure($page, $data);

        //Create the SEO friendly title
        $data['titulo'] = $this->create_page_title($page, $data);
        $data['nombre_sitio'] = $this->m_config->site_name;
        $data['footermenu'] = $this->Page->getPages($this->m_idioma);

        $data['loggedIn'] = $this->ion_auth->logged_in();

        //Get the number of items in the Cart
        $cart_data = $this->flexi_cart->cart_array();
        $data['cantPedidos'] = count($cart_data['items']);

        //Stats
        $stats = $this->Stats->getTotalVisits();
        $data['visitas'] = $stats->hits_total;

        //Add the page class
        $page->paginaClase ? $data['clase'] = 'class="' . $page->paginaClase . '"' : $data['clase'] = '';

        //Creamos el menu principal
        $data['menu'] = $this->cms_modules->createMenu($this->m_idioma);

        //Creamos el menu de idioma
        $data['menu_idiomas'] = $this->create_language_menu($page);

        Console::log_memory($data, 'Data');

        /**************************************************************************************************************
         * Print all the views and the HTML after all the logic
         *************************************************************************************************************/

        if( ! $page->paginaEsPopup OR ! $this->input->is_ajax_request()) {
            $this->benchmark->mark('main_view_start');
            $html = $this->load->view('main_view', $data, TRUE);
            $this->benchmark->mark('main_view_end');
        } else {
            $html = $this->m_views_html;
        }

        //Indent the code in development for better readability
        if(ENVIRONMENT === 'development') {
            ini_set('memory_limit', '-1'); //Disable the memory limit on development, no worries here
            $indenter = new \Gajus\Dindent\Indenter();
            $html = $indenter->indent($html); //Indent all the HTML
        }

        if(ENVIRONMENT === 'production') {

            //Start generating stats in production
            $this->log_activity($page->paginaId);

            //Minify HTML
            $html = Minify_HTML::minify($html, array(
                'cssMinifier' => array('Minify_CSS', 'minify'),
                'jsMinifier' => array('JSMin', 'minify')
            ));

        }

        Console::log_memory($html, 'Html');

        //Finally print the HTML
        $this->output->set_output($html);

    }

    /**
     * Get any popup "publicity" banners on the page
     * @param $page
     * @return stdClass|string
     */
    private function popup_banners($page)
    {
        $popup_banner = $this->Publicidad->getByPage($page->paginaId);
        $popup = '';

        if($popup_banner){
            $moduleData['archivo'] = $popup_banner->publicidadArchivo1;
            $popup = new stdClass();
            $popup->id = $popup_banner->publicidadId;
            $popup->html = trim(preg_replace('/\s+/', ' ', $this->cms_modules->loadView($popup_banner->publicidadArchivo1, $moduleData)));
        }

        return $popup;

    }

    /**
     * Generate the page's title
     * @param $page
     * @return string
     */
    private function create_page_title($page, $data)
    {

        //Add an identifier to the title if in development
        $environment = '';
        if(ENVIRONMENT !== 'production') {
            $environment = '[' . strtoupper(ENVIRONMENT) . '] ';
        }

        $title = $environment . $this->m_config->site_name . ' | '; //Site name
        $title .= $this->m_breadcrumbs['page']['names']['nombre']; //Breadcrumb slug
        if( ! isset($data['meta_title']) && $page->paginaTitulo) {
            $title .= ' - ';
            $title .= $page->paginaTitulo; //Site meta title
        } else if(isset($data['meta_title'])){
            $title .= ' - ';
            $title .= $data['meta_title']; //Product / Services meta title
        }

        return $title;

    }

    /**
     * Get all the necessary banner assets
     * @return stdClass
     */
    private function get_banner_assets()
    {

        $assets = new stdClass();
        $assets->css = array();
        $assets->js = array();
        $assets->cdn = array();
        $assets->generated = array();
        $banners_path = FCPATH . 'assets/public/banners/';

        $this->benchmark->mark('banners_start');

        //Get all the banners
        $banners = $this->Banners->getAll(FALSE);
        $bannerTypes = array();

        foreach ($banners as $key => $banner) {

            //Get the asset paths form the config file
            $data =  json_decode(file_get_contents($banners_path . $banner['bannerType'] . '/config.json'));

            //Get development or production assets, depending on site's environment status
            if(ENVIRONMENT === 'development') {
                $b_assets = $data->assets->dev;
            } else {
                $b_assets = $data->assets->prod;
            }

            //Check if the assets haven't already been loaded
            if( ! array_key_exists($banner['bannerType'], $bannerTypes)) {

                //Add the CSS asset path
                foreach ($b_assets->css as $path) {
                    $assets->css[] = $path;
                }
                //Add the JS asset path
                foreach ($b_assets->js as $path) {
                    $assets->js[] = $path;
                }

                //Fixes if there are multiple banners of the same type.
                $bannerTypes[$banner['bannerType']] = 1;
            }

        }

        $assets->js = array_merge($assets->js, $assets->generated);

        $this->benchmark->mark('banners_end');

        return $assets;

    }

    /**
     * Generates the page structure (Rows, Columns, Modules)
     * @param $page
     * @param $data
     * @return mixed
     */
    private function generate_structure($page, $data)
    {

        if(property_exists($this->theme_config, 'framework') AND $this->theme_config->framework === 'bootstrap') {

            $spans['large'] = 'col-md-';
            $spans['medium'] = 'col-sm-';
            $spans['small'] = 'col-xs-';

            $offset['large'] = 'col-md-offset-';
            $offset['medium'] = 'col-sm-offset-';
            $offset['small'] = 'col-xs-offset-';

            $pull['large'] = 'col-md-pull-';
            $pull['medium'] = 'col-sm-pull-';
            $pull['small'] = 'col-xs-pull-';

            $push['large'] = 'col-md-push-';
            $push['medium'] = 'col-sm-push-';
            $push['small'] = 'col-xs-push-';

        } else {

            $spans['large'] = 'large-';
            $spans['medium'] = 'medium-';
            $spans['small'] = 'small-';

            $offset['large'] = 'large-offset-';
            $offset['medium'] = 'medium-offset-';
            $offset['small'] = 'small-offset-';

            $pull['large'] = 'large-pull-';
            $pull['medium'] = 'medium-pull-';
            $pull['small'] = 'small-pull-';

            $push['large'] = 'large-push-';
            $push['medium'] = 'medium-push-';
            $push['small'] = 'small-push-';

        }

        //Check if modules are cached
        if ( ! $modules = $this->cache->get('modules'))
        {
            $modules = $this->Modulos->getPageModules($page->paginaId, $this->m_idioma);
            if(ENVIRONMENT !== 'development') {
                $this->cache->save('modules', $modules, $this->m_cache_time);
            }
        }

        $modules_exist = FALSE;

        $this->m_breadcrumbs = $this->paths($page, $this->m_idioma);

        //Check and see if the current user has access to the page, or the page is public
        if(
            $this->ion_auth->in_group($page->name) ||
            $page->name == 'public' ||
            $this->ion_auth->in_group('admin') ||
            $this->ion_auth->in_group('superadmin')
        ){
            $this->benchmark->mark('modules_start');

            //Check if Html is cached
            if ( ! $this->m_views_html = $this->cache->get('m_views_html'))
            {

                if(!$page->estructura) {
                    return $data;
                }

                $rows = json_decode($page->estructura);

                //Creates the rows
                foreach ($rows as $key => $row)
                {

                    $rowData['class'] = $row->class;
                    $rowData['expandida'] = $row->expanded;
                    $rowData['position'] = $key + 1;
                    $rowData['quantity'] = count($rows);
                    $this->m_views_html .= $this->load->view('modulos/row_header_view', $rowData, true);

                    //Creates the columns (spans are the column's width)
                    foreach ($row->columns as $col_key => $col)
                    {

                        $columnData['position'] = $col_key + 1;
                        $columnData['quantity'] = count($row->columns);
                        $columnData['class'] = $col->class;

                        //Some Foundation responsive classes
                        $foundation = $col->span->large ? ' ' . $spans['large'] . $col->span->large : '';
                        $foundation .= $col->span->medium ? ' ' . $spans['medium'] . $col->span->medium : '';
                        $foundation .= $col->span->small ? ' ' . $spans['small'] . $col->span->small : '';

                        $foundation .= $col->offset->large ? ' ' . $offset['large'] . $col->offset->large : '';
                        $foundation .= $col->offset->medium ?  ' ' . $offset['medium'] . $col->offset->medium : '';
                        $foundation .= $col->offset->small ? ' ' . $offset['small'] . $col->offset->small : '';

                        $foundation .= $col->pull->large ? ' ' . $pull['large'] . $col->pull->large : '';
                        $foundation .= $col->pull->medium ? ' ' . $pull['medium'] . $col->pull->medium : '';
                        $foundation .= $col->pull->small ? ' ' . $pull['small'] . $col->pull->small : '';

                        $foundation .= $col->push->large ? ' ' . $push['large'] . $col->push->large : '';
                        $foundation .= $col->push->medium ? ' ' . $push['medium'] . $col->push->medium : '';
                        $foundation .= $col->push->small ?' ' . $push['small'] . $col->push->small : '';

                        $columnData['foundation'] = $foundation;

                        $this->m_views_html .= $this->load->view('modulos/column_header_view', $columnData, true);

                        foreach ($col->modules as $module_id)
                        {

                            foreach ($modules as $mod_key => $module) {
                                if ($module_id === (int)$module->moduloId) {
                                    $m_value = $module;
                                    unset($modules[$mod_key]); //remove that module so that we don't have to loop over it again
                                    break;
                                }
                            }

                            //Module was probably deleted or disabled
                            if(!isset($m_value) OR ! $m_value->moduloHabilitado) {
                                continue;
                            }

                            $modules_exist = TRUE;

                            //Creates the Modules
                            $moduleData['titulo'] = $m_value->moduloNombre;
                            $moduleData['diminutivo'] = $this->m_idioma;
                            $moduleData['moduloClase'] = $m_value->moduloClase;

                            switch ($m_value->paginaModuloTipoId)
                            {
                                case 1:
                                    $this->m_views_html .= $this->cms_modules->publicaciones($page, $m_value, $moduleData, $this->m_idioma, $data);
                                    break;
                                case 2:
                                    $this->m_views_html .= $this->cms_modules->productosCategoria($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;
                                case 3:
                                    $this->m_views_html .= $this->cms_modules->html($m_value, $moduleData, $this->m_idioma, $data);
                                    break;
                                case 4:
                                    $this->m_views_html .= $this->cms_modules->twitter($m_value, $moduleData, $this->m_idioma, $data);
                                    break;
                                case 5:
                                    $this->m_views_html .= $this->cms_modules->facebook($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                //CONTENT
                                case 8:
                                    $data = $this->render_page($m_value, $page, $data);
                                    break;

                                case 9:
                                    $this->m_views_html .= $this->cms_modules->banner($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 10:
                                    $this->m_views_html .= $this->cms_modules->productosDestacados($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;

                                case 11:
                                    $this->m_views_html .= $this->cms_modules->productosMenu($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;

                                case 12:
                                    $this->m_views_html .= $this->cms_modules->titulo($m_value, $moduleData, $page, $data);
                                    break;

                                case 13:
                                    $this->m_views_html .= $this->cms_modules->faq($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;

                                case 14:
                                    $this->m_views_html .= $this->cms_modules->enlaces($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;

                                case 15:
                                    $this->m_views_html .= $this->cms_modules->galeria($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;

                                case 16:
                                    $this->m_views_html .= $this->cms_modules->mapa($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 17:
                                    $this->m_views_html .= $this->cms_modules->productoFiltros($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 18:
                                    $this->m_views_html .= $this->cms_modules->menu($m_value, $moduleData, $this->m_idioma, $this->m_currentPage, $data);
                                    break;

                                case 19:
                                    $this->m_views_html .= $this->cms_modules->productosAzar($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 20:
                                    $this->m_views_html .= $this->cms_modules->contacto($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 21:
                                    $this->m_views_html .= $this->cms_modules->articulo($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 22:
                                    $this->m_views_html .= $this->cms_modules->servicios($m_value, $moduleData, $this->m_idioma);
                                    break;

                                case 23:
                                    $this->m_views_html .= $this->cms_modules->breadcrumbs($m_value, $page, $this->m_idioma, $this->m_breadcrumbs);
                                    break;

                                case 24:
                                    $this->m_views_html .= $this->cms_modules->direcciones($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 25:
                                    $this->m_views_html .= $this->cms_modules->publicidad($m_value, $moduleData, $this->m_idioma, $data);
                                    break;

                                case 26:
                                    $this->m_views_html .= $this->cms_modules->productosDestacadosAzar($m_value, $moduleData, $this->m_idioma);
                                    break;

                                case 27:
                                    $this->m_views_html .= $this->cms_modules->serviciosDestacados($m_value, $moduleData, $this->m_idioma);
                                    break;

                                default:
                                    $this->m_views_html .= '[cree una vista para este m&oacute;dulo' . ($moduleData['titulo'] ? ': ' . $moduleData['titulo'] : '') . ']';
                                    break;
                            }

                        }

                        $this->m_views_html .= $this->load->view('modulos/column_footer_view', '', true);

                    }

                    $this->m_views_html .= $this->load->view('modulos/row_footer_view', $rowData, true);

                }
                if(ENVIRONMENT !== 'development' AND
                    $this->m_currentPage !== $data['pagAutenticacion']->paginaNombreURL AND
                    $this->m_currentPage !== $data['pagAutenticacion']->paginaNombreURL
                ){
                    $this->cache->save('m_views_html', $this->m_views_html, $this->m_cache_time);
                }
            }

            $this->benchmark->mark('modules_end');
        }

        //User needs to be logged in to view the page or doesn't have enough access rights
        else {

            //User is logged in but doesn't have enough access rights
            if($this->ion_auth->logged_in()) {
                $data['mensaje'] = 'No tiene los privilegios suficientes para ver esta página';
                $this->m_views_html .=$this->load->view('paginas/autenticacion/no_privilegios_view', $data, true);
            }

            //Show the login page
            else {
                $rowData['class'] = '';
                $rowData['expandida'] = FALSE;
                $rowData['position'] = 1;
                $rowData['quantity'] = 1;

                $columnData['position'] = 1;
                $columnData['quantity'] = 1;
                $columnData['span_large'] = 12;
                $columnData['span_medium'] = 12;
                $columnData['span_small'] = 12;

                $this->m_views_html .= $this->load->view('modulos/row_header_view', $rowData, true);
                $this->m_views_html .= $this->load->view('modulos/column_header_view', $columnData, true);
                $this->m_views_html .= $this->cms_authenticate->renderLoginView($data, $this->m_idioma, $data['pagAutenticacion']->paginaNombreURL, '');
                $this->m_views_html .= $this->load->view('modulos/column_footer_view', '', true);
                $this->m_views_html .= $this->load->view('modulos/row_footer_view', $rowData, true);
            }

        }

        if(!$modules_exist) {
            return $data;
        }

        return $data;

    }

    /**
     * Renders the main page content
     * @param $module
     * @param $page
     * @param $data
     * @return mixed
     */
    private function render_page($module, $page, $data)
    {

        $data['moduleClass'] = $module->moduloClase;

        //Check and see what setcion is the page rendering
        switch($module->moduloParam1)
        {
            case 1:
                $this->load->library('CMS_Article');
                $this->m_views_html .= $this->cms_article->create($page->paginaId, $data, $this->m_idioma);
                break;

            case 2:
                $this->load->library('CMS_Faq');
                $this->m_views_html .= $this->cms_faq->create($data, $this->m_idioma);
                break;

            case 3:
                $this->load->library('CMS_Contact');
                $this->m_views_html .= $this->cms_contact->create($data, $this->m_idioma);
                break;

            case 4:
                $this->load->library('CMS_Catalog');
                $catData = $this->cms_catalog->create($page, $data, $this->m_idioma, $this->m_currentPage, $module);
                $data = $catData->data;
                $this->m_views_html .= $catData->html;
                break;

            case 5:
                $this->load->library('CMS_Publications');
                $return_data = $this->cms_publications->create($page->paginaId, $data, $module, $this->m_idioma);
                $data['og_image'] = $return_data->og_image;
                $data['og_title'] = $data['meta_title'] = $return_data->og_title;
                $data['og_description'] = $data['description'] = strip_tags($return_data->og_description);
                $this->m_views_html .= $return_data->html;
                break;

            case 6:
                $this->load->library('CMS_Gallery');
                $this->m_views_html .= $this->cms_gallery->create($page->paginaId, $data, $this->m_idioma);
                break;

            case 7:
                $this->load->library('CMS_Redirect');
                $this->cms_redirect->create($module, $this->m_idioma);
                break;

            case 8:
                $this->load->library('CMS_Sitemap');
                $this->m_views_html .= $this->cms_sitemap->create($data, $this->m_idioma, $this->m_currentPage);
                break;

            case 9:
                $this->load->library('CMS_Shoppingcart');
                // Deletes cache for the currently requested URI, we don't need to cache this
                $this->output->delete_cache();
                $this->output->cache(0);
                $this->m_views_html .= $this->cms_shoppingcart->create($data, $this->m_idioma);
                break;

            case 10:
                $this->load->library('CMS_Links');
                $this->m_views_html .= $this->cms_links->create($page->paginaId, $data, $this->m_idioma);
                break;

            case 11:
                // Deletes cache for the currently requested URI, we don't need to cache this
                $this->output->delete_cache();
                $this->output->cache(0);
                $this->m_views_html .= $this->cms_authenticate->create($data, $this->m_idioma, $this->m_currentPage);
                break;

            case 12:
                $this->load->library('CMS_Services');
                $return_data = $this->cms_services->create($page->paginaId, $data, $this->m_idioma, $module);
                $data = $return_data->data;
                $this->m_views_html .= $return_data->html;
                break;

            case 13:
                $this->load->library('CMS_Calendar');
                $this->m_views_html .= $this->cms_calendar->create($page->paginaId, $data, $this->m_idioma);
                break;

        }

        return $data;

    }

    /**
     * Creates the language menu with the correct links according to the current link
     * @param $page
     * @return array
     */
    private function create_language_menu($page)
    {
        $idiomas = $this->Idiomas->getLanguages();
        $menuArr = array();

        if(count($idiomas) > 1)
        {
            $pageNames = $this->Idiomas->getPageTranslations($page->paginaId);

            $param1 = $this->uri->segment(3);
            $param2 = $this->uri->segment(4);
            $param3 = $this->uri->segment(5);

            $diminutivos = array();
            foreach ($idiomas as $diminutivo => $value) {
                $diminutivos[$value['idiomaDiminutivo']] = $value['idiomaNombre'];
            }

            $page_type = $this->Modulos->getPageType($page->paginaId, $this->m_idioma);

            foreach($pageNames as $diminutivo => $pagina)
            {

                if($page_type) {
                    switch($page_type->moduloParam1)
                    {
                        case 1:
                            //Article
                            break;

                        case 2:
                            //Faq
                            break;

                        case 3:
                            //Contact
                            break;

                        case 4: //Catalog

                            $categoria = null;
                            $producto = null;

                            if($this->uri->segment(4)){
                                $categoria = $this->Catalog->getCategoryTranslation($this->uri->segment(4), $this->m_idioma, $diminutivo);
                                if($categoria) {
                                    $param1 = $categoria->id;
                                    $param2 = $this->uri->segment(4);
                                }
                            }

                            if($this->uri->segment(5)) {
                                $producto = $this->Catalog->getProductTranslation($this->uri->segment(5), $this->m_idioma, $diminutivo);
                                if($producto)
                                    $param3 = $producto->productoUrl;
                            }

                            //if(!$categoria AND !$producto)
                            //break 2;

                            break;

                        case 5: //Publications

                            $publicacion = null;


                            if($this->uri->segment(3) && !(int)$this->uri->segment(3)){
                                $publicacion = $this->Noticias->getTranslation($this->uri->segment(3), $this->m_idioma, $diminutivo);
                                if($publicacion)
                                    $param1 = $publicacion->publicacionUrl;
                            }

                            if(!$publicacion && $this->uri->segment(3))
                                break 2;

                            break;

                        case 6: //Gallery

                            $categoria = null;

                            if($this->uri->segment(3)){
                                $categoria = $this->Downloads->getCategoryTranslation($this->uri->segment(3), $this->m_idioma, $diminutivo);
                                if($categoria)
                                    $param1 = $categoria->descargaCategoriaUrl;
                            }

                            //if(!$categoria && $this->uri->segment(3))
                                //break 2;

                            break;

                        case 7:
                            //Redirect
                            break;

                        case 8:
                            //Sitemap
                            break;

                        case 9:
                            //ShoppingCart
                            break;

                        case 10:
                            //Links
                            break;

                        case 11:
                            //Authenticate
                            break;

                        case 12: //Services

                            $servicio = null;

                            if($this->uri->segment(3)){
                                $servicio = $this->Servicios->getTranslation($this->m_idioma, $this->uri->segment(3));
                                if($servicio)
                                    $param1 = $servicio->servicioUrl;
                            }

                            if(!$servicio && $this->uri->segment(3))
                                break 2;

                            break;

                    }
                }

                $params = [$param1, $param2, $param3];

                if($diminutivo == $this->m_idioma)
                    $activo = 'class="active"';
                else
                    $activo = '';

                $nombre = $diminutivos[$diminutivo];

                $array = array(
                    "link" => base_url() . $diminutivo . '/' . $pagina . '/' . implode('/', array_filter($params)),
                    "label" => $nombre,
                    "diminutivo" => $diminutivo,
                    "activo" => $activo
                );

                array_push($menuArr, $array);
            }
        }

        return $menuArr;

    }

    /**
     * Log each hit to the database, this serves the site's stats
     * @param int $pageId
     * @return bool
     */
    private function log_activity($pageId = 0) {

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

    /**
     * Method that generates the breadcrumb data
     * @param $page
     * @param $lang
     * @return array
     */
    private function paths($page, $lang)
    {

        //Check if paths are cached
        if ( ! $paths = $this->cache->get('paths'))
        {

            //Initialize the structured var so that we don't get any errors anywhere else
            $paths = array(
                'page' => array(
                    'nodes' => []
                ),
                'catalog' => array(
                    'nodes' => [],
                    'path' => [],
                    'item' => ''
                ),
                'gallery' => array(
                    'nodes' => [],
                    'path' => []
                ),
                'publications' => array(
                    'item' => ''
                ),
                'services' => array(
                    'item' => ''
                ),
            );

            /*--------------------------------------------------------------------------------------------------------------
             * Get PAGE path
             */
            $active_node = PageTree::find($page->paginaId);

            $path = [];
            $path[] = $active_node->id;

            //TODO: $active_node->getPath() is returning wrong path order
            //$path = $active_node->getPath();
            $root_nodes = PageTree::allRoot()->toArray();

            $root_nodes_ids = [];
            foreach ($root_nodes as $node) {
                $root_nodes_ids[] = $node['id'];
            }
            while (!in_array($active_node->id, $root_nodes_ids) && $active_node = $active_node->getParent()){
                $path[] = $active_node->id;
            }

            $nodes = PageTree::whereIn('id', $path)
                ->join("{$lang}_paginas", "{$lang}_paginas.paginaId", '=', 'paginas.id')
                ->get();

            $names = array(
                'nombre' => $page->paginaNombreMenu,
                'titulo' => $page->paginaTitulo,
            );

            $paths['page'] = compact('nodes', 'names', 'path');

            /*--------------------------------------------------------------------------------------------------------------
             * Get CATALOG path
             */
            $path = [];
            $nodes = [];
            $catalog_page = $this->Modulos->getPageByType(4, $lang);
            $item = '';

            //Check to see if catalog page exists and if its the current page
            if($catalog_page && $page->paginaId === $catalog_page->paginaId)
            {

                $category_id = $this->uri->segment(3);
                $prodName = $this->uri->segment(5);

                //We are in a product, no pagination
                if($prodName && !(int)$prodName) {

                    $producto = $this->Catalog->getProduct($prodName, $lang);

                    if($producto) {

                        $categoria = $this->Catalog->getCategoryById($category_id, $lang);
                        $active_node = CatalogTree::find($categoria->id);
                        $path[] = $active_node->id;

                        //TODO: $active_node->getPath() is returning wrong path order
                        //$path = $active_node->getPath();
                        while ($active_node->id !== 1 && $active_node = $active_node->getParent()){
                            $path[] = $active_node->id;
                        }

                        $names = array(
                            'nombre' => $categoria->productoCategoriaNombre . ' - ' . $producto->productoNombre,
                        );

                        $item = $producto->productoNombre;

                    }

                }

                //We are in a category
                else if ($category_id) {

                    $categoria = $this->Catalog->getCategoryById($category_id, $lang);

                    if($categoria){

                        $active_node = CatalogTree::find($categoria->id);
                        $path[] = $active_node->id;

                        //TODO: $active_node->getPath() is returning wrong path order
                        //$path = $active_node->getPath();
                        while ($active_node->id !== 1 && $active_node = $active_node->getParent()){
                            $path[] = $active_node->id;
                        }

                        $names = array(
                            'nombre' => $categoria->productoCategoriaNombre,
                        );

                    }

                }

                if($path){
                    $nodes = CatalogTree::whereIn('id', $path)
                        ->join("{$lang}_producto_categorias", "{$lang}_producto_categorias.productoCategoriaId", '=', 'producto_categorias.id')
                        ->get();
                }

                $paths['catalog'] = compact('nodes', 'names', 'path', 'item');

            }

            /*--------------------------------------------------------------------------------------------------------------
            * Get GALLERY path
            */
            $path = [];
            $gallery_page = $this->Modulos->getPageByType(6, $lang);

            if($gallery_page && $page->paginaId == $gallery_page->paginaId)
            {
                $category_id = $this->uri->segment(4);

                if($category_id){
                    $categoria = $this->Descargas->getCategory($category_id, $lang);

                    if($categoria) {

                        $active_node = GalleryTree::find($categoria['id']);
                        $path[] = $active_node->id;

                        //TODO: $active_node->getPath() is returning wrong path order
                        //$path = $active_node->getPath();
                        while ($active_node->id !== 1 && $active_node = $active_node->getParent()){
                            $path[] = $active_node->id;
                        }

                        $names = array(
                            'nombre' => $categoria['descargaCategoriaNombre'],
                        );

                        $nodes = GalleryTree::whereIn('id', $path)
                            ->join("{$lang}_descargas_categorias", "{$lang}_descargas_categorias.descargaCategoriaId", '=', 'descargas_categorias.id')
                            ->get();

                        $paths['gallery'] = compact('nodes', 'names', 'path');

                    }

                }

            }

            /*--------------------------------------------------------------------------------------------------------------
            * Get PUBLICACIONES path
            */
            $publication_page = $this->Modulos->getPageByType(5, $lang);

            if($publication_page && $page->paginaId === $publication_page->paginaId)
            {
                $pubName = $this->uri->segment(3, 0);
                $pagina = (int) $pubName;

                if($pubName && !$pagina){
                    $publicacion = $this->Noticias->get($pubName, $lang);

                    if($publicacion){
                        $paths['publications']['item'] = $publicacion->publicacionNombre;
                    }

                }

            }

            /*--------------------------------------------------------------------------------------------------------------
            * Get SERVICIOS path
            */
            $service_page = $this->Modulos->getPageByType(12, $lang);

            if($service_page && $page->paginaId === $service_page->paginaId)
            {
                $servName = $this->uri->segment(3, 0);

                if($servName){
                    $servicio = $this->Servicios->get($servName, $lang);

                    if($servicio){
                        $paths['services']['item'] = $servicio->servicioTitulo;
                    }

                }

            }

            if(ENVIRONMENT !== 'development') {
                $this->cache->save('paths', $paths, $this->m_cache_time);
            }

        }

        return $paths;

    }

    /**
     * Checks if the site is offline and stops the Controller from executing
     */
    private function is_offline(){
        if(ENVIRONMENT === 'offline') {
            $this->lang->load('errors', $this->m_idioma_object->idiomaNombre);
            set_status_header(503);
            $data['theme_path'] = base_url('themes/' . $this->m_config->theme);
            $this->load->view('paginas/offline_view', $data);
            $this->output->_display();
            exit;
        }
    }

    /**
     * mini_cart_data
     * This function is called by the '__construct()' to set item data to be displayed on the 'Mini Cart' menu.
     */
    private function mini_cart_data()
    {
        $this->data['mini_cart_items'] = $this->flexi_cart->cart_items();
    }

}