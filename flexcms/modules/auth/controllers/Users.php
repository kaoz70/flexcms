<?php

namespace auth;
$_ns = __NAMESPACE__;

use App\Category;
use App\Config;
use App\Field;
use App\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel as Sentinel;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends \AdminController implements \AdminInterface {

    const SECTION = 'user';
    const URL_UPDATE = 'admin/auth/users/update/';
    const URL_EDIT = 'auth/users/edit/';
    const URL_DELETE = 'auth/users/delete/';

    private static $config;

    function __construct(){
        parent::__construct();
        static::$config = Config::get('auth');
    }

    public function index()
    {

    }

    /**
     * Create form interface
     *
     * @return mixed
     */
    public function create()
    {
        $this->_showView(new User(), true);
    }

    /**
     * Edit form interface
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $this->_showView(User::findOrNew($id));
    }

    /**
     * Insert the item into database
     *
     * @return mixed
     */
    public function insert()
    {
        //
    }

    /**
     * Update the item in the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($id)
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            User::updateData($id, $this->input->post(), static::SECTION);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));
    }

    /**
     * Remove the item from the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $user = Sentinel::findById($id);
            $user->delete();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(\Illuminate\Database\Eloquent\Model $model, $new = FALSE)
    {

        $user = $model;

        if($new) {
            //FIXME
            //Set a random email so that the database does'nt complain of unique email if it's a temp user
            $user->email = random_string() . '@' . random_string() . '.temp';
            $user->save();
        }

        $user = Sentinel::findById($user->id);

        $data['user'] = $user;
        $data['roles'] = Sentinel::getRoleRepository()->all();
        $data['nuevo'] = $new ? 'nuevo' : '';

        $root = Category::allRoot()->first();
        $root->findChildren(999);
        $data['root_node'] = $root;

        $data['active'] = '';
        if($new || ($user && Sentinel::getActivationRepository()->completed($user))) {
            $data['active'] = 'checked="checked"';
        }

        $data['permissions'] = [];

        if($new) {

            foreach (Category::all() as $category) {
                $data['permissions'][$category->id] = true;
            }

        } else {

            foreach ($user->permissions as $key => $permission) {
                if(str_contains($key, 'category.')) {
                    $catId = explode('.', $key);
                    $data['permissions'][$catId[1]] = $permission;
                }
            }

        }

        $data['hasPermissions'] = (bool)$data['root_node']->getChildren();

        $data['titulo'] = $new ? 'Crear Usuario' : 'Modificar Usuario';
        $data['link'] = base_url(static::URL_UPDATE . $user->id);
        $data['txt_boton'] = $new ? 'crear nuevo usuario' : "modificar usuario";
        $data['fields'] = Field::where('section', 'user')->get();

        $data['url_edit'] = static::URL_EDIT;
        $data['url_delete'] = static::URL_DELETE;

        $data['txt_botImagen'] = 'Subir Imagen';
        $data['image_coord'] = urlencode($user->image_coord);
        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['imagenExtension'] = '';

        //TODO: finish images
        /*$data['cropDimensions'] = $this->General->getCropImage(13);


        if($user->image_extension != '')
        {
            //Eliminamos el cache del navegador
            $extension = $data['user']->image_extension;
            $extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/usuarios/usuario_' . $user->id . '_admin.' . $extension . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/usuarios/usuario_' . $user->id . '_orig.' . $extension;
            $data['imagenExtension'] = $data['user']->image_extension;
        }*/

        $this->load->view('auth/user_view', $data);

    }

    /**
     * Sets the user's permissions to access catalog and gallery categories,
     * this overrides the permissions set in the user's role
     *
     * @param $user
     */
    private function setPermissions($user)
    {

        $permissions = [];
        $active = $this->input->post('active');

        if($active && isset($active['catalog'])) {
            foreach ($active['catalog'] as $id) {
                $permissions['catalog.' . $id] = isset($this->input->post('catalog')[$id]);
            }
        }

        if($active && isset($active['gallery'])) {
            foreach ($active['gallery'] as $id) {
                $permissions['gallery.' . $id] = isset($this->input->post('gallery')[$id]);
            }
        }

        $user->permissions = $permissions;
        $user->save();

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {
        // TODO: Implement _store() method.
    }
}