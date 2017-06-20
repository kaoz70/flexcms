<?php

namespace auth;
$_ns = __NAMESPACE__;

use App\Admin;
use Cartalyst\Sentinel\Native\Facades\Sentinel as Sentinel;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends \AdminController implements \AdminInterface{

    const URL_CREATE = 'admin/auth/roles/create';
    const URL_UPDATE = 'admin/auth/roles/update/';
    const URL_DELETE = 'admin/auth/roles/delete/';
    const URL_INSERT = 'admin/auth/roles/insert';
    const URL_EDIT = 'admin/auth/roles/edit/';

    public function index()
    {

        $data['items'] = Sentinel::getRoleRepository()->all();

        $data['title'] = 'Roles';
        $data['url_sort'] = '';
        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'roles';

        /*
         * MENU
         */
        $data['menu'][] = anchor(base_url(static::URL_CREATE), 'crear nuevo rol', [
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        ]);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/list_view', $data);
    }

    public function create()
    {
        $this->_showView(new EloquentRole(), true);
    }

    public function edit($id)
    {
        $this->_showView(Sentinel::findRoleById($id));
    }

    public function insert()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try {

            $role = new EloquentRole();
            $this->_store($role);
            $response->new_id = $role->id;

        } catch (Exception $e) {
            $response->error_code = 1;
            $response->message = $e->getMessage();
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{

            $role = Sentinel::findRoleById($id);
            $this->_store($role);

        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el usuario!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $role = Sentinel::findRoleById($id);
            $role->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el rol!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * @param Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $model, $new = FALSE)
    {
        $data['role'] = $model;
        $data['nuevo'] = $new ? 'nuevo' : '';
        $data['user'] = Sentinel::getUser();
        $data['menu'] = Admin::getModules();
        $data['hasPermissions'] = false;
        $data['url_edit'] = static::URL_EDIT;
        $data['url_delete'] = static::URL_DELETE;

        /*$root = CatalogTree::allRoot()->first();
        $root->findChildren(999);
        $data['catalog_root_node'] = $root;

        $data['catalog_names'] = array(
            'category' => 'productoCategoriaNombre',
        );

        $root = GalleryTree::allRoot()->first();
        $root->findChildren(999);
        $data['gallery_root_node'] = $root;

        $data['gallery_names'] = array(
            'category' => 'descargaCategoriaNombre',
        );

        $data['catalog_permissions'] = [];
        $data['gallery_permissions'] = [];

        foreach (CatalogTree::all() as $category) {
            $data['catalog_permissions'][] = $category->id;
        }

        foreach (GalleryTree::all() as $category) {
            $data['gallery_permissions'][] = $category->id;
        }

        $data['hasPermissions'] = false;
        if($data['catalog_root_node']->getChildren() || $data['gallery_root_node']->getChildren()) {
            $data['hasPermissions'] = true;
        }*/

        $data['titulo'] = $new ? 'Crear Rol de Usuario' : 'Modificar Rol de Usuario';
        $data['link'] = $new ? base_url(static::URL_INSERT) :  base_url(static::URL_UPDATE . $model->id);
        $data['txt_boton'] = $new ? "crear nuevo rol" : "modificar rol";

        $this->load->view('auth/role_view', $data);









        /*$root = CatalogTree::allRoot()->first();
        $root->findChildren(999);
        $data['catalog_root_node'] = $root;

        $data['catalog_names'] = array(
            'category' => 'productoCategoriaNombre',
        );

        $root = GalleryTree::allRoot()->first();
        $root->findChildren(999);
        $data['gallery_root_node'] = $root;

        $data['gallery_names'] = array(
            'category' => 'descargaCategoriaNombre',
        );

        $data['active'] = '';
        if(Sentinel::getActivationRepository()->completed($data['user'])) {
            $data['active'] = 'checked="checked"';
        }

        $data['catalog_permissions'] = [];
        $data['gallery_permissions'] = [];

        foreach ($role->permissions as $key => $permission) {
            if(str_contains($key, 'catalog.')) {
                $catId = explode('.', $key);
                $data['catalog_permissions'][] = $catId[1];
            }
            if(str_contains($key, 'gallery.')) {
                $galId = explode('.', $key);
                $data['gallery_permissions'][] = $galId[1];
            }
        }

        $data['hasPermissions'] = false;
        if(!$role->hasAccess(['admin']) && ($data['catalog_root_node']->getChildren() || $data['gallery_root_node']->getChildren())) {
            $data['hasPermissions'] = true;
        }*/

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {

        $role = $model;
        $role->name = $this->input->post('name');
        $role->slug = $this->input->post('slug');

        $permissions = [];
        if($this->input->post('permissions')) {
            foreach ($this->input->post('permissions') as $name) {
                $permissions[$name] = true;
            }
        }

        //Set catalog permissions
        /*$permissions = [];
        if($this->input->post('catalog')) {
            foreach ($this->input->post('catalog') as $catId) {
                $permissions['catalog.' . $catId] = true;
            }
        }

        //Set gallery permissions
        if($this->input->post('gallery')) {
            foreach ($this->input->post('gallery') as $catId) {
                $permissions['gallery.' . $catId] = true;
            }
        }*/

        $role->permissions = $permissions;

        $role->save();
    }
}