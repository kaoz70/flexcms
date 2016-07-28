<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
        $data['users'] = Sentinel::getUserRepository()->all();
        $data['roles'] =  Sentinel::getRoleRepository()->all();
        $data['titulo'] = 'Usuarios';

        $data['url_create'] = static::URL_CREATE;
        $data['url_edit'] = static::URL_EDIT;
        $data['url_delete'] = static::URL_DELETE;

        $data['txt_usuarios'] = "crear nuevo usuario";

        $this->load->view('auth/index_view', $data);
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