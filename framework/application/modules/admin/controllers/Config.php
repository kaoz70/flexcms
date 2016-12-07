<?php use App\Response;
use App\Utils;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends AdminController {

    public function general(){


        $response = new Response();

        try{

            $data['config'] = \App\Config::get();
            $data['pages'] = \App\Page::getList(1);
            $data['themes'] = Utils::getFolders('./themes/');

            $response->setSuccess(true);
            $response->setData($data);

        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al obtener la configuraci&oacute;n!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }

    public function save()
    {

        $response = new Response();

        try{
            \App\Config::saveData($this->input->post());
            $response->setSuccess(true);
            $response->setMessage("Configuraci&oacute;n guardada correctamente");
        } catch (Exception $e) {
            $response->setMessage($this->error('Ocurri&oacute; un problema al guardar!', $e));
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }

}
