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

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema!', $e);
        }

        $this->response($response);

    }

    private function tree()
    {

        $lang_id = \App\Language::getDefault()->id;

        //Get the root page
        $root = \App\Page::find(1);
        $root->setLang($lang_id);

        $depth = 99999;
        $tree = $this->getNodes($root, $depth);

        return $tree;

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

    public function images_get($page_id)
    {

        $response = new Response();

        try{
            $response->setData(\App\ImageConfig::where('category_id', $page_id)->orderBy('position', 'asc')->get());
        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response);

    }

    /**
     * Create new image configuration
     *
     * @param $page_id
     */
    public function images_post($page_id)
    {

        $response = new Response();

        try{

            $imageConfig = new \App\ImageConfig();
            $imageConfig->category_id;

            $response->setData(\App\ImageConfig::where('category_id', $page_id)->orderBy('position', 'asc')->get());
        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response);

    }

}
