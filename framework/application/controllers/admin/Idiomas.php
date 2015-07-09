<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Idiomas extends CI_Controller {
	
	var $txt_boton = '';
	var $pagina_info = array();
	var $link;
	 
	function __construct(){
		parent::__construct();
		
		$this->load->model('idiomas_model', 'Idiomas');
		$this->load->model('admin/page_model', 'Paginas');

		$this->load->library('Seguridad');
		$this->seguridad->init();
		
	}
	
	public function index()
	{

		$data['items'] = $this->Idiomas->getLanguages();

		$data['url_rel'] = base_url('admin/idiomas');
		$data['url_sort'] = base_url('admin/idiomas/reorganizar');
		$data['url_modificar'] = base_url('admin/idiomas/modificarIdioma');
		$data['url_eliminar'] = base_url('admin/idiomas/eliminarIdioma');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel2';
		$data['list_id'] = 'idiomas';

		$data['idx_id'] = 'idiomaId';
		$data['idx_nombre'] = 'idiomaNombre';

		$data['txt_titulo'] = 'Idiomas';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crear',
			'class' => $data['nivel'] . ' ajax importante n1 boton'
		);

		$data['menu'][] = anchor(base_url('admin/idiomas/crearIdioma'), 'crear nuevo idioma', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);

	}
	
	public function crearIdioma()
	{
		$data['titulo'] = "Crear Idioma";
		$data['link'] = base_url("admin/idiomas/insertarIdioma");
		$data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
		
		$idioma = new stdClass();
		$idioma->idiomaNombre = '';
		$idioma->idiomaDiminutivo = '';
		
		$data['result'] = $idioma;
		
		$this->load->view('admin/idiomaCrear_view', $data);
	}
	
	public function modificarIdioma()
	{
		$id = $this->uri->segment(4);
		$data['result'] = $this->Idiomas->getLanguageInfo($id); //Datos de cada idioma
		$data['titulo'] = "Modificar Idioma";
		$data['txt_boton'] = "modificar";
		$data['link'] = base_url("admin/idiomas/actualizarIdioma/" . $data['result']->idiomaId);
        $data['nuevo'] = '';
		
		$this->load->view('admin/idiomaCrear_view', $data);
	}
	
	public function actualizarIdioma()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Idiomas->updateLanguage();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el idioma!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function insertarIdioma()
	{
		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Idiomas->addLanguage();
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el idioma!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	//Dummy function
	public function reorganizar()
	{
		return;
	}
	
	public function eliminarIdioma()
	{

		$id = $this->uri->segment(4);
		$response = new stdClass();
		$response->error_code = 0;

		try{

			$this->Idiomas->deleteLanguage($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el idioma!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
		
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */