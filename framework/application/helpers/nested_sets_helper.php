<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('admin_tree'))
{
    /**
     * Recursive function to create a tree structure
     *
     * @param $nodes
     * @param $level
     * @param $modify_url
     * @param $delete_url
     * @param $section
     * @param string $attributes
     * @return string
     */
    function admin_tree($nodes, $level, $modify_url, $delete_url, $section, $attributes = '') {

        if ($attributes !== '') {
            $attributes = _stringify_attributes($attributes);
        }

        $return = '<ul'.$attributes.'>';

        foreach ($nodes as $childNode) {

            //Set the translation so that its exposed in the node's variable
            try {
                $childNode->getTranslation('es');
            } catch (TranslationException $e) {
                $childNode->translation = new stdClass();
                $childNode->translation->name = '{missing translation}';
            }

            if($childNode->temporary != 1 && $childNode->type === $section) {
                $return .= '<li class="treedrag" id="' . $childNode->id . '">';
                $return .= '<div class="controls">';
                $return .= '<div class="mover"></div>';
                $return .= '<a class="nombre modificar ' .$level . '" href="' . $modify_url . '/' . $childNode->id . '">';
                $return .= '<span>' . $childNode->translation->name . '</span>';
                $return .= '</a>';
                $return .= '<a href="' . $delete_url . '/' . $childNode->id . '" class="eliminar" ></a>';
                $return .= '</div>';
                if (count($childNode->getChildren()) > 0) {
                    $return .= admin_tree($childNode->getChildren(), $level, $modify_url, $delete_url, $section);
                }
                $return .= '</li>';
            }

        }
        $return .= '</ul>';

        return $return;

    }

}
if ( ! function_exists('admin_structure_tree'))
{
    /**
     * Recursive function to create a tree structure
     *
     * @param $nodes
     * @param array $visible
     * @param string $attributes
     * @return string
     */
    function admin_structure_tree($nodes, array $visible, $attributes = '') {

        if ($attributes !== '') {
            $attributes = _stringify_attributes($attributes);
        }

        $return = '<ul'.$attributes.'>';

        foreach ($nodes as $childNode) {

            if($childNode->temporary !== 1 && $childNode->type === 'page' && (in_array($childNode->id, $visible) || $childNode->getChildren()) ) {

                //Set the translation so that its exposed in the node's variable
                try {
                    $childNode->getTranslation('es');
                } catch (TranslationException $e) {
                    $childNode->translation = new stdClass();
                    $childNode->translation->name = '{missing translation}';
                }

                $nivel = in_array($childNode->id, $visible) ? 'nivel1' : 'disabled';

                $return .= '<li>';
                $return .= '<a class="nombre modificar ' . $nivel . '" href="' . base_url('admin/page/edit') . '/' . $childNode->id . '">';
                $return .= '<span class="page">' . $childNode->translation->name . '</span>';
                $return .= '</a>';
                if (count($childNode->getChildren()) > 0) {
                    $return .= admin_structure_tree($childNode->getChildren(), $visible);
                }
                $return .= '</li>';
            }

        }
        $return .= '</ul>';

        return $return;

    }

}

if ( ! function_exists('admin_select_tree'))
{
    /**
     * Recursive function to create a tree select structure
     *
     * @param $node
     * @param $selected_id
     * @return string
     */
    function admin_select_tree($node, $selected_id) {

        $return = '';

        foreach ($node as $childNode) {

            if((int)$childNode->temporary !== 1) {
                try {
                    $childNode->getTranslation('es');
                } catch (TranslationException $e) {
                    $childNode->translation = new stdClass();
                    $childNode->translation->name = '{missing translation}';
                }
                $return .= '<option ' . ((int)$childNode->id === (int)$selected_id ? 'selected' : '') . ' value="' . $childNode->id . '">' . str_repeat("-", $childNode->depth) . ' ' . $childNode->translation->name . '</option>';
                if (count($childNode->getChildren()) > 0) {
                    $return .= admin_select_tree($childNode->getChildren(), $selected_id);
                }
            }

        }

        return $return;

    }

}

if ( ! function_exists('admin_cat_tree'))
{
    /**
     * Recursive function to create a tree structure
     *
     * @param $node
     * @param $level
     * @param array $item_methods
     * @param array $urls
     * @param array $attributes
     * @return string
     */
    function admin_cat_tree($node, $level, array $item_methods, array $urls, $attributes = array()) {

        $attrs = '';
        $CI = get_instance();

        if (!empty($attributes)) {
            $attrs = _stringify_attributes($attributes);
        }

        $return = '<ul'.$attrs.'>';

        foreach ($node as $childNode) {

            if($childNode->temporal !== 1) {

                try {
                    $childNode->getTranslation('es');
                } catch (TranslationException $e) {
                    $childNode->translation = new stdClass();
                    $childNode->translation->name = '{missing translation}';
                }

                //Get the items
                $items = $item_methods['class']::$item_methods['method']((int)$childNode->id);

                $return .= '<li class="pagina field" id="' . $childNode->id . '">';
                $return .= '<h3 class="header">Categoría: ' . $childNode->translation->name . '</h3>';
                $return .= '<ul id="list_' . $childNode->id . '" class="sorteable content" data-sort="' . $urls['sort'] . '/' . $childNode->id . '">';

                foreach ($items as $item) {

                    try {
                        $item->getTranslation('es');
                    } catch (TranslationException $e) {
                        $item->translation = new stdClass();
                        $item->translation->name = '{missing translation}';
                    }

                    $return .= '<li class="listado drag" id="' . $item->id . '">
                                    <div class="mover"></div>
                                    <a class="nombre modificar ' . $level . '" href="' . $urls['edit'] . '/' . $item->id . '">
                                        <span>' . $item->translation->name . '</span>
                                    </a>
                                    <a href="' . $urls['delete'] . '/' . $item->id . '" class="eliminar"></a>
                                </li>';
                }

                $return .= '</ul>';
                $return .= '<script type="text/javascript">initSortables($("list_'.$childNode->id.'"));</script>';
                if (count($childNode->getChildren()) > 0) {
                    $return .= admin_cat_tree($childNode->getChildren(), $level, $item_methods, $urls);
                }
                $return .= '</li>';
            }

        }
        $return .= '</ul>';

        return $return;

    }

}

if ( ! function_exists('admin_gallery_tree'))
{
    /**
     * Recursive function to create a tree structure
     *
     * @param $node
     * @param $level
     * @param array $item_methods
     * @param stdClass $dim
     * @param array $urls
     * @param array $names
     * @param array $attributes
     * @return string
     */
    function admin_gallery_tree($node, $level, array $item_methods, stdClass $dim, array $urls, array $names, $attributes = array()) {

        $attrs = '';
        $CI = get_instance();

        if (!empty($attributes)) {
            $attrs = _stringify_attributes($attributes);
        }

        $width = 100;

        //Proportional resize
        $ratio = $dim->imagenAncho / $dim->imagenAlto;   // get ratio for scaling image
        if( $ratio > 1) {
            $final_width = $width;
            $final_height = $width/$ratio;
        }
        else {
            $final_width = $width*$ratio;
            $final_height = $width;
        }

        $return = '<ul'.$attrs.'>';

        foreach ($node as $childNode) {

            if($childNode->temporal !== 1) {

                //Get the category children items
                $items = $CI->$item_methods['library']->$item_methods['method']((int)$childNode->id, 'es');

                $return .= '<li class="pagina field" id="' . $childNode->id . '">';
                $return .= '<h3 class="header">Categoría: ' . $childNode->$names['category'] . '</h3>';
                $return .= '<ul class="content">';

                $return .= '<fieldset id="upload-gallery_'.$childNode->id.'">
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre los archivos aquí</div>
                </div>
                <ul class="list galeria sorteable" id="list_'.$childNode->id.'" style="overflow: hidden" data-sort="' . $urls['sort'] . '/' . $childNode->id . '">';

                foreach ($items as $item) {
                    $data['item'] = $item;
                    $data['extension'] = $item['descargaArchivo'];
                    $data['final_width'] = $final_width;
                    $data['final_height'] = $final_height;
                    $return .= $CI->load->view('admin/item/gallery_view', $data, TRUE);
                }

                $return .= '</ul>' . PHP_EOL;
                $return .= '</fieldset>' . PHP_EOL;
                $return .= '</ul>' . PHP_EOL;
                $return .= '<script type="text/javascript">initSortables($("list_'.$childNode->id.'"));</script>' . PHP_EOL;
                $return .= '<script type="text/javascript">upload.gallery("upload-gallery_' . $childNode->id . '", "galeria/' . $childNode->id . '", ' . $dim->imagenAncho . ', ' . $dim->imagenAlto . ', "' . $level . '", "' . $urls['edit'] . '", "' . $urls['delete'] . '");</script>' . PHP_EOL;

                if (count($childNode->getChildren()) > 0) {
                    $return .= admin_gallery_tree($childNode->getChildren(), $level, $item_methods, $dim, $urls, $names);
                }
                $return .= '</li>';
            }

        }
        $return .= '</ul>';

        return $return;

    }

}

/**
 * Renders the menu
 *
 * @param string $name
 * @param \App\Category $tree
 * @param array $menu
 * @param array $attributes
 * @param string $view
 */
function render_menu($name = '', \App\Category $tree, array $menu, $attributes = array(), $view = 'pages_view')
{
    $CI = get_instance();
    $cache_time = 3000;

    $CI->benchmark->mark($name . '_menu_start');

    //Check if menu is cached
    if ( ! $return = $CI->cache->get('menu'))
    {
        render_menu_uncached($tree, $menu, $attributes, $view);
        if(ENVIRONMENT !== 'development') {
            $CI->cache->save('menu', $return, $cache_time);
        }
    }

    $CI->benchmark->mark($name . '_menu_end');

}

/**
 * Generate the menu if there is no cached version
 *
 * @param \App\Category $tree
 * @param array $menu
 * @param array $attributes
 * @param string $view
 */
function render_menu_uncached(\App\Category $tree, array $menu, $attributes = array(), $view)
{
    $CI = get_instance();
    $attrs = '';

    if (!empty($attributes)) {
        $attrs = _stringify_attributes($attributes);
    }

    $data['children'] = $tree->getChildren();
    $data['attrs'] = $attrs;
    $data['attrs_array'] = $attributes;
    $data['view'] = $view;
    $data['menu'] = $menu;
    $data['lang'] = Html::get_instance()->language->slug;

    $CI->load->view('menu/' . $view, $data);

}

/**
 * Renders the generic menu
 *
 * @param \Cartalyst\NestedSets\Nodes\EloquentNode $menu
 * @param array $path
 * @param $page
 * @param $show_items
 * @param string $view
 * @param array $attributes
 */
function render_generic_menu(\Cartalyst\NestedSets\Nodes\EloquentNode $menu, array $path, $page, $show_items, $view, $attributes = array())
{
    $CI = get_instance();
    $attrs = '';

    if (!empty($attributes)) {
        $attrs = _stringify_attributes($attributes);
    }

    $data['children'] = $menu->getChildren();
    $data['attrs'] = $attrs;
    $data['lang'] = $CI->m_idioma;
    $data['CI'] = $CI;
    $data['path'] = $path;
    $data['view'] = $view;
    $data['page'] = $page;
    $data['show_items'] = $show_items;

    $CI->load->view('menu/' . $view, $data);

}

/**
 * Checks whether a menu item has a sub-menu or not
 *
 * @param $childNode
 * @param array $menu
 * @return bool
 */
function has_dropdown($childNode, $menu = [])
{

    $children_count = $childNode->getChildrenCount();
    $has_dropdown = FALSE;

    if(
        $children_count OR
        count($menu) AND
        (
            (array_key_exists('page', $menu['catalog']) && $menu['catalog']['page']->paginaNombreURL === $childNode->paginaNombreURL) OR
            (array_key_exists('page', $menu['gallery']) && $menu['gallery']['page']->paginaNombreURL === $childNode->paginaNombreURL)
        )
    ) {
        $has_dropdown = TRUE;
    }

    return $has_dropdown;

}