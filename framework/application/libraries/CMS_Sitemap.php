<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Sitemap {

    public function create($data, $idioma, $currentPage, $paginas)
    {
        $CI =& get_instance();

        //Get the Catalog Content module to check whether to show catalog category submenu in the catalog menu item
        $catalogModule = $CI->Modulos->getContentModule(4);
        $catalogoPagina = $CI->Modulos->getPageByType(4, $idioma);
        $categories = '';

        if(count($catalogModule) > 0 && $catalogModule->moduloParam2) {
            $categories = $CI->cms_modules->createCatalogMenu(TRUE, $catalogoPagina->paginaNombreURL, $currentPage, 0 , $idioma);
        }

        $data['menu'] = $CI->cms_modules->createMenu('sitemap', 0, $idioma, $currentPage, $categories, null, $paginas);
        return $CI->load->view('paginas/sitemap_view', $data, true);
    }

}

/* End of file CMS_Sitemap.php */