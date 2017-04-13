<?php use App\Response;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends RESTController {

    var $txt_boton = '';
    var $pagina_info = array();
    var $link;
    var $mptt;

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

            if($id && $id !== 'null') {
                $data = $this->edit($id);
            } else {
                $data = $this->tree();
            }

            $response->setData($data);

        } catch (CMSException $e) {
            $response->setError('Ocurri&oacute; un problema!', $e);
        }

        $this->response($response);

    }

    /**
     * @return \Baum\Extensions\Eloquent\Collection
     */
    private function tree()
    {
        //Get the root page
        $root = \App\Page::find(1);
        return $root->getDescendantsLang(\App\Language::getDefault())->toHierarchy();
    }

    private function edit($id)
    {

        //Get the content module
        $contentWidget = \App\Widget::getContentWidget($id);
        $contentType = $contentWidget->getData();
        $class = '\\' . $contentType->content_type . '\Content';

        //Get the content's index page
        return $class::index($id);

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
