<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Links {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('enlaces_model', 'Enlaces');
    }

    public function create($pagina_id, $data, $idioma)
    {
        $CI =& get_instance();
        $data['enlaces'] = $CI->Enlaces->getByPage($pagina_id, $idioma);
        return $CI->load->view('paginas/enlaces_view', $data, true);
    }

}

/* End of file CMS_Links.php */