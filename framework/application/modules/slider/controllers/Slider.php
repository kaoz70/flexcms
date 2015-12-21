<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider extends BaseController implements AdminInterface {
	 
	function __construct(){
		parent::__construct();
		/*$this->load->helper('text');
		
		$this->load->library('image_lib');
		
		$this->load->model('banners_model', 'Banners');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();*/
		
	}
	
	public function index()
	{

        $data['items'] = \App\Slider::all();

        $data['url_rel'] = base_url('admin/sliders/slider');
        $data['url_sort'] = '';
        $data['url_edit'] = base_url('admin/slider/edit');
        $data['url_eliminar'] = base_url('admin/slider/delete');
        $data['url_modificar'] = base_url('admin/slider/edit');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'banners';

        $data['idx_id'] = 'id';
        $data['idx_nombre'] = 'name';

        $data['txt_titulo'] = 'Banners';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n2'
        );
        $data['menu'][] = anchor(base_url('admin/slider/create'), 'crear nuevo banner', $atts);

        $atts = array(
            'id' => 'editarTemplateBanner',
            'class' => $data['nivel'] . ' nivel2 ajax boton n1'
        );
        $data['menu'][] = anchor(base_url('admin/slider/field'), 'editar template', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
	}
	
	public function create()
	{
		$this->_showView($this->insert()->id, TRUE);
	}

	public function insert()
	{
		$slider = new \App\Slider();
		$slider->save();
		return $slider;
	}

	public function edit($id)
	{
		$this->_showView($id);
	}

	public function _showView( $id, $new = false ) {

		$data = \App\Slider::find($id)->toArray();
		$data['banner_config'] = \App\Slider::getTypes();
		$config = json_decode($data['config'], TRUE);
		$data['config'] = $config ? $config : [];
		$data['nuevo'] = $new;
		$data['removeUrl'] = $new ? base_url("admin/slider/delete/" . $id) : '';
		$data['titulo'] = $new ? "Crear Slider" : "Modificar Slider";
		$data['txt_boton'] = $new ? "crear" : "modificar";
		$data['link'] = base_url("admin/slider/update/" . $id);

		$this->load->view('slider_view', $data);
	}

	public function update($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $slider = \App\Slider::find($id);
	        $slider->store($this->input->post());
            $response->new_id = $this->input->post('id');
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el banner!', $e);
        }

        $this->load->view('admin/request/json', [ 'return' => $response ] );

	}
	
	public function delete($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $slider = \App\Slider::find($id);

	        //TODO remove slider's images

	        $slider->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el banner!', $e);
        }

        $this->load->view('admin/request/json', [ 'return' => $response ] );

	}

	public function reorder($id){

	}

}
