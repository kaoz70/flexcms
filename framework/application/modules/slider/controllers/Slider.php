<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider extends AdminController implements AdminInterface {

    const URL_CREATE = 'admin/slider/create';
    const URL_UPDATE = 'admin/slider/update';
    const URL_DELETE = 'admin/slider/delete';
    const URL_INSERT = 'admin/slider/insert';
    const URL_EDIT = 'admin/slider/image/index/';

    public function index()
    {

        $data['items'] = \App\Slider::all();

        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);
        $data['url_sort'] = '';
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'banners';

        $data['title'] = 'Banners';

        /*
         * Menu
         */
        $data['menu'][] = anchor(base_url(static::URL_CREATE), 'crear nuevo banner', [
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n1'
        ]);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/list_view', $data);
    }

    public function create()
    {
        $this->_showView($this->insert(), TRUE);
    }

    public function insert()
    {
        $slider = new \App\Slider();
        $slider->save();
        $slider = \App\Slider::find($slider->id);
        return $slider;
    }

    public function edit($id)
    {
        $this->_showView(\App\Slider::find($id));
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $slider = \App\Slider::find($id);
            $slider->store($this->input->post());
            $response->new_id = $this->input->post('id');
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el banner!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $slider = \App\Slider::find($id);

            //TODO remove slider's images here

            $slider->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el banner!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }

    public function reorder($id){

    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(\Illuminate\Database\Eloquent\Model $model, $new = FALSE)
    {

        $data = $model->toArray();
        $data['banner_config'] = \App\Slider::getTypes();
        $config = isset($data['config']) ? json_decode($data['config'], TRUE) : null;
        $data['config'] = $config ? $config : [];
        $data['nuevo'] = $new;
        $data['removeUrl'] = $new ? base_url(static::URL_DELETE . '/' . $model->id) : '';
        $data['titulo'] = $new ? "Crear Slider" : "Modificar Slider";
        $data['txt_boton'] = $new ? "crear" : "modificar";
        $data['link'] = base_url("admin/slider/update/" . $model->id);

        $this->load->view('slider_view', $data);
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function _store(\Illuminate\Database\Eloquent\Model $model)
    {
        // TODO: Implement _store() method.
    }
}
