<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Map extends MY_Controller implements AdminInterface
{

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		
		$this->load->helper('form');
		$this->load->helper('text');
		
		$this->load->model('mapas_model', 'Mapas');
		$this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();

	}

	public function index()
	{

        $data['items'] = $this->Mapas->getUbicaciones();
        $data['grupos'] = $this->Mapas->getAll();

        $data['url_rel'] = base_url('admin/maps');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/maps/location/edit');
        $data['url_eliminar'] = base_url('admin/maps/location/delete');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = '';

        $data['idx_nombre'] = 'mapaNombre';
        $data['idx_grupo_id'] = 'mapaId';
        $data['idx_item_id'] = 'mapaUbicacionId';
        $data['idx_item_nombre'] = 'mapaUbicacionNombre';

        $data['txt_titulo'] = 'Mapas';
        $data['txt_grupoNombre'] = 'Mapa';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax importante n3 boton'
        );
        $data['menu'][] = anchor(base_url('admin/maps/location/create'), 'Crear Ubicación', $atts);

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax n2 boton'
        );
        $data['menu'][] = anchor(base_url('admin/maps/map/view_all'), 'Mapas', $atts);

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax n1 boton'
        );
        $data['menu'][] = anchor(base_url('admin/maps/field'), 'Template', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoAgrupado_view', $data);
	}
	
	public function create()
	{

		$data['mapaId'] = $this->cms_general->generarId('mapas');
		
		$data['mapaNombre'] = '';
		$data['mapaImagen'] = '';
		$data['mapaPublicado'] = 'checked="checked"';
        $data['nuevo'] = 'nuevo';

		$data['titulo'] = 'Nuevo Mapa';
		$data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['mapaImagenCoord'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(4);
		
		$data['txt_boton'] = 'Crear Mapa';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/maps/map/insert');

		$this->load->view('admin/maps/mapasCrear_view', $data);
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id = $this->Mapas->create();;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el mapa!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function edit($id)
	{

		$mapa = $this->Mapas->get($id);

		$data['mapaId'] = $mapa->mapaId;
		
		$data['mapaNombre'] = $mapa->mapaNombre;
		$data['mapaImagen'] = $mapa->mapaImagen;
        $data['mapaImagenCoord'] = urlencode($mapa->mapaImagenCoord);
        $data['nuevo'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(4);
		
		$data['mapaPublicado'] = '';
		
		if($mapa->mapaPublicado)
			$data['mapaPublicado'] = 'checked="checked"';

		$data['titulo'] = 'Nuevo Mapa';
		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		
		if($mapa->mapaImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $mapa->mapaImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/mapas/mapa_' . $mapa->mapaId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/mapas/mapa_' . $mapa->mapaId . '_orig.' . $extension . '?' . time();
		}

		$data['txt_boton'] = 'Modificar Mapa';
		$data['link'] = base_url('admin/maps/map/update/' . $mapa->mapaId);
		$data['txt_botImagen'] = 'Subir Imagen';

		$this->load->view('admin/maps/mapasCrear_view', $data);

	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Mapas->update($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el mapa!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{

			$mapa = $this->Mapas->get($id);

			//Eliminamos la imagen
			$imageExtension = preg_replace('/\?+\d{0,}/', '', $mapa->mapaImagen);

			if(file_exists('./assets/public/images/mapas/mapa_' . $mapa->mapaId . '.' . $imageExtension))
				unlink('./assets/public/images/mapas/mapa_' . $mapa->mapaId . '.' . $imageExtension);

			$this->Mapas->delete($id);

		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el mapa!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function view_all($select = 0)
	{
		$data['items'] = $this->Mapas->getAll();

		$data['url_rel'] = base_url('admin/maps/map/view_all');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/maps/map/edit');
		$data['url_eliminar'] = base_url('admin/maps/map/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'mapas';

		if((int)$select)
			$data['select'] = true;

		$data['idx_id'] = 'mapaId';
		$data['idx_nombre'] = 'mapaNombre';

		$data['txt_titulo'] = 'Mapas';
		$data['add_class'] = 'mapa';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax importante n1 boton'
		);
		$data['menu'][] = anchor(base_url('admin/maps/map/create'), 'crear nuevo mapa', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function data($id)
	{
		$mapa = $this->Mapas->get($id);
		$this->load->view('admin/request/json', array('return' => $mapa));
	}

}