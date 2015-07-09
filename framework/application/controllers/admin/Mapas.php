<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Mapas extends CI_Controller
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

        $data['url_rel'] = base_url('admin/mapas');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/mapas/modificarUbicacion');
        $data['url_eliminar'] = base_url('admin/mapas/eliminarUbicacion');
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
        $data['menu'][] = anchor(base_url('admin/mapas/nuevaUbicacion'), 'Crear Ubicación', $atts);

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax n2 boton'
        );
        $data['menu'][] = anchor(base_url('admin/mapas/verMapas'), 'Mapas', $atts);

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax n1 boton'
        );
        $data['menu'][] = anchor(base_url('admin/mapas/template'), 'Template', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoAgrupado_view', $data);
	}
	
	public function verMapas()
	{
        $data['items'] = $this->Mapas->getAll();

        $data['url_rel'] = base_url('admin/mapas/verMapas');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/mapas/modificar');
        $data['url_eliminar'] = base_url('admin/mapas/eliminar');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'mapas';

        $data['idx_id'] = 'mapaId';
        $data['idx_nombre'] = 'mapaNombre';

        $data['txt_titulo'] = 'Mapas';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax importante n1 boton'
        );
        $data['menu'][] = anchor(base_url('admin/mapas/nuevo'), 'crear nuevo mapa', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
	}
	
	public function ajax_obtenerDatosMapa()
	{
		$id = $this->input->post('id');
		$mapa = $this->Mapas->get($id);
		$this->load->view('admin/request/json', array('return' => $mapa));
	}

	public function nuevo()
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
		$data['link'] = base_url('admin/mapas/crear');

		$this->load->view('admin/mapas/mapasCrear_view', $data);
	}

	public function crear()
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

	public function modificar()
	{

		$id = $this->uri->segment(4);
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
		
		if($mapa->mapaImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $mapa->mapaImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/mapas/mapa_' . $mapa->mapaId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/mapas/mapa_' . $mapa->mapaId . '_orig.' . $extension . '?' . time();
		}

		/*
		 * TRADUCCIONES
		 */
		/*$data['idiomas'] = $this->Idioma->getLanguages();
		
		foreach ($data['idiomas'] as $key => $idioma) {
		    $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->articuloTitulo = '';
			$traducciones[$idioma['idiomaDiminutivo']]->articuloContenido = '';
		}
		
		$data['traducciones'] = $traducciones;*/

		$data['txt_boton'] = 'Modificar Mapa';
		$data['link'] = base_url('admin/mapas/actualizar/' . $mapa->mapaId);
		$data['txt_botImagen'] = 'Subir Imagen';

		$this->load->view('admin/mapas/mapasCrear_view', $data);

	}

	public function actualizar()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$this->Mapas->update($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el mapa!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	//Miguel
	public function eliminar()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{

			$id = $this->uri->segment(4);

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

	public function nuevaUbicacion()
	{
		$data['mapaUbicacionId'] = $this->cms_general->generarId('mapas_ubicaciones');
		
		$data['mapaUbicacionNombre'] = '';
		$data['mapaUbicacionX'] = '';
		$data['mapaUbicacionY'] = '';
		$data['mapaUbicacionImagen'] = '';
        $data['mapaUbicacionClase'] = '';
		$data['mapaUbicacionPublicado'] = 'checked="checked"';

		$data['titulo'] = 'Nueva Ubicación';
		$data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['mapaUbicacionImagenCoord'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(11);
		
		$data['mapaId'] = '';
		$data['mapaNombre'] = 'Seleccionar Mapa';
        $data['nuevo'] = 'nuevo';
        $data['idiomas'] = $this->Idioma->getLanguages();

        $campos = $this->Mapas->getCampos();
        $inputs = array();

        foreach($campos as $row)
        {

            $row['mapaCampoTexto'] = array();

            foreach ($data['idiomas'] as $idioma)
            {
                $row['mapaCampoTexto'][$idioma['idiomaDiminutivo']] = array();
                $row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'] = '';
            }

            array_push($inputs,  $row);
        }

		$data['campos'] = $inputs;

		$data['txt_boton'] = 'Crear Ubicación';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/mapas/crearUbicacion');

		$this->load->view('admin/mapas/mapasUbicacionCrear_view', $data);
	}

	public function crearUbicacion()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$response->new_id = $this->Mapas->createUbicacion($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear la ubicaci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function modificarUbicacion()
	{
		$id = $this->uri->segment(4);
		$ubicacion = $this->Mapas->getUbicacion($id);
		
		$mapa = $this->Mapas->get($ubicacion->mapaId);
		
		$data['mapaId'] = $ubicacion->mapaId;
		$data['mapaUbicacionNombre'] = $ubicacion->mapaUbicacionNombre;
		$data['mapaUbicacionId'] = $ubicacion->mapaUbicacionId;
		$data['mapaUbicacionX'] = $ubicacion->mapaUbicacionX;
		$data['mapaUbicacionY'] = $ubicacion->mapaUbicacionY;
		$data['mapaUbicacionImagen'] = $ubicacion->mapaUbicacionImagen;
		$data['mapaUbicacionClase'] = $ubicacion->mapaUbicacionClase;
		$data['mapaUbicacionPublicado'] = '';
        $data['nuevo'] = '';
		
		if($ubicacion->mapaUbicacionPublicado)
			$data['mapaUbicacionPublicado'] = 'checked="checked"';

		$data['titulo'] = 'Nueva Ubicación';
		
		$data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['mapaUbicacionImagenCoord'] = urlencode($ubicacion->mapaUbicacionImagenCoord);
        $data['cropDimensions'] = $this->General->getCropImage(11);
		
		if($ubicacion->mapaUbicacionImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $ubicacion->mapaUbicacionImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '_orig.' . $extension . '?' . time();
		}
		
		
		$data['imagenMapa'] = '';

		if($mapa->mapaImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $mapa->mapaImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
			$data['imagenMapa'] = base_url() . 'assets/public/images/mapas/mapa_' . $mapa->mapaId . '.' . $extension . '?' . time();
			$path = getcwd() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR .'mapas' . DIRECTORY_SEPARATOR . 'mapa_' . $mapa->mapaId . '.' . $extension;

            $data['imageSize'] = array(0,0);

            if(file_exists($path))
                $data['imageSize'] = getimagesize($path);
		}
		
		$data['mapaNombre'] = 'Seleccionar Mapa';

        /*
          * TRADUCCIONES
          */
        $data['idiomas'] = $this->Idioma->getLanguages();

        $campos = $this->Mapas->getCampos();
        $inputs = array();

        foreach($campos as $row)
        {

            $row['mapaCampoTexto'] = array();

            foreach ($data['idiomas'] as $idioma)
            {

                $mapaTraduccion = $this->Mapas->getPositionTranslation($idioma['idiomaDiminutivo'], $row['mapaCampoId'], $ubicacion->mapaUbicacionId);

                $row['mapaCampoTexto'][$idioma['idiomaDiminutivo']] = array();

                if($mapaTraduccion)
                    $row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'] = $mapaTraduccion->mapaCampoTexto;
                else
                    $row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'] = '';
            }

            array_push($inputs,  $row);
        }

        $data['campos'] = $inputs;
		$data['txt_boton'] = 'Modificar Ubicación';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/mapas/actualizarUbicacion/' . $ubicacion->mapaUbicacionId);

		$this->load->view('admin/mapas/mapasUbicacionCrear_view', $data);
	}

	public function actualizarUbicacion()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$this->Mapas->updateUbicacion($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la ubicaci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function eliminarUbicacion()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);

			$ubicacion = $this->Mapas->getUbicacion($id);

			//Eliminamos la imagen
			$imageExtension = preg_replace('/\?+\d{0,}/', '', $ubicacion->mapaUbicacionImagen);

			if(file_exists('./assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '.' . $imageExtension))
				unlink('./assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '.' . $imageExtension);

			$this->Mapas->deleteUbicacion($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la ubicaci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function cargarMapas()
	{
		$data['mapas'] = $this->Mapas->getAll();

		$data['txt_titulo'] = 'Mapas';
		$data['txt_nuevo'] = 'crear nuevo mapa';

		$this->load->view('admin/mapas/mapasVentana_view', $data);

	}

    public function template(){

        $data['items'] = $this->Mapas->getCampos();

        $data['url_rel'] = base_url('admin/mapas/template');
        $data['url_sort'] = base_url('admin/mapas/reorganizarCampos');
        $data['url_modificar'] = base_url('admin/mapas/modificarCampo');
        $data['url_eliminar'] = base_url('admin/mapas/eliminarCampo');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'campos';

        $data['idx_id'] = 'mapaCampoId';
        $data['idx_nombre'] = 'mapaCampoLabel';

        $data['txt_titulo'] = 'Campos';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax importante n1 boton'
        );
        $data['menu'][] = anchor(base_url('admin/mapas/nuevoCampo'), 'crear nuevo campo', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nuevoCampo(){
        $data['mapaCampoId'] = $this->cms_general->generarId('mapas_campos');
        $data['inputs'] = $this->Mapas->getInputs();
        $data['txt_titulo'] = 'Crear Campo';
        $data['txt_boton'] = 'Crear Campo';
        $data['mapaCampoLabel'] = '';
        $data['mapaCampoClase'] = '';
        $data['titulo'] = 'Crear Campo';
        $data['inputId'] = '';
        $data['link'] = base_url('admin/mapas/crearCampo');
        $data['nuevo'] = 'nuevo';
        $data['mapaCampoPublicado'] = 'checked="cheched"';

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();
        $traducciones = array();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/mapas/campoCrear_view', $data);
    }

    public function modificarCampo(){

        $campo = $this->Mapas->getCampo($this->uri->segment(4));

        $data['mapaCampoId'] = $campo->mapaCampoId;
        $data['inputs'] = $this->Mapas->getInputs();
        $data['txt_titulo'] = 'Modificar Campo';
        $data['txt_boton'] = 'Modificar Campo';
        $data['mapaCampoClase'] = $campo->mapaCampoClase;
        $data['titulo'] = 'Modificar Campo';
        $data['inputId'] = $campo->inputId;
        $data['link'] = base_url('admin/mapas/actualizarCampo/'.$campo->mapaCampoId);
        $data['nuevo'] = '';

        if($campo->mapaCampoPublicado)
            $data['mapaCampoPublicado'] = 'checked="cheched"';
        else
            $data['mapaCampoPublicado'] = '';

        /*
          * TRADUCCIONES
          */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $campoTraduccion = $this->Mapas->getCampoTranslation($idioma['idiomaDiminutivo'], $campo->mapaCampoId);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($campoTraduccion)
                $traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel = $campoTraduccion->mapaCampoLabel;
            else
                $traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/mapas/campoCrear_view', $data);
    }

    public function crearCampo(){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id =$this->Mapas->createCampo();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

    }

    public function actualizarCampo(){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$this->Mapas->updateCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

    }

    public function eliminarCampo(){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$this->Mapas->deleteCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorganizarCampos(){
        $this->Mapas->reorderCampos();
    }

}