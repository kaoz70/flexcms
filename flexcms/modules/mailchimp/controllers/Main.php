<?php

namespace mailchimp;
$_ns = __NAMESPACE__;

use stdClass;
use Mailchimp_Error;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Main extends \MY_Controller
{

	var $mail_chimp;
	var $data_center = 'us1';

	public function __construct()
	{
		parent::__construct();

		if(!function_exists('curl_init')) {

			$response = new stdClass();
			$response->message = 'Error: PHP CURL is not installed or active in system';
			$response->error_code = 1;
			$response->error_message = 'Install by console: sudo apt-get install php5-curl, then restart apache';

			echo $this->load->view('admin/request/json', array(
				'return' => $response
			), TRUE);
			exit;

		}
		$this->load->library('CMS_General');
		$this->load->helper('mailing');

		//Load the Mailchimp Library with the config set in the config file
		$this->config->load('mailchimp');

		try {
			$this->mail_chimp = new \Mailchimp($this->config->item('apikey'), array(
				'ssl_verifypeer' => $this->config->item('ssl_verifypeer')
			));
		} catch (Mailchimp_Error $e) {
			$this->_error('Error', $e, TRUE);
		}

		//Get the datacenter from the api key, found in Mailchimp's class constructor
		if (strstr($this->config->item('apikey'), "-")){
			list($key, $dc) = explode("-", $this->config->item('apikey'), 2);
			if (!$dc) {
				$dc = "us1";
			}
		}

		$this->data_center = $dc;

	}

	/**
	 * Lists all of Mailchimp's campaigns
	 */
	public function index()
	{

		$data['items'] = $this->mail_chimp->campaigns->getList()['data'];

		$data['url_rel'] = base_url('admin/mailchimp');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/mailchimp/campaign/edit');
		$data['url_eliminar'] = base_url('admin/mailchimp/campaign/delete');
		$data['url_search'] = '';

		$data['additional_buttons'] = array(
			array(
				'class' => 'mailing_send ajax nivel2',
				'link' => base_url('admin/mailchimp/campaign/check/'),
				'text' => '',
				'function' => array(
					'name' => 'mailchimp_status',
					'params' => 'status'
				),
			)
		);

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel2';
		$data['list_id'] = 'mailchimp';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'title';

		$data['txt_titulo'] = 'Campa&ntilde;as de Email';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$data['menu'][] = anchor(base_url('admin/mailchimp/campaign/create'), '3 - crear nueva campa&ntilde;a', array(
			'class' => $data['nivel'] . ' ajax importante n2 boton'
		));

		$data['menu'][] = anchor(base_url('admin/mailchimp/lists'), '1 - listado de destinatarios', array(
			'class' => $data['nivel'] . ' ajax importante n4 boton'
		));

		$data['menu'][] = anchor(base_url('admin/mailchimp/main/account'), 'detalles de la cuenta', array(
			'class' => $data['nivel'] . ' ajax n1 boton'
		));

		$data['menu'][] = anchor(base_url('admin/mailchimp/template'), '2 - templates', array(
			'class' => $data['nivel'] . ' ajax importante n3 boton'
		));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function report($id)
	{

	}


	/**
	 * Render the form to sent the test emails
	 * @param $id
	 */
	public function test($id)
	{

		//Get campaign data
		$campaign = $this->mail_chimp->campaigns->getList(array(
			'campaign_id' => $id
		));

		$data = $campaign['data'][0];
		$data['link'] = base_url('admin/mailchimp/send_test/' . $id);

		$this->load->view('admin/mailchimp/test_view', $data);

	}

	/**
	 * Sends an test email to the recipients from the form
	 * @param $id
	 */
	public function send_test($id)
	{
		$response = new stdClass();
		$response->error_code = 0;

		$emails = explode(',', $this->input->post('emails'));

		try {
			$this->mail_chimp->campaigns->sendTest($id, $emails, $this->input->post('email_type'));
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo enviar las pruebas', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));
	}

	public function send($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->campaigns->send($id);
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo enviar la campa&ntilde;a', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));
	}

	/**
	 * Render the account details
	 */
	public function account()
	{

		$data = $this->mail_chimp->helper->accountDetails();

		$data['window_title'] = 'Detalles de la Cuenta';
		$data['emails_limit'] = 12000;
		$data['emails_sent'] = $data['emails_limit'] - (int)$data['emails_left'];

		$this->load->view('admin/mailing/account_view', $data);

	}


	/**
	 * Stringify GET params for use in URL.
	 *
	 * Helper function used to convert an array, or object
	 * of params to a string.
	 *
	 * @param	mixed array, object
	 * @param	bool
	 * @return	string
	 */
	private function _stringify_params($params)
	{
		$p = NULL;

		if (empty($params))
		{
			return $p;
		}

		$params = (array) $params;

		foreach ($params as $key => $val)
		{
			$p .= ($key) ? '&'.$key.'='.$val.'' : '?'.$key.'='.$val.'';
		}

		return $p;
	}

	/**
	 * Use Mailchimp's export API to get some data
	 *
	 * @link https://apidocs.mailchimp.com/export/1.0/
	 *
	 * @param array $params
	 * @return array
	 */
	protected function _get_export_data($method, $params = array())
	{

		if (!empty($params)) {
			$p = $this->_stringify_params($params);
		} else {
			$p = '';
		}

		$chunk_size = 4096; //in bytes
		$list = array();

		/** a more robust client can be built using fsockopen **/
		$handle = @fopen('http://' . $this->data_center . '.api.mailchimp.com/export/1.0/' . $method . '?apikey='.$this->mail_chimp->apikey . $p,'r');
		if (!$handle) {
			echo "failed to access url\n";
		} else {
			$i = 0;
			$header = array();
			while (!feof($handle)) {
				$buffer = fgets($handle, $chunk_size);
				if (trim($buffer)!=''){
					$obj = json_decode($buffer);
					if ($i==0){
						//store the header row
						$header = $obj;
					} else {
						foreach ($header as $key => $member) {
							$list[$i][$header[$key]] = $obj[$key];
						}
					}
					$i++;
				}
			}
			fclose($handle);
		}

		return $list;
	}

	/**
	 * Remove duplicate values from an multidimensional array
	 * @link http://stackoverflow.com/a/308955
	 * @param $input
	 * @return array
	 */
	protected function _array_unique_multidimensional($input)
	{
		$serialized = array_map('serialize', $input);
		$unique = array_unique($serialized);
		return array_intersect_key($input, $unique);
	}

}