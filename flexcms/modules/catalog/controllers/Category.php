<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:30 AM
 */

namespace catalog;
use App\Role;
use App\Translation;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

$_ns = __NAMESPACE__;


class Category extends \AdminController implements \AdminInterface {

    const SECTION = 'catalog';
    const URL_CREATE = 'admin/catalog/category/create/';
    const URL_UPDATE = 'admin/catalog/category/update/';
    const URL_DELETE = 'admin/catalog/category/delete/';
    const URL_INSERT = 'admin/catalog/category/insert';
    const URL_EDIT = 'admin/catalog/category/edit';
    const URL_REORDER = 'admin/catalog/category/reorder/';

    static protected $PAGE_ID;

    /**
     * List all the categories
     */
    public function index()
    {

        $page_id = $this->uri->segment(5);

        $root = \App\Category::find($page_id);
        $root->findChildren(999);

        //FIXME: check if this is correct
        $depth = 0;
        foreach (\App\Category::allLeaf() as $leaf) {
            if($depth < $leaf->getDepth()) {
                $depth = $leaf->getDepth();
            }
        }

        $data['root_node'] = $root;
        $data['tree_size'] = $depth;

        $data['txt_nuevo'] = 'crear nueva categoría';
        $data['titulo'] = 'Categorías';

        $data['url_sort'] = base_url(static::URL_REORDER . $page_id);
        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);

        $data['id'] = 'catalogo_tree';
        $data['nivel'] = 'nivel3';
        $data['section'] = static::SECTION;

        /*
         * MENU
         */
        $data['menu'][] = anchor(base_url(static::URL_CREATE . $page_id), 'crear nueva categoría', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        ]);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoArbol_view', $data);
    }

    /**
     * Create a new category
     */
    public function create()
    {
        $this->_showView(new \catalog\models\Category(), true);
    }

    /**
     * Insert the category in the DB
     * @return mixed
     */
    public function insert()
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{

            //Create the new page
            $node = new \catalog\models\Category();
            $node->makeLastChildOf(\App\Category::find($this->input->post('page_id')));
            $node->temporary = 0;
            $this->_store($node);
            $response->new_id = $node->id;

        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al insertar la categor&iacute;a!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);
    }

    /**
     * Edit the category data
     * @param $id
     * @return string
     */
    public function edit($id)
    {
        $this->_showView(\App\Category::find($id));
    }

    /**
     * Update the category in the DB
     * @param $id
     * @return string
     */
    public function update($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $this->_store(\catalog\models\Category::find($id));
            $response->new_id = $id;
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al modificar la categor&iacute;a!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Delete the category from the DB
     * @param $id
     * @return string
     */
    public function delete($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{

            //Delete the node with children
            $node = \catalog\models\Category::find($id);
            $node->delete();

            //Delete the category's translations
            $translations = Translation::where('parent_id', $id)->where('type', static::SECTION . '_category');
            $translations->delete();

        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar la categor&iacute;a!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Reorder the categories
     * @return string
     */
    public function reorder($page_id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $pages = \catalog\models\Category::find($page_id);
            $pages->mapTree(json_decode($this->input->post('posiciones'), true));
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al reorganizar las categor&iacute;as!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

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

        $data['category'] = $model;
        $data['title'] = $new ? 'Nueva Categor&iacute;a' : 'Editar Categor&iacute;a';
        $data['txt_boton'] = $new ? 'Crear Categoría' : 'Modificar Categoría';
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $model->id);
        $data['nuevo'] = $new ? 'nuevo' : '';
        $data['page_id'] = $this->uri->segment(5);
        $data['groups'] =  Role::all();

        $data['url_edit'] = static::URL_EDIT;
        $data['url_delete'] = static::URL_DELETE;
        $data['url_reorder'] = static::URL_REORDER;

        $data['translations'] = $model->getTranslations(static::SECTION . '_category');

        $this->load->view('catalog/category_view',$data);

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {
        $model->type = static::SECTION;
        $model->group_visibility = $this->input->post('group_visibility');
        $model->save();
        $model->setTranslations($this->input->post());
        return $model;
    }
}