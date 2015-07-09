<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Mailing extends CI_Controller
{

	var $mail_chimp;
	var $data_center = 'us1';

	public function __construct()
	{
		parent::__construct();

		$this->load->library('Seguridad');
		$this->load->library('CMS_General');

		$this->load->helper('mailing');

		//Load the Mailchimp Library with the config set in the config file
		$this->config->load('mailing');

		try {
			$this->mail_chimp = new Mailchimp($this->config->item('apikey'), array(
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

		$this->seguridad->init();

	}

	/**
	 * Lists all of mailchimps campaigns
	 */
	public function index()
	{

		$data['items'] = $this->mail_chimp->campaigns->getList()['data'];

		$data['url_rel'] = base_url('admin/mailing');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/mailing/modify_campaign');
		$data['url_eliminar'] = base_url('admin/mailing/delete_campaign');
		$data['url_search'] = '';

		$data['additional_buttons'] = array(
			array(
				'class' => 'mailing_send ajax nivel2',
				'link' => base_url('admin/mailing/check/'),
				'text' => 'enviar',
				'function' => array(
					'name' => 'mailchimp_status',
					'params' => 'status'
				),
			)
		);

		$data['search'] = false;
		$data['drag'] = false;
		$data['nivel'] = 'nivel2';
		$data['list_id'] = 'mailing';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'title';

		$data['txt_titulo'] = 'Campa&ntilde;as de Email';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$data['menu'][] = anchor(base_url('admin/mailing/new_campaign'), '3 - crear nueva campa&ntilde;a', array(
			'class' => $data['nivel'] . ' ajax importante n2 boton'
		));

		$data['menu'][] = anchor(base_url('admin/mailing/lists'), '1 - listado de destinatarios', array(
			'class' => $data['nivel'] . ' ajax importante n4 boton'
		));

		$data['menu'][] = anchor(base_url('admin/mailing/account'), 'detalles de la cuenta', array(
			'class' => $data['nivel'] . ' ajax n1 boton'
		));

		$data['menu'][] = anchor(base_url('admin/mailing/templates'), '2 - templates', array(
			'class' => $data['nivel'] . ' ajax importante n3 boton'
		));

		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function report($id)
	{

	}

	/**
	 * Check if the campaign is ready to send or has some errors
	 * @param $id
	 */
	public function check($id)
	{

		try {
			$data = $this->mail_chimp->campaigns->ready($id);
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('Ocurrio un error', $e);
			$this->load->view('admin/request/json', array(
				'return' => $response
			));
			return;
		}

		$data['window_title'] = 'Resumen del estado';

		//Get campaign data
		$campaign = $this->mail_chimp->campaigns->getList(array(
			'campaign_id' => $id
		));

		//Get list data
		$list = $this->mail_chimp->lists->getList(array(
			'id' => $campaign['data'][0]['list_id']
		));

		$data['menu'] = array();
		$data['nivel'] = 'nivel3';
		$data['list_id'] = $campaign['data'][0]['list_id'];

		$data['menu'][] = anchor(base_url('admin/mailing/send/' . $id), 'Enviar Correos', array(
			'data-list' => $list['data'][0]['name'],
			'data-subscribers' => $list['data'][0]['stats']['member_count'],
			'class' => 'importante mailchimp_send n1 boton'
		));
		$data['menu'][] = anchor(base_url('admin/mailing/test/' . $id), 'Probar env&iacute;o de Correos', array(
			'class' => $data['nivel'] . ' ajax importante n2 boton'
		));
		$data['menu'][] = anchor(base_url('admin/mailing/preview/' . $id), 'Previsualizar', array(
			'class' => $data['nivel'] . ' ajax n3 boton'
		));
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/mailing/campaign_ready_view', $data);
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
		$data['link'] = base_url('admin/mailing/send_test/' . $id);

		$this->load->view('admin/mailing/test_view', $data);

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
	 * Shows the form to create a new campaign
	 */
	public function new_campaign()
	{

		$data['window_title'] = 'Nueva Campa&ntilde;a';
		$data['title'] = '';
		$data['status'] = '';
		$data['subject'] = '';
		$data['from_name'] = '';
		$data['from_email'] = '';
		$data['content'] = array(
			'text' => '',
			'html' => '',
		);
		$data['analytics'] = '';
		$data['id'] = '';
		$data['new'] = 'nuevo';

		$data['list_id'] = '';
		$data['template_id'] = '';

		$lists = $this->mail_chimp->lists->getList();
		$data['lists'] = $lists['data'];

		$templates = $this->mail_chimp->templates->getList();
		$data['templates'] = $templates['user'];

		$data['button_text'] = 'Crear Campa&ntilde;a';
		$data['link'] = base_url('admin/mailing/create_campaign/');

		$this->load->view('admin/mailing/campaign_view', $data);
	}

	/**
	 * Show the campaign's data so that we can edit it
	 * @param $campaign_id
	 */
	public function modify_campaign($campaign_id)
	{

		$campaigns = $this->mail_chimp->campaigns->getList(array(
			'campaign_id' => $campaign_id
		));

		$data = $campaigns['data'][0];
		$lists = $this->mail_chimp->lists->getList();
		$data['lists'] = $lists['data'];

		$templates = $this->mail_chimp->templates->getList();
		$data['templates'] = $templates['user'];
		$data['lists'] = $lists['data'];

		$data['new'] = '';
		$data['removeUrl'] = base_url('admin/mailing/delete_campaign/');
		$data['window_title'] = 'Modificar Campa&ntilde;a';
		$data['button_text'] = 'Modificar Campa&ntilde;a';
		$data['link'] = base_url('admin/mailing/update_campaign/' . $campaign_id);

		if($campaigns['data'][0]['status'] !== 'sent') {
			$this->load->view('admin/mailing/campaign_view', $data);
		} else {
			$this->load->view('admin/mailing/campaign_sent_view', $data);
		}



	}

	/**
	 * Sends the data to Mailchimp and creates a new campaign
	 */
	public function create_campaign(){

		$options = $this->input->post();
		$options['analytics'] = array('google' => $options['analytics']);

		$response = new stdClass();
		$response->error_code = 0;

		try {

			//Get the template's content
			$content = $this->mail_chimp->templates->info($options['template_id']);

			$retval = $this->mail_chimp->campaigns->create('regular', $options, array(
				'html' => $content['source']
			));

			$response->new_id = $retval['id'];

		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo crear la campa&ntilde;a', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));

	}

	/**
	 * Updates the campaign with the info from the form
	 * @param $campaign_id
	 */
	public function update_campaign($campaign_id)
	{

		$options = $this->input->post();
		$options['analytics'] = array('google' => $options['analytics']);

		$response = new stdClass();
		$response->error_code = 0;

		try {

			//Update the campaign's options
			$this->mail_chimp->campaigns->update($campaign_id, 'options', $options);

			//Get the template's content
			$content = $this->mail_chimp->templates->info($options['template_id']);

			//Update the campaign's content from the template
			$this->mail_chimp->campaigns->update($campaign_id, 'content', array(
				'html' => $content['source']
			));

		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo actualizar la campa&ntilde;a', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));

	}

	/**
	 * Deletes a campaign from Mailchimp
	 * @param $campaign_id
	 */
	public function delete_campaign($campaign_id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->campaigns->delete($campaign_id);
			$this->load->view('admin/request/json', array('return' => $response));
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo eliminar la campa&ntilde;a', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));

	}

	public function preview($campaign_id)
	{

		$campaigns = $this->mail_chimp->campaigns->getList(array(
			'campaign_id' => $campaign_id
		));

		$data = $campaigns['data'][0];
		$this->load->view('admin/mailing/preview_view', $data);

	}

	public function get_campaign_content($campaign_id)
	{
		$content = $this->mail_chimp->campaigns->content($campaign_id);
		$data['return'] = $content['html'];
		$this->load->view('admin/request/html', $data);
	}

	/**
	 * Get all the recipient lists
	 * @param bool $select - is the list a selectable or modifiable one
	 */
	public function lists($select = FALSE)
	{
		$data['items'] = $this->mail_chimp->lists->getList()['data'];
		$data['url_rel'] = base_url('admin/mailing/lists');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/mailing/modify_list');
		$data['url_eliminar'] = base_url('admin/mailing/delete_list');
		$data['url_search'] = '';

		$data['select'] = $select;
		$data['search'] = FALSE;
		$data['drag'] = FALSE;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'mailing';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'name';

		$data['txt_titulo'] = 'Listados de destinatarios';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	/**
	 * Modify an existing recipient lists's details
	 * @param $list_id
	 */
	public function modify_list($list_id)
	{

		$data = $this->mail_chimp->lists->getList(array(
			'id' => $list_id
		))['data'];

		foreach($data as $list) {
			if($list_id === $list['id']){
				$data = $list;
			}
		}

		$this->load->view('admin/mailing/list_view', $data);
	}

	/**
	 * List all subscribers from a list
	 * @param $list_id
	 */
	public function subscribers($list_id)
	{

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

		$data['menu'][] = anchor(base_url('admin/mailing/new_subscriber/' . $list_id), 'crear nuevo Subscriptor', $atts);

		$atts = array(
			'class' => $data['nivel'] . ' ajax importante n1 boton'
		);

		$data['menu'][] = anchor(base_url('admin/mailing/import_subscribers/' . $list_id), 'importar Subscriptores', $atts);
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
	 * @param $list_id
	 */
	public function new_subscriber($list_id) {

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

		$data['link'] = 'admin/mailing/create_subscriber/' . $list_id;
		$data['button_text'] = 'A&ntilde;adir Subscriptor';

		$this->load->view('admin/mailing/subscriber_view', $data);

	}

	/**
	 * Get the subscriber's data from a list
	 * @param $list_id
	 * @param $subscriber_id
	 */
	public function modify_subscriber($list_id, $subscriber_id)
	{

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
		$data['link'] = 'admin/mailing/update_subscriber/' . $list_id .'/' . $subscriber_id;
		$this->load->view('admin/mailing/subscriber_view', $data);
	}

	/**
	 * Adds a subscriber to the list, optionally sends an confirmation email (double opt-in)
	 * @param $list_id
	 */
	public function create_subscriber($list_id)
	{
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
	 * @param $subscriber_id
	 */
	public function update_subscriber($list_id, $subscriber_id)
	{
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
	 * @param $subscriber_id
	 */
	public function delete_subscriber($list_id, $subscriber_id)
	{
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
	public function import_subscribers($list_id){

		$data['window_title'] = 'Importar Suscritores';
		$data['link'] = 'admin/mailing/batch_import/' . $list_id;
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

	/**
	 * List templates
	 * @param bool $select
	 */
	public function templates($select = FALSE)
	{
		$data = $this->mail_chimp->templates->getList();
		$data['select'] = (bool)$select;
		$this->load->view('admin/mailing/templates_view', $data);
	}

	/**
	 * Show the HTML editor to create a template from scratch
	 */
	public function create_template()
	{
		$data['window_title'] = 'Nuevo Template';
		$data['name'] = '';
		$data['html'] = '';
		$data['new'] = 'nuevo';

		$data['button_text'] = 'Crear Template';
		$data['link'] = base_url('admin/mailing/insert_template/');

		$this->load->view('admin/mailing/template_view', $data);
	}

	/**
	 * Creates the template in Mailchimp
	 */
	public function insert_template()
	{
		$options = $this->input->post();

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$ret = $this->mail_chimp->templates->add($options['name'], $options['html']);
			$response->new_id = $ret['template_id'];
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo crear este template', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	/**
	 * Modify an existing template
	 * @param $template_id
	 */
	public function modify_template($template_id)
	{

		$content = $this->mail_chimp->templates->info($template_id);

		//TODO: find a better way to get the template's name
		$templates = $this->mail_chimp->templates->getList();
		foreach ($templates['user'] as $template) {
			if ($template['id'] === (int)$template_id) {
				$data['name'] = $template['name'];
				break;
			}
		}

		$data['window_title'] = 'Modificar Template';
		$data['html'] = $content['source'];
		$data['new'] = '';

		$data['button_text'] = 'Modificar Template';
		$data['link'] = base_url('admin/mailing/update_template/' . $template_id);

		$this->load->view('admin/mailing/template_view', $data);
	}

	/**
	 * Update the tempate in Mailchimp
	 * @param $template_id
	 */
	public function update_template($template_id) {
		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->templates->update($template_id, $this->input->post());
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo actualizar este template', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	/**
	 * Deactivates a template, it doesen't delete it
	 * @param $template_id
	 */
	public function delete_template($template_id)
	{
		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->templates->del($template_id);
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo eliminar este template', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	/**
	 * Get the abuse info
	 * @param $list_id
	 */
	public function list_abuse($list_id)
	{

		$data['items'] = $this->mail_chimp->lists->abuseReports($list_id)['data'];
		$data['url_rel'] = base_url('admin/mailing/list_abuse');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/mailing/view_abuse');
		$data['url_eliminar'] = '';
		$data['url_search'] = '';

		$data['select'] = FALSE;
		$data['search'] = TRUE;
		$data['drag'] = FALSE;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'abuse';

		$data['idx_id'] = 'campaign_id';
		$data['idx_nombre'] = 'email';

		$data['txt_titulo'] = 'Reportes de abuso';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);

	}

	/**
	 * Format an error message
	 *
	 * @param string $message
	 * @param Mailchimp_Error $e
	 * @param bool $render
	 * @return stdClass
	 */
	private function _error($message = '', $e, $render = FALSE)
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
	private function _get_export_data($method, $params = array())
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
	private function _array_unique_multidimensional($input)
	{
		$serialized = array_map('serialize', $input);
		$unique = array_unique($serialized);
		return array_intersect_key($input, $unique);
	}

}