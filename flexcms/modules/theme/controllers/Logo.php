<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/16/15
 * Time: 12:00 PM
 */

//namespace themes; Codeigniter does not support controller namespaces
include_once('Theme.php'); //TODO: Why include??

class Logo extends \Theme {

	function __construct(){
		parent::__construct();
		$this->load->library('image_moo');
	}

	public function index()
	{

		$folder = $this->uri->segment(5);
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

}