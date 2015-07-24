<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Service extends MY_Controller implements AdminInterface
{

	public function __construct()
	{
		parent::__construct();

		$this->load->database();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');
		
		$this->load->model('servicios_model', 'Servicios');
		$this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
		$this->load->model('admin/module_model', 'Modulo');

        $this->load->library('CMS_General');

	}

    public function index(){}

    public function create()
    {

	    $paginaId = $this->uri->segment(5);
		$servicioId = $this->Servicios->create($this->cms_general);
		$data['servicioId'] = $servicioId;

        $data['titulo'] = 'Nuevo Servicio';
        $data['servicioPublicado'] = 'checked="checked"';
        $data['nuevo'] = 'nuevo';

        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['servicioImagen'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['cropDimensions'] = $this->General->getCropImage(10);
        $data['servicioImagenCoord'] = '';

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();
        $traducciones = array();

        foreach ($data['idiomas'] as $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->servicioTitulo = '';
            $traducciones[$idioma['idiomaDiminutivo']]->servicioTexto = '';
            $traducciones[$idioma['idiomaDiminutivo']]->servicioKeywords = '';
            $traducciones[$idioma['idiomaDiminutivo']]->servicioMetaTitulo = '';
            $traducciones[$idioma['idiomaDiminutivo']]->servicioDescripcion = '';
        }

        $data['traducciones'] = $traducciones;
        $data['servicioClase'] = '';
        $data['servicioDestacado'] = '';
        $data['txt_boton'] = 'Crear Servicio';
		$data['removeUrl'] = base_url('admin/services/service/delete/' . $servicioId);
        $data['link'] = base_url('admin/services/service/update/' . $servicioId);
		$data['paginaId'] = $paginaId;

        $this->load->view('admin/servicios/servicioCrear_view', $data);
    }

	public function edit($id)
	{

		$servicio = $this->Servicios->get((int)$id, 'es');

		$data['titulo'] = 'Modificar Servicio';

		$data['servicioPublicado'] = '';
		if ($servicio->servicioPublicado)
			$data['servicioPublicado'] = 'checked="checked"';

        if($servicio->servicioImagen != '')
        {
            $data['txt_botImagen'] = 'Cambiar Imagen';
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/servicios/servicio_' . $servicio->servicioId . '_admin.' . $servicio->servicioImagen . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/servicios/servicio_' . $servicio->servicioId . '_orig.' . $servicio->servicioImagen;
            $data['servicioImagen'] = $servicio->servicioImagen;
            $data['servicioImagenCoord'] = urlencode($servicio->servicioImagenCoord);
        }
        else
        {
            $data['imagen'] = '';
            $data['imagenOrig'] = '';
            $data['servicioImagen'] = '';
            $data['txt_botImagen'] = 'Subir Imagen';
            $data['servicioImagenCoord'] = '';
        }

        $data['cropDimensions'] = $this->General->getCropImage(10);

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Servicios->getLanguages();

        $traducciones = array();
		foreach ($data['idiomas'] as $idioma)
		{
			$articuloTraduccion = $this->Servicios->getTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->servicioTitulo = isset($articuloTraduccion->servicioTitulo) ? $articuloTraduccion->servicioTitulo : '';
			$traducciones[$idioma['idiomaDiminutivo']]->servicioTexto = isset($articuloTraduccion->servicioTexto) ? $articuloTraduccion->servicioTexto : '';
			$traducciones[$idioma['idiomaDiminutivo']]->servicioKeywords = isset($articuloTraduccion->servicioKeywords) ? $articuloTraduccion->servicioKeywords : '';
			$traducciones[$idioma['idiomaDiminutivo']]->servicioMetaTitulo = isset($articuloTraduccion->servicioMetaTitulo) ? $articuloTraduccion->servicioMetaTitulo : '';
			$traducciones[$idioma['idiomaDiminutivo']]->servicioDescripcion = isset($articuloTraduccion->servicioDescripcion) ? $articuloTraduccion->servicioDescripcion : '';
		}
		
		$data['traducciones'] = $traducciones;
        $data['nuevo'] = '';

		$data['servicioId'] = $servicio->servicioId;
		$data['servicioClase'] = $servicio->servicioClase;
		$data['servicioDestacado'] = $servicio->servicioDestacado;
		$data['txt_boton'] = 'Modificar Servicio';
		$data['link'] = base_url('admin/services/service/update/' . $servicio->servicioId);
		$data['removeUrl'] = '';
		$data['paginaId'] = $servicio->paginaId;

		$this->load->view('admin/servicios/servicioCrear_view', $data);

	}

	public function insert(){}

	public function update($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Servicios->update($id, $this->cms_general);
			$response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el servicio!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Servicios->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el servicio!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{
		$this->Servicios->reorder($id);
		$this->index();
	}

}