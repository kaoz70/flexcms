<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/16/15
 * Time: 2:24 PM
 */

namespace mailchimp;
$_ns = __NAMESPACE__;
include_once ('Main.php');

use mailing\Mailchimp;
use stdClass;
use Mailchimp_Error;


class Subscriber extends Main implements \AdminInterface {

	/**
	 * List all subscribers from a list
	 */
	public function index()
	{

		$list_id = $this->uri->segment(5);

		//According to Mailchimp's docs:
		// https://apidocs.mailchimp.com/api/2.0/lists/members.php
		// using these methods are too slow, use export lists:
		// https://apidocs.mailchimp.com/export/1.0/list.func.php
		//------------------------------------------------------------------------------------------
		//$subscribed = $this->mail_chimp->lists->members($list_id)['data'];
		//$unsubscribed = $this->mail_chimp->lists->members($list_id, 'unsubscribed')['data'];

		//Get all subscribed
		$data['subscribed'] = $this->_get_export_data('list', array(
			'id' => $list_id,
		));

		//Get all unsubscribed
		$data['unsubscribed'] = $this->_get_export_data('list', array(
			'id' => $list_id,
			'status' => 'unsubscribed',
		));

		$data['list_id'] = $list_id;
		$data['txt_titulo'] = 'Subscriptores';
		$data['nivel'] = 'nivel5';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'class' => $data['nivel'] . ' ajax importante n2 boton'
		);

		$data['menu'][] = anchor(base_url('admin/mailchimp/subscriber/create/' . $list_id), 'crear nuevo Subscriptor', $atts);

		$atts = array(
			'class' => $data['nivel'] . ' ajax importante n1 boton'
		);

		$data['menu'][] = anchor(base_url('admin/mailchimp/subscriber/import/' . $list_id), 'importar Subscriptores', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/mailing/subscribers_view', $data);
	}

	/**
	 * Return a formatted string of all the lists's subscribers
	 * @param $list_id
	 */
	public function get_subscribers($list_id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$response->subscribed = $this->_get_export_data('list', array(
				'id' => $list_id,
			));
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo eliminar la campa&ntilde;a', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));

	}

	/**
	 * Add a new subscriber to a list
	 */
	public function create() {

		$list_id = $this->uri->segment(5);

		$data['window_title'] = 'Nuevo Subscriptor';

		$data['email'] = '';
		$data['new'] = 'nuevo';
		$data['id'] = '';
		$data['list_id'] = $list_id;
		$data['email_type'] = 'html';
		$data['merges'] = array(
			'FNAME' => '',
			'LNAME' => '',
		);

		$data['link'] = 'admin/mailchimp/subscriber/insert/' . $list_id;
		$data['button_text'] = 'A&ntilde;adir Subscriptor';

		$this->load->view('admin/mailing/subscriber_view', $data);

	}

	/**
	 * Get the subscriber's data from a list
	 * @param $list_id
	 * @return string
	 */
	public function edit($list_id)
	{

		$subscriber_id = $this->uri->segment(6);

		$ret = $this->mail_chimp->lists->memberInfo($list_id, array(array('leid'=>$subscriber_id)));

		if(!$ret['success_count']) {
			$response = new stdClass();
			$response->message = 'No se puede acceder a los detalles de este suscriptor';
			$response->error_code = $ret['errors'][0]['code'];
			$response->error_message = $ret['errors'][0]['error'];
			$this->load->view('admin/request/json', array('return' => $response));
			return;
		}

		$data = $ret['data'][0];
		$data['new'] = '';
		$data['window_title'] = 'Modificar Subscriptor';
		$data['button_text'] = 'Modificar Subscriptor';
		$data['link'] = 'admin/mailchimp/subscriber/update/' . $list_id .'/' . $subscriber_id;
		$this->load->view('admin/mailing/subscriber_view', $data);
	}

	/**
	 * Adds a subscriber to the list, optionally sends an confirmation email (double opt-in)
	 */
	public function insert()
	{

		$list_id = $this->uri->segment(5);
		$options = $this->input->post();

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$ret = $this->mail_chimp->lists->subscribe($list_id, array('email' => $options['email']), $options['merges'], $options['email_type'], (bool)$this->input->post('double_optin'));
			$response->new_id = $ret['leid'];
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo suscribir este correo', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	/**
	 * Updates the subscribers data
	 * @param $list_id
	 * @return string
	 */
	public function update($list_id)
	{

		$subscriber_id = $this->uri->segment(5);
		$options = $this->input->post();
		$options['merges']['EMAIL'] = $options['email'];

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->lists->updateMember($list_id, array('leid' => $subscriber_id),$options['merges'], $options['email_type']);
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo actualizar el Subscriptor', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	/**
	 * Completely removes the subscriber's email from the list
	 * @param $list_id
	 * @return string
	 */
	public function delete($list_id)
	{

		$subscriber_id = $this->uri->segment(6);
		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->lists->unsubscribe($list_id, array('leid' => $subscriber_id), TRUE);
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo eliminar el correo', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	/**
	 * Shows the import form. TODO: select which columns of the CSV file are the ones you need
	 * @param $list_id
	 */
	public function import($list_id){

		$data['window_title'] = 'Importar Suscritores';
		$data['link'] = 'admin/mailchimp/subscriber/batch_import/' . $list_id;
		$data['button_text'] = 'Importar';
		$data['list_id'] = $list_id;

		$this->load->view('admin/mailing/batch_subscribe_view', $data);

	}

	/**
	 * Checks the CSV file and formats the data correctly
	 * @param $list_id
	 */
	public function batch_import($list_id)
	{

		$batch = array();
		$email_errors = array();

		//REad the CSV data
		$csv = new parseCSV($this->input->post('mailchimp_import'));

		//Remove any duplicates
		$arr_no_dup = $this->_array_unique_multidimensional($csv->data);

		//Format the data according to Mailchimp's data
		foreach ($arr_no_dup as $data) {

			$email = reset($data);
			$fname = next($data);
			$lname = next($data);

			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				//TODO: get this from DB, dynamic fields
				$merge_vars = array(
					'FNAME' => $fname !== FALSE ? $fname : '',
					'LNAME' => $lname !== FALSE ? $lname : '',
				);

				$batch[] = array(
					'email' => array('email' => $email),
					'email_type' => 'html',
					'merge_vars' => $merge_vars
				);
			} else {
				$email_errors[] = $email;
			}

		}

		if($email_errors) {

			//Save the good data to a temp file
			$temp_path = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'mailer' . DIRECTORY_SEPARATOR . mt_rand();

			if (!write_file($temp_path, json_encode($batch))){
				//TODO: error handling
				echo 'could not write to: ' . $temp_path;
			}

			$response = new stdClass();
			$response->message = 'Los siguientes correos tienen problemas';
			$response->error_code = 1;
			$response->error_emails = $email_errors;
			$response->temp_path = $temp_path;
			$response->list_id = $list_id;
			$this->load->view('admin/request/json', array('return' => $response));
		} else {
			$this->continue_import($list_id, $batch);
		}


	}

	/**
	 * This will get called from this Class and from an AJAX call, this method finally sends the data to Mailchimp
	 * @param $list_id
	 * @param null $batch
	 */
	public function continue_import($list_id, $batch = NULL)
	{

		if(!$batch) {
			//get from temp path
			$batch = file_get_contents($this->input->post('temp_path'));
			$batch = json_decode($batch);
			unlink($this->input->post('temp_path'));
		}

		//return;

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$response = $this->mail_chimp->lists->batchSubscribe($list_id, $batch, (bool)$this->input->post('double_optin'), (bool)$this->input->post('auto_update'));
			$response = json_decode(json_encode($response), FALSE); //Convert to Object
			$response->error_code = 0;
			$response->list_id = $list_id;
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('Hubieron problemas al importar la informaci&oacute;n', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	/**
	 * Unsubscribes the email from the list
	 * @param $list_id
	 * @param $subscriber_id
	 */
	public function unsubscribe($list_id, $subscriber_id)
	{
		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->lists->unsubscribe($list_id, array('leid' => $subscriber_id));
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo desuscribir el correo', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

}