<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Gallery {

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->model('descargas_model', 'Downloads');
    }

    public function create($pagina_id, $data, $idioma)
    {
        $CI =& get_instance();
        $categoriaId = $CI->uri->segment(3);
        $categoria_nombre = $CI->uri->segment(4);
        $html = '';

        /*************************
         * LISTADO CATEGORIAS
         *************************/
        if(!$categoriaId)
        {

            $tree = GalleryTree::allRoot()->first();
            $tree->lang = $idioma;
            $tree->findChildren(9999);

            $data['categorias'] = $tree->getChildren();

            if(count($data['categorias']) === 1) {
                $html .= $this->renderList($data['categorias'][0], $data, $idioma);
            } else {
                $html .= $CI->load->view('paginas/descargas/categories_view', $data, true);
            }

        }

        /*********************************
         * LISTADO DENTRO DE LA CATEGORIA
         ********************************/
        else if ($categoriaId) {

            $categoria = $CI->Downloads->getCategory((int)$categoriaId, $idioma);

            if($categoria){
                $html .= $this->renderList($categoria, $data, $idioma);
            } else {
	            //TODO: category not found, not 404
	            show_my_404(base_url($idioma . '/' . $CI->m_currentPage), $CI->m_config->theme);
            }
        }

        return $html;

    }

    private function renderList($categoria, $data, $idioma)
    {

        $CI =& get_instance();
        $html = '';

        $tree = GalleryTree::find($categoria['id']);
        $tree->lang = $idioma;
        $tree->findChildren(9999);

        $data['categorias'] = $tree->getChildren();
        $descargas = $CI->Downloads->getDownloads((int)$categoria['id'], $idioma);

        $data['title'] = $categoria['descargaCategoriaNombre'];
        $data['cat_link'] = $categoria['descargaCategoriaEnlace'];
        $data['back'] = base_url($idioma.'/'.$CI->uri->segment(2));
        $data['cat_clase'] = $categoria['descargaCategoriaClase'];

        $html .= $CI->load->view('paginas/content_open_view', $data, true);
        $html .= $CI->load->view('paginas/descargas/categories_view', $data, true);

        if(count($descargas) > 0){
            $data['descargas'] = $descargas;
            $html .= $CI->load->view('paginas/descargas/items_view', $data, true);
        }

        $html .= $CI->load->view('paginas/content_close_view', $data, true);

        return $html;

    }

}

/* End of file CMS_Gallery.php */