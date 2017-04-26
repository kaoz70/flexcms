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

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * @return \Baum\Extensions\Eloquent\Collection
     */
    private function tree()
    {
        //Get the root page
        $root = \App\Page::find(1);
        $lang = \App\Language::getDefault();

        //Baum's toHierarchy() returns an object for root nodes, this returns an array
        //@link https://github.com/etrepat/baum/issues/213
        $roots = new \Baum\Extensions\Eloquent\Collection();
        foreach ($root->getDescendantsLang($lang)->toHierarchy() as $child) {
            $roots->add($child);
        }

        return $roots;
    }

    private function edit($id)
    {

        //Get the content module
        $contentWidget = \App\Widget::getContentWidget($id);

        if(!$contentWidget) {
            throw new CMSException('No content module for page: ' . $id);
        }

        $contentType = $contentWidget->data;

        if(!$contentType['content_type']) {
            throw new CMSException('No content module for page: ' . $id);
        }

        $class = '\content\\' . ucfirst($contentType['content_type']);

        if(!class_exists($class)) {
            throw new CMSException("Class '$class' does not exist");
        }

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

    /**
     * Update the category tree structure with the one passed in the request data
     *
     * @return string
     */
    public function index_put()
    {

        $response = new Response();

        try {

            $categories = [
                [
                    'id' => 1,
                    'children' => \App\Category::setOrder($this->put())
                ],
            ];

            \App\Category::buildTree($categories);
            $response->setData($categories);

        }  catch (\Exception $e) {
            $response->setError('Ocurri&oacute; un problema!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

}
