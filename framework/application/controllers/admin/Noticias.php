<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('text');

        $this->load->library('image_lib');

        $this->load->model('noticias_model', 'Noticias');
        $this->load->model('configuracion_model', 'Config');
        $this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/page_model', 'Paginas');
        $this->load->model('admin/module_model', 'Modulo');
        $this->load->model('admin/general_model', 'General');

        $this->load->library('Seguridad');
        $this->load->library('CMS_General');

        $this->seguridad->init();

    }

    public function crear($paginaId)
    {
        date_default_timezone_set("America/Guayaquil");

        $publicacionId = $this->Noticias->add($this->cms_general);
        $data['publicacionId'] = $publicacionId;
        $data['publicacionFecha'] = date('Y-m-d H:i:s');
        $data['publicacionClase'] = '';
        $data['publicacionImagen'] = '';
        $data['titulo'] = "Crear Publicación";
        $data['link'] = base_url("admin/noticias/actualizar");
        $data['txt_boton'] = "crear";
        $data['publicacionHabilitado'] = 'checked="checked"';
        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/noticias/eliminar/'.$publicacionId);
        $data['cropDimensions'] = $this->General->getCropImage(2);
        $data['publicacionImagenCoord'] = '';
        $data['paginaId'] = $paginaId;

        /*
		 * TRADUCCIONES
		 */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->publicacionNombre = '';
            $traducciones[$idioma['idiomaDiminutivo']]->publicacionTexto = '';
            $traducciones[$idioma['idiomaDiminutivo']]->publicacionLink = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/publicaciones/noticiaCrear_view', $data);
    }

    public function modificar()
    {

        $id = $this->uri->segment(4);
        $noticia = $this->Noticias->get((int)$id, 'es');

        $data['publicacionId'] = $noticia->publicacionId;
        $data['publicacionFecha'] = $noticia->publicacionFecha;
        $data['publicacionClase'] = $noticia->publicacionClase;
        $data['publicacionImagen'] = $noticia->publicacionImagen;
        $data['titulo'] = "Modificar Publicación";
        $data['link'] = base_url("admin/noticias/actualizar");
        $data['txt_boton'] = "modificar";
        $data['publicacionHabilitado'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(2);
        $data['publicacionImagenCoord'] = urlencode($noticia->publicacionImagenCoord);
        $data['paginaId'] = $noticia->paginaId;

        $data['imagen'] = '';
        $data['imagenOrig'] = '';

        if($noticia->publicacionImagen != '')
        {
            //Eliminamos el cache del navegador
            $extension = $noticia->publicacionImagen;
            $extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/noticias/noticia_' . $noticia->publicacionId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/noticias/noticia_' . $noticia->publicacionId . '_orig.' . $extension . '?' . time();
        }

        if($noticia->publicacionHabilitado)
            $data['publicacionHabilitado'] = 'checked="checked"';

        /*
		 * TRADUCCIONES
		 */
        $data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

        foreach ($data['idiomas'] as $key => $idioma) {
            $articuloTraduccion = $this->Noticias->getTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($articuloTraduccion){
                $traducciones[$idioma['idiomaDiminutivo']]->publicacionNombre = $articuloTraduccion->publicacionNombre;
                $traducciones[$idioma['idiomaDiminutivo']]->publicacionTexto = $articuloTraduccion->publicacionTexto;
                $traducciones[$idioma['idiomaDiminutivo']]->publicacionLink = $articuloTraduccion->publicacionLink;
            } else {
                $traducciones[$idioma['idiomaDiminutivo']]->publicacionNombre = '';
                $traducciones[$idioma['idiomaDiminutivo']]->publicacionTexto = '';
                $traducciones[$idioma['idiomaDiminutivo']]->publicacionLink = '';
            }
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/publicaciones/noticiaCrear_view', $data);
    }

    public function actualizar()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Noticias->update($this->cms_general);
            $response->new_id = $this->input->post('publicacionId');
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la publicacion!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function eliminar()
    {
        $id = $this->uri->segment(4);
        $noticia = $this->Noticias->get($id);

        //Eliminamos la imagen
        if($noticia->publicacionImagen != '')
        {
            $extension = preg_replace('/\?+\d{0,}/', '', $noticia->publicacionImagen);

            if(file_exists('./assets/public/images/noticias/noticia_' . $id . '_small.' . $extension))
                unlink('./assets/public/images/noticias/noticia_' . $id . '_small.' . $extension);

            if(file_exists('./assets/public/images/noticias/noticia_' . $id . '_medium.' . $extension))
                unlink('./assets/public/images/noticias/noticia_' . $id . '_medium.' .$extension);

            if(file_exists('./assets/public/images/noticias/noticia_' . $id . '_big.' . $extension))
                unlink('./assets/public/images/noticias/noticia_' . $id . '_big.' . $extension);
        }

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Noticias->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la publicacion!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function galeria()
    {
        $id = $this->uri->segment(4);
        $data['items'] = $this->Noticias->getAllImages($id);

        $data['url_rel'] = base_url('admin/noticias/galeria');
        $data['url_sort'] = base_url('admin/noticias/reorganizarImagenes/' . $id);
        $data['url_modificar'] = base_url('admin/noticias/modificarImagen');
        $data['url_eliminar'] = base_url('admin/noticias/eliminarImagen');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'publicaciones_imagenes';

        $data['idx_id'] = 'publicacionImagenId';
        $data['idx_nombre'] = 'publicacionImagenNombre';

        $data['txt_titulo'] = 'Galer&iacute;a';

        $data['url_path'] =  base_url() . 'assets/public/images/noticias/noticia_' . $id . '_';
        $data['url_upload'] =  base_url() . 'admin/imagen/publicacionGaleria/' . $id;
        $data['method'] =  'publicacionGaleria/' . $id;

        $dimensiones = $this->General->getCropImage(15);
        $data['width'] = $dimensiones->imagenAncho;
        $data['height'] = $dimensiones->imagenAlto;

        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'noticias_images';

        $data['idx_id'] = 'publicacionImagenId';
        $data['idx_nombre'] = 'publicacionImagenNombre';
        $data['idx_extension'] = 'publicacionImagenExtension';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoGaleria_view', $data);

    }

    public function nuevaImagen()
    {
        $data['publicacionImagenId'] = $this->General->generarId('publicaciones_imagenes','publicacionImagenId');
        $data['publicacionImagen'] = '';
        $data['titulo'] = "Crear Imágen";
        $data['link'] = "admin/noticias/insertarImagen";
        $data['txt_boton'] = "crear";
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['imagen'] = '';
        $data['imagenExtension'] = '';
        $data['imagenOrig'] = '';
        $data['publicacionId'] = '';
        $data['nuevo'] = 'nuevo';
        $data['cropDimensions'] = $this->General->getCropImage(15);
        $data['imagenCoord'] = '';

        $data['publicacionImagenNombre'] = '';
        $data['publicacionId'] = $this->uri->segment(4);

        $this->load->view('admin/publicaciones/noticiasImagenCrear_view', $data);
    }

    public function modificarImagen()
    {

        $imagen = $this->Noticias->getImagen($this->uri->segment(4));

        $data['publicacionImagenId'] = $imagen->publicacionImagenId;
        $data['publicacionImagen'] = '';
        $data['titulo'] = "Modificar Imágen";
        $data['link'] = "admin/noticias/actualizarImagen/".$imagen->publicacionImagenId;
        $data['txt_boton'] = "modificar";
        $data['imagen'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['publicacionId'] = $imagen->publicacionId;
        $data['nuevo'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(15);
        $data['imagenCoord'] = urlencode($imagen->publicacionImagenCoord);

        $data['imagen'] = '';


        if($imagen->publicacionImagenExtension != '')
        {
            $data['txt_botImagen'] = 'Cambiar Imagen';
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_admin.' . $imagen->publicacionImagenExtension . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_orig.' . $imagen->publicacionImagenExtension;
            $data['imagenExtension'] = $imagen->publicacionImagenExtension;
        }
        else
        {
            $data['txt_botImagen'] = 'Subir Imagen';
            $data['imagen'] = '';
            $data['imagenExtension'] = '';
            $data['imagenOrig'] = '';
        }

        $data['publicacionImagenNombre'] = $imagen->publicacionImagenNombre;
        $data['publicacionId'] = $imagen->publicacionId;

        $this->load->view('admin/publicaciones/noticiasImagenCrear_view', $data);
    }

    public function insertarImagen()
    {
        $this->Noticias->addImage();
    }

    public function actualizarImagen()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Noticias->updateImage();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la imagen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function eliminarImagen()
    {
        $id = $this->uri->segment(4);
        $imagen = $this->Noticias->getImagen($id);

        //Get the images
        $images = $this->Modulo->getImages(15);

        foreach ($images as $img) {
            if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . $img->imagenSufijo . '.' . $imagen->publicacionImagenExtension))
                unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . $img->imagenSufijo . '.' . $imagen->publicacionImagenExtension);
        }

        //image
        if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '.' . $imagen->publicacionImagenExtension))
            unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '.' . $imagen->publicacionImagenExtension);

        //Admin image
        if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_admin.' . $imagen->publicacionImagenExtension))
            unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_admin.' . $imagen->publicacionImagenExtension);

        //Original image
        if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_orig.' . $imagen->publicacionImagenExtension))
            unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_orig.' . $imagen->publicacionImagenExtension);

        //Search image
        if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_search.' . $imagen->publicacionImagenExtension))
            unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_search.' . $imagen->publicacionImagenExtension);

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Noticias->deleteImage($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la imagen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));
    }

    public function reorganizarImagenes()
    {
        $noticiaId = $this->uri->segment(4);
        $this->Noticias->reorganizarImagenes($noticiaId);
    }

    /*
	 * CONFIGURACION
	 */
    public function configuracion()
    {
        $data['link'] = base_url('admin/noticias/guardarConfiguracion');
        $data['titulo'] = 'Configuración';
        $data['txt_boton'] = 'Guardar Configuracion';
        $data['configuracion'] = $this->Noticias->getConfiguration();

        $this->load->view('admin/noticiasConfiguracion_view.php', $data);

    }

    public function guardarConfiguracion()
    {
        $this->Noticias->updateConfiguration();
        $this->configuracion();
    }

}
