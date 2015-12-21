<?php
/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    public $theme = 'default';
    public $theme_root;
    public $theme_path;
    public $theme_config;

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