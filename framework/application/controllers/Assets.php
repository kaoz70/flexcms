<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assets extends CI_Controller {

	var $m_config;

    function __construct(){
        parent::__construct();
		$this->load->model('configuracion_model', 'Config');
        $this->load->model('banners_model', 'Banners');

		$this->m_config = $this->Config->get();

    }

	public function slideshow_js()
	{

		$this->output->set_header('Content-Type: application/javascript');
		$banners = $this->Banners->getAll(FALSE);
		$js = '';

		foreach ($banners as $key => $banner){
			$this->load->set_theme($this->m_config->theme);
			$data['return'] =  $this->load->view('modulos/banners/' . $banner['bannerType'] . '/' . 'javascript', $banner, TRUE);
			$this->load->set_admin_theme();
			$js .= $this->load->view('admin/request/html', $data, TRUE);
		}

		if(ENVIRONMENT === 'production') {
			$js = JSMin::minify($js);
		}

		$this->output->set_output($js);

	}


}