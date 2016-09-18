<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:16 AM
 */

namespace Contact;
use App\Language;
use Illuminate\Database\Eloquent\Model;

$_ns = __NAMESPACE__;

class Form extends \Contact implements \AdminInterface {

    const URL_CREATE = 'admin/contact/form/create';
    const URL_UPDATE = 'admin/contact/form/update';
    const URL_DELETE = 'admin/contact/form/delete';
    const URL_INSERT = 'admin/contact/form/insert';
    const URL_EDIT = 'admin/contact/form/edit';
    const URL_FIELDS = 'admin/contact/field/index/';

    public function index(){
        return Models\Form::all();
    }

    public function create()
    {
        $this->_showView(new Models\Form(), true);
    }

    public function insert()
    {
        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $form =  $this->_store(new Models\Form());
            $form->save();
            $response->new_id = $form->id;
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear el formulario!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);
    }

    public function edit($id)
    {
        $this->_showView(Models\Form::find($id));
    }

    public function update($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $this->_store(Models\Form::find($id));
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el formulario!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function delete($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $form = Models\Form::find($id);
            $form->delete();
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el formulario!', $e);
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
        
        $data['languages'] = Language::all();
        $data['form'] = $model;
        $data['translations'] = $model->getTranslations('form');
        $data['people'] = Models\Contact::all();

        $data['titulo'] = $new ? "Crear Formulario" : "Modificar Formulario";
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . '/' . $model->id);
        $data['edit_url'] = static::URL_EDIT;
        $data['delete_url'] = static::URL_DELETE;
        $data['fields_url'] = base_url(static::URL_FIELDS . $model->id);
        $data['txt_boton'] = $new ? "crear" : 'modificar';
        $data['nuevo'] = $new ? 'nuevo' : '';

        $this->load->view('contact/form_view', $data);

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
        $model->contact_id = $this->input->post('contact_id');
        $model->save();
        return $model;
    }
}