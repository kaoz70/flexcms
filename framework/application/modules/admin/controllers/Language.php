<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends AdminController implements AdminInterface {

    const URL_CREATE = 'admin/language/create';
    const URL_UPDATE = 'admin/language/update/';
    const URL_DELETE = 'admin/language/delete/';
    const URL_INSERT = 'admin/language/insert';
    const URL_EDIT = 'admin/language/edit/';

    public function index()
    {
        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => \App\Language::all()->getDictionary()]);
    }

    public function create()
    {
        $this->_showView(new \App\Language(), true);
    }

    public function edit($id)
    {
        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => \App\Language::find($id)]);
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->_store(\App\Language::find($id));
            $response->message = 'Idioma actualizado correctamente';
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    public function insert()
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $lang = $this->_store(new App\Language());
            $response->new_id = $lang->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);
    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $lang = \App\Language::find($id);
            $lang->delete();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear el idioma!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(\Illuminate\Database\Eloquent\Model $model, $new = FALSE)
    {

        $data['titulo'] = $new ? "Crear Idioma" : "Modificar Idioma";
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $model->id);
        $data['txt_boton'] = $new ? "crear" : "modificar";
        $data['nuevo'] = $new ? 'nuevo' : '';

        $data['lang'] = $model;

        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);

        $this->load->view('admin/lang_view', $data);

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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */