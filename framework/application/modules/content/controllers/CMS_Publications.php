<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Publications {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('noticias_model', 'Noticias');
        $CI->load->model('module_model', 'Modulo');
        $CI->load->model('imagenes_model', 'Images');
    }

    public function create($paginaId, $data, $module, $idioma)
    {
        $CI =& get_instance();
        $publicacionId = $CI->uri->segment(3, 0);

        $pagina = (int) $publicacionId;

        $return = new stdClass();
        $return->html = '';
	    $return->og_image = '';
	    $return->og_title = '';
	    $return->og_description = '';

        /****************************************
         * PAGINA PRINCIPAL DE LA NOTICIA
         ***************************************/
        if(!$publicacionId || $pagina)
        {

            /*
             * VISTA EN LISTADO
            */
            if($module->moduloParam2)
            {

                if($module->moduloVerPaginacion) {

                    $noticiasCant = count($CI->Noticias->getByPage($paginaId, $idioma));

                    $pag_config = array();
                    $pag_config['base_url'] = base_url($idioma.'/'.$CI->uri->segment(2).'/');
                    $pag_config['total_rows'] =$noticiasCant;
                    $pag_config['per_page'] = $module->moduloParam4;
                    $pag_config['uri_segment'] = 3;

                    $CI->pagination->initialize($pag_config);
                    $pagination = $CI->pagination->create_links();

                    $data['pagination'] = $pagination;

                    $data['html'] = $CI->Modulo->getItemsForPublicaciones($paginaId, $module->moduloParam4, $pagina, $idioma);
                } else {
                    $data['pagination'] = '';
                    $data['html'] = $CI->Noticias->getByPage($paginaId, $idioma);
                }

				if (! $data['html']) {
					show_my_404(base_url($idioma . '/' . $CI->m_currentPage), $CI->m_config->theme);
				}

                $return->html .= $CI->load->view('paginas/publicaciones/publicacion_listado_view', $data, true);

            }

            /*
             * VER LA ULTIMA NOTICIA
            */
            else
            {

                $noticia = $CI->Noticias->getLastNews($paginaId, $idioma);

                $data['clase'] = '';
                $data['html'] = array();
                $data['imagenes'] = array();

                if($noticia){

                    $data['html'] = $noticia;
                    $data['clase'] = strtolower($noticia->publicacionClase);
                    $data['imagenes'] = $CI->Noticias->getImagenes($noticia->publicacionId);

	                //Some Facebook OpenGraph data
	                $return->og_title = $noticia->publicacionNombre;
	                $return->og_description = $noticia->publicacionTexto;

	                //Get the image used for Facebook's OpenGraph
	                if($noticia->publicacionImagen) {
		                $image = $CI->Images->getImages(2);
		                $return->og_image = base_url('assets/public/images/noticias/noticia_' . $noticia->publicacionId . $image[0]->imagenSufijo . '.' . $noticia->publicacionImagen);
	                } else if ($data['imagenes']) {
		                $return->og_image = base_url('assets/public/images/noticias/noticia_' . $noticia->publicacionId . '_' .$data['imagenes'][0]->publicacionImagenId  . $data['imagenes'][0]->publicacionImagenExtension . '.' . $noticia->publicacionImagen);
	                }

                    $return->html .= $CI->load->view('paginas/publicaciones/publicacion_detalle_view', $data, true);

                }

            }

        }

        /*****************************************
         * DETALLE DE LA NOTICIA
         ****************************************/
        else {
            $noticia = $CI->Noticias->get($publicacionId, $idioma);

	        if (! $noticia) {
		        show_my_404(base_url($idioma . '/' . $CI->m_currentPage), $CI->m_config->theme);
	        }

            $data['html'] = $noticia;
            $data['imagenes'] = $CI->Noticias->getImagenes($noticia->publicacionId);
            $return->html .= $CI->load->view('paginas/publicaciones/publicacion_detalle_view', $data, true);

	        //Some Facebook OpenGraph data
	        $return->og_title = $noticia->publicacionNombre;
	        $return->og_description = $noticia->publicacionTexto;

	        //Get the image used for Facebook's OpenGraph
	        if($noticia->publicacionImagen) {
		        $image = $CI->Images->getImages(2);
		        $return->og_image = base_url('assets/public/images/noticias/noticia_' . $noticia->publicacionId . $image[0]->imagenSufijo . '.' . $noticia->publicacionImagen);
	        } else if ($data['imagenes']) {
		        $return->og_image = base_url('assets/public/images/noticias/noticia_' . $noticia->publicacionId . '_' .$data['imagenes'][0]->publicacionImagenId  . $data['imagenes'][0]->publicacionImagenExtension . '.' . $noticia->publicacionImagen);
	        }

        }

        return $return;

    }

}

/* End of file CMS_Publications.php */