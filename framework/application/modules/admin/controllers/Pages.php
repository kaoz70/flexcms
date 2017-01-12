<?php use App\Response;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends RESTController {

    var $txt_boton = '';
    var $pagina_info = array();
    var $link;
    var $mptt;

    public function index_get($lang_id = 'null')
    {

        if($lang_id === 'null') {
            $lang_id = \App\Language::getDefault()->id;
        }

        $response = new Response();

        try{

            //Get the root page
            $root = \App\Page::find(1);
            $root->setLang($lang_id);

            $depth = 99999;
            $tree = $this->getNodes($root, $depth);

            $response->setData($tree);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obtener las p&aacute;ginas!', $e);
        }

        $this->response($response);

    }

    private function getNodes($root, $depth)
    {

        $nodes = [];

        $root->findChildren($depth);

        // We can now loop through our children
        foreach ($root->getChildren() as $node) {

            $nodes[] = $node;

            /*if (count($node->getChildren())) {
                $node->nodes = $this->getNodes($node, $depth);
            }*/

        }

        return $nodes;

    }

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
            $response->setError($e->getMessage(), $e);
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
