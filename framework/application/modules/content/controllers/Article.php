<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Article extends MY_Controller implements AdminInterface
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('article_model', 'Articles');
		$this->load->model('idiomas_model', 'Idioma');

		$this->load->library('ion_auth');
		$this->load->library('Seguridad');

	}

	public function index(){}

	public function create()
	{
		$this->_showView($this->uri->segment(4));
	}

	public function edit($id)
	{
		$this->_showView($id, $id);
	}

	/**
	 * Shows the create/edit interface
	 *
	 * @param $page_id
	 * @param null $id
	 */
	public function _showView($page_id, $id = NULL)
	{

		$data = $this->Articles->get($id);
		$data['titulo'] = $id ? 'Modificar' : 'Nuevo';
		$data['nuevo'] = $id ? '' : 'nuevo';
		$data['removeUrl'] = '';
		$data['paginaId'] = $page_id;
		$data['txt_boton'] = $id ? 'Modificar' : 'Crear';
		$data['link'] = base_url('admin/article/' . ($id ? 'update/' . $data['articuloId'] : 'insert'));
		$data['removeUrl'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$translation = $this->Articles->getTranslation($idioma['idiomaDiminutivo'], $id);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();

			if($translation){
				$traducciones[$idioma['idiomaDiminutivo']] = $translation;
			} else {
				$traducciones[$idioma['idiomaDiminutivo']]->articuloTitulo = '';
				$traducciones[$idioma['idiomaDiminutivo']]->articuloContenido = '';
			}
		}

		$data['traducciones'] = $traducciones;

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

	public function delete($id)
	{

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