<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 11:21 AM
 */

namespace admin\auth;
use App\Role;
use App\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

$_ns = __NAMESPACE__;

class Main extends \AdminController {

	protected $baseUrl = "auth/admin/";

	/**
	 * The Sentinel Activations repository.
	 *
	 * @var \Cartalyst\Sentinel\Activations\ActivationRepositoryInterface
	 */
	protected $activation;

	/**
	 * The Sentinel Roles repository.
	 *
	 * @var \Cartalyst\Sentinel\Roles\RoleRepositoryInterface
	 */
	protected $roles;

	/**
	 * The Sentinel Users repository.
	 *
	 * @var \Cartalyst\Sentinel\Users\UserRepositoryInterface
	 */
	protected $users;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->roles = Sentinel::getRoleRepository();
		$this->users = Sentinel::getUserRepository();
		$this->activation = Sentinel::getActivationRepository();
		$this->baseUrl .= get_class($this) . '/';
	}

	public function index(){

		$data['usuarios'] = User::all();
		$data['roles'] =  Role::all();
		$data['titulo'] = 'Usuarios';
		$data['txt_usuarios'] = "crear nuevo usuario";

		$this->load->view('index_view', $data);

	}

}