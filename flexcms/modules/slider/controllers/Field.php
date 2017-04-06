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

class Field extends \Field implements \AdminParentInterface {

    const FIELD_SECTION = 'slider';

    const URL_CREATE = 'admin/slider/field/create/';
    const URL_UPDATE = 'admin/slider/field/update/';
    const URL_DELETE = 'admin/slider/field/delete';
    const URL_INSERT = 'admin/slider/field/insert/';
    const URL_EDIT = 'admin/slider/field/edit';
    const URL_REORDER = 'admin/slider/field/reorder';

    public function create($parent_id)
    {
        try {
            $model = new \Slider\Models\Field();
            $model->parent_id = $parent_id;
            $this->_showView($model, true);
        } catch (Exception $e) {
            $this->error('Error', $e);
        }

    }

    public function edit($id)
    {
        $this->_showView(\Slider\Models\Field::find($id));
    }

    public function insert($parent_id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $field = $this->_store(new \Slider\Models\Field());
            $field->parent_id = $parent_id;
            $field->position = \App\Field::where('section', static::FIELD_SECTION)->where('parent_id', $parent_id)->get()->count();
            $field->save();
            $response->new_id = $field->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

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
        $data['link'] = $new ? base_url(static::URL_INSERT . $model->parent_id) : base_url(static::URL_UPDATE . $model->id);

        $this->load->view('field_view',$data);

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
        $model = \Slider\Models\Field::find($model->id);
        $model->setTranslations($input);

        return $model;
    }

}