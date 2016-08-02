<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:33 PM
 */

namespace auth;
$_ns = __NAMESPACE__;

use App\Input;
use App\User;
use Illuminate\Database\Eloquent\Model;
use stdClass;
use Exception;

class Field extends \Field implements \AdminInterface {

    const FIELD_SECTION = 'user';

    const URL_CREATE = 'admin/auth/field/create';
    const URL_UPDATE = 'admin/auth/field/update/';
    const URL_DELETE = 'admin/auth/field/delete';
    const URL_INSERT = 'admin/auth/field/insert';
    const URL_EDIT = 'admin/auth/field/edit';
    const URL_REORDER = 'admin/auth/field/reorder';

    public function index()
    {

        $data['items'] = \App\Field::where('section', static::FIELD_SECTION)
            ->orderBy('position')
            ->get();

        $data['title'] = 'Campos';
        $data['list_id'] = 'campos';
        $data['nivel'] = 'nivel3';

        $data['search'] = false;
        $data['drag'] = true;

        $data['url_sort'] = base_url(static::URL_REORDER);
        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);

        /*
         * MENU
         */
        $data['menu'][] = anchor(base_url(static::URL_CREATE), 'Crear Campo', [
            'class' => $data['nivel'] . ' ajax boton importante n1'
        ]);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/list_view', $data);

    }

    public function create()
    {
        $field = new \App\Field();
        $field->section = static::FIELD_SECTION;
        $field->data = null;
        $this->_showView($field, true);
    }

    public function insert()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $field = $this->_store(new \Auth\Models\Field());
            $field->position = \App\Field::where('section', static::FIELD_SECTION)->get()->count();
            $field->save();
            $field->createChildTableFields(User::where('temporary', 0)->get(), static::FIELD_SECTION);
            $response->new_id = $field->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al insertar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    public function edit($id)
    {
        $this->_showView(\Auth\Models\Field::find($id));
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $response->new_id = $this->_store(\Auth\Models\Field::find($id))->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $field = \App\Field::find($id);
            $field->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    public function reorder()
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            \App\Field::reorder($this->input->post('posiciones'), static::FIELD_SECTION);
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );
    }

    /**
     * @param Model $field
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $field, $new = FALSE)
    {

        $data = $field->toArray();
        $data['data'] = json_decode($data['data']);

        $data['titulo'] = $new ? 'Crear Campo' : 'Editar Campo';

        $data['txt_boton'] = $new ? 'Crear' : 'Modificar';
        $data['link']  = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $field->id);
        $data['nuevo'] = $new ? 'nuevo' : '';

        $data['inputs'] = Input::where('section', static::FIELD_SECTION)->get();
        $data['translations'] = $field->getTranslations(static::FIELD_SECTION . '_field');

        $this->load->view('auth/field_view', $data);
    }

    public function _store(Model $model) {

        $input = $this->input->post();

        $model->css_class = $this->input->post('css_class');
        $model->section = static::FIELD_SECTION;
        $model->name = $this->input->post('name');
        $model->input_id = $this->input->post('input_id');
        $model->label_enabled = (bool) $this->input->post('label_enabled');
        $model->enabled = (bool) $this->input->post('enabled');
        $model->required = (bool) $this->input->post('required');
        $model->validation = $this->input->post('validation');

        //Custom user field data
        $data = [
            'type' => $this->input->post('type'),
            'cart_order_col' => $this->input->post('cart_order_col'),
            'two_checkout_name' => $this->input->post('two_checkout_name'),
        ];
        $model->data = json_encode($data);

        $model->save();

        //Update the content's translations
        $model->setTranslations($input);

        return $model;
    }

}