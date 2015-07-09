<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Servicios extends CI_Controller
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

		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();

	}

    public function nuevo($paginaId)
    {

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
		$data['removeUrl'] = base_url('admin/servicios/eliminar/' . $servicioId);
        $data['link'] = base_url('admin/servicios/actualizar/' . $servicioId);
		$data['paginaId'] = $paginaId;

        $this->load->view('admin/servicios/servicioCrear_view', $data);
    }

	public function modificar()
	{

		$id = $this->uri->segment(4);
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
		$data['link'] = base_url('admin/servicios/actualizar/' . $servicio->servicioId);
		$data['removeUrl'] = '';
		$data['paginaId'] = $servicio->paginaId;

		$this->load->view('admin/servicios/servicioCrear_view', $data);

	}

	public function actualizar()
	{
		$id = $this->uri->segment(4);

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

	public function eliminar()
	{
		$id = $this->uri->segment(4);

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Servicios->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el servicio!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorganizar()
	{
		$id = $this->uri->segment(4);
		$this->Servicios->reorder($id);
		$this->index();
	}

	public function imagenes($id)
	{
		$data['items'] = $this->Servicios->getImages($id);

		$data['url_rel'] = base_url('admin/servicios/imagenes/'.$id);
		$data['url_sort'] = base_url('admin/servicios/reorganizar_imagenes/'.$id);
		$data['url_modificar'] = base_url('admin/servicios/modificar_imagen/');
		$data['url_eliminar'] = base_url('admin/servicios/eliminar_imagen/');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'servicio_images';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'nombre';

		$data['txt_titulo'] = 'Imágenes';

		$data['url_path'] =  base_url() . 'assets/public/images/servicios/gal_' . $id . '_';
		$data['url_upload'] =  base_url() . 'admin/imagen/productoGaleria/' . $id;
		$data['method'] =  'servicioGaleria/' . $id;

		$dimensiones = $this->General->getCropImage(16);
		$data['width'] = $dimensiones->imagenAncho;
		$data['height'] = $dimensiones->imagenAlto;

		$data['nivel'] = 'nivel5';
		$data['list_id'] = 'servicio_galeria_images';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'nombre';
		$data['idx_extension'] = 'extension';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listadoGaleria_view', $data);
	}

	public function modificar_imagen($imagenId)
	{
		$image = $this->Servicios->getImage($imagenId);

		$data['id'] = $image->id;
		$data['servicio_id'] = $image->servicio_id;
		$data['titulo'] = 'Modificar Imágen';
		$data['nombre'] = $image->nombre;
		$data['txt_boton'] = 'Modificar Imágen';
		$data['productoImagen'] = $image->extension;
		$data['nuevo'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(16);
		$data['coords'] = urlencode($image->coords);

		if($image->extension != '')
		{
			$data['txt_botImagen'] = 'Cambiar Imágen';
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $imagenId . '_admin.' . $image->extension . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $imagenId . '_orig.' . $image->extension;
		}
		else
		{
			$data['txt_botImagen'] = 'Subir Imagen';
			$data['imagen'] = '';
			$data['imagenOrig'] = '';
		}

		$data['productoImagenId'] = $imagenId;
		$data['link'] = base_url('admin/servicios/actualizar_imagen/' . $image->id);

		$this->load->view('admin/servicios/imagen_view', $data);
	}

	public function reorganizar_imagenes()
	{
		$this->Servicios->reorderImages();
	}

	public function actualizar_imagen($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Servicios->updateImage($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la im&aacute;gen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function eliminar_imagen($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			//Get the images
			$images = $this->Modulo->getImages(16);
			$this->Servicios->deleteImage($id, $images);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la im&aacute;gen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

}