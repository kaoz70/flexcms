<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends CI_Controller {

    var $theme_path;

    function __construct()
    {

        parent::__construct();

        $idiomas = \App\Language::all();

        $languageArray = array();

        foreach ($idiomas as $key => $value) {
            array_push($languageArray, $value->slug);
        }

        $language = \App\Language::preferred();

        //Load the language file for the Interface Translations
        $this->lang->load('errors', $language->name);

        //Set theme
        $config = \App\Config::get();
        $this->load->set_theme($config['theme']);
        $this->theme_path = base_url('themes/' . $config['theme']);

    }

    public function e404()
    {
	    set_status_header('404');
        $data['theme_path'] = $this->theme_path;
        $this->load->view('errors/error_404', $data);
    }

}