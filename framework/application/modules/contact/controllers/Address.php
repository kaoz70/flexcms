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

class Address extends \Contact implements \AdminInterface {

    const URL_CREATE = 'admin/contact/address/create';
    const URL_UPDATE = 'admin/contact/address/update';
    const URL_DELETE = 'admin/contact/address/delete';
    const URL_INSERT = 'admin/contact/address/insert';
    const URL_EDIT = 'admin/contact/address/edit';

    public function index(){
        return \Contact\Models\Address::all();
    }

    public function create()
    {
        $this->_showView(new \Contact\Models\Address(), true);
    }

    public function insert()
    {
        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $address =  $this->_store(new \Contact\Models\Address());
            $address->position = \Contact\Models\Address::all()->count();
            $address->save();
            $response->new_id = $address->id;
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear la direcci&oacute;n!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);
    }

    public function edit($id)
    {
        $this->_showView(\Contact\Models\Address::find($id));
    }

    public function update($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $this->_store(\Contact\Models\Address::find($id));
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar la direcci&oacute;n!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function delete($id)
    {

        $response = new \stdClass();
        $response->error_code = 0;

        try{
            $address = \Contact\Models\Address::find($id);
            $address->delete();
        } catch (\Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la direcci&oacute;n!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function reorder()
    {
        $response = new \stdClass();
        $response->error_code = 0;

        try{
            \Contact\Models\Address::reorder($this->input->post('posiciones'));
        } catch (\Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );
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
        $data['address'] = $model;
        $data['image'] = json_decode($model->image);
        $data['translations'] = $model->getTranslations('address');

        $data['titulo'] = $new ? "Crear Direcci&oacute;n" : "Modificar Direcci&oacute;n";
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . '/' . $model->id);
        $data['edit_url'] = static::URL_EDIT;
        $data['delete_url'] = static::URL_DELETE;
        $data['txt_boton'] = $new ? "crear" : 'modificar';
        $data['nuevo'] = $new ? 'nuevo' : '';

        $this->load->view('contact/address_view', $data);

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
        $model->position = \Contact\Models\Address::all()->count() + 1;
        $model->image = $this->input->post('image');
        $model->save();

        $model->setTranslations($this->input->post());

        return $model;

    }
}