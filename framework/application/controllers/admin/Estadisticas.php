<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estadisticas extends CI_Controller {
	 
	function __construct(){
		parent::__construct();
		$this->load->model('estadisticas_model', 'Stats');
		
		$this->load->library('Seguridad');
		$this->seguridad->init();
		
	}
	
	public function index()
	{
		$data['titulo'] = 'Estadísticas';
		$visitas = $this->Stats->getTotalVisits(TRUE);
		
		$data['visitas_day'] = $visitas->hits_day;
		$data['visitas_month'] = $visitas->hits_month;
		$data['visitas_year'] = $visitas->hits_year;
		$data['visitas_total'] = $visitas->hits_total;
		$data['paginas'] = $this->Stats->getMostViewedPages();
		
		$this->load->view('admin/estadisticas_view', $data);
	}
	
}
