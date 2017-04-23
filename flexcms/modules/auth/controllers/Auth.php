<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel as Sentinel;

class Auth extends AdminController {

    const URL_CREATE = 'admin/auth/users/create';
    const URL_DELETE = 'admin/auth/users/delete/';
    const URL_EDIT = 'admin/auth/users/edit/';

    function __construct(){
        parent::__construct();

        $this->load->library('Seguridad');
        $this->seguridad->init();

    }

    /**
     * List of items
     *
     * @return mixed
     */
    public function index()
    {

        $response = new Response();

        try{

            $users = Sentinel::getUserRepository()->all();
            $roles =  Sentinel::getRoleRepository()->all();
            $data = [];

            foreach($roles as $role) {

                $data[$role->id] = [];

                foreach($users as $user_key => $user) {
                    if($user->inRole($role)) {
                        $data[$role->id][] = $user;
                    }
                }
            }

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obtener el contenido!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    public function config()
    {
        $data['config'] = \App\Config::get('auth');

        $data['titulo'] = 'Configuraci&oacute;n';
        $data['txt_guardar'] = 'Guardar Configuraci&oacute;n';
        $data['roles'] =  Sentinel::getRoleRepository()->all();

        $this->load->view('auth/config_view', $data);
    }

}