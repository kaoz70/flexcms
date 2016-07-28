<?php
/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    public $theme = 'default';
    public $theme_root;
    public $theme_path;
    public $theme_config;

    public function __construct() {

        $this->_ci_ob_level  = ob_get_level();

        // Default
        $this->_ci_view_paths = array(
            APPPATH . 'views/' => TRUE
        );

        // Modules
        $module_view_paths = glob(APPPATH . 'modules/*/views/', GLOB_ONLYDIR);

        foreach ($module_view_paths as $module_view_path) {
            $this->_ci_view_paths = array(
                $module_view_path => TRUE,
            );
        }

        $this->_ci_library_paths = array(APPPATH, BASEPATH);

        $this->_ci_model_paths = array(APPPATH);

        $this->_ci_helper_paths = array(APPPATH, BASEPATH);

        log_message('debug', "Loader Class Initialized");

    }

    public function set_theme($theme_name)
    {
        $this->theme = $theme_name;
        $this->theme_root = FCPATH . 'themes' . DIRECTORY_SEPARATOR . $this->theme . DIRECTORY_SEPARATOR;
        $this->theme_config = json_decode(file_get_contents('themes/' . $this->theme . '/theme.json'))->config;
        $this->theme_path = $this->theme_root . 'views' . DIRECTORY_SEPARATOR;
        $this->_ci_view_paths = [ $this->theme_path => TRUE ];
        return $this->theme_config;
    }

    public function set_admin_theme()
    {
        $this->_ci_view_paths = [
            APPPATH.'views' . DIRECTORY_SEPARATOR	=> TRUE
        ];
    }

}