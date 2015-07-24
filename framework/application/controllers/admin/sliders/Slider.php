<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider extends MY_Controller implements AdminInterface {
	 
	function __construct(){
		parent::__construct();
		$this->load->helper('text');
		
		$this->load->library('image_lib');
		
		$this->load->model('banners_model', 'Banners');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
	}
	
	public function index()
	{

        $data['items'] = $this->Banners->getAll();

        $data['url_rel'] = base_url('admin/sliders/slider');
        $data['url_sort'] = '';
        $data['url_edit'] = base_url('admin/sliders/slider/edit');
        $data['url_eliminar'] = base_url('admin/sliders/slider/delete');
        $data['url_modificar'] = base_url('admin/sliders/slider/edit');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'banners';

        $data['idx_id'] = 'bannerId';
        $data['idx_nombre'] = 'bannerName';

        $data['txt_titulo'] = 'Banners';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n2'
        );
        $data['menu'][] = anchor(base_url('admin/sliders/slider/create'), 'crear nuevo banner', $atts);

        $atts = array(
            'id' => 'editarTemplateBanner',
            'class' => $data['nivel'] . ' nivel2 ajax boton n1'
        );
        $data['menu'][] = anchor(base_url('admin/sliders/field'), 'editar template', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
	}
	
	public function create()
	{

		$bannerId = $this->insert();
		$data['bannerId'] = $bannerId;
		$data['bannerName'] = '';
		$data['bannerClass'] = '';
		$data['bannerType'] = 'bxSlider';
		$data['bannerEnabled'] = 'checked="checked"';
		$data['banner_config'] = $this->Banners->getTypes();
		$data['config'] = array();
		$data['txt_botImagen'] = 'Subir Imágenes';
		$data['titulo'] = "Crear Banner";
        $data['link'] = base_url("admin/sliders/slider/update/".$bannerId);
		$data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/sliders/slider/delete/'.$bannerId);

		$data['bannerWidth'] = '';
		$data['bannerHeight'] = '';
		
		$this->load->view('admin/sliders/slideshow_view', $data);
	}

	public function insert()
	{
		return $this->Banners->add();
	}

	public function edit($id)
	{
		
		$data = $this->Banners->get($id, 'es');
		$data['banner_config'] = $this->Banners->getTypes();
		$config = json_decode($data['config'], TRUE);
		$data['config'] = $config ? $config : array();
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
		$data['titulo'] = "Modificar Banner";
		$data['link'] = base_url("admin/sliders/slider/update/" . $id);
		$data['txt_boton'] = "modificar";
		
		$this->load->view('admin/sliders/slideshow_view', $data);
	}
	
	public function update($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->update();
            $response->new_id = $this->input->post('bannerId');
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el banner!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el banner!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id){

	}

}
