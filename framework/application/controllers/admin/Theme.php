<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme extends CI_Controller {

	var $quality = 80;
	
	function __construct(){
		parent::__construct();
        $this->load->library('image_moo');
		$this->load->library('Seguridad');
		$this->seguridad->init();
	}
	
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

	public function modificar($folder)
	{
		$config = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
		$config->folder = $folder;
		$this->load->view('admin/themes/modificar_view', $config);
	}

	public function actualizar()
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

	public function logo($folder)
	{

		$imagedata = json_decode($this->input->post('imagedata'));
		$filedata = $this->input->post('filedata');
		$imagedata->path = 'themes/' . $folder . '/images/';
		$tempPath = $filedata['temp_path'];

		$response = new stdClass();
		$response->code = -1;
		$response->name = $imagedata->name;

		if(file_exists($tempPath))
		{

			$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
			$response->extension = pathinfo($imagedata->path . $imagedata->name, PATHINFO_EXTENSION);
			$new_path = $imagedata->path . 'logo.' . $response->extension;
			$old_path = $imagedata->path . 'logo.' . preg_replace('/\?+\d{0,}/', '', $theme->config->logo_extension);
            $og_path = $imagedata->path . 'og_logo.' . preg_replace('/\?+\d{0,}/', '', $theme->config->logo_extension);

			//Remove the old logo
			if(file_exists($old_path)){
				if(! unlink($old_path)) {
					$response->error_message = 'No se pudo eliminar el archivo anterior';
				}
			} else {
				$response->error_message = 'El archivo anterior no existe';
			}

			if(rename($tempPath, $new_path))
			{

				$time = time();

				// Read and write for owner, read for everybody else
				// We set this just in case (in some cases it was creating the file as 600)
				chmod($new_path, 0644);

				$theme->config->logo_extension = $response->extension . '?' . $time;
				$this->_save_conf($theme, $folder);

                //Create the default OpenGraph image from the logo
                //Make sure the logo is 200 x 200
                $this->image_moo
                    ->load($new_path)
                    //->set_background_colour("#FFF")
                    ->set_jpeg_quality($this->quality)
                    ->resize(200,200,TRUE)
                    ->save($og_path, true);

				$response->path = $new_path . '?' . $time;
				$response->code = 1;
				$response->message = 'success';

			}

			else
			{
				$response->message = 'No se pudo mover el archivo subido';
			}

		}

		else {
			$response->message = 'El archivo subido no existe';
		}

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);
	}

	public function delete_background($folder, $file)
	{

		$response = new stdClass();
		$path = 'themes/' . $folder . '/images/fondos/' . preg_replace('/\?+\d{0,}/', '', $file);

		//Remove the old logo
		if( file_exists($path)){

			if(unlink($path)) {
				$this->remove_background($folder, $file);
			} else {
				$response->message = 'No se pudo eliminar el archivo anterior';
				$data['return'] = $response;
				$this->load->view('admin/request/json', $data);
			}

		} else {
			$this->remove_background($folder, $file);
		}

	}

	private function remove_background($folder, $file)
	{
		$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
		foreach($theme->config->backgrounds as $key => $bg) {
			if($bg === $file){
				unset($theme->config->backgrounds[$key]);
			}
		}
		//Reset the array's keys or else json_encode will encode as an object instead of an array
		$theme->config->backgrounds = array_values($theme->config->backgrounds);
		$this->_save_conf($theme, $folder);

		//Generate the CSS
		$CSS = $this->load->view('admin/themes/background_css_view', array(
			'backgrounds' => $theme->config->backgrounds,
			'folder' => $folder,
		), TRUE);
		//Save the CSS to the file
		file_put_contents('themes/' . $folder . '/css/generated/background.css', $CSS);

		$this->load->view('admin/request/html', array('return' => 'success'));
	}

	private function _save_conf($theme, $folder)
	{
		return file_put_contents('themes/' . $folder . '/theme.json', json_encode($theme));
	}

	public  function reorganizar($folder)
	{
		$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
		$theme->config->backgrounds = json_decode($this->input->post('posiciones'));
		$this->_save_conf($theme, $folder);
	}

}
