<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends AdminController {

    var $txt_boton = '';
    var $pagina_info = array();
    var $link;
    var $mptt;

    public function edit($id)
    {

        try {

            //Get the content module
            $contentWidget = \App\Widget::getContentWidget($id);
            $contentType = $contentWidget->getData();
            $class = '\\' . $contentType->content_type . '\Content';

            //Get the content's index page
            $class::index($id);

        } catch (Exception $e) {

            $response = new \App\Response();
            $response->setMessage($e->getMessage());
            $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

        }

    }

    /**
     * Checks if the name of the page will be unique, we do this because the name is transformed into the
     * page's slug and we cant have any duplicates
     */
    public function uniqueName(){

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $lang = $this->input->post('lang');

        $category = \App\Category::find($id);

        $this->load->view('admin/request/json', [
            'return' => [
                'unique' => $category->isUniqueName($name, $lang)
            ]
        ]);

    }

}
