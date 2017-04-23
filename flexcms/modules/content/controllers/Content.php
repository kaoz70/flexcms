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
use App\File;
use App\ImageConfig;
use App\ImageSection;
use App\Language;
use App\Page;
use App\Response;
use App\Translation;
use App\Widget;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Image;
use stdClass;

class Content extends \RESTController implements \AdminInterface
{

    private static $image_folder = './assets/images/content/';

    public static function index($page_id)
    {

        $page = Page::find($page_id);
        $widget = Widget::getContentWidget($page_id);

        $data['items'] = static::getItems($page_id);
        $data['menu'] = [
            [
                'title' => 'ima&aacute;enes',
                'icon' => 'photo',
                'url' => 'page/' . $page_id  . '/images/',
            ],
            [
                'title' => 'configuraci&oacute;n',
                'icon' => 'settings',
                'url' => 'page/' . $page_id  . '/config/' . $widget->id,
            ],
            [
                'title' => 'nuevo',
                'icon' => 'add',
                'url' => 'page/' . $page_id  . '/content/create/',
            ],
        ];

        try {

            $data['title'] = $page->getTranslation(Language::getDefault()->id) ?
                $page->getTranslation(Language::getDefault()->id)->name :
                "{missing translation}";

        } catch (\TranslationException $e) {
            $data['title'] = "{Missing translation}";
        }

        return $data;

    }

    private static function getItems($id)
    {
        $widget = Widget::getContentWidget($id);
        $contentOrder = $widget->getConfig()->order;
        return \App\Content::getByPage($id, Language::getDefault()->id, $contentOrder);
    }

    /**
     * Gets one resource
     *
     * @param null $id
     * @return mixed
     */
    public function index_get($id = null)
    {

        $response = new Response();

        try{
            $response->setData(\App\Content::getForEdit($id));
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obtener el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Update a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_put($id)
    {

        $response = new Response();

        try {

            $content = $this->_store(\App\Content::findOrNew($id), $this->put());
            $items = static::getItems($content->category_id);

            $data = [
                'content' => \App\Content::getForEdit($content->id),
                'items' => $items,
            ];

            $response->setMessage('Contenido actualizado correctamente');
            $response->setData($data);

        } catch (ErrorException $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar el contenido!', $e);
        } catch (NotReadableException $e) {
            $response->setError('Ocurri&oacute; un problema al crear el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Insert a new resource
     *
     * @return mixed
     */
    public function index_post()
    {

        $response = new Response();

        try {

            $content = $this->_store(new \App\Content(), $this->post());
            $items = static::getItems($content->category_id);
            $content->position = $items->count();
            $content->save();

            //We get all the items again because if its a new Content it did'nt have the position set yet
            $items = static::getItems($content->category_id);

            $data = [
                'content' => \App\Content::getForEdit($content->id),
                'items' => $items,
            ];

            $response->setMessage('Contenido creado correctamente');
            $response->setData($data);

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al crear el contenido!', $e);
        } catch (ErrorException $e) {
            $response->setError('Ocurri&oacute; un problema al crear el contenido!', $e);
        } catch (NotReadableException $e) {
            $response->setError('Ocurri&oacute; un problema al crear el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Delete a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_delete($id)
    {

        $response = new Response();

        try{

            //Delete the content
            $content = \App\Content::find($id);
            $content->delete();

            //Delete the content's translations
            $translations = Translation::where('parent_id', $id)->where('type', \App\Content::getType());
            $translations->delete();

            $response->setMessage('Contenido eliminado satisfactoriamente');

            $items = static::getItems($content->category_id);

            $response->setData($items);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model, $data)
    {

        $model->css_class = isset($data['css_class']) ? $data['css_class'] : '';
        $model->enabled = (bool) $data['enabled'];
        $model->important = (bool) $data['important'];
        $model->category_id = $data['category_id'];
        $model->timezone = $data['timezone'];
        $model->publication_start = isset($data['publication_start']) ? $data['publication_start'] : NULL;
        $model->publication_end = isset($data['publication_end']) ? $data['publication_end'] : NULL;
        $model->save();

        $model->setTranslations($data['translations']);
        $model->setImages($data['images']);

        return $model;

    }

    /**
     * Content settings
     *
     * @param $widget_id
     * @return mixed
     */
    public function config_get($widget_id)
    {

        $response = new Response();

        try{
            $response->setData($this->getConfig($widget_id));
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    private function getConfig($widget_id)
    {

        $widget = Widget::find($widget_id);
        $data['page'] = Category::getForEdit($widget->category_id);
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
    public function config_put($widget_id)
    {

        $response = new Response();

        try{

            $configData = $this->put('config');

            //Update the widget's config
            $widget = Widget::find($widget_id);
            $widget->setConfig($configData);

            //Update the page's config
            $pageData = $this->put('page');

            $page = Page::find($widget->category_id);
            $page->popup = (bool) $pageData['popup'];
            $page->enabled = (bool) $pageData['enabled'];
            $page->group_visibility = $pageData['group_visibility'];
            $page->setTranslations($pageData['translations']);
            $page->save();

            $response->setData(static::getItems($page->id));
            $response->setMessage('Configuraci&oacute;n guardada correctamente');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema guardar la configuraci&oacute;n!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Get the list of content views from the current theme
     *
     * @param $theme
     * @param $view
     * @return array
     * @throws Exception
     */
    private function getViews($theme, $view)
    {
        $path = FCPATH . 'themes/' . $theme . '/views/pages/content/';
        if(!file_exists($path)) {
            throw new Exception("Path does not exist: ");
        }
        return array_values(preg_grep("~^{$view}_.*\.(php)$~", scandir($path)));
    }

    public function reorder_put($page_id)
    {
        $response = new Response();

        try{

            \App\Content::reorder($this->put(), $page_id);

            $widget = Widget::getContentWidget($page_id);
            $contentOrder = $widget->getConfig()->order;

            if($contentOrder == 'manual') {
                $response->setMessage('Se guard&oacute; el nuevo orden de elementos');
            } else {
                $response->setType('warning');
                $response->setMessage('Se guard&oacute; el nuevo orden de elementos, pero el listado no tiene orden manual');
            }

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al reorganizar el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

}