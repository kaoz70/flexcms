<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Calendar {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('calendar_model', 'Calendar');
        $CI->load->model('mapas_model', 'Mapas');
    }

    public function create($pagina_id, $data, $idioma)
    {
        $CI =& get_instance();

        $data['days'] = $CI->Calendar->getDays($idioma);
        $data['places'] = $CI->Mapas->getUbicaciones();

        $html = $CI->load->view('paginas/content_open_view', $data, true);
        $html .= $CI->load->view('paginas/calendar_view', $data, true);
        $html .= $CI->load->view('paginas/content_close_view', $data, true);

        return $html;

    }

}

/* End of file CMS_Calendar.php */