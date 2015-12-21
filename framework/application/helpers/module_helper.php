<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 6/26/15
 * Time: 3:41 PM
 */

if ( ! function_exists('important_products'))
{
    /**
     * @param $quantity
     * @param string $view
     * @param string $imageSize
     * @param string $categoria_id
     *
     * @return mixed
     */
    function important_products($quantity, $view = 'default_view', $imageSize = '', $categoria_id = 'todas')
    {
        $CI =& get_instance();

        $catalogoPagina = $CI->Modulos->getPageByType(4, $CI->m_idioma);
        $data['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
        $data['productos'] = $CI->Modulos->getItemsForProductosDestacados($categoria_id, $quantity, 0, $CI->m_idioma);
        $data['diminutivo'] = $CI->m_idioma;
        $data['imageSize'] = $imageSize;

        return $CI->load->view('modulos/catalogo/product/' . $view, $data, TRUE);

    }
}

if ( ! function_exists('publications'))
{
    /**
     * @param $page_id
     * @param $quantity
     * @param string $view
     * @param string $imageSize
     * @return mixed
     */
    function publications($page_id, $quantity, $view = 'default_view', $imageSize = '')
    {
        $CI =& get_instance();

        $page = $CI->Page->getPage($page_id, $CI->m_idioma);
        $data['diminutivo'] = $CI->m_idioma;
        $data['paginaNoticiaUrl'] = $page->paginaNombreURL;
        $data['imageSize'] = $imageSize;
        $data['noticias'] = $CI->Modulos->getItemsForPublicaciones($page_id, $quantity, 0, $CI->m_idioma);
        $data['pagination'] = '';

        return $CI->load->view('modulos/publicaciones/' . $view, $data, TRUE);

    }
}

if ( ! function_exists('article'))
{
    /**
     * @param $article_id
     * @param string $view
     * @return mixed
     */
    function article($article_id, $view = 'default_view')
    {
        $CI =& get_instance();

        $data['articulo'] = $CI->Modulos->articulo($article_id, $CI->m_idioma);
        $data['diminutivo'] = $CI->m_idioma;

        return $CI->load->view('modulos/articulo/' . $view, $data, TRUE);

    }
}

if ( ! function_exists('links'))
{
    /**
     * @param $page_id
     * @param $quantity
     * @param string $view
     * @param string $imageSize
     * @return mixed
     */
    function links($page_id, $quantity, $view = 'default_view', $imageSize = '')
    {
        $CI =& get_instance();

        $page = $CI->Page->getPage($page_id, $CI->m_idioma);
        $data['diminutivo'] = $CI->m_idioma;
        $data['paginaEnlacesUrl'] = $page->paginaNombreURL;
        $data['imageSize'] = $imageSize;
        $data['enlaces'] = $CI->Modulos->getItemsForEnlaces($page_id, $quantity, 0, $CI->m_idioma);
        $data['pagination'] = '';

        return $CI->load->view('modulos/enlaces/' . $view, $data, TRUE);

    }
}

if ( ! function_exists('address'))
{
    /**
     * @param string $address
     * @param string $view
     * @param string $imageSize
     * @return mixed
     */
    function address($address = NULL, $view = 'default_view', $imageSize = '')
    {
        $CI =& get_instance();

        //[12, 5, 4] -- Gets individual addresses
        if(is_array($address)) {
            $data['direcciones'] = $CI->Contact->getAddressById($address, $CI->m_idioma);
        }

        //get all
        else {
            $data['direcciones'] = $CI->Contact->getDirecciones($CI->m_idioma);
        }

        $parseData = array(
            'base_url' => base_url(),
            'asset_url' => base_url('themes/' . $CI->m_config->theme) . '/',
        );

        foreach($data['direcciones'] as $dir) {
            $dir->contactoDireccion = $CI->parser->parse_string(auto_link($dir->contactoDireccion, 'email'), $parseData, TRUE);
        }

        $data['diminutivo'] = $CI->m_idioma;
        $data['imageSize'] = $imageSize;
        $data['pagination'] = '';

        return $CI->load->view('modulos/contacto/direcciones/' . $view, $data, TRUE);

    }
}

// ------------------------------------------------------------------------
/* End of file module_helper.php */
/* Location: ./system/helpers/module_helper.php */