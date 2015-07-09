<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends CI_Controller {

    var $theme_path;

    function __construct()
    {

        parent::__construct();

        $this->load->model('idiomas_model', 'Idiomas');
        $this->load->library('CMS_General');
        $this->load->model('configuracion_model', 'Config');

        $idiomas = $this->Idiomas->getLanguages();

        $languageArray = array();

        foreach ($idiomas as $key => $value) {
            array_push($languageArray, $value['idiomaDiminutivo']);
        }

        if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER))
            $lang = $this->cms_general->prefered_language($languageArray);
        else
            $lang = 'es';

        //Load the language file for the Interface Translations
        $idioma = $this->Idiomas->get($lang);
        $this->lang->load('errors', $idioma->idiomaNombre);

        //Set theme
        $config = $this->Config->get();
        $this->load->set_theme($config->theme);
        $this->theme_path = base_url('themes/' . $config->theme);

    }

    public function e404()
    {
	    set_status_header('404');
        $data['theme_path'] = $this->theme_path;
        $this->load->view('errors/error_404', $data);
    }

}