<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:18 PM
 */

namespace cart;
use stdClass;

class Cart extends \MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');

		// IMPORTANT! This global must be defined BEFORE the flexi cart library is loaded!
		// It is used as a global that is accessible via both models and both libraries, without it, flexi cart will not work.
		$this->flexi = new stdClass;

		// Load 'admin' flexi cart library by default.
		$this->load->library('flexi_cart_admin');

		$this->load->model('admin/cart_model', 'Cart');

		$this->load->library('ion_auth');
		$this->load->library('CMS_General');

	}

}