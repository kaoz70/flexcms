<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advert extends AdminController  implements AdminInterface {

    const URL_CREATE = 'admin/advert/create/';
    const URL_UPDATE = 'admin/advert/update/';
    const URL_DELETE = 'admin/advert/delete/';
    const URL_INSERT = 'admin/advert/insert';
    const URL_EDIT = 'admin/advert/edit';

	public function index()
	{

        $data['groups'] = \App\Category::where('type', 'page')->get();
        $data['items'] = \App\Advert::all();
        $data['title'] = 'Publicidad';
        $data['group_title'] = 'P&aacute;gina';

        $data['url_sort'] = '';
        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'adverts';

        //MENU
        $data['menu'][] = anchor(base_url(static::URL_CREATE . 'normal'), 'crear publicidad normal', [
            'class' => $data['nivel'] . ' nivel2 ajax boton n3'
        ]);

        $data['menu'][] = anchor(base_url(static::URL_CREATE . 'expansible'), 'crear publicidad expandible', [
            'class' => $data['nivel'] . ' nivel2 ajax boton n2'
        ]);

        $data['menu'][] = anchor(base_url(static::URL_CREATE . 'popup'), 'crear publicidad popup', [
            'class' => $data['nivel'] . ' nivel2 ajax boton n1'
        ]);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/grouped_list_view', $data);

	}

    public function create(){
        $advert = new \App\Advert();
        $advert->type = $this->uri->segment(4);
        $this->_showView($advert, true);
    }

    public function edit($id)
    {
        $this->_showView(\App\Advert::find($id));
    }

    public function insert()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $advert = $this->_store(new \App\Advert());
            $advert->save();
            $response->new_id = $advert->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al insertar la publicidad!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

	public function update($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->_store(\App\Advert::find($id));
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el publicidad!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
        $response->error_code = 0;

        try{
            $advert = \App\Advert::find($id);
            $advert->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar la publicidad!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

	}

    /**
     * Shows the editor view
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(\Illuminate\Database\Eloquent\Model $model, $new = FALSE)
    {

        $data['advert'] = $model;
        $data['nuevo'] = $new ? 'nuevo' : '';
        $data['title'] = $new ? 'Crear' : 'Modificar';
        $data['edit_url'] = static::URL_EDIT;
        $data['delete_url'] = static::URL_DELETE;
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $model->id);
        $data['txt_boton'] = $new ? 'Crear' : 'Modificar';

        $widgets = \App\Widget::where('type', 'Advert')->get();
        $pages = \App\Category::where('type', 'page')->get();

        $data['ubicaciones'] = $this->formatLocations($pages, $widgets);
        $data['paginas'] = $pages;

        //Since its a new advert we will set it to show itself a default of 1 month
        if($new) {

            //Now
            $model->date_start = date('Y-m-d H:i:s');
            $d = new DateTime($model->date_start);

            //1 month from now
            $d->modify('next month');
            $model->date_end = $d->format('Y-m-d H:i:s');

        }

        $data['file_url'] = '';

        switch($model->type){
            case 'normal':
                $file_url = \App\Advert::generateTag($model->file1);
                $this->load->view('advert/normal_view', $data);
                break;
            case 'expandable':
                $data['archivoUrl1'] = \App\Advert::generateTag($model->file1);
                $data['archivoUrl2'] = \App\Advert::generateTag($model->file2);
                $data['txt_botImagen1'] = 'Subir Imagen Peque&ntilde;a';
                $data['txt_botImagen2'] = 'Subir Archivo Expandido';
                $this->load->view('advert/expansible_view', $data);
                break;
            case 'popup':
                $data['paginaIds'] = (array)json_decode($model->categories);
                $file_url = \App\Advert::generateTag($model->file1);
                $this->load->view('advert/popup_view', $data);
        }

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function _store(\Illuminate\Database\Eloquent\Model $model)
    {
        $model->widget_id = $this->input->post('widget_id');
        $model->categories = $this->input->post('categories');
        $model->name = $this->input->post('name');
        $model->date_start = $this->input->post('date_start');
        $model->date_end = $this->input->post('date_end');
        $model->enabled = (bool) $this->input->post('enabled');
        $model->css_class = (bool) $this->input->post('css_class');
        $model->file1 = (bool) $this->input->post('file1');
        $model->file2 = (bool) $this->input->post('file2');
        $model->save();

        return $model;
    }

    private function formatLocations($pages, $widgets, $unset = TRUE){
        $ubicaciones = array();

        foreach($pages as $key => $page) {

            $pag = new stdClass();
            $pag->id = $page->id;
            $pag->nombre = $page->getTranslation('es')->name;
            $pag->modulos = array();

            $ubicaciones[$key] = $pag;

            foreach($widgets as $widget) {
                if($page->id === $widget->category_id){
                    $mod = new stdClass();
                    $mod->id = $widget->id;
                    $mod->nombre = $widget->name ?: '[sin nombre]';
                    $ubicaciones[$key]->modulos[] = $mod;
                }
            }
        }

        //Remove the empty pages
        foreach($ubicaciones as $key => $ubicacion){
            if(empty($ubicacion->modulos) && $unset){
                unset($ubicaciones[$key]);
            }
        }
        return $ubicaciones;
    }


    public function images($bannerId='')
    {

        $data['items'] = $this->Banners->getImages($bannerId);

        $data['url_rel'] = base_url('admin/slideshows/images/'.$bannerId);
        $data['url_sort'] = base_url('admin/slideshows/reorganizar/'.$bannerId);
        $data['url_modificar'] = base_url('admin/slideshows/modificarImagen/'.$bannerId);
        $data['url_eliminar'] = base_url('admin/slideshows/eliminarImagen/'.$bannerId);
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'banner_images';

        $data['idx_id'] = 'bannerImagesId';
        $data['idx_nombre'] = 'bannerImageName';

        $data['txt_titulo'] = 'Imágenes del Banner';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax boton n1'
        );
        $data['menu'][] = anchor(base_url('admin/slideshows/crearImagen/'.$bannerId), 'subir nueva imagen', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

}
