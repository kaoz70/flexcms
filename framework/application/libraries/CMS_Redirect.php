<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Redirect {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('admin/page_model', 'Page');
    }

    public function create($module, $idioma)
    {

        if((int)$module->moduloParam2 === 0)
        {
            redirect($module->moduloParam3);
            return;
        }

        $CI =& get_instance();
        $pagina = $CI->Page->getPage($module->moduloParam2, $idioma);
        redirect($idioma . '/' . $pagina->paginaNombreURL, 301);
    }

}

/* End of file CMS_Redirect.php */