<?php

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:49 PM
 */
class MY_Controller extends CI_Controller {

	/**
	 * Image creation quality
	 * @var int
	 */
	protected $quality = 80;

	function __construct()
	{
		parent::__construct();
		$this->load->library('Seguridad');
		$this->seguridad->init();
	}

	/**
	 * Format an error message
	 *
	 * @param string $message
	 * @param Exception $e
	 * @param bool $render
	 * @return stdClass
	 */
	protected function _error($message = '', Exception $e, $render = FALSE)
	{
		$response = new stdClass();
		$response->message = $message;
		$response->error_code = $e->getCode();
		$response->error_message = $e->getMessage();

		if($render) {
			$this->load->view('admin/request/json', array('return' => $response));
		} else {
			return $response;
		}

	}

}