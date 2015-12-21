<?

class AdminController extends BaseController
{

	function __construct(){
		parent::__construct();
		$this->load->set_admin_theme();
		$this->lang->load('ui', 'spanish', FALSE, TRUE, '', 'admin');
	}

}