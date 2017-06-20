<?

class AdminController extends BaseController
{

    /**
     * View that we will use to show any CRUD messages / errors
     * @var string
     */
    const RESPONSE_VIEW = 'admin/request/json';

    /**
     * Variable name that we will use in the view to return the message / error
     * @var string
     */
    const RESPONSE_VAR = 'return';

    function __construct(){
        parent::__construct();
        $this->load->set_admin_theme();
        $this->lang->load('ui', 'spanish', FALSE, TRUE, '', 'admin');
    }

}