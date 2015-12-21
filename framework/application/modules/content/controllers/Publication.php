<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publication extends MY_Controller implements AdminInterface {

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

    public function index(){}

    public function reorder($id){}

    public function insert(){}

    public function create()
    {

        $paginaId = $this->uri->segment(5);
        $publicacionId = $this->Noticias->add($this->cms_general);
        $data['publicacionId'] = $publicacionId;
        $data['publicacionFecha'] = date('Y-m-d H:i:s');
        $data['publicacionClase'] = '';
        $data['publicacionImagen'] = '';
        $data['titulo'] = "Crear Publicación";
        $data['link'] = base_url("admin/publications/publication/update/" . $publicacionId);
        $data['txt_boton'] = "crear";
        $data['publicacionHabilitado'] = 'checked="checked"';
        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/publications/publication/delete/'.$publicacionId);
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

    public function edit($id)
    {

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

    public function update($id)
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

    public function delete($id)
    {

	    $response = new stdClass();
	    $response->error_code = 0;

	    if (!$noticia = $this->Noticias->get((int)$id)) {
		    $response->error_code = 1;
		    $response->message = 'Este recurso no existe, id:' . $id;
		    $response->error_message = '';
	    } else {
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

		    try{
			    $this->Noticias->delete($id);
		    } catch (Exception $e) {
			    $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la publicacion!', $e);
		    }

	    }


        $this->load->view('admin/request/json', array('return' => $response));

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
