<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Response;
use Cartalyst\Sentinel\Native\Facades\Sentinel as Sentinel;

class Auth extends RESTController implements AdminInterface {

    const URL_CREATE = 'admin/auth/users/create';
    const URL_DELETE = 'admin/auth/users/delete/';
    const URL_EDIT = 'admin/auth/users/edit/';

    public function config()
    {
        $data['config'] = \App\Config::get('auth');

        $data['titulo'] = 'Configuraci&oacute;n';
        $data['txt_guardar'] = 'Guardar Configuraci&oacute;n';
        $data['roles'] =  Sentinel::getRoleRepository()->all();

        $this->load->view('auth/config_view', $data);
    }

    /**
     * Gets one or all resources
     *
     * @param null $id
     * @return mixed
     */
    public function index_get($id = null)
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

    /**
     * Update a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_put($id)
    {
        // TODO: Implement index_put() method.
    }

    /**
     * Insert a new resource
     *
     * @return mixed
     */
    public function index_post()
    {
        // TODO: Implement index_post() method.
    }

    /**
     * Delete a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_delete($id)
    {
        // TODO: Implement index_delete() method.
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param \App\BaseModel $model
     * @param $data
     * @return mixed
     */
    public function _store(\App\BaseModel $model, $data)
    {
        // TODO: Implement _store() method.
    }
}