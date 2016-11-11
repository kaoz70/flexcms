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
use App\Widget;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Content extends \AdminController implements \ContentInterface
{

    const URL_CREATE = 'admin/content/create/';
    const URL_UPDATE = 'admin/content/update/';
    const URL_DELETE = 'admin/content/delete/';
    const URL_INSERT = 'admin/content/insert';
    const URL_EDIT = 'admin/content/edit';
    const URL_REORDER = 'admin/content/reorder/';
    const URL_SEARCH = 'admin/content/search/';
    const URL_CONFIG = 'admin/content/config/';
    const URL_SAVE_CONFIG = 'admin/content/config_save/';

    public static function index($page_id)
    {

        //We use this because we are in a static context
        $CI = &get_instance();

        $page = Category::find($page_id);
        $page->setType('page');
        $widget = Widget::getContentWidget($page_id);
        $contentOrder = $widget->getConfig()->order;

        $data['items'] = \App\Content::getByPage($page_id, 1, $contentOrder)->getDictionary();
        $data['menu'] = [
            ['title' => 'configuraci&oacute;n', 'url' => static::URL_CONFIG],
            ['title' => '"crear nuevo contenido', 'url' => static::URL_CREATE],
        ];

        try {
            $data['title'] = $page->getTranslation('es') ? $page->getTranslation('es')->name : "{missing translation}";
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
        $content = new \App\Content();
        $content->category_id = $this->uri->segment(4);
        $this->_showView($content, true);
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

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    /**
     * Insert the item into database
     *
     * @return mixed
     */
    public function insert()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $content = $this->_store(new \App\Content());
            $content->position = \App\Content::where('category_id', $this->input->post('page_id'))->get()->count();
            $content->save();
            $response->new_id = $content->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al insertar el contenido!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

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
            $this->_store(\App\Content::find($id));
            $response->setSuccess(true);
            $response->setMessage('Contenido actualizado correctamente');
        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al actualizar el contenido!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

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
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $content = \App\Content::find($id);
            $content->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el contenido!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));
    }

    /**
     * Shows the editor view
     *
     * @param Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $model, $new = FALSE)
    {

        $data['content'] = $model;
        $data['title'] = $new ? 'Nuevo Contenido' : 'Modificar Contenido';
        $data['nuevo'] = $new ? 'nuevo' : '';
        $data['edit_url'] = static::URL_EDIT;
        $data['delete_url'] = static::URL_DELETE;
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $model->id);
        $data['translations'] = $model->getTranslations('content');
        $data['txt_boton'] = $new ? 'Crear' : 'Modificar';

        $this->load->view('content/edit_view', $data);

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

        $widget = Widget::find($widget_id);
        $data['config'] = $widget->getConfig();
        $data['save_url'] = base_url(static::URL_SAVE_CONFIG . $widget_id);

        $category = \admin\Category::find($widget->category_id);
        $data['translations'] = $category->getTranslations('page');
        $data['page'] = $category;
        $data['roles'] =  \App\Role::all();

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

            //Update the widget's config
            $widget = Widget::find($widget_id);
            $widget->setConfig($this->input->post());

            //Update the page's config
            $page = \admin\Category::find($widget->category_id);
            $page->popup = (bool) $this->input->post('popup');
            $page->enabled = $this->input->post('enabled');
            $page->group_visibility = $this->input->post('group_visibility');
            $page->setTranslations($this->input->post());
            $page->save();

        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema guardar la configuraci&oacute;n!', $e);
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
        return preg_grep("~^{$view}_.*\.(php)$~", scandir(FCPATH . 'themes/' . $theme . '/views/pages/content/'));
    }

    public function reorder($page_id)
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            \App\Content::reorder($this->input->post('posiciones'), $page_id);
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al reorganizar el contenido!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );
    }

}