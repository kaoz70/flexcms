<?php use App\Response;
use App\Utils;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends RESTController {

    public function index_get(){

        $response = new Response();

        try{

            $data['config'] = \App\Config::get();
            $data['pages'] = \App\Page::getList(1);
            $data['themes'] = Utils::getFolders('./themes/');

            $response->setData($data);

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obtener la configuraci&oacute;n!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    public function index_put()
    {

        $response = new Response();

        try{
            \App\Config::saveData($this->put());
            $response->setMessage("Configuraci&oacute;n guardada correctamente");
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al guardar!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

}
