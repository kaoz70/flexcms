<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/16/15
 * Time: 11:55 AM
 */

//namespace themes; Codeigniter does not support controller namespaces

include_once('Theme.php'); //TODO: Why include??

class Background extends \Theme {

	public function delete($folder, $file)
	{

		$response = new stdClass();
		$response->code = 0;
		$data['return'] = $response;

		$path = 'themes/' . $folder . '/images/fondos/' . preg_replace('/\?+\d{0,}/', '', $file);

		//Remove the old background
		if(file_exists($path)){

			if(unlink($path)) {
				$this->remove($folder, $file);
			} else {
				$response->message = 'No se pudo eliminar el archivo anterior';
				$response->code = -1;
			}

		} else {
			$this->remove($folder, $file);
		}

		$this->load->view('admin/request/json', $data);

	}

	private function remove($folder, $file)
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
	}

	public function reorder($folder)
	{
		$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
		$theme->config->backgrounds = json_decode($this->input->post('posiciones'));
		$this->_save_conf($theme, $folder);
	}

}