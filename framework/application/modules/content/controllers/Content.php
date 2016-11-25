<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 7/29/2016
 * Time: 2:12 PM
 */

namespace content;
$_ns = __NAMESPACE__;

use App\Category;
use App\Config;
use App\Response;
use App\Translation;
use App\Widget;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Content extends \AdminController implements \ContentInterface
{

    const URL_CREATE = 'content/create/';
    const URL_UPDATE = 'content/update/';
    const URL_DELETE = 'content/delete/';
    const URL_INSERT = 'content/insert';
    const URL_EDIT = 'content/edit';
    const URL_REORDER = 'content/reorder/';
    const URL_SEARCH = 'content/search/';
    const URL_CONFIG = 'config';
    const URL_SAVE_CONFIG = 'content/config_save';

    public static function index($page_id)
    {

        //We use this because we are in a static context
        $CI = &get_instance();

        $page = Category::find($page_id);
        $page->setType('page');
        $widget = Widget::getContentWidget($page_id);

        $data['items'] = static::getItems($page_id);
        $data['menu'] = [
            [
                'title' => 'configuraci&oacute;n',
                'icon' => 'settings',
                'url' => 'page/' . $page_id  . '/' . static::URL_CONFIG . '/' . $widget->id,
            ],
            [
                'title' => 'nuevo',
                'icon' => 'add',
                'url' => 'page/' . $page_id  . '/' . static::URL_CREATE,
            ],
        ];

        try {
            $data['title'] = $page->getTranslation(1) ? $page->getTranslation(1)->name : "{missing translation}";
        } catch (\TranslationException $e) {
            $data['title'] = "{Missing translation}";
        }

        $CI->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $data]);

    }

    /**
     * Create form interface
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Edit form interface
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {

        $response = new Response();

        try{
            $content = \App\Content::getForEdit($id);
            $response->setSuccess(true);
            $response->setData($content);
        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al obtener el contenido!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Insert the item into database
     *
     * @return mixed
     */
    public function insert()
    {
        //
    }

    /**
     * Update the item in the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($id)
    {

        $response = new Response();

        try{

            $content = $this->_store(\App\Content::findOrNew($id));
            $items = static::getItems($content->category_id);

            //New content set the position
            if(!$id) {
                $content->position = $items->count();
                $content->save();
            }

            $response->setSuccess(true);
            $response->setMessage('Contenido actualizado correctamente');

            $response->setData($items);

        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al actualizar el contenido!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    private static function getItems($id)
    {
        $widget = Widget::getContentWidget($id);
        $contentOrder = $widget->getConfig()->order;
        return \App\Content::getByPage($id, 1, $contentOrder);
    }

    /**
     * Remove the item from the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {

        $response = new Response();

        try{

            //Get the content
            $content = \App\Content::find($id);

            //Delete the content's translations
            $translations = Translation::where('parent_id', $id)->where('type', $content->getType());
            $translations->delete();

            //Delete the content
            $content->delete();

            $response->setSuccess(true);
            $response->setMessage('Contenido eliminado satisfactoriamente');

            $items = static::getItems($content->category_id);

            $response->setData($items);

        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al eliminar el contenido!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {

        $contentPost = json_decode($this->input->post('content'));

        $model->css_class = $contentPost->css_class;
        $model->enabled = (bool) $contentPost->enabled;
        $model->important = (bool) $contentPost->important;
        $model->category_id = $contentPost->category_id;
        $model->timezone = $contentPost->timezone;
        $model->publication_start = $contentPost->publication_start;
        $model->publication_end = $contentPost->publication_end;
        $model->save();

        $model->setTranslations(json_decode($this->input->post('translations')));

        return $model;

    }

    /**
     * Content settings
     *
     * @param $widget_id
     * @return mixed
     */
    public function config($widget_id)
    {

        $response = new Response();

        try{
            $response->setData($this->getConfig($widget_id));
            $response->setSuccess(true);
        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al eliminar el contenido!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    private function getConfig($widget_id)
    {

        $widget = Widget::find($widget_id);
        $data = Category::getForEdit($widget->category_id);
        $data['config'] = $widget->getConfig();

        $data['roles'] =  \App\Role::all();

        $theme = Config::theme();
        $data['list_views'] = $this->getViews($theme, 'list');
        $data['detail_views'] = $this->getViews($theme, 'detail');

        return $data;

    }

    /**
     * Save the widget's settings for the content
     *
     * @param $widget_id
     * @return mixed
     */
    public function config_save($widget_id)
    {

        $response = new Response();

        try{

            $configData = $this->input->post('config');

            //Update the widget's config
            $widget = Widget::find($widget_id);
            $widget->setConfig($configData);

            //Update the page's config
            $pageData = json_decode($this->input->post('page'), true);

            $page = \admin\Category::find($widget->category_id);
            $page->popup = (bool) $pageData['popup'];
            $page->enabled = (bool) $pageData['enabled'];
            $page->group_visibility = $pageData['group_visibility'];
            $page->setTranslations($this->input->post('translations'));
            $page->save();

            $response->setData(static::getItems($page->id));
            $response->setSuccess(true);
            $response->setMessage('Configuraci&oacute;n guardada correctamente');

        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema guardar la configuraci&oacute;n!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    /**
     * Get the list of content views from the current theme
     *
     * @param $theme
     * @param $view
     * @return array
     */
    private function getViews($theme, $view)
    {
        return array_values(preg_grep("~^{$view}_.*\.(php)$~", scandir(FCPATH . 'themes/' . $theme . '/views/pages/content/')));
    }

    public function reorder($page_id)
    {
        $response = new Response();

        try{

            \App\Content::reorder($this->input->post('order'), $page_id);

            $widget = Widget::getContentWidget($page_id);
            $contentOrder = $widget->getConfig()->order;

            if($contentOrder == 'manual') {
                $response->setSuccess(true);
                $response->setMessage('Se guard&oacute; el nuevo orden de elementos');
            } else {
                $response->setType('warning');
                $response->setMessage('Se guard&oacute; el nuevo orden de elementos, pero el listado no tiene orden manual');
            }

        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al reorganizar el contenido!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );
    }

}