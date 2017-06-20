<?php use App\Response;
use App\Translation;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Widget extends RESTController implements AdminInterface {

    var $theme = 'default';

    /**
     * Gets one or all resources
     *
     * @param null $id
     * @return mixed
     */
    public function index_get($id = null)
    {
        $response = new Response();

        try{

            $widget = \App\Widget::find($id);

            //Get the model class
            $modelClass = '\App\Widget\\' . $widget->type . '\Model\Widget';

            $data = [
                'widget' => $modelClass::admin($widget->id),
                'languages' => \App\Language::all(),
            ];

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obtener el widget!', $e);
        }

        $this->response($response, $response->getStatusHeader());
    }

    /**
     * Update a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_put($id)
    {
        $response = new Response();

        try{

            $widget = \App\Widget::find($id);

            //Get the model class
            $modelClass = '\App\Widget\\' . $widget->type . '\Model\Widget';

            //Get the new model
            $model = $modelClass::find($id);
            $model->store($this->put());

            $data = [
                'widget' => $modelClass::admin($widget->id),
                'languages' => \App\Language::all(),
            ];

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al actualizar el widget!', $e);
        }

        $this->response($response, $response->getStatusHeader());
    }

    /**
     * Insert a new resource
     *
     * @return mixed
     */
    public function index_post()
    {
        $response = new Response();

        try{

            $class = ucfirst($this->post('type'));

            //Get the model class
            $modelClass = '\App\Widget\\' . $class . '\Model\Widget';

            $widget = new \App\Widget();
            $widget->category_id = $this->post('category_id') ?: 0;
            $widget->type = $class;
            $widget->save();

            $data = [
                'widget' => $modelClass::admin($widget->id),
                'languages' => \App\Language::all(),
            ];

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al crear el widget!', $e);
        }

        $this->response($response, $response->getStatusHeader());
    }

    /**
     * Delete a resource
     *
     * @param $id
     * @return mixed
     */
    public function index_delete($id)
    {
        $response = new Response();

        try{

            //Delete the content
            $widget = \App\Widget::find($id);

            //Get the model class
            $modelClass = '\App\Widget\\' . $widget->type . '\Model\Widget';

            //Get the new model
            $model = $modelClass::find($id);
            $model->delete();

            //Delete the widget's translations
            $translations = Translation::where('parent_id', $id)->where('type', \App\Widget::getType());
            $translations->delete();

            $response->setMessage('Widget eliminado satisfactoriamente');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el widget!', $e);
        }

        $this->response($response, $response->getStatusHeader());
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param $data
     * @return mixed
     */
    public function _store(\Illuminate\Database\Eloquent\Model $model, $data)
    {
        $widget->type = $this->post('namespace');
        $widget->save();

        return [
            'widget' => $class::admin($widget->id),
            'languages' => \App\Language::all(),
        ];
    }















/*
    public function update($id)
    {

        $moduleData = $this->input->post('module');
        $moduleData = $moduleData[$id];

        $widget = \App\Widget::find($id);
        $widget->data = json_encode($moduleData);
        $widget->save();

    }*/








    /**
     * Adds the new widget id to the correct place and shows its view
     *
     * @param $type
     * @param $page_id
     * @param $row_id
     * @param $column_id
     *
     * @return mixed
     */
    public function add($type, $page_id, $row_id, $column_id)
    {

        try {

            $widget = new \App\Widget();
            $widget->category_id = $page_id;
            $widget->type = $type;
            $widget->save();

            //Get and save the structure
            /*$page = \App\Category::find($page_id);
            $data = json_decode($page->data);
            $data->structure[$row_id]->columns[$column_id]->widgets[] = $widget->id;

            $page->data = json_encode($data);
            $page->save();*/

            //Get the widgets's class
            $class = "\\App\\Widget\\{$type}";

            $this->load->view('admin/request/json', array(
                'return' => [
                    'id' => $widget->id,
                    'html' => $class::admin($widget->id),
                ]
            ));


        } catch (Exception $e) {
            //TODO JSON error
            show_error($e->getMessage());
        }

    }

    /**
     * Gets the module's basic html data
     *
     * @param $view
     * @param array $data
     * @return stdClass
     */
    private function get_module_html($view, $data = array())
    {
        $moduleData = new stdClass();
        $moduleData->moduloId = $data['moduleId'];
        $moduleData->moduloNombre = '';
        $moduleData->moduloParam1 = '';
        $moduleData->moduloParam2 = '';
        $moduleData->moduloParam3 = '';
        $moduleData->moduloParam4 = '';
        $moduleData->moduloMostrarTitulo = 'checked="checked"';
        $moduleData->moduloClase = '';
        $moduleData->moduloVerPaginacion = 'checked="checked"';
        $moduleData->moduloHabilitado = 'checked="checked"';
        $moduleData->moduloVista = '';

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

        foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->moduloNombre = '';
            $traducciones[$idioma['idiomaDiminutivo']]->moduloHtml = '';
        }

        $moduleData->traducciones = $traducciones;

        $data['moduleData'] = $moduleData;

        $return = new stdClass();
        $return->html = $this->load->view('admin/modulos/' . $view, $data, true);
        $return->id = $data['moduleId'];

        return $return;

    }






    /*
     * //TODO: Move all these to another place, ex: application/widgets
     * MODULE TYPES
     */

    public function publicaciones($page_id, $row_id, $column_id)
    {
        $data['publicaciones'] = $this->Modulo->get_page_by_type(5);
        $data['moduleId'] = $this->Modulo->createModule($page_id, 1);
        $data['moduleImages'] = $this->Modulo->getImages(2);

        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/publicaciones/");

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('publicaciones_view', $data)
        ));

    }











    public function catalogoCategoria($page_id, $row_id, $column_id)
    {
        $data['categorias'] = $this->Catalogo->getCategories();
        $data['moduleId'] = $this->Modulo->createModule($page_id,2);
        $data['moduleImages'] = $this->Modulo->getImages(5);

        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('catalogoCategorias_view', $data)
        ));

    }

    public function catalogoProductosDestacados($page_id, $row_id, $column_id)
    {
        $data['categorias'] = $this->Catalogo->getCategories();

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");

        $data['moduleId'] = $this->Modulo->createModule($page_id, 10);
        $data['moduleImages'] = $this->Modulo->getImages(5);

        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('catalogoProductosDestacados_view', $data)
        ));
    }

    public function catalogoMenu($page_id, $row_id, $column_id)
    {
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/menu/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 11);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);
        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('catalogoMenu_view', $data)
        ));
    }

    public function catalogoFiltros($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/filtros/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 17);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('catalogoFiltros_view', $data)
        ));

    }
    public function catalogoProductoAzar($page_id, $row_id, $column_id) {

        $data['categorias'] = $this->Catalogo->getCategories();
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 19);
        $data['moduleImages'] = $this->Modulo->getImages(5);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('catalogoProductoAzar_view', $data)
        ));
    }

    public function catalogoProductosDestacadosAzar($page_id, $row_id, $column_id)
    {
        $data['categorias'] = $this->Catalogo->getCategories();
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/catalogo/product/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 26);
        $data['moduleImages'] = $this->Modulo->getImages(5);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('catalogoProductosDestacadosAzar_view', $data)
        ));
    }














    public function html($page_id, $row_id, $column_id)
    {
        $data['moduleId'] = $this->Modulo->createModule($page_id, 3);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);
        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('html_view', $data)
        ));
    }

    public function twitter($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/twitter/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 4);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('twitter_view', $data)
        ));

    }

    public function facebook($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/facebook/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 5);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('facebook_view', $data)
        ));

    }

    public function banner($page_id, $row_id, $column_id)
    {

        $data['banners'] = $this->Banners->getAll();
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/banners/");
        $data['bannerType'] = 'bxSlider';
        $data['moduleId'] = $this->Modulo->createModule($page_id, 9);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('banner_view', $data)
        ));

    }

    public function titulo($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/titulo/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 12);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('titulo_view', $data)
        ));

    }

    public function faq($page_id, $row_id, $column_id)
    {
        $data['faq'] = $this->Modulo->get_page_by_type(2);
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/faq/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 13);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('faq_view', $data)
        ));

    }

    public function enlaces($page_id, $row_id, $column_id)
    {
        $data['enlaces'] = $this->Modulo->get_page_by_type(10);
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/enlaces/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 14);
        $data['moduleImages'] = $this->Modulo->getImages(1);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('enlaces_view', $data)
        ));

    }

    public function galeria($page_id, $row_id, $column_id)
    {
        $data['galeria'] = $this->Gallery->getCategories();
        $data['moduleId'] = $this->Modulo->createModule($page_id, 15);
        $data['moduleImages'] = $this->Modulo->getImages(8);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('galeria_view', $data)
        ));

    }

    public function mapa($page_id, $row_id, $column_id)
    {
        $data['mapas'] = $this->Maps->getAll();
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/mapa/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 16);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('mapas_view', $data)
        ));

    }



    public function menu($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/menu/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 18);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('menu_view', $data)
        ));

    }




    public function contacto($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/contacto/formulario/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 20);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('contacto_view', $data)
        ));

    }

    public function articulo($page_id, $row_id, $column_id)
    {
        $data['articulos'] = $this->Articles->all('es');
        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/articulo/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 21);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('articulo_view', $data)
        ));

    }

    public function servicios($page_id, $row_id, $column_id)
    {

        $data['servicios'] = $this->Modulo->get_page_by_type(12);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/servicios/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 22);
        $data['moduleImages'] = $this->Modulo->getImages(10);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('servicios_view', $data)
        ));

    }

    public function breadcrumbs($page_id, $row_id, $column_id)
    {

        $data['moduleId'] = $this->Modulo->createModule($page_id, 23);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('breadcrumbs_view', $data)
        ));

    }

    public function direcciones($page_id, $row_id, $column_id)
    {

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/contacto/direcciones/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 24);
        $data['moduleImages'] = $this->Modulo->getImages(14);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('direcciones_view', $data)
        ));

    }

    public function publicidad($page_id, $row_id, $column_id)
    {

        $data['moduleId'] = $this->Modulo->createModule($page_id, 25);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('publicidad_view', $data)
        ));

    }





    public function serviciosDestacados($page_id, $row_id, $column_id)
    {

        $data['servicios'] = $this->Modulo->get_page_by_type(12);

        //Get the list of views that a module has
        $data['moduleViews'] = directory_map("themes/" . $this->theme . "/views/modulos/servicios/");
        $data['moduleId'] = $this->Modulo->createModule($page_id, 27);
        $data['moduleImages'] = $this->Modulo->getImages(10);
        $this->add($page_id, $row_id, $column_id, $data['moduleId']);

        $this->load->view('admin/request/json', array(
            'return' => $this->get_module_html('serviciosDestacados_view', $data)
        ));

    }



}
