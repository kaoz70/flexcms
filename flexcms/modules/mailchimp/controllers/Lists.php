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

class Lists extends Main implements \AdminInterface  {

	/**
	 * Get all the recipient lists
	 * @param bool $select - is the list a selectable or modifiable one
	 * @return string
	 */
	public function index($select = FALSE)
	{
		$data['items'] = $this->mail_chimp->lists->getList()['data'];
		$data['url_rel'] = base_url('admin/mailchimp/lists');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/mailchimp/lists/edit');
		$data['url_eliminar'] = base_url('admin/mailchimp/lists/delete');
		$data['url_search'] = '';

		$data['select'] = $select;
		$data['search'] = FALSE;
		$data['drag'] = FALSE;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'mailchimp';

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

	public function create(){}

	public function insert(){}

	/**
	 * Modify an existing recipient lists's details
	 * @param $list_id
	 * @return string
	 */
	public function edit($list_id)
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

	public function update($id){}

	public function delete($id){}

	/**
	 * Get the abuse info
	 * @param $list_id
	 */
	public function abuse($list_id)
	{

		$data['items'] = $this->mail_chimp->lists->abuseReports($list_id)['data'];
		$data['url_rel'] = base_url('admin/mailchimp/lists/abuse');
		$data['url_sort'] = '';
		$data['url_modificar'] = base_url('admin/mailchimp/view_abuse');
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

}