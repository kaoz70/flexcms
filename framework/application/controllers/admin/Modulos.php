<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modulos extends CI_Controller {

    var $theme = 'default';
	
	function __construct(){
		parent::__construct();
		$this->load->helper('text');
		$this->load->model('admin/module_model', 'Modulo');
		$this->load->model('admin/page_model', 'Pages');
		$this->load->model('idiomas_model', 'Idioma');
		$this->load->model('configuracion_model', 'Config');

		$this->load->model('article_model', 'Articles');
		$this->load->model('banners_model', 'Banners');
		$this->load->model('descargas_model', 'Gallery');
		$this->load->model('mapas_model', 'Maps');
		$this->load->model('admin/Catalogo_model', 'Catalogo');

        $config = $this->Config->get();

        $this->theme = $config->theme;
		
		$this->load->library('Seguridad');
		$this->seguridad->init();
		
	}
	
	public function index()
	{
        $modulos = $this->Modulo->tipos();

        $groups=array();
        foreach ($modulos as $modulo) {
            $groups[$modulo->moduloTipoGrupo][] = $modulo;
        }

        $data['modulos'] = $groups;

		$this->load->view('admin/modulos_view', $data);
	}
	
	public function addRow($pagina_id, $columns_amount)
	{

		$pagina = $this->Pages->getPage((int)$pagina_id);

		/*
		 * Estructura basica
		 */
		$fila = new stdClass();
		$fila->class = '';
		$fila->expanded = FALSE;

		$spans = new stdClass();
		$spans->large = 12;
		$spans->medium = 12;
		$spans->small = 12;

		$offset = new stdClass();
		$offset->large = 0;
		$offset->medium = 0;
		$offset->small = 0;

		$push = new stdClass();
		$push->large = 0;
		$push->medium = 0;
		$push->small = 0;

		$pull = new stdClass();
		$pull->large = 0;
		$pull->medium = 0;
		$pull->small = 0;

		$columna = new stdClass();
		$columna->class = '';
		$columna->span = $spans;
		$columna->offset = $offset;
		$columna->push = $push;
		$columna->pull = $pull;
		$columna->modules = array();

		for ($i = 0; $i < $columns_amount; $i++){
			$fila->columns[] = $columna;
		}

		//Does the page have the structure set? and is it valid?
		if($pagina->estructura = json_decode($pagina->estructura)) {
			$data['key'] = count($pagina->estructura);

			//Append the row
			$pagina->estructura[] = $fila;

		} else {
			$data['key'] = 0;

			//Add the row
			$pagina->estructura = array($fila);

		}

		//Save the structure to DB
		$this->Pages->save_structure($pagina_id, $pagina->estructura);

		$data['row'] = $fila;
		$data['page_id'] = $pagina_id;

		$this->load->view('admin/modulos/row_view', $data);
		
	}
	
	public function removeRow($page_id, $row_id)
	{

		$pagina = $this->Pages->getPage((int)$page_id);
		$pagina->estructura = json_decode($pagina->estructura);

		//Get all the row's modules and delete them
		$modules = array();
		foreach ($pagina->estructura[$row_id]->columns as $column) {
			$modules = array_merge($modules, $column->modules);
		}
		if($modules) {
			$this->db->where_in('moduloId', $modules)->delete('modulos');
		}

		unset($pagina->estructura[$row_id]);

		//Save the structure to DB
		$this->Pages->save_structure($page_id, array_values($pagina->estructura));

	}

    public function updateRow() {
        $rowId = $this->input->post('rowId');
        $spans = implode(',', $this->input->post('spans'));
        $this->Modulo->updateRow($rowId, $spans);
    }
	
	public function sortRows($page_id)
	{
		
		$from_index = $this->input->post('from_index');
		$to_index = $this->input->post('to_index');

		$pagina = $this->Pages->getPage((int)$page_id);
		$pagina->estructura = json_decode($pagina->estructura);

		//print_r($pagina->estructura);

		$row = $pagina->estructura[$from_index];

		unset($pagina->estructura[$from_index]); //remove the element
		array_splice($pagina->estructura, $to_index, 0, array($row)); //Place the a new array in the correct place of array

		//Remove any empty values
		foreach($pagina->estructura as $key=>$val){
			if(empty($val)) unset($pagina->estructura[$key]);
		}
		array_values($pagina->estructura);

		//Save the structure to DB
		$this->Pages->save_structure($page_id, array_values($pagina->estructura));

	}
	
	public function sortModules($page_id)
	{
		$orig = json_decode($this->input->post('orig'));
		$target = json_decode($this->input->post('target'));

		$page = $this->Pages->getPage((int)$page_id);
		$structure = json_decode($page->estructura);
		$structure[$orig->row_id]->columns[$orig->column_id]->modules = $orig->ids;
		$structure[$target->row_id]->columns[$target->column_id]->modules = $target->ids;
		$this->Pages->save_structure($page_id, $structure);

	}
	
	public function removeModule($page_id, $row_id, $column_id)
	{
		$moduleId = (int)$this->input->post('moduleId');

		//Get and save the structure
		$page = $this->Pages->getPage((int)$page_id);
		$structure = json_decode($page->estructura);
		foreach ($structure[$row_id]->columns[$column_id]->modules as $key => $module) {
			if($module === $moduleId) {
				unset($structure[$row_id]->columns[$column_id]->modules[$key]);
			}
		}
		$structure[$row_id]->columns[$column_id]->modules = array_values($structure[$row_id]->columns[$column_id]->modules); //re index
		$this->Pages->save_structure($page_id, $structure);

		$this->Modulo->removeModule($moduleId);

	}
	
	public function updateModule()
	{
		$moduleData = json_decode($this->input->post('moduleData'));
		$this->Modulo->updateModule($moduleData);
	}
	
	/*
	 * MODULE TYPES
	 */
	
	public function publicaciones($page_id, $row_id, $column_id)
	{
		$data['publicaciones'] = $this->Modulo->get_page_by_type(5);
		$data['moduleId'] = $this->Modulo->createModule($page_id, 1);
        $data['moduleImages'] = $this->Modulo->getImages(2);

		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/publicaciones/");

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('publicaciones_view', $data)
		));
		
	}

	public function catalogoCategoria($page_id, $row_id, $column_id)
	{
		$data['categorias'] = $this->Catalogo->getCategories();
		$data['moduleId'] = $this->Modulo->createModule($page_id,2);
        $data['moduleImages'] = $this->Modulo->getImages(5);

		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");

        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('catalogoCategorias_view', $data)
		));

	}
	
	public function catalogoProductosDestacados($page_id, $row_id, $column_id)
	{
		$data['categorias'] = $this->Catalogo->getCategories();

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");
		
		$data['moduleId'] = $this->Modulo->createModule($page_id, 10);
        $data['moduleImages'] = $this->Modulo->getImages(5);

		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('catalogoProductosDestacados_view', $data)
		));
	}

	public function catalogoMenu($page_id, $row_id, $column_id)
	{
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/menu/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 11);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);
        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('catalogoMenu_view', $data)
		));
	}
	
	public function html($page_id, $row_id, $column_id)
	{
		$data['moduleId'] = $this->Modulo->createModule($page_id, 3);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);
        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('html_view', $data)
		));
	}
	
	public function twitter($page_id, $row_id, $column_id)
	{

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/twitter/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 4);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);
		
        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('twitter_view', $data)
		));

	}
	
	public function facebook($page_id, $row_id, $column_id)
	{

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/facebook/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 5);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('facebook_view', $data)
		));

	}
	
	public function banner($page_id, $row_id, $column_id)
	{

		$data['banners'] = $this->Banners->getAll();
		$data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/banners/");
		$data['bannerType'] = 'bxSlider';
		$data['moduleId'] = $this->Modulo->createModule($page_id, 9);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('banner_view', $data)
		));

	}
	
	public function content($page_id, $row_id, $column_id)
	{

		$data['pageTypes'] = $this->Pages->getPageType();
		$data['paginas'] = $this->Pages->getPages();
		$data['moduleId'] = $this->Modulo->createModule($page_id, 8);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('content_view', $data)
		));

	}
	
	public function titulo($page_id, $row_id, $column_id)
	{

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/titulo/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 12);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);
		
        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('titulo_view', $data)
		));

	}
	
	public function faq($page_id, $row_id, $column_id)
	{
		$data['faq'] = $this->Modulo->get_page_by_type(2);
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/faq/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 13);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('faq_view', $data)
		));

	}
	
	public function enlaces($page_id, $row_id, $column_id)
	{
		$data['enlaces'] = $this->Modulo->get_page_by_type(10);
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/enlaces/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 14);
		$data['moduleImages'] = $this->Modulo->getImages(1);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('enlaces_view', $data)
		));

	}
	
	public function galeria($page_id, $row_id, $column_id)
	{
		$data['galeria'] = $this->Gallery->getCategories();
		$data['moduleId'] = $this->Modulo->createModule($page_id, 15);
        $data['moduleImages'] = $this->Modulo->getImages(8);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('galeria_view', $data)
		));

	}

	public function mapa($page_id, $row_id, $column_id)
	{
		$data['mapas'] = $this->Maps->getAll();
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/mapa/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 16);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('mapas_view', $data)
		));
		
	}

	public function catalogoFiltros($page_id, $row_id, $column_id)
	{

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/filtros/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 17);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('catalogoFiltros_view', $data)
		));
		
	}

    public function menu($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/menu/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 18);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('menu_view', $data)
		));

    }

    public function catalogoProductoAzar($page_id, $row_id, $column_id) {

        $data['categorias'] = $this->Catalogo->getCategories();
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 19);
        $data['moduleImages'] = $this->Modulo->getImages(5);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('catalogoProductoAzar_view', $data)
		));
    }

    public function contacto($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/contacto/formulario/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 20);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('contacto_view', $data)
		));

    }

    public function articulo($page_id, $row_id, $column_id)
    {
        $data['articulos'] = $this->Articles->all('es');
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/articulo/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 21);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('articulo_view', $data)
		));

    }

    public function servicios($page_id, $row_id, $column_id)
    {

		$data['servicios'] = $this->Modulo->get_page_by_type(12);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/servicios/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 22);
        $data['moduleImages'] = $this->Modulo->getImages(10);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('servicios_view', $data)
		));

    }

	public function breadcrumbs($page_id, $row_id, $column_id)
    {

        $data['moduleId'] = $this->Modulo->createModule($page_id, 23);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('breadcrumbs_view', $data)
		));

    }

    public function direcciones($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/contacto/direcciones/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 24);
        $data['moduleImages'] = $this->Modulo->getImages(14);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('direcciones_view', $data)
		));

    }

    public function publicidad($page_id, $row_id, $column_id)
    {

        $data['moduleId'] = $this->Modulo->createModule($page_id, 25);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('publicidad_view', $data)
		));

    }

    public function catalogoProductosDestacadosAzar($page_id, $row_id, $column_id)
    {
        $data['categorias'] = $this->Catalogo->getCategories();
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 26);
        $data['moduleImages'] = $this->Modulo->getImages(5);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('catalogoProductosDestacadosAzar_view', $data)
		));
    }

	public function serviciosDestacados($page_id, $row_id, $column_id)
	{

		$data['servicios'] = $this->Modulo->get_page_by_type(12);

		//Get the list of views that a module has
		$data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/servicios/");
		$data['moduleId'] = $this->Modulo->createModule($page_id, 27);
		$data['moduleImages'] = $this->Modulo->getImages(10);
		$this->add_module($page_id, $row_id, $column_id, $data['moduleId']);

		$this->load->view('admin/request/json', array(
			'return' => $this->get_module_html('serviciosDestacados_view', $data)
		));

	}

	/**
	 *
	 * Adds the new module id to the correct place
	 *
	 * @param $page_id
	 * @param $row_id
	 * @param $column_id
	 * @param $module_id
	 */
	private function add_module($page_id, $row_id, $column_id, $module_id)
	{
		//Get and save the structure
		$page = $this->Pages->getPage((int)$page_id);
		$structure = json_decode($page->estructura);
		$structure[$row_id]->columns[$column_id]->modules[] = $module_id;
		$this->Pages->save_structure($page_id, $structure);
	}

	/**
	 * Gets the module's basic html data
	 *
	 * @param $view
	 * @param array $data
	 * @return stdClass
	 */
	private function get_module_html($view, $data = array())
	{
		$moduleData = new stdClass();
		$moduleData->moduloId = $data['moduleId'];
		$moduleData->moduloNombre = '';
		$moduleData->moduloParam1 = '';
		$moduleData->moduloParam2 = '';
		$moduleData->moduloParam3 = '';
		$moduleData->moduloParam4 = '';
		$moduleData->moduloMostrarTitulo = 'checked="checked"';
		$moduleData->moduloClase = '';
		$moduleData->moduloVerPaginacion = 'checked="checked"';
		$moduleData->moduloHabilitado = 'checked="checked"';
		$moduleData->moduloVista = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->moduloNombre = '';
			$traducciones[$idioma['idiomaDiminutivo']]->moduloHtml = '';
		}

		$moduleData->traducciones = $traducciones;

		$data['moduleData'] = $moduleData;

		$return = new stdClass();
		$return->html = $this->load->view('admin/modulos/' . $view, $data, true);
		$return->id = $data['moduleId'];

		return $return;

	}
	
}
