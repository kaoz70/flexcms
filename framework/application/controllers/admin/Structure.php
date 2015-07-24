<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Structure extends MY_Controller implements AdminInterface {
	
	var $txt_boton = '';
	var $pagina_info = array();
	var $link;
	var $mptt;

	function __construct(){

		parent::__construct();
		$this->load->helper('text');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('admin/module_model', 'Modulo');
		$this->load->model('article_model', 'Articles');
		$this->load->model('banners_model', 'Banners');
		$this->load->model('descargas_model', 'Gallery');
		$this->load->model('mapas_model', 'Maps');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('admin/Catalogo_model', 'Catalogo');
		$this->load->model('idiomas_model', 'Idioma');
		
		$this->load->library('ion_auth');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

        $config = $this->Config->get();
        $this->theme = $config->theme;

		$this->seguridad->init();
		
	}
	
	public function index()
	{	

		$root = PageTree::allRoot()->first();
		$root->findChildren(999);

		$depth = 0;
		foreach (PageTree::allLeaf() as $leaf) {
			if($depth < $leaf->getDepth()) {
				$depth = $leaf->getDepth();
			}
		}

		$data['root_node'] = $root;
		$data['tree_size'] = $depth;

		$data['titulo'] = 'Estructura';
		$data['id'] = 'pagina_tree';

		$data['url_reorganizar'] = base_url('admin/structure/reorder');
		$data['url_rel'] = base_url('admin/structure');

		$data['edit_url'] = base_url('admin/structure/edit');
		$data['delete_url'] = base_url('admin/structure/delete');
		$data['name'] = 'paginaNombre';

		$data['nivel'] = 'nivel2';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/structure/create'), 'crear nueva página', $atts);

        $data['bottomMargin'] = count($data['menu']) * 34;
		
		$this->load->view('admin/listadoArbol_view', $data);
	}
	
	public function create()
	{

        $pagina = $this->insert();

        $data['paginaId'] = $pagina->insert_id;
		$data['paginaEsPopup'] = '';
		$data['paginaVisiblePara'] = 4;
        $data['paginaEnabled'] = 'checked="checked"';
		$data['groups'] =  $this->ion_auth->groups()->result();
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/structure/delete/'.$pagina->insert_id);
        $data['theme'] = $this->theme;
        $data['paginas'] = $this->Paginas->getPages();

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->paginaNombre = '';
			$traducciones[$idioma['idiomaDiminutivo']]->paginaNombreMenu = '';
			$traducciones[$idioma['idiomaDiminutivo']]->paginaKeywords = '';
			$traducciones[$idioma['idiomaDiminutivo']]->paginaDescripcion = '';
			$traducciones[$idioma['idiomaDiminutivo']]->paginaTitulo = '';
		}
		
		$data['traducciones'] = $traducciones;
		
		$data['estructura'] = array();
		$data['titulo'] = "Crear Pagina";
        $data['link'] = base_url("admin/structure/update/" . $pagina->insert_id);
		$data['txt_boton'] = "crear";

        $data['pagina_info'] = new stdClass();
		$data['pagina_info']->pagTitulo = "";
		$data['pagina_info']->paginaClase = "";
		$data['pagina_info']->pagina_tipoId = 0;
		
		$this->load->view('admin/paginaCrear_view', $data);
	}

	public function insert()
	{
		return $this->Paginas->addPage();
	}
	
	public function edit($id)
	{

		$page = $this->Paginas->getPage((int)$id);
		
		$data['idiomas'] = $this->Paginas->idiomas();
		$data['pagina_tipo'] = $this->Paginas->getPageType(); //Todos los tipos de pagina
		$data['pagina_info'] = $page;
		$data['paginaInicial'] = '';
		$data['paginaEsPopup'] = '';
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
        $data['theme'] = $this->theme;
		$data['paginas'] = $this->Paginas->getPages();

        $data['paginaEnabled'] = '';

        if($page->paginaEnabled == 1)
            $data['paginaEnabled'] = 'checked="checked"';
		
		$data['paginaVisiblePara'] = $page->paginaVisiblePara;
		$data['groups'] =  $this->ion_auth->groups()->result();
		
		if($page->paginaEsPopup == 1)
			$data['paginaEsPopup'] = 'checked="checked"';
		
		$data['titulo'] = "Modificar Pagina";
		$data['txt_boton'] = "modificar";
		$data['link'] = base_url("admin/structure/update/" . $page->paginaId);
		$data['paginaId'] = $page->paginaId;

        /*
         * TODO fix the need to pass the models to the view
         */
        $data['pagina_model'] = $this->Paginas;
        $data['modulo_model'] = $this->Modulo;
        $data['catalogo_model'] = $this->Catalogo;
        $data['article_model'] = $this->Articles;
        $data['banner_model'] = $this->Banners;
        $data['gallery_model'] = $this->Gallery;
        $data['mapas_model'] = $this->Maps;

		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();
		
		$traducciones = array();
		
		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = $this->Paginas->getPageTranslation($idioma['idiomaDiminutivo'], $id);
		}
		
		$data['traducciones'] = $traducciones;
		$data['estructura'] = json_decode($page->estructura) ? : array();

		$this->load->view('admin/paginaCrear_view', $data);
	}
	
	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Paginas->updatePage($this->cms_general);
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la p&aacute;ina!', $e);
	}
	
		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$page = PageTree::find($id);
			$page->deleteWithChildren();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la p&aacute;ina!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function cargarListadoPaginas()
	{
		$data['paginas'] = $this->Paginas->getPages();
		$data['titulo'] = 'Seleccionar Pagina';
		$data['nueva_pagina_txt'] = 'crear nueva pagina';
		$data['pagina_id'] = $id = $this->uri->segment(4);
		
		$this->load->view('admin/paginasVentana_view', $data);
	}
	
	public function cargarPaginasParaArticulo()
	{
		$data['paginas'] = $this->Modulo->getContentByType(1);
		$data['titulo'] = 'Seleccionar Pagina';
		$data['nueva_pagina_txt'] = 'crear nueva pagina';
		$data['pagina_id'] = $id = $this->uri->segment(4);
		
		$this->load->view('admin/paginasVentana_view', $data);
	}
	
	public function cargarPaginasParaNoticia()
	{
		$data['paginas'] = $this->Modulo->getContentByType(5);
		$data['titulo'] = 'Seleccionar Pagina';
		$data['nueva_pagina_txt'] = 'crear nueva pagina';
		$data['pagina_id'] = $id = $this->uri->segment(4);
		
		$this->load->view('admin/paginasVentana_view', $data);
	}
	
	public function cargarPaginasParaBanners()
	{
		
		$data['paginas'] = $this->Paginas->getPages();
		$data['titulo'] = 'Seleccionar Pagina';
		$data['nueva_pagina_txt'] = 'crear nueva pagina';
		$data['pagina_id'] = $id = $this->uri->segment(4);
		
		$this->load->view('admin/paginasVentana_view', $data);
	}

	public function cargarPaginasParaEnlaces()
	{
		$data['paginas'] = $this->Modulo->getContentByType(10);
		$data['titulo'] = 'Seleccionar Pagina';
		$data['nueva_pagina_txt'] = 'crear nueva pagina';
		$data['pagina_id'] = $id = $this->uri->segment(4);
		
		$this->load->view('admin/paginasVentana_view', $data);
	}
	
	public function reorder($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		/*$parent = PageTree::find(1);
		$childs = new Illuminate\Support\Collection($parent->findChildren());
		$parent->createWorker()->mapTree($parent, $childs->toArray());*/

		try{
			$pages = PageTree::find(1);
			$pages->mapTree(json_decode($this->input->post('posiciones'), true));
		} catch (Exception $e) {
			$response->code = $e->getCode();
			$response->message = $e->getMessage();
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	/**
	 * Copies the structure from one page to another
	 */
	public function copy()
	{

		$response = new stdClass();

		if($this->Paginas->copy_structure()) {
			$response->code = 1;
			$response->message = 'success';
		} else {
			$response->code = -1;
			$response->message = 'La p&aacute;gina de origen no tiene estructura';
		}

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);

	}
	
}
