<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Config;
use App\Category;
use App\Language;
use App\Content;
use App\Row;

class Structure extends BaseController implements AdminInterface {
	
	var $theme;

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

		$data['url_reorganizar'] = base_url('admin/structure/reorder');
		$data['url_rel'] = base_url('admin/structure');

		$data['edit_url'] = base_url('admin/structure/edit');
		$data['delete_url'] = base_url('admin/structure/delete');
		$data['name'] = 'paginaNombre';

		$data['nivel'] = 'nivel2';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/structure/create'), 'crear nueva página', $atts);

        $data['bottomMargin'] = count($data['menu']) * 34;
		
		$this->load->view('admin/listadoArbol_view', $data);

	}

	/**
	 * Show the create view
	 */
	public function create()
	{
		$this->_showView($this->insert()->id, TRUE);
	}

	/**
	 * Show the edit view
	 *
	 * @param $id
	 * @return string
	 */
	public function edit($id)
	{
		$this->_showView($id);
	}

	/**
	 * Inserts a new temporary page
	 *
	 * @return Category
	 */
	public function insert()
	{
		return Category::insertTemporary();
	}

	/**
	 * Shows the edit/create view
	 *
	 * @param $id
	 * @param bool $new
	 *
	 * @return mixed
	 */
	public function _showView($id, $new = FALSE)
	{
		$category = Category::find($id);
		$data = $category->toArray();
		$data['translations'] = $category->getTranslations();

		$data['idiomas'] = Language::all();
		$data['nuevo'] = $new ? 'nuevo' : '';
		$data['removeUrl'] = $new ? base_url( 'admin/structure/delete/' . $id) : '';
		$data['theme'] = $this->theme;

		$root = Category::allRoot()->first();
		$root->findChildren(999);
		$data['pages'] = $root->getChildren();

		$data['groups'] =  \App\Role::all();

		$data['titulo'] = $new ? "Crear Pagina" : "Modificar Pagina";
		$data['txt_boton'] = $new ? "crear" : "modificar";
		$data['link'] = base_url("admin/structure/update/" . $data['id']);

		$page_data = json_decode($data['data']);
		$data['structure'] = $page_data ? $page_data->structure : array();

		return $this->load->view('page', $data);
	}
	
	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			Category::updatePage($id, $this->input->post());
			$response->new_id = $id;

		} catch (Exception $e) {
			$response = $this->error('Ocurri&oacute; un problema al actualizar la p&aacute;ina!', $e);
		}

		$this->load->view('request/json', [ 'return' => $response ] );

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

		$this->load->view('request/json', [ 'return' => $response ] );

	}

	//TODO check for unique names in nodes at the same depth
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

		$this->load->view('admin/request/json', [ 'return' => $response ] );

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

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);

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

}
