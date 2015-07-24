<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/16/15
 * Time: 2:23 PM
 */

namespace mailchimp;
$_ns = __NAMESPACE__;
include_once ('Main.php');

use mailing\Mailchimp;
use stdClass;
use Mailchimp_Error;

class Campaign extends Main implements \AdminInterface {

	public function index(){}

	/**
	 * Shows the form to create a new campaign
	 */
	public function create()
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
		$data['link'] = base_url('admin/mailchimp/campaign/insert/');

		$this->load->view('admin/mailing/campaign_view', $data);
	}

	/**
	 * Show the campaign's data so that we can edit it
	 * @param $campaign_id
	 * @return mixed
	 */
	public function edit($campaign_id)
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
		$data['removeUrl'] = base_url('admin/mailchimp/campaign/delete/');
		$data['window_title'] = 'Modificar Campa&ntilde;a';
		$data['button_text'] = 'Modificar Campa&ntilde;a';
		$data['link'] = base_url('admin/mailchimp/campaign/update/' . $campaign_id);

		if($campaigns['data'][0]['status'] !== 'sent') {
			$this->load->view('admin/mailing/campaign_view', $data);
		} else {
			$this->load->view('admin/mailing/campaign_sent_view', $data);
		}

	}

	/**
	 * Sends the data to Mailchimp and creates a new campaign
	 * @return string
	 */
	public function insert(){

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
	 * @return string
	 */
	public function update($campaign_id)
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
	 * Deletes a campaign
	 * @param $campaign_id
	 * @return string
	 */
	public function delete($campaign_id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try {
			$this->mail_chimp->campaigns->delete($campaign_id);
		} catch (Mailchimp_Error $e) {
			$response = $this->_error('No se pudo eliminar la campa&ntilde;a', $e);
		}

		$this->load->view('admin/request/json', array(
			'return' => $response
		));

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

		$data['menu'][] = anchor(base_url('admin/mailchimp/send/' . $id), 'Enviar Correos', array(
			'data-list' => $list['data'][0]['name'],
			'data-subscribers' => $list['data'][0]['stats']['member_count'],
			'class' => 'importante mailchimp_send n1 boton'
		));
		$data['menu'][] = anchor(base_url('admin/mailchimp/test/' . $id), 'Probar env&iacute;o de Correos', array(
			'class' => $data['nivel'] . ' ajax importante n2 boton'
		));
		$data['menu'][] = anchor(base_url('admin/mailchimp/preview/' . $id), 'Previsualizar', array(
			'class' => $data['nivel'] . ' ajax n3 boton'
		));
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/mailchimp/campaign_ready_view', $data);
	}

	public function preview($campaign_id)
	{

		$campaigns = $this->mail_chimp->campaigns->getList(array(
			'campaign_id' => $campaign_id
		));

		$data = $campaigns['data'][0];
		$this->load->view('admin/mailing/preview_view', $data);

	}

	public function content($campaign_id)
	{
		$content = $this->mail_chimp->campaigns->content($campaign_id);
		$data['return'] = $content['html'];
		$this->load->view('admin/request/html', $data);
	}

}