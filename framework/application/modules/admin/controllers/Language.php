<?php use App\Response;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends AdminController implements AdminInterface {

    const URL_CREATE = 'language/create';
    const URL_UPDATE = 'admin/language/update/';
    const URL_DELETE = 'admin/language/delete/';
    const URL_INSERT = 'admin/language/insert';
    const URL_EDIT = 'admin/language/edit/';

    public function index()
    {

        $response = new Response();

        try{

            $data['items'] = \App\Language::orderBy('position', 'asc')->get();
            $data['menu'] = [
                [
                    'title' => 'nuevo',
                    'icon' => 'add',
                    'url' => static::URL_CREATE,
                ],
            ];

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);
    }

    public function edit($id)
    {

        $response = new Response();

        try{
            $response->setData(\App\Language::find($id));
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function update($id)
    {

        $response = new Response();

        try{
            $this->_store(\App\Language::find($id));
            $response->setMessage('Idioma actualizado correctamente');
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function insert()
    {

        $response = new Response();

        try{

            $lang = $this->_store(new App\Language());
            $lang->position = \App\Language::all()->count();
            $lang->save();

            $response->setMessage('Idioma creado correctamente');
            $response->setData(\App\Language::orderBy('position', 'asc')->get());

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un error al crear el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     *
     */
    public function delete()
    {

        $response = new Response();

        try{

            $ids = $this->input->post();

            if(\App\Language::all()->count() === 1) {
                $response->setNotify(false);
                throw new LengthException('Debe tener al menos un idioma disponible!');
            }

            //Delete the content
            \App\Language::destroy($ids);

            $response->setData(\App\Language::orderBy('position', 'asc')->get());
            $response->setMessage('Idioma eliminado correctamente');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function _store(\Illuminate\Database\Eloquent\Model $model)
    {
        $model->name = $this->input->post('name');
        $model->slug = $this->input->post('slug');
        $model->save();
        return $model;
    }

    public function reorder()
    {

        $response = new Response();

        try{

            \App\Language::reorder($this->input->post('order'), '');
            $response->setMessage('Se guard&oacute; el nuevo orden de elementos');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al reorganizar los idiomas!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */