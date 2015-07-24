<?php

class Link extends MY_Controller implements AdminInterface {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');
		
		$this->load->library('image_lib');
		
		$this->load->model('enlaces_model', 'Enlaces');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('admin/module_model', 'Modulo');
        $this->load->model('admin/general_model', 'General');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
	}

	public function index(){

	}

	public function create()
	{

		$pagina_id = $this->uri->segment(4);
		$data['titulo'] = "Nuevo Enlace";
		$data['txt_boton'] = "Guardar Enlace";
		$data['txt_botImagen'] = 'Subir Imagen';
        $data['link'] = base_url("admin/link/insert/");
		$data['enlaceId'] = $this->General->generarId('enlaces');
		$data['enlaceLink'] = '';
		$data['enlaceClase'] = '';
		$data['enlaceImagen'] = '';
		$data['enlaceImagenCoord'] = '';
		$data['imagen'] = '';
        $data['imagenOrig'] = '';
		$data['enlacePublicado'] = 'checked="checked"';
        $data['cropDimensions'] = $this->General->getCropImage(1);
		$data['paginaId'] = $pagina_id;
		
        $data['nuevo'] = 'nuevo';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->enlaceTexto = '';
		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/link_view', $data);
	}

	public function edit($id)
	{
		$enlace = $this->Enlaces->getInfo($id); //Datos de cada FAQ
		$data['titulo'] = "Modificar Enlace";
		$data['txt_boton'] = "Modificar Enlace";
		$data['txt_botImagen'] = 'Actualizar Imagen';
		$data['link'] = base_url("admin/link/update/" . $enlace->enlaceId);
		$data['enlaceId'] = $enlace->enlaceId;
		$data['enlaceLink'] = $enlace->enlaceLink;
		$data['enlaceClase'] = $enlace->enlaceClase;
		$data['enlaceImagen'] = $enlace->enlaceImagen;
        $data['enlaceImagenCoord'] = urlencode($enlace->enlaceImagenCoord);
		
		$page = $this->Paginas->getPage($enlace->paginaId);
		$data['paginaNombre'] = $page['paginaNombre'];
		$data['paginaId'] = $enlace->paginaId;
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(1);
		
		$data['imagen'] = '';
        $data['imagenOrig'] = '';

		if($enlace->enlaceImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $enlace->enlaceImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/enlaces/enlace_' . $enlace->enlaceId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/enlaces/enlace_' . $enlace->enlaceId . '_orig.' . $extension . '?' . time();
		}
		
		if($enlace->enlacePublicado){
			$data['enlacePublicado'] = 'checked="checked"';
		} else {
			$data['enlacePublicado'] = '';
		}
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$enlaceTraduccion = $this->Enlaces->getEnlaceTranslation($idioma['idiomaDiminutivo'], $id);

            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			
			$texto = '';
			
			if(count($enlaceTraduccion) > 0){
				$texto = $enlaceTraduccion->enlaceTexto;
			}
			
			$traducciones[$idioma['idiomaDiminutivo']]->enlaceTexto = $texto;
		}
		
		$data['traducciones'] = $traducciones;
				
		$this->load->view('admin/link_view', $data);
		
	}

    public function insert()
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Enlaces->create();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el enlace!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));
    }

	public function update($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Enlaces->update($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al editar el enlace!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete($id)
	{

		$enlace = $this->Enlaces->getInfo($id);
		
		//Eliminamos la imagen
		if($enlace->enlaceImagen != '')
		{
			$extension = preg_replace('/\?+\d{0,}/', '', $enlace->enlaceImagen);
			
			if(file_exists('./assets/public/images/enlaces/enlace_' . $id . '_small.' . $extension))	
				unlink('./assets/public/images/enlaces/enlace_' . $id . '_small.' . $extension);
			
			if(file_exists('./assets/public/images/enlaces/enlace_' . $id . '_medium.' . $extension))	
				unlink('./assets/public/images/enlaces/enlace_' . $id . '_medium.' .$extension);
			
			if(file_exists('./assets/public/images/enlaces/enlace_' . $id . '_big.' . $extension))	
				unlink('./assets/public/images/enlaces/enlace_' . $id . '_big.' . $extension);
		}

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Enlaces->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al editar el enlace!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function reorder($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Enlaces->reorder($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los links!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}