<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:16 AM
 */

namespace Contact;
use App\Response;
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

    public function index_put()
    {

        $response = new Response();

        try{

            $form = $this->_store(new Models\Form());

            $data = [
                'form' => $form,
                'items' => Models\Form::all(),
            ];

            $response->setMessage('Formulario creado correctamente');
            $response->setData($data);

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un error al crear el formulario!', $e);
        }

        $this->response($response);

    }

    public function index_post($id)
    {

        $response = new Response();

        try{
            $this->_store(Models\Form::find($id));
        } catch (QueryException $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el formulario!', $e);
        }

        $this->response($response);

    }

    public function index_delete($id)
    {

        $response = new Response();

        try{

            $ids = $this->input->post();

            //Delete the form
            Models\Form::destroy($ids);

            //Delete the form's fields
            Models\Field::deleteWithTranslations($ids);

            $response->setMessage('Formulario eliminado satisfactoriamente');
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
    public function _store(Model $model)
    {

        $model->name = $this->post('name');
        $model->email = $this->post('email');
        $model->save();

        return $model;
    }
}