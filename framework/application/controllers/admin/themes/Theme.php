<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme extends MY_Controller {

	var $quality = 80;
	
	public function index()
	{

		$folders = array();
		$themes = array();

		foreach (new \DirectoryIterator('./themes/') as $file) {
			if ($file->isDot()) continue;
			if ($file->isDir()) {
				$folders[] = $file->getFilename();
			}
		}

		//Get theme data
		foreach ($folders as $folder) {
			$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
			$theme->folder = $folder;
			$themes[] = $theme;
		}

		$data['themes'] = $themes;

		$this->load->view('admin/themes/themes_view', $data);

	}

	public function edit($folder)
	{
		$config = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
		$config->folder = $folder;
		$this->load->view('admin/themes/modificar_view', $config);
	}

	public function update()
	{
		$folder = $this->input->post('theme');
		$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
		$theme->config->primary_color = $this->input->post('primary_color');
		$theme->config->secondary_color = $this->input->post('secondary_color');
		$theme->config->logo_extension = $this->input->post('logo_extension');
		$this->_save_conf($theme, $folder);

		//Generate the colors CSS
		$CSS = $this->load->view('admin/themes/colors_css_view', array(
			'primary_color' => $theme->config->primary_color,
			'secondary_color' => $theme->config->secondary_color,
		), TRUE);
		//Save the CSS to the file
		file_put_contents('themes/' . $folder . '/css/generated/_colors.scss', $CSS);

		//Clean the cache
		foreach (new \FilesystemIterator(APPPATH . '/cache/assetic/') as $file) {
			unlink($file->getPathname());
		}

		$response = new stdClass();
		$response->code = 0;

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);

	}

	protected function _save_conf($theme, $folder)
	{
		return file_put_contents('themes/' . $folder . '/theme.json', json_encode($theme));
	}


}
