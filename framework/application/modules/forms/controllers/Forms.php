<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:16 AM
 */

namespace Contact;
use App\Response;
use Contact\Models\Field;
use Contact\Models\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

$_ns = __NAMESPACE__;

class Forms extends \RESTController implements \AdminInterface {

    const FIELD_SECTION = 'form';
    const TRANSLATION_SECTION = 'form_field';

    /**
     * Get one or all the forms
     *
     * @param null $id
     * @return string
     */
    public function index_get($id = null)
    {

        $response = new Response();

        try{

            if($id) {
                $data = Models\Form::find($id);
            } else {
                $data = Models\Form::all();
            }

            $response->setData($data);

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obtener los formularios!', $e);
        }

        $this->response($response);

    }

    /**
     * Create a new form
     */
    public function index_post()
    {

        $response = new Response();

        try{

            $form = $this->_store(new Models\Form(), $this->post());

            $response->setMessage('Formulario creado correctamente');
            $response->setData($form);

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un error al crear el formulario!', $e);
        }

        $this->response($response);

    }

    /**
     * Update a form
     *
     * @param $id
     * @return string
     */
    public function index_put($id)
    {

        $response = new Response();

        try{
            $form = $this->_store(Models\Form::find($id), $this->put());
            $response->setData($form);
            $response->setMessage('Formulario actualizado correctamente');
        } catch (QueryException $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el formulario!', $e);
        }

        $this->response($response);

    }

    public function index_delete($id)
    {

        $response = new Response();

        try{

            //Delete the form
            $form = Form::find($id);
            $form->delete();

            //Delete the form's fields
            Field::deleteWithTranslations($id);

            $response->setMessage("Formulario {$form->name} eliminado satisfactoriamente");
            $response->setData(Models\Form::all());

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el formulario!', $e);
        }

        $this->response($response);

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model, $data)
    {

        //Save the form
        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->save();

        //Save the fields if any
        if(isset($data['fields'])) {
            foreach ($data['fields'] as $index => $fieldData) {
                $field = isset($fieldData['isNew']) ? new Field() : Field::find($fieldData['id']);
                $field->input_id = $fieldData['input_id'];
                $field->parent_id = $model->id;
                $field->position = $index + 1;
                $field->css_class = isset($fieldData['css_class']) ? $fieldData['css_class'] : null;
                $field->section = static::FIELD_SECTION;
                $field->label_enabled = $fieldData['label_enabled'];
                $field->required = $fieldData['required'];
                $field->validation = isset($fieldData['validation']) ? $fieldData['validation'] : null;
                $field->save();
                $field->setTranslations($fieldData['translations']);
            }
        }

        return $model;

    }
}