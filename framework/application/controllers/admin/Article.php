<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Article extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->database();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');
		
		$this->load->model('article_model', 'Articles');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('idiomas_model', 'Idioma');
		$this->load->model('admin/module_model', 'Modulo');

		$this->load->library('ion_auth');
		$this->load->library('Seguridad');
		$this->load->library('CMS_General');

		$this->seguridad->init();

	}

	public function create($page_id)
	{

		$data['articuloId'] = '';
		$data['titulo'] = 'Nuevo Articulo';
		$data['habilitado'] = 'checked="checked"';
		$data['nuevo'] = 'nuevo';
		$data['removeUrl'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->articuloTitulo = '';
			$traducciones[$idioma['idiomaDiminutivo']]->articuloContenido = '';
		}
		
		$data['traducciones'] = $traducciones;

		$data['articuloClase'] = '';
		$data['articuloClase'] = '';

		$data['paginaId'] = $page_id;

		$data['txt_boton'] = 'Crear Articulo';
		$data['link'] = base_url('admin/article/insert');

		$this->load->view('admin/article_view', $data);
	}

	public function edit($id)
	{

		$data = $this->Articles->get($id);

		$data['titulo'] = 'Modificar Articulo';
		$data['habilitado'] = $data['articuloHabilitado'] == 'on' ? 'checked="checked"' : '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$articuloTraduccion = $this->Articles->getTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();

			if($articuloTraduccion){
				$traducciones[$idioma['idiomaDiminutivo']] = $articuloTraduccion;
			} else {
				$traducciones[$idioma['idiomaDiminutivo']]->articuloTitulo = '';
				$traducciones[$idioma['idiomaDiminutivo']]->articuloContenido = '';
			}
		}
		
		$data['traducciones'] = $traducciones;
		$data['nuevo'] = '';
		$data['removeUrl'] = '';

		$data['txt_boton'] = 'Modificar Articulo';
		$data['link'] = base_url('admin/article/update/' . $data['articuloId']);

		$this->load->view('admin/article_view', $data);

	}

	public function insert()
	{
		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Articles->insert();
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Articles->update($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al modificar el art&iacute;culo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete()
	{
		$id = $this->uri->segment(4);

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Articles->delete($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function reorder($id)
	{
		$this->Articles->reorder($id);
	}

}