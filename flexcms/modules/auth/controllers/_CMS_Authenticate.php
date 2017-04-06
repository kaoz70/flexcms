<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Authenticate {

	public function __construct($config)
	{
		$CI =& get_instance();
		$CI->load->library('ion_auth');
		$CI->load->library('session');
		$CI->load->library('form_validation');
		$CI->load->library('facebook', $config);

		$CI->load->helper('url');

		$CI->load->model('usuarios_model', 'Usuarios');
		$CI->load->model('pedido_model', 'Pedido');

		$CI->load->model('configuracion_model', 'Config');
		$CI->load->model('submit_model', 'Submit');

	}

	public function create($data, $idioma, $currentPage)
	{
		$CI =& get_instance();
		$metodo = $CI->uri->segment(3, '');
		$ion_auth_config = $CI->config->item('ion_auth');

		$CI->form_validation->set_error_delimiters('<small class="error">', '</small>');

		$html = '';

		switch ($metodo) {

			case '':

				/*
				 * Pagina perfil del usuario
				 */
				if($CI->ion_auth->logged_in())
				{
					$message = $CI->session->userdata('message');
					$CI->session->unset_userdata('mensaje');

					$html .= $this->renderProfileView($data, $CI->ion_auth->user()->row(), $message, $idioma, $currentPage);
				}

				/*
				 * Pagina de login (Formulario)
				 */
				else
				{
					$html .= $this->renderLoginView($data, $idioma, $currentPage, '');
				}

				break;

			/*
			* LOGIN CONTROLLER
			*/
			case 'login':

				//validate form input
				$CI->form_validation->set_rules($ion_auth_config['identity'], $CI->lang->line('ui_auth_email'), 'required');
				$CI->form_validation->set_rules('password', $CI->lang->line('ui_auth_password'), 'required');

				if ($CI->form_validation->run() == true) {

					/*
					 * User has login attempts
					 */
					if(!$CI->ion_auth->is_time_locked_out($CI->input->post('email'))) {
						$this->login($CI->input->post('email'), $CI->input->post('password'), $data);
					}

					/*
					 * User does not have login attempts
					 */
					else
					{
						$blockTimeout = $ion_auth_config['lockout_time'] / 60;
						$error = '<div data-alert class="alert-box error radius">Su cuenta ha sido <strong>bloqueada por su seguridad</strong>, por favor vuelva a intentarlo en <strong>'.$blockTimeout.' minutos</strong><a href="#" class="close">&times;</a></div>';

						$CI->session->set_flashdata('error', $error);

						redirect('/', 'refresh');
					}

				}

				else {

					//Get the user data
					if($session = $CI->facebook->get_session()) {
						$user =  $CI->facebook->get_user();
						$this->login($user['email'], $user['id'], $data);
					}

					else {
						$CI->session->set_flashdata('error', '<div data-alert class="alert-box error radius">'.validation_errors().'<a href="#" class="close">&times;</a></div>');
						redirect($data['diminutivo'] . '/' . $data['pagAutenticacion']->paginaNombreURL);
					}

				}

				break;

			case 'logout':
				$CI->ion_auth->logout();
				$message = '<div data-alert class="alert-box radius">'.$CI->ion_auth->messages().'<a href="#" class="close">&times;</a></div>';
				$html .= $this->renderLoginView($data, $idioma, $currentPage, $message);
				break;

			case 'password':

				$passwordMethod = $CI->uri->segment(4, '');
				$data['linkForgotPasswordSuccess'] = base_url($idioma . '/' . $currentPage . '/password/confirm');

				switch($passwordMethod){

					/*
					 * Pagina recuperar contraseña
					 */
					case '':

						$data['message'] = '';
						$data['email'] = $CI->input->post('email');

						$html .= $CI->load->view('paginas/autenticacion/forgot_password_view', $data, true);

						break;

					/*
					* Pagina de confirmacion de envio
					*/
					case 'confirm':

						$CI->form_validation->set_rules('email', $CI->lang->line('ui_auth_email'), 'required');

						if ($CI->form_validation->run() == false)
						{
							//set any errors and display the form
							$data['message'] = validation_errors();
							$data['email'] = str_replace('"', '&quot;', $CI->input->post('email', TRUE)); //Replace " in case of XSS

							$html .= $CI->load->view('paginas/autenticacion/forgot_password_view', $data, true);

						}
						else
						{

							//run the forgotten password method to email an activation code to the user
							$forgotten = $CI->ion_auth->forgotten_password($CI->input->post('email'));

							if ($forgotten)
							{
								$data['message'] = '<div class="success">'.$CI->ion_auth->messages().'</div>';
								$CI->session->unset_userdata('message');
								$html .= $CI->load->view('paginas/autenticacion/forgot_password_confirmation_view', $data, true);
							}
							else
							{
								$data['message'] = $CI->ion_auth->errors();
								$data['email'] = str_replace('"', '&quot;', $CI->input->post('email', TRUE)); //Replace " in case of XSS

								$html .= $CI->load->view('paginas/autenticacion/forgot_password_view', $data, true);

							}

						}

						break;

					//Reseteo de contraseña
					case 'reset':

						$code = $CI->uri->segment(5);

						if (!$code)
						{
							show_404();
						}

						$user = $CI->ion_auth->forgotten_password_check($code);

						if ($user)
						{
							//if the code is valid then display the password reset form

							$CI->form_validation->set_rules('new', $data['ui_auth_password'], 'required|min_length[' . $CI->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $CI->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
							$CI->form_validation->set_rules('new_confirm', $data['ui_auth_password_again'], 'required');

							if ($CI->form_validation->run() == false)
							{
								//display the form
								$data['min_password_length'] = $CI->config->item('min_password_length', 'ion_auth');

								$data['user_id'] = array(
									'name'  => 'user_id',
									'id'    => 'user_id',
									'type'  => 'hidden',
									'value' => $user->id,
								);
								$data['csrf'] = $this->_get_csrf_nonce();
								$data['code'] = $code;
								$data['link'] = $idioma . '/' . $currentPage . '/password/reset/' . $code;

								//render
								$html .= $CI->load->view('paginas/autenticacion/reset_password', $data, TRUE);
							}
							else
							{
								// do we have a valid request?
								if ($this->_valid_csrf_nonce() === FALSE || $user->id != $CI->input->post('user_id'))
								{

									//something fishy might be up
									$CI->ion_auth->clear_forgotten_password_code($code);

									$CI->session->set_flashdata('error', '<div data-alert class="alert-box error radius">This form post did not pass our security checks.<a href="#" class="close">&times;</a></div>');
									redirect($idioma . '/' . $currentPage . '/password/reset/' . $code, 'refresh');

								}
								else
								{
									// finally change the password
									$identity = $user->{$CI->config->item('identity', 'ion_auth')};

									$change = $CI->ion_auth->reset_password($identity, $CI->input->post('new'));

									if ($change)
									{
										//if the password was successfully changed
										$CI->session->set_flashdata('error', '<div data-alert class="alert-box success radius">'.$CI->ion_auth->messages().'<a href="#" class="close">&times;</a></div>');
										redirect($idioma . '/' . $currentPage . '/login/', 'refresh');
									}
									else
									{
										$CI->session->set_flashdata('error', '<div data-alert class="alert-box error radius">'.$CI->ion_auth->errors().'<a href="#" class="close">&times;</a></div>');
										redirect($idioma . '/' . $currentPage . '/password/reset/' . $code, 'refresh');
									}
								}
							}
						}
						else
						{
							//if the code is invalid then send them back to the forgot password page
							$CI->session->set_flashdata('error', '<div data-alert class="alert-box error radius">'.$CI->ion_auth->errors().'<a href="#" class="close">&times;</a></div>');
							redirect($idioma . '/' . $currentPage . '/password', 'refresh');
						}

						break;

				}


				break;

			/*
			 * Pagina de registro
			 */
			case 'register':

				$registerMethod = $CI->uri->segment(4, '');

				switch($registerMethod){

					/*
					 * Pagina formulario registro
					 */
					case '':
						if ($CI->ion_auth->logged_in())
						{
							$html .= $this->renderProfileView($data, $CI->ion_auth->user()->row(), '', $idioma, $currentPage);
						}
						else
						{
							$campos = $CI->Usuarios->getCampos($idioma);
							foreach($campos as $campo)
								$campo->userFieldRelContent = '';

							if (array_key_exists('user', $data)) {
								$data['user']->campos = $campos;
							} else {
								$data['user'] = new stdClass();
								$data['user']->campos = $campos;
							}

							$html .= $this->renderRegisterView('', $campos, $data);
						}

						break;

					/*
					* Pagina validacion registro
					*/
					case 'add':

						//TODO: xss_clean not cleaning netsparker's attack correctly
						$CI->form_validation->set_rules('first_name', 'Nombre', 'required|xss_clean');
						$CI->form_validation->set_rules('last_name', 'Apelido', 'required|xss_clean');
						$CI->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email|required|is_unique[users.email]');
						$CI->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $CI->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $CI->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
						$CI->form_validation->set_rules('password_confirm', 'Verificación de Contraseña', 'required');

						$camposInput = $CI->input->post('campo');
						$campos = $CI->Usuarios->getCampos($idioma);
						foreach($campos as $key => $campo) {
							if($campo->userFieldClass != ''){
								$CI->form_validation->set_rules('campo['.$campo->userFieldId.']', $campo->userFieldLabel, $campo->userFieldClass.'|xss_clean');
							}
							else
								$CI->form_validation->set_rules('campo['.$campo->userFieldId.']', $campo->userFieldLabel, 'xss_clean');

							$campos[$key]->userFieldRelContent = str_replace('"', '', $camposInput[$campo->userFieldId]); // XSS Filter "

						}

						if (isset($_POST) && !empty($_POST))
						{
							// do we have a valid request?
							/* if ($this->_valid_csrf_nonce() === FALSE)
							{
								show_error('This form post did not pass our security checks.');
							}*/

							if ($CI->form_validation->run() === TRUE)
							{

								$username = $CI->input->post('username');
								$password = $CI->input->post('password');
								$email = $CI->input->post('email');

								$additional_data = array(
									'first_name' => $CI->input->post('first_name'),
									'last_name'  => $CI->input->post('last_name')
								);

								$id = $CI->ion_auth->register($username, $password, $email, $additional_data);

								if($id){
									$this->post_register($id, $password);
								}
								else {
									$html .= $this->renderRegisterView($CI->ion_auth->errors(), $campos, $data);
								}
							}

							else
							{
								$message = $CI->ion_auth->errors();
								$html .= $this->renderRegisterView($message, $campos, $data);
							}

						}

						break;

					case 'facebook':

						$CI->form_validation->set_error_delimiters('<div class="error">', '</div>');

						//validate form input
						//Registro de usuarios
						$CI->form_validation->set_rules('fb_nombres', 'nombres', 'trim|required|xss_clean');
						$CI->form_validation->set_rules('fb_apellidos', 'apellidos', 'trim|required|xss_clean');
						$CI->form_validation->set_rules('fb_email', 'correo elecrónico', 'trim|required|xss_clean|valid_email|is_unique[users.email]');

						if ($CI->form_validation->run() == true)
						{

							//Get the user data
							if( ! $session = $CI->facebook->get_session()) {
								$CI->session->set_flashdata('error', "<div class='error'>Necesita loguearse primero en Facebook para poder continuar</div>");
								redirect($data['diminutivo'] . '/' . $data['pagAutenticacion']->paginaNombreURL);
							}

							$user =  $CI->facebook->get_user();
							$token = $CI->facebook->exchange_token($session->getToken());

							$additional_data = array(
								'first_name' => $CI->input->post('fb_nombres') . ' ' . $CI->input->post('fb_apellidos'),
								'last_name' => $CI->input->post('fb_nombres'),
								'facebook_id' => $user['id'],
								'access_token' => $token['access_token'],
								'token_expires' => date('Y-m-d H:i:s',$token['expires']),
								'facebook_login' => 1,
							);

							$id = $CI->ion_auth->register($CI->input->post('fb_email'), $user['id'], $CI->input->post('fb_email'), $additional_data);

							if($id){
								$this->post_register($id, $user['id']);
							}
							else {
								$campos = $CI->Usuarios->getCampos($idioma);
								$html .= $this->renderRegisterView($CI->ion_auth->errors(), $campos, $data);
							}

						}
						else
						{
							$CI->session->set_flashdata('error', validation_errors());
							redirect($data['diminutivo'] . '/' . $data['pagAutenticacion']->paginaNombreURL . '/register');
						}

						break;

				}

				break;

			case 'update':

				if ($CI->ion_auth->logged_in())
				{
					$user = $CI->ion_auth->user()->row();

					//validate form input
					$CI->form_validation->set_rules('first_name', 'Nombre', 'required|xss_clean');
					$CI->form_validation->set_rules('last_name', 'Apelido', 'required|xss_clean');

					$camposInput = $CI->input->post('campo');
					$campos = $CI->Usuarios->getCampos($idioma);

					foreach($campos as $key => $campo) {
						if($campo->userFieldClass != ''){
							$CI->form_validation->set_rules('campo['.$campo->userFieldId.']', $campo->userFieldLabel, $campo->userFieldClass.'|xss_clean');
						}
						else
							$CI->form_validation->set_rules('campo['.$campo->userFieldId.']', $campo->userFieldLabel, 'xss_clean');

						if(array_key_exists($campo->userFieldId, $camposInput)) {
							$campos[$key]->userFieldRelContent = $camposInput[$campo->userFieldId];
						} else {
							log_message('error', 'Undefined offset ' . $campo->userFieldId . ' for var $camposInput on file: '. __FILE__ . '. Line 457');
						}

					}

					if (isset($_POST) && !empty($_POST))
					{
						// do we have a valid request?
						/* if ($this->_valid_csrf_nonce() === FALSE)
						{
							show_error('This form post did not pass our security checks.');
						}*/

						$user_data = array(
							'first_name' => $CI->input->post('first_name'),
							'last_name'  => $CI->input->post('last_name'),
							'email'      => $CI->input->post('email'),
						);

						//update the password if it was posted
						if ($CI->input->post('password'))
						{
							$CI->form_validation->set_rules('password', 'Contraseña', 'required|min_length[' . $CI->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $CI->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
							$CI->form_validation->set_rules('password_confirm', 'Verificación de Contraseña', 'required');

							$data['password'] = $CI->input->post('password');
						}

						if ($CI->form_validation->run() === TRUE)
						{
							$CI->ion_auth->update($user->id, $user_data);
							$CI->Usuarios->actualizarCampos($user->id);
							$message = '<div data-alert class="alert-box radius">'.$CI->ion_auth->messages().'<a href="#" class="close">&times;</a></div>';
							$html .= $this->renderProfileView($user_data, $CI->ion_auth->user()->row(), $message, $idioma, $currentPage);
							return $html;
						}
					}

					//set the flash data error message if there is one
					$message = (validation_errors() ? validation_errors() : ($CI->ion_auth->errors() ? $CI->ion_auth->errors() : $CI->session->flashdata('message')));
					$messageHtml = '<div data-alert class="alert-box error radius">'.$message.'<a href="#" class="close">&times;</a></div>';

					$html .= $this->renderProfileView($data, $CI->ion_auth->user()->row(), $messageHtml, $idioma, $currentPage);
				}
				else
				{
					$html .= $this->renderLoginView($data, $idioma, $currentPage, '');
				}


				break;

			//404 Error
			default:
				show_my_404(base_url($idioma . '/' . $currentPage), $CI->m_config->theme);
				break;
		}

		return $html;

	}

	private function login($email, $password, $data)
	{

		$CI =& get_instance();

		/*
		 * Login Successful
		 */
		if ($CI->ion_auth->login($email, $password, TRUE)){

			$CI->session->set_flashdata('error', '<div data-alert class="alert-box error radius">'.$CI->ion_auth->messages().'<a href="#" class="close">&times;</a></div>');

			$pedidos = $CI->session->userdata('pedidos');

			if(($pedidos) > 0 && isset($pedidos))
			{
				$CI->Pedido->addFromSession($pedidos, $CI->ion_auth->user()->row());
				$CI->session->unset_userdata('pedidos');
				$CI->session->unset_userdata('totalPedidos');
			}

			redirect(base_url(), 'refresh');

		}

		/*
		 * Login Unsuccessful
		 */
		else {
			$CI->session->set_flashdata('error', '<div data-alert class="alert-box error radius">'.$CI->ion_auth->errors().'<a href="#" class="close">&times;</a></div>');
			redirect($data['diminutivo'] . '/' . $data['pagAutenticacion']->paginaNombreURL);
		}
	}


	/**
	 * This gets executed after a successful user registration
	 *
	 * @param $id
	 * @param $password
	 */
	private function post_register($id, $password)
	{

		$CI =& get_instance();
		$ion_auth_config = $CI->config->item('ion_auth');

		$contacts = $CI->Submit->getContacts();
		$contactoId = $contacts[0]->contactoId;
		$contacto = $CI->Submit->getContact($contactoId);
		$user = $CI->ion_auth->user($id)->row();

		$CI->Usuarios->insertarCampos($id);

		//If its not manual activation log the person in and send an email
		if(!$ion_auth_config['manual_activation'])
		{

			//User email
			$message = 'Esta es una notificación de que Ud. se ha registrado en ' . $_SERVER['SERVER_NAME'];
			$subject = 'Confirmación de registro desde ' . $_SERVER['SERVER_NAME'];

			//We try to log in
			if($CI->ion_auth->login($user->email, $password))
			{
				$pedidos = $CI->session->userdata('pedidos');

				if(($pedidos) > 0 && isset($pedidos))
				{
					$CI->Pedido->addFromSession($pedidos, $user);
					$CI->session->unset_userdata('pedidos');
					$CI->session->unset_userdata('totalPedidos');
				}

				$this->sendEmail($contacto->contactoEmail, $user->email, $subject, $message);
				$CI->session->set_flashdata('error', '<div data-alert="" class="alert-box radius"><div class="success">Un email notificando su registro ha sido enviado a su correo</div></div>');
			}

			//If there was a problem logging in
			else {
				$this->sendEmail($contacto->contactoEmail, $user->email, $subject, $message);
				$CI->session->set_flashdata('error', '<div data-alert="" class="alert-box radius"><div class="error">' . $CI->ion_auth->errors() . ', adicionalmente se ha enviado un email notificando su registro a su correo</div></div>');
			}

		}

		//If its manual activation send an email to user to wait for the activation
		//and send admin the email
		else {

			//User email
			$message = 'Tiene que esperar a que un administrador active su cuenta';
			$subject = 'Espere activacion desde ' . $_SERVER['SERVER_NAME'];
			$this->sendEmail($contacto->contactoEmail, $user->email, $subject, $message);

			//Admin email
			$message = 'El usuario ' . $CI->input->post('first_name') . ' ' . $CI->input->post('last_name') . ' está en espera de que active su cuenta';
			$subject = $user->first_name . ' ' . $user->last_name . ' espera activacion en ' . $_SERVER['SERVER_NAME'];
			$this->sendEmail($user->email, $contacto->contactoEmail, $subject, $message);

			$CI->session->set_flashdata('error', '<div data-alert="" class="alert-box radius"><div class="success">Un email de confirmaci&oacute;n ha sido enviado a su correo, por favor espere a que un administrador active su cuenta</div></div>');

		}

		redirect(base_url(), 'refresh');
	}

	function renderLoginView($data, $idioma, $currentPage, $message)
	{
		$CI =& get_instance();

		$data['linkForgotPassword'] = base_url($idioma . '/' . $currentPage . '/password');
		$data['mensaje'] = $message;

		$data['link'] = $idioma . '/' . $currentPage . '/login';

		return $CI->load->view('paginas/autenticacion/login_view', $data, true);
	}

	function renderProfileView($data, $user, $message, $idioma, $currentPage)
	{
		$CI =& get_instance();

		if($user) {
			$data['user'] = $user;
			$data['campos'] = $CI->Usuarios->getCamposUser($user->id, $idioma);
			$data['message'] = $message;
			$data['diminutivo'] = $idioma;
			$data['update_link'] = base_url($idioma . '/' . $currentPage . '/update');

			$data['countries'] = $CI->Usuarios->countries();

			return $CI->load->view('paginas/autenticacion/usuario_perfil_view', $data, true);
		}

		else {
			$CI->ion_auth->logout();
			$CI->session->set_flashdata('error', '<div data-alert="" class="alert-box radius"><div class="error">El usuario registrado ya no existe</div></div>');
			redirect($data['diminutivo'] . '/' . $data['pagAutenticacion']->paginaNombreURL);
		}


	}

	function renderRegisterView($message, $campos, $data)
	{
		$CI =& get_instance();

		$data['message'] = $message;
		$data['user'] = new stdClass();
		$data['user']->first_name = $CI->input->post('first_name');
		$data['user']->last_name = $CI->input->post('last_name');
		$data['user']->email = $CI->input->post('email');
		$data['user']->campos = $campos;
		$data['countries'] = $CI->Usuarios->countries();

		return $CI->load->view('paginas/autenticacion/registro_view', $data, true);
	}

	function _get_csrf_nonce()
	{
		$CI =& get_instance();

		$CI->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$CI->session->set_flashdata('csrfkey', $key);
		$CI->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{

		$CI =& get_instance();

		if ($CI->input->post($CI->session->flashdata('csrfkey')) !== FALSE &&
			$CI->input->post($CI->session->flashdata('csrfkey')) == $CI->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function sendEmail($from, $to, $subject, $message) {

		$CI =& get_instance();
		$conf = $CI->Config->get();

		$CI->email->from($from, $conf->site_name);
		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->message($message);

		if (!$CI->email->send()) {
			return FALSE;
		} else {
			return TRUE;
		}

	}

}

/* End of file CMS_Authenticate.php */