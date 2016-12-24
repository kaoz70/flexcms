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

        $this->_ci_library_paths = array(APPPATH, BASEPATH);

        $this->_ci_model_paths = array(APPPATH);

        $this->_ci_helper_paths = array(APPPATH, BASEPATH);

        log_message('debug', "Loader Class Initialized");

    }

    /**
     * Added the widgets view paths
     *
     * @param string $view
     * @param array $vars
     * @param bool $return
     * @return object|string
     */
    public function view($view, $vars = array(), $return = FALSE)
    {
        list($path, $_view) = Modules::find($view, $this->_module, 'views/');

        if ($path != FALSE)
        {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        }

        // Widgets
        $this->_ci_view_paths[APPPATH . 'widgets' . DIRECTORY_SEPARATOR] = TRUE;

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
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