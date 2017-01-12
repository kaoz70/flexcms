<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:19 AM
 */

namespace Contact;
$_ns = __NAMESPACE__;

use App\Input;
use App\Response;
use App\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Field extends \Field implements \AdminParentInterface {

    const TRANSLATION_SECTION = 'form_field';

    const URL_CREATE = 'admin/contact/field/create/';
    const URL_UPDATE = 'admin/contact/field/update';
    const URL_DELETE = 'admin/contact/field/delete';
    const URL_INSERT = 'admin/contact/field/insert/';
    const URL_EDIT = 'admin/contact/field/edit';
    const URL_REORDER = 'admin/contact/field/reorder';

    public function edit($id)
    {
        //
    }

    public function create($parent_id = null)
    {
        //
    }

    public function insert($parent_id)
    {

        $response = new Response();

        try{
            
            foreach ($this->input->post() as $fieldData) {
                $field = $this->_store(new Models\Field(), $fieldData);
                $field->parent_id = $parent_id;
                $field->save();
            }

            $response->setMessage('Formulario creado correctamente');

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un error al crear el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function delete($id)
    {

        //Delete any translations
        Translation::where('parent_id', $id)->where('type', static::TRANSLATION_SECTION)->delete();

        //Delete the field
        parent::delete($id);

    }

    public function getTypes()
    {

        $response = new Response();

        try{
            $response->setData(Input::where('section', static::FIELD_SECTION)->get());
        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un error al obtener los tipos de campos!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model, $fieldData)
    {

        $fieldData = json_decode($fieldData, true);

        $model->css_class = isset($fieldData['css_class']) ? $fieldData['css_class'] : NULL;
        $model->input_id = $fieldData['type'];
        $model->validation = isset($fieldData['validation']) ? $fieldData['validation'] : NULL;
        $model->required = (bool) $fieldData['required'];
        $model->enabled = (bool) $fieldData['enabled'];
        $model->section = static::FIELD_SECTION;

        $model->save();

        //Update the content's translations
        $model = Models\Field::find($model->id);
        $model->setTranslations($fieldData['translations']);

        return $model;

    }

}