<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 7/29/2016
 * Time: 2:12 PM
 */

namespace catalog;
$_ns = __NAMESPACE__;

use App\Category;
use App\Config;
use App\Widget;
use Exception;
use stdClass;

class Content extends \AdminController
{

    const URL_CREATE = 'admin/catalog/product/create/';
    const URL_UPDATE = 'admin/catalog/product/update/';
    const URL_DELETE = 'admin/catalog/product/delete/';
    const URL_INSERT = 'admin/catalog/product/insert';
    const URL_EDIT = 'admin/catalog/product/edit/';
    const URL_REORDER = 'admin/catalog/product/reorder/';
    const URL_SEARCH = 'admin/catalog/product/search/';
    const URL_CONFIG = 'admin/catalog/config/';
    const URL_SAVE_CONFIG = 'admin/catalog/config_save/';

    public static function index($page_id)
    {

        $CI = &get_instance();

        $root = Category::find($page_id);
        $root->findChildren(999);

        $data['root_node'] = $root;

        $data['url_rel'] = base_url('admin/catalog');
        $data['url_search'] = base_url("admin/search/productos");

        $data['urls'] = array(
            'edit' => base_url(static::URL_EDIT . $page_id),
            'delete' => base_url(static::URL_DELETE),
            'sort' => base_url(static::URL_REORDER),
        );

        $data['item_methods'] = array(
            'class' => '\catalog\models\Product',
            'method' => 'get',
        );

        $data['search'] = true;
        $data['drag'] = true;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'catalogo';

        $data['txt_titulo'] = 'Cat&aacute;logo';
        $data['txt_grupoNombre'] = 'Categoría';

        $data['menu'][] = anchor(base_url(static::URL_CREATE . $page_id), 'crear nuevo producto', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n4'
        ]);

        $data['menu'][] = anchor(base_url('admin/catalog/category/index/' . $page_id), 'categor&iacute;as', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton n3'
        ]);

        $data['menu'][] = anchor(base_url('admin/catalog/field'), 'template', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton n2'
        ]);

        $data['menu'][] = anchor(base_url('admin/catalog/config'), 'configuracion', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton n1'
        ]);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $CI->load->view('admin/listadoCategorias_view', $data);

    }

    /**
     * Content settings
     *
     * @param $widget_id
     * @return mixed
     */
    public function config($widget_id)
    {

        $widget = Widget::find($widget_id);
        $data['config'] = $widget->getConfig();
        $data['save_url'] = base_url(static::URL_SAVE_CONFIG . $widget_id);

        $theme = Config::theme();
        $data['list_views'] = $this->getViews($theme, 'list');
        $data['detail_views'] = $this->getViews($theme, 'detail');

        $this->load->view('content/config_view', $data);

    }

    /**
     * Save the widget's settings for the content
     *
     * @param $widget_id
     * @return mixed
     */
    public function config_save($widget_id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $widget = Widget::find($widget_id);
            $widget->setConfig($this->input->post());
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema guardar la configuraci&oacute;n!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

}