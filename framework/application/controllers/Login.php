<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 08/01/14
 * Time: 02:32 PM
 */

class Login extends CI_Controller {

	private $assets_css = array(
		'assets/admin/css/admin.scss',
		'assets/admin/css/datepicker_dashboard.css',
		'assets/admin/css/MooEditable.css',
		'assets/admin/css/MooEditable.Extras.css',
		'assets/admin/css/MooEditable.UploadImage.css',
		'assets/admin/css/MooEditable.Forecolor.css',
		'assets/admin/css/MooEditable.SilkTheme.css',
		'assets/admin/css/Tree.css',
		'assets/admin/css/ImageManipulation.css',
		'assets/admin/css/Scrollable.css',
		'assets/admin/css/Uploader.css'
	);

	function __construct(){
		parent::__construct();
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('admin/usuarios_model', 'Usuarios');
		$this->load->model('admin/module_model', 'Modulo');

		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');

		$this->load->helper('date');

		$this->config->load('ion_auth', TRUE);

		$this->load->set_admin_theme();

	}

	/**
	 * Initial check
	 */
	public function index()
	{

		$config = $this->Config->get();
		$data['title'] = $config->site_name;
		$data['error'] = '';
		$data['assets_css'] = $this->assets_css;

		//User is loged in and is administrator
		if ($this->ion_auth->logged_in() AND ($this->ion_auth->is_admin() || $this->ion_auth->in_group('superadmin'))) {
			$this->renderAdmin();
		}

		//User is logged in but not admin
		else if ($this->ion_auth->logged_in() AND !$this->ion_auth->is_admin() AND !$this->ion_auth->in_group('superadmin')) {
			$data['error'] = '<div class="error">Usted no tiene los permisos necesarios para poder entrar a esta secci&oacute;n. <a href="'. base_url() .'">regresar el inicio</a></div>';
			$this->load->view('admin/login_view', $data);
		}

		//Not loged in
		else {
			$this->load->view('admin/login_view', $data);
		}

	}

	private function renderAdmin()
	{

		date_default_timezone_set("America/Guayaquil");
		setlocale(LC_ALL, 'es_ES');

		$config = $this->Config->get();
		$data['titulo'] = $config->site_name . " Control Panel";
		$data['user'] = $this->ion_auth->user()->row();

		$root = PageTree::allRoot()->first();
		$root->findChildren(9999);

		$data['root_node'] = $root;
		$data['visible'] = $this->Modulo->getContentPages();

		$data['sess_expiration'] = $this->config->item('sess_expiration');

		if($this->ion_auth->in_group('superadmin'))
			$data['secciones'] = $this->Config->getSecciones();
		else
			$data['secciones'] = $this->Config->getSecciones(1);

		$data['assets_css'] = $this->assets_css;

		$data['assets_js'] = array();

		$this->load->view('admin/index_view', $data);
	}

	public function terminate()
	{
		$this->ion_auth->logout();
		redirect('login');
	}

	public function logged_in()
	{
		$data['return'] = $this->ion_auth->logged_in();
		$this->load->view('admin/request/json', $data);
	}

	public function form(){
		$data['error'] = '';
		$data['form_action'] = base_url('login/validate');
		$this->load->view('admin/login_form_view', $data);
	}

	/**
	 * Checks the user login
	 */
	public function validate(){

		$errorMsg = '';

		if($this->input->is_ajax_request()) {
			$view = 'admin/login_form_view';
		}
		else {
			$view = 'admin/login_view';
		}

		if($this->input->post()){
			$errorMsg = '<div class="error">No se pudo verificar sus datos, por favor intentelo de nuevo</div>';
		}

		$this->form_validation->set_rules('username', 'Usuario', 'required');
		$this->form_validation->set_rules('password', 'Contraseña', 'required');

		$data['assets_css'] = $this->assets_css;

		/*
		 * IF FORM IS VALID
		 */
		if ($this->form_validation->run() == true)
		{

			/*
			 * User has login attempts
			 */
			if(!$this->ion_auth->is_time_locked_out($this->input->post('username')))
			{

				/*
				 * Login Successful
				 */
				if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), (bool) $this->input->post('remember')))
				{
					if($this->input->is_ajax_request() AND ($this->ion_auth->is_admin() || $this->ion_auth->in_group('superadmin'))) {
						$data['return'] = $this->ion_auth->messages();
						$this->load->view('admin/request/html', $data);
					}
					else {
						redirect('login');
					}
				}

				/*
				 * Login Unsuccessful
				 */
				else
				{
					$data['error'] = $this->ion_auth->errors();
					$this->load->view($view, $data);
				}
			}

			/*
			 * User does not have login attempts
			 */
			else
			{
				$ion_auth_config = $this->config->item('ion_auth');
				$blockTimeout = $ion_auth_config['lockout_time'] / 60;
				$data['error'] = '<div class="error_red">Su cuenta ha sido <strong>bloqueada por su seguridad</strong>, por favor vuelva a intentarlo en <strong>'.$blockTimeout.' minutos</strong>.</div>';
				$this->load->view($view, $data);
			}

		}

		/*
		 * IF INVALID FORM
		 */
		else
		{
			$data['error'] = $errorMsg;
			$this->load->view($view, $data);
		}

	}

} 