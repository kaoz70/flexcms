<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Config;
use App\Category;
use App\Language;
use App\Content;
use App\Row;
use Illuminate\Database\Eloquent\Model;

class Structure extends BaseController implements AdminInterface {

    var $theme;

    const URL_CREATE = 'admin/structure/create';
    const URL_UPDATE = 'admin/structure/update/';
    const URL_DELETE = 'admin/structure/delete/';
    const URL_INSERT = 'admin/structure/insert';
    const URL_REORDER = 'admin/structure/reorder';
    const URL_EDIT = 'admin/structure/edit/';

    function __construct(){
        parent::__construct();

        $this->load->library('Seguridad');

        $config = Config::get();
        $this->theme = $config['theme'];

        $this->seguridad->init();

    }

    /**
     * Show a formatted list of pages
     */
    public function index()
    {

        $root = Category::allRoot()->first();
        $root->findChildren(999);

        $depth = 0;
        foreach (Category::allLeaf() as $leaf) {
            if($depth < $leaf->getDepth()) {
                $depth = $leaf->getDepth();
            }
        }

        $data['root_node'] = $root;
        $data['tree_size'] = $depth;

        $data['titulo'] = 'Estructura';
        $data['id'] = 'pagina_tree';

        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);
        $data['url_sort'] = base_url(static::URL_REORDER);
        $data['name'] = 'paginaNombre';
        $data['section'] = 'page';

        $data['nivel'] = 'nivel2';

        /*
         * MENU
         */
        $data['menu'][] = anchor(base_url(static::URL_CREATE), 'crear nueva página', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        ]);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoArbol_view', $data);

    }

    /**
     * Show the create view
     */
    public function create()
    {
        $this->_showView($this->insert(), TRUE);
    }

    /**
     * Show the edit view
     *
     * @param $id
     * @return string
     */
    public function edit($id)
    {
        $this->_showView(\admin\Category::find($id));
    }

    /**
     * Inserts a new temporary page
     *
     * @return Category
     */
    public function insert()
    {
        return \admin\Category::insertTemporary('page');
    }

    /**
     * Shows the edit/create view
     *
     * @param $model
     * @param bool $new
     *
     * @return mixed
     */
    public function _showView(Model $model, $new = FALSE)
    {
        $category = $model;
        $data = $category->toArray();
        $data['translations'] = $category->getTranslations('page');

        $data['idiomas'] = Language::all();
        $data['nuevo'] = $new ? 'nuevo' : '';
        $data['removeUrl'] = $new ? base_url( static::URL_DELETE . $model->id) : '';
        $data['theme'] = $this->theme;

        $root = Category::allRoot()->first();
        $root->findChildren(999);
        $data['pages'] = $root->getChildren();

        $data['roles'] =  \App\Role::all();

        $data['titulo'] = $new ? "Crear Pagina" : "Modificar Pagina";
        $data['txt_boton'] = $new ? "crear" : "modificar";
        $data['link'] = base_url(static::URL_UPDATE . $data['id']);

        $page_data = json_decode($data['data']);
        $data['structure'] = $page_data ? $page_data->structure : array();

        return $this->load->view('page', $data);
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            \admin\Category::updatePage($id, $this->input->post());
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar la p&aacute;gina!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ]);

    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            Category::remove($id);
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar la p&aacute;ina!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ] );

    }

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
    public function get($type = 'html')
    {
        $root = Category::allRoot()->first();
        $root->findChildren(999);
        $this->load->view('admin/request/' . $type, [
            'return' => admin_structure_tree($root->getChildren(), Content::getEditable(TRUE))
        ] );
    }

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
    public function _store(Model $model)
    {
        // TODO: Implement _store() method.
    }
}
