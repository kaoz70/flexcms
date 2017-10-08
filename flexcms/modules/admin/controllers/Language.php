<?php use App\Response;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends RESTController implements AdminInterface {

    const URL_CREATE = 'language/create';
    const URL_UPDATE = 'admin/language/update/';
    const URL_DELETE = 'admin/language/delete/';
    const URL_INSERT = 'admin/language/insert';
    const URL_EDIT = 'admin/language/edit/';

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
                $data = \App\Language::find($id);
            } else {
                $data = \App\Language::orderBy('position', 'asc')->get();
            }

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obtener el idioma!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Update a language
     *
     * @param $id
     * @return string
     */
    public function index_put($id)
    {

        $response = new Response();

        try{
            $this->_store(\App\Language::find($id), $this->put());
            $response->setMessage('Idioma actualizado correctamente');
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar el idioma!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Create a language
     */
    public function index_post()
    {

        $response = new Response();

        try{

            $lang = $this->_store(new App\Language(), $this->post());
            $lang->position = \App\Language::all()->count();
            $lang->save();

            $response->setMessage('Idioma creado correctamente');
            $response->setData(\App\Language::orderBy('position', 'asc')->get());

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un error al crear el idioma!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Deletes a language
     */
    public function index_delete($id)
    {

        $response = new Response();

        try{

            if(\App\Language::all()->count() === 1) {
                $response->setNotify(false);
                throw new LengthException('Debe tener al menos un idioma disponible!');
            }

            //Delete the content
            $language = \App\Language::find($id);
            $language->delete();

            $response->setData(\App\Language::orderBy('position', 'asc')->get());
            $response->setMessage('Idioma eliminado correctamente');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el idioma!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    public function reorder_put()
    {

        $response = new Response();

        try{

            \App\Language::reorder($this->put(), '');
            $response->setMessage('Se guard&oacute; el nuevo orden de elementos');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al reorganizar los idiomas!', $e);
        }

        $this->response($response, $response->getStatusHeader());
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param \App\BaseModel $model
     * @return mixed
     */
    public function _store(\App\BaseModel $model, $data)
    {
        $model->name = $data['name'];
        $model->slug = $data['slug'];
        $model->save();
        return $model;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */