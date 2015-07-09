<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Faq {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('faq_model', 'Faq');
    }

    public function create($data, $idioma)
    {

        $CI =& get_instance();
        $data['faqs'] = $CI->Faq->getByPage($data['page']->paginaId, $idioma);

        $html = $CI->load->view('paginas/content_open_view', $data, true);
        $html .= $CI->load->view('paginas/faq_view', $data, true);
        $html .= $CI->load->view('paginas/content_close_view', $data, true);

        return $html;

    }

}

/* End of file CMS_Faq.php */