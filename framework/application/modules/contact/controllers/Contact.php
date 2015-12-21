<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {
	
	var $txt_boton = '';
	var $pagina_info = array();
	var $link;
	 
	function __construct(){
		parent::__construct();

		$this->load->model('contact_model', 'Contacto');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
        $this->load->library('CMS_General');
	}
	
	public function index()
	{
		$data['result_contactos'] = $this->Contacto->getContacts('es');
		$data['result_elementos'] = $this->Contacto->getFormElements();
		$data['result_direcciones'] = $this->Contacto->getDirecciones('es');

		$data['titulo'] = 'Contacto';
		$data['txt_nuevoContacto'] = "crear nuevo contacto";
		$data['txt_nuevoElem'] = "crear nuevo campo";
		$data['txt_nuevaDireccion'] = "crear nueva direccion";
		
		$this->load->view('admin/contact/index_view', $data);
	}

}
