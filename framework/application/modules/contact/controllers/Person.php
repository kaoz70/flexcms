<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:14 AM
 */

namespace Contact;
use App\Language;
use Contact\Models\Contact;
use Illuminate\Database\Eloquent\Model;

$_ns = __NAMESPACE__;

class Person extends \Contact implements \AdminInterface {

    const URL_CREATE = 'admin/contact/person/create';
    const URL_UPDATE = 'admin/contact/person/update/';
    const URL_DELETE = 'admin/contact/person/delete/';
    const URL_INSERT = 'admin/contact/person/insert';
    const URL_EDIT = 'admin/contact/person/edit/';

    public function index(){
        return Contact::all();
    }

    public function create()
    {
        $this->_showView(new Contact(), true);
    }

    public function edit($id)
    {
        $this->_showView(Contact::find($id));
    }

    public function insert()
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $person = $this->_store(new Contact());
            $response->new_id = $person->id;
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear el contacto!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }


    public function update($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $this->_store(Contact::find($id));
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el contacto!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function delete($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $contact = Contact::find($id);
            $contact->delete();
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el contacto!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Shows the editor view
     *
     * @param Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $model, $new = FALSE)
    {
        $data['idiomas'] = Language::all();

        $data['person'] = $model;

        $data['titulo'] = $new ? "Crear Contacto" : 'Modificar Contacto';
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE) . '/' . $model->id;
        $data['txt_boton'] = $new ? "crear" : 'modificar';
        $data['nuevo'] = $new ? 'nuevo' : '';
        $data['edit_url'] = static::URL_EDIT;
        $data['delete_url'] = static::URL_DELETE;

        $this->load->view('contact/person_view', $data);
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {
        $model->name = $this->input->post('name');
        $model->email = $this->input->post('email');
        $model->save();
        return $model;
    }

}