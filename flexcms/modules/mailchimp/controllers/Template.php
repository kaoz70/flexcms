<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/16/15
 * Time: 2:25 PM
 */

namespace mailchimp;
$_ns = __NAMESPACE__;
include_once ('Main.php');

use mailing\Mailchimp;
use stdClass;
use Mailchimp_Error;

class Template extends Main implements \AdminInterface {

	/**
	 * List templates
	 * @param bool $select
	 * @return string
	 */
	public function index($select = FALSE)
	{
		$data = $this->mail_chimp->templates->getList();
		$data['select'] = (bool)$select;
		$this->load->view('admin/mailing/templates_view', $data);
	}

	/**
	 * Show the HTML editor to create a template from scratch
	 */
	public function create()
	{
		$data['window_title'] = 'Nuevo Template';
		$data['name'] = '';
		$data['html'] = '';
		$data['new'] = 'nuevo';

		$data['button_text'] = 'Crear Template';
		$data['link'] = base_url('admin/mailchimp/insert/');

		$this->load->view('admin/mailing/template_view', $data);
	}

	/**
	 * Creates the template in Mailchimp
	 */
	public function insert()
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
	 * @return string
	 */
	public function edit($template_id)
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
		$data['link'] = base_url('admin/mailchimp/template/update/' . $template_id);

		$this->load->view('admin/mailing/template_view', $data);
	}

	/**
	 * Update the tempate in Mailchimp
	 * @param $template_id
	 * @return string
	 */
	public function update($template_id) {
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
	 * @return string
	 */
	public function delete($template_id)
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

}