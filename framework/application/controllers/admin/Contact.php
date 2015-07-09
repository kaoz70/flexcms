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
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
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
	
	/******************/
	/**** CONTACTO ****/
	/******************/
	
	public function create_contact()
	{
		
		$data['idiomas'] = $this->Contacto->idiomas();
		
		$data['contactoId'] = $this->cms_general->generarId('contactos');
		$data['contactoEmail'] = '';
		
		$data['titulo'] = "Crear Contacto";
		$data['link'] = base_url("admin/contact/insert_contact");
		$data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();
		
		foreach ($data['idiomas'] as $key => $idioma)
		{
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->contactoNombre = '';
		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/contact/contact_view', $data);
	}
	
	public function insert_contact()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Contacto->addContact();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el contacto!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function edit_contact()
	{
		$id = $this->uri->segment(4);
		$contact= $this->Contacto->getContact($id);
	
		$data['titulo'] = "Modificar Contacto";
		$data['txt_boton'] = "modificar";
		$data['link'] = base_url("admin/contact/update_contact/" . $contact->contactoId);
		$data['contactoId'] = $contact->contactoId;
		$data['contactoEmail'] = $contact->contactoEmail;
        $data['nuevo'] = '';
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $contactoTraduccion = $this->Contacto->getContactoTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if(isset($contactoTraduccion->contactoNombre)) {
                $traducciones[$idioma['idiomaDiminutivo']]->contactoNombre = $contactoTraduccion->contactoNombre;
            } else {
                $traducciones[$idioma['idiomaDiminutivo']]->contactoNombre = '';
            }

        }
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/contact/contact_view', $data);
	}
	
	public function update_contact($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Contacto->updateContact($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el contacto!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete_contact($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Contacto->deleteContact($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el contacto!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	/******************/
	/***** DIRECCIONES *****/
	/******************/

    public function create_address()
    {

        $data['idiomas'] = $this->Contacto->idiomas();

        $data['direccionId'] = $this->cms_general->generarId('contacto_direcciones');
        $data['contactoDireccionNombre'] = '';

        $data['titulo'] = "Crear Direcci&oacute;n";
        $data['link'] = base_url("admin/contact/insert_address");
        $data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';

        $data['imagenExtension'] = '';
        $data['contactoDireccionCoord'] = '';
        $data['imagenOrig'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(14);
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['imagen'] = '';

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/contact/address_view', $data);
    }
	
	public function insert_address()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Contacto->addDireccion();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear la direcci&oacute;n!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function edit_address()
	{
		
		$id = $this->uri->segment(4);

        $direccion = $this->Contacto->getDireccion($id);
        $data['idiomas'] = $this->Contacto->idiomas();

        $data['direccionId'] = $direccion->contactoDireccionId;
        $data['contactoDireccionNombre'] = $direccion->contactoDireccionNombre;

        $data['titulo'] = "Modificar Direcci&oacute;n";
        $data['link'] = base_url("admin/contact/update_address");
        $data['txt_boton'] = "modificar";
        $data['nuevo'] = '';

        $data['imagenExtension'] = $direccion->contactoDireccionImagen;
        $data['contactoDireccionCoord'] = urlencode($direccion->contactoDireccionCoord);
        $data['imagenOrig'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(14);
        $data['imagen'] = '';

        if($direccion->contactoDireccionImagen != '')
        {
            $data['txt_botImagen'] = 'Cambiar Imagen';
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/contacto/dir_' . $direccion->contactoDireccionId . '_admin.' . $direccion->contactoDireccionImagen . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/contacto/dir_' . $direccion->contactoDireccionId . '_orig.' . $direccion->contactoDireccionImagen;
            $data['imagenExtension'] = $direccion->contactoDireccionImagen;
        }
        else
        {
            $data['txt_botImagen'] = 'Subir Imagen';
            $data['imagen'] = '';
            $data['imagenExtension'] = '';
            $data['imagenOrig'] = '';
        }

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma)
        {
            $direccionTraduccion = $this->Contacto->getDireccionTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();

            if($direccionTraduccion) {
                $traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion = $direccionTraduccion->contactoDireccion;
            }
            else {
                $traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion = '';
            }

        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/contact/address_view', $data);
		
	}
	
	public function update_address()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->input->post('direccionId');
            $this->Contacto->updateDireccion($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la direcci&oacute;n!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete_address($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Contacto->deleteDireccion($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la direcci&oacute;n!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

    public function reorder_addresses()
    {
        $this->Contacto->reorderDirecciones();
    }
	
	
	/**********************/
	/***** FORMULARIO *****/
	/**********************/
	public function create_form_field()
	{
		
		$data['contactoCampoId'] = '';
		$data['titulo'] = "Crear Elemento";
		$data['link'] = base_url("admin/contact/insert_form_field");
		$data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
        $data['contactoCampoRequerido'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();
		
		foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor = '';
			$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder = '';
		}
		
		$data['traducciones'] = $traducciones;
		
		//obtengo entradas
		$data['inputId'] = '';
		$data['inputs'] = $this->Contacto->getContactoInputs('es');
		$data['contactoCampoClase'] = '';
		$data['contactoCampoValidacion'] = '';
		$this->load->view('admin/contact/form_field_view', $data);
	}
	
	public function edit_form_field()
	{
		$contactoCampoId = $this->uri->segment(4);
		
		$data['titulo'] = 'Editar Elemento';
		$data['habilitado']	= 'checked="checked"';
		$data['result'] = array();
		$infoElemento = $this->Contacto->getDatosInForm($contactoCampoId);
		$data['contactoCampoId'] = $infoElemento->contactoCampoId;
		//ontengo entradas
		$data['inputId'] = $infoElemento->inputId;
		$data['inputs'] = $this->Contacto->getContactoInputs('es');
		$data['contactoCampoClase'] = $infoElemento->contactoCampoClase;
		$data['contactoCampoValidacion'] = $infoElemento->contactoCampoValidacion;
		$data['txt_boton'] = 'Modificar Elemento';
		$data['link']  = base_url('admin/contact/update_form_field');
        $data['nuevo'] = '';
        $data['contactoCampoRequerido'] = '';

        if($infoElemento->contactoCampoRequerido)
            $data['contactoCampoRequerido'] = 'checked="checked"';
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$campoTraduccion = $this->Contacto->getCampoTranslation($idioma['idiomaDiminutivo'], $contactoCampoId);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if(isset($campoTraduccion->contactoCampoValor)) {
                $traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor = $campoTraduccion->contactoCampoValor;
                $traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder = $campoTraduccion->contactoCampoPlaceholder;
            }
            else {
                $traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor = '';
                $traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder = '';
            }


		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/contact/form_field_view',$data);
	}
	
	public function reorganizarElementos()
	{
		$this->Contacto->reorderInputs();
		$this->index();
	}
	
	//guardo campos en DB
	public function insert_form_field()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Contacto->guardarInForm();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear la el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	public function update_form_field()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Contacto->updateForm();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	//elimino campos en DB
	public function delete_form_field($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Contacto->deleteForm($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	

}
