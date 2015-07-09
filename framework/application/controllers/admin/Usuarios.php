<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {
	
	var $txt_boton = '';
	var $pagina_info = array();
	var $link;
	 
	function __construct(){
		parent::__construct();
		
		$this->load->model('admin/usuarios_model', 'Usuarios');
        $this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
		$this->load->library('ion_auth');

		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
	}
	
	public function index()
	{
        $data['usuarios'] = $this->Usuarios->get();
        $data['groups'] =  $this->Usuarios->getGroups($this->ion_auth->in_group('superadmin'));
		$data['titulo'] = 'Usuarios';
		$data['txt_usuarios'] = "crear nuevo usuario";

		$this->load->view('admin/usuarios_view', $data);
	}
	
	public function crear()
	{
        $this->load->library('ion_auth');

        $data['user'] = new stdClass();
		$data['user']->first_name = '';
		$data['user']->last_name = '';
		$data['user']->username = '';
		$data['user']->password = '';
		$data['user']->email = '';
		$data['user']->active = '';
		$data['user']->id = $this->cms_general->generarId('users');

        $data['active'] = 'checked="checked"';

		$data['groups'] =  $this->ion_auth->groups()->result();
		$data['usergroup'] = new stdClass();
        $data['nuevo'] = 'nuevo';

        $data['countries'] = $this->Usuarios->countries();

        $campos = $this->Usuarios->getTemplate();

        foreach($campos as $key => $campo) {
            $campos[$key]->userFieldRelContent = '';
        }

        $data['campos'] = $campos;

		$data['titulo'] = 'Crear Usuario';
		$data['link'] = base_url("admin/usuarios/insertar");
		$data['txt_boton'] = "crear nuevo usuario";

        if($this->ion_auth->is_admin())
        {
            foreach ( $data['groups'] as $key => $group) {
                if($group->id == 3)
                    unset($data['groups'][$key]);
            }
        }

        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['imagenExtension'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['cropDimensions'] = $this->General->getCropImage(13);
        $data['usuarioImagenCoord'] = '';

		$this->load->view('admin/usuarioCrear_view', $data);
	}
	
	public function modificar()
	{
        $this->load->library('ion_auth');

		$id = $this->uri->segment(4);
		
		$data['user'] = $this->ion_auth->user($id)->row();
		$data['groups'] =  $this->ion_auth->groups()->result();
		$data['usergroup'] =  $this->ion_auth->get_users_groups($id)->row();
        $data['nuevo'] = '';

        $data['active'] = '';
        if($data['user']->active)
            $data['active'] = 'checked="checked"';
		
		$data['titulo'] = 'Modificar Usuario';
		$data['link'] = base_url("admin/usuarios/actualizar");
		$data['txt_boton'] = "modificar usuario";

        if($this->ion_auth->is_admin())
        {
            foreach ( $data['groups'] as $key => $group) {
                if($group->id == 3)
                    unset($data['groups'][$key]);
            }
        }

        $data['campos'] = $this->Usuarios->getUserFieldsTemplate($id);
        $data['countries'] = $this->Usuarios->countries();

        $data['txt_botImagen'] = 'Subir Imagen';
        $data['cropDimensions'] = $this->General->getCropImage(13);
        $data['usuarioImagenCoord'] = urlencode($data['user']->usuarioImagenCoord);
        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['imagenExtension'] = '';

        if($data['user']->usuarioImagen != '')
        {
            //Eliminamos el cache del navegador
            $extension = $data['user']->usuarioImagen;
            $extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/usuarios/usuario_' . $data['user']->id . '_admin.' . $extension . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/usuarios/usuario_' . $data['user']->id . '_orig.' . $extension;
            $data['imagenExtension'] = $data['user']->usuarioImagen;
        }

		$this->load->view('admin/usuarioCrear_view', $data);
	}
	
	public function insertar()
	{

        $this->load->library('ion_auth');

		$username = $this->input->post('username');
		$password = $this->input->post('userPass1');
		$email = $this->input->post('email');
		$additional_data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
            'usuarioImagen' => $this->input->post('usuarioImagen'),
            'usuarioImagenCoord' => urldecode($this->input->post('usuarioImagenCoord'))
		);								
		$group = array($this->input->post('group')); // Sets user to admin. No need for array('1', '2') as user is always set to member by default

        $userId = $this->ion_auth->register($username, $password, $email, $additional_data, $group);

        $response = new stdClass();
        $response->error_code = 0;

        if($userId) {
            $this->Usuarios->insertar($userId);
            $response->new_id = $userId;
        } else {
            $response->message = 'Ocurri&oacute; un problema al crear el usuario!';
        }

        $this->load->view('admin/request/json', array('return' => $response));


	}

    public function actualizar()
    {

        $this->load->library('ion_auth');

        $id = $this->input->post('userId');

        $active = 0;
        if($this->input->post('active') == 'on')
            $active = 1;

        $activeOld = $this->input->post('activeOld');

        if($active != $activeOld)
        {

            if($active == 0)
            {
                //mandar mail desactivacion
                $output = 'Ud ha sido desactivado de ' . $_SERVER['SERVER_NAME'];

                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from('contacto@' . str_replace('www.', '', $_SERVER['SERVER_NAME']), 'Website Mailer');
                $this->email->to($this->input->post('email'));
                $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
                $this->email->message($output);
            }
            else {
                //mandar mail activacion
                $output = 'Su cuenta en ' . $_SERVER['SERVER_NAME'] . ' ha sido activada.';

                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from('contacto@' . str_replace('www.', '', $_SERVER['SERVER_NAME']), 'Website Mailer');
                $this->email->to($this->input->post('email'));
                $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
                $this->email->message($output);

            }

            $this->email->send();

        }

        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'active' => $active,
            'usuarioImagen' => $this->input->post('usuarioImagen'),
            'usuarioImagenCoord' => urldecode($this->input->post('usuarioImagenCoord'))
        );

        if($this->input->post('userPass1'))
            $data['password'] = $this->input->post('userPass1');

        $this->ion_auth->update($id, $data);

        //For some reason Ion Auth doesnt update user group
        $this->Usuarios->updateUserGroup($id, $this->input->post('group'));

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Usuarios->actualizar($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

	public function eliminar()
	{
        $this->load->library('ion_auth');
		$id = $this->uri->segment(4);

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->ion_auth->delete_user($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

    public function template()
    {
        $data['campos'] = $this->Usuarios->getTemplate();
        $data['titulo'] = 'Campos';
        $this->load->view('admin/usuariosCampos_view', $data);
    }

    public function crearCampo()
    {

        $data['userFieldId'] = $this->cms_general->generarId('user_fields');
        $data['titulo'] = "Crear Campo";
        $data['link'] = base_url("admin/usuarios/guardarCampo");
        $data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';

        /*
         * TRADUCCIONES
         */
        $traducciones = array();
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel = '';
            $traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder = '';
        }

        $data['traducciones'] = $traducciones;

        //obtengo entradas
        $data['inputId'] = '';
        $data['habilitado']	= 'checked="checked"';
        $data['userFieldRequired']	= '';
        $data['userFieldValidation'] = '';
        $data['inputs'] = $this->Usuarios->getInputs();
        $data['userFieldClass'] = '';
        $this->load->view('admin/usuariosCampoCrear_view', $data);
    }

    public function guardarCampo()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $response->new_id = $this->Usuarios->guardarCampo();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function modificarCampo()
    {
        $campoId = $this->uri->segment(4);
        $campo = $this->Usuarios->getCampo($campoId);

        $data['titulo'] = 'Editar Campo';

        $data['habilitado']	= '';
        if($campo->userFieldActive)
            $data['habilitado']	= 'checked="checked"';

        $data['userFieldRequired']	= '';
        if($campo->userFieldRequired)
            $data['userFieldRequired']	= 'checked="checked"';


        $data['userFieldId'] = $campo->userFieldId;
        $data['inputId'] = $campo->inputId;
        $data['inputs'] = $this->Usuarios->getInputs();
        $data['userFieldClass'] = $campo->userFieldClass;
        $data['userFieldValidation'] = $campo->userFieldValidation;
        $data['txt_boton'] = 'Modificar Campo';
        $data['link']  = base_url('admin/usuarios/actualizarCampo');
        $data['nuevo'] = '';

        /*
         * TRADUCCIONES
         */
        $traducciones = array();
        $data['idiomas'] = $this->Idioma->getLanguages();

        foreach ($data['idiomas'] as $key => $idioma) {
            $campoTraduccion = $this->Usuarios->getCampoTranslation($idioma['idiomaDiminutivo'], $campoId);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();

            if((bool)$campoTraduccion) {
                $traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel = $campoTraduccion->userFieldLabel;
                $traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder = $campoTraduccion->userFieldPlaceholder;
            } else {
                $traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel = '';
                $traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder = '';
            }

        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/usuariosCampoCrear_view', $data);
    }

    public function actualizarCampo()
    {
        $this->Usuarios->actualizarCampo();
    }

    public function eliminarCampo()
    {
        $id = $this->uri->segment(4);


        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Usuarios->eliminarCampo($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorganizarCampos()
    {
        $this->Usuarios->reorganizarCampos();
        $this->template();
    }

}