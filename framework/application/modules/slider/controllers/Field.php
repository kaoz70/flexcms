<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:19 PM
 */

namespace slider;
$_ns = __NAMESPACE__;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Exception;

class Field extends \Field implements \AdminInterface {

    const FIELD_SECTION = 'slider';

    const URL_CREATE = 'admin/slider/field/create';
    const URL_UPDATE = 'admin/slider/field/update/';
    const URL_DELETE = 'admin/slider/field/delete';
    const URL_INSERT = 'admin/slider/field/insert';
    const URL_EDIT = 'admin/slider/field/edit';
    const URL_REORDER = 'admin/slider/field/reorder';

    public function index()
    {

        $data['items'] = \App\Field::where('section', static::FIELD_SECTION)
            ->orderBy('position')
            ->get();

        $data['title'] = 'Editar Template';
        $data['list_id'] = 'banner_campos';
        $data['nivel'] = 'nivel3';

        $data['search'] = false;
        $data['drag'] = true;

        $data['url_sort'] = base_url(static::URL_REORDER);
        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);

        /*
         * MENU
         */
        $data['menu'][] = anchor(base_url('admin/slider/field/create'), 'Crear Nuevo Elemento', [
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax boton n1'
        ]);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/list_view', $data);
    }

    public function create()
    {
        try {
            $this->_showView(new \App\Field());
        } catch (Exception $e) {
            $this->error('Error', $e);
        }

    }

    public function edit($id)
    {

        try {
            $this->_showView(\App\Field::findOrNew($id), false);
        } catch (Exception $e) {
            $this->error('Error', $e);
        }

    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(\Illuminate\Database\Eloquent\Model $model, $new = FALSE)
    {

        $field = $model;
        $data = $field->toArray();

        $data['titulo'] = 'Elemento';
        $data['nuevo'] = $new;

        $data['inputs'] = \App\Input::where('section', static::FIELD_SECTION)->get();
        $data['translations'] = $field->getTranslations('slider_field');

        $data['txt_boton'] = 'Guardar';
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $model->id);

        $this->load->view('field_view',$data);

    }

    public function insert()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $field = $this->_store(new \Slider\Models\Field());
            $field->position = \App\Field::where('section', static::FIELD_SECTION)->get()->count();
            $field->save();
            $response->new_id = $field->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }
    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $response->new_id = $this->_store(\Slider\Models\Field::find($id))->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al modificar el campo!', $e);
            var_dump($response);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }

    public function _store(Model $model) {

        $input = $this->input->post();

        $model->css_class = $this->input->post('css_class');
        $model->section = 'slider';
        $model->name = $this->input->post('name');
        $model->input_id = $this->input->post('input_id');
        $model->label_enabled = $this->input->post('label_enabled');
        $model->required = $this->input->post('required');
        $model->save();

        //Update the content's translations
        $model->setTranslations($input);

        return $model;
    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $field = \App\Field::find($id);
            $field->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

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

}