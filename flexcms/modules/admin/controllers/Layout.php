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
     * Show the edit view
     *
     * @param $id
     * @return string
     */
    public function edit($id)
    {

        $response = new \App\Response();

        try {

            $page = \App\Page::getForEdit($id);

            $data['page'] = $page;
            $data['widgets'] = \App\Widget::getInstalled();
            $data['theme'] = $this->theme;

            $root = Category::allRoot()->first();
            $root->findChildren(999);
            $data['pages'] = $root->getChildren();

            $data['roles'] =  \App\Role::all();

            $data['rows'] = $page->data ? $page->data->structure : [];

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Hubo un problema para obtener la estructura', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Inserts a new temporary page
     *
     * @return Category
     */
    public function insert()
    {
        //
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            \App\Page::updatePage($id, $this->input->post());
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar la p&aacute;gina!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ]);

    }

    /*public function delete()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $ids = $this->input->post('ids');
            Category::remove($ids);
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar la p&aacute;ina!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ] );

    }*/

    //TODO check for unique names in nodes only at the same depth
    public function reorder()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $pages = Category::find(1);
            $pages->mapTree(json_decode($this->input->post('posiciones'), true));
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al reorganizar las p&aacute;inas!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ] );

    }

    /**
     * Copies the structure from one page to another
     */
    public function copy()
    {

        $response = new stdClass();

        try {
            Row::copyStructure($this->input->post());
            $response->code = 1;
        } catch (Exception $e) {
            $response = $this->error('Hubo un error al copiar la estructura', $e);
        }

        $data[$this->responseVarName] = $response;
        $this->load->view($this->responseView, $data);

    }

    /**
     * Show the website's structure
     * TODO: json version, only html is available
     *
     * @param string $type
     */
    /*public function get($type = 'html')
    {
        $root = Category::allRoot()->first();
        $root->findChildren(999);
        $this->load->view('admin/request/' . $type, [
            'return' => admin_structure_tree($root->getChildren(), Content::getEditable(TRUE))
        ] );
    }*/

    public function addRow($page_id, $col_quantity)
    {
        $data = Row::add($page_id, $col_quantity);
        $this->load->view('widgets/row_view', $data);
    }

    public function removeRow($page_id, $row_id)
    {
        Row::remove($page_id, $row_id);
    }

    public function sortRows($page_id)
    {
        Row::sort($page_id, $this->input->post());
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model, $data)
    {
        // TODO: Implement _store() method.
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

           /* if($page = \App\Page::getForEdit($id)) {
                $data['page'] = $page;
                $data['rows'] = $page->data ? $page->data->structure : [];
            }*/

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
        // TODO: Implement index_put() method.
    }

    /**
     * Insert a new resource
     *
     * @return mixed
     */
    public function index_post()
    {
        // TODO: Implement index_post() method.
    }

    /**
     * Delete a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_delete($id)
    {
        // TODO: Implement index_delete() method.
    }
}