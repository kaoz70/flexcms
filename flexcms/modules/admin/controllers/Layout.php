<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Config;
use App\Category;
use App\Language;
use App\Content;
use App\Response;
use App\Row;
use Illuminate\Database\Eloquent\Model;

class Layout extends RESTController {

    function __construct(){
        parent::__construct();
        $config = Config::get();
        $this->theme = $config['theme'];
    }

    /**
     * Gets one or all resources
     *
     * @param null $id
     * @return mixed
     */
    public function index_get($id = null)
    {

        $response = new Response();

        try{

            $page = \App\Page::findOrNew($id);
            $page->setLanguage(Language::orderBy('position', 'asc')->first());
            $data['page'] = $page;

            $data['widgets'] = \App\Widget::getInstalled();
            $data['theme'] = $this->theme;

            $root = Category::find(1);
            $data['pages'] = $root->descendants();

            $data['roles'] =  \App\Role::all();

            $response->setData($data);

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema!', $e);
        }

        $this->response($response);

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

        try{

            $page = \App\Page::findOrFail($id);
            $page->popup = $this->put('popup');
            $page->enabled = $this->put('enabled');
            $page->group_visibility = $this->put('group_visibility');
            $page->data = $this->put('data');
            $page->save();

            $page->setTranslations($this->put('translations'));

            $data = [
                'page' => $page,
                'pages' => $this->tree(),
            ];

            $response->setData($data);

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema!', $e);
        }

        $this->response($response);
    }

    /**
     * Insert a new resource
     *
     * @return mixed
     */
    public function index_post()
    {

        $response = new Response();

        try{

            $root = \App\Page::find(1);
            $page = $root->children()->create([
                'popup' => $this->post('popup'),
                'enabled' => $this->post('enabled'),
                'group_visibility' => $this->post('group_visibility'),
                'data' => $this->post('data'),
            ]);

            $page->setTranslations($this->post('translations'));

            $data = [
                'page' => $page,
                'pages' => $this->tree(),
            ];

            $response->setData($data);
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar la p&aacute;gina!', $e);
        }

        $this->response($response);

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

            $page = \App\Page::findOrFail($id);

            //Delete translations
            $translations = \App\Translation::where('type', \App\Page::getType())
                ->where('parent_id', $page->id);
            $translations->delete();

            //Delete the page
            $page->delete();

            $response->setData($this->tree());
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar la p&aacute;gina!', $e);
        }

        $this->response($response);
    }

    /**
     * @return \Baum\Extensions\Eloquent\Collection
     */
    private function tree()
    {
        //Get the root page
        $root = \App\Page::find(1);
        return $root->getDescendantsLang(\App\Language::getDefault())->toHierarchy();
    }

}
