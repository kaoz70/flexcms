<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 08/01/14
 * Time: 02:32 PM
 */

use App\Config;
use App\Category;
use App\Content;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class Admin extends AdminController {

    private $site_config;

    private $assets_css = array(
        'assets/admin/css/admin.scss',
        'assets/admin/css/datepicker_dashboard.css',
        'assets/admin/css/MooEditable.css',
        'assets/admin/css/MooEditable.Extras.css',
        'assets/admin/css/MooEditable.UploadImage.css',
        'assets/admin/css/MooEditable.Forecolor.css',
        'assets/admin/css/MooEditable.SilkTheme.css',
        'assets/admin/css/Tree.css',
        'assets/admin/css/ImageManipulation.css',
        'assets/admin/css/Scrollable.css',
        'assets/admin/css/Uploader.css'
    );

    function __construct(){
        parent::__construct();

        //$this->load->module('auth/main');
        $this->load->library('form_validation');
        //$this->config->load('auth/config', TRUE);
        $this->lang->load('auth', 'spanish');
        $this->load->set_admin_theme();

    }

    /**
     * Initial check
     */
    public function index()
    {

       /* $reset = Sentinel::findById(1);

        Sentinel::update($reset, [
            'password' => 'admin'
        ]);*/

       try {

           $user = Sentinel::getUser();
           $this->site_config = Config::get();
           $data['title'] = $this->site_config['site_name'];
           $data['error'] = $this->session->flashdata('error');
           $data['assets_css'] = $this->assets_css;

           //User is loged in and is administrator
           if ($user && Sentinel::hasAccess(['admin'])) {
               $this->renderAdmin();
           }

           //User is logged in but not admin
           else if ($user && !Sentinel::hasAccess(['admin'])) {
               $data['error'] = 'Usted no tiene los permisos necesarios para poder entrar a esta secci&oacute;n. <a href="'. base_url() .'">regresar el inicio</a>';
               $this->load->view('admin/login_view', $data);
           }

           //Not loged in
           else {
               $this->load->view('admin/login_view', $data);
           }

       } catch (Exception $e) {

           $data = [
               "heading" => "Error",
               "message" => $e->getMessage(),
           ];

           echo \App\View::blade(APPPATH . 'views/errors/html/general.blade.php', $data)->render();

       }

    }

    /**
     * Shows the admin control panel
     */
    private function renderAdmin()
    {

        $data['titulo'] = $this->site_config['site_name'] . " Control Panel";
        $data['user'] = Sentinel::getUser();

        $root = Category::allRoot()->first();
        $root->findChildren(9999);

        $data['root_node'] = $root;
        $data['visible'] = Content::getEditable(TRUE);
        $data['menu'] = \App\Admin::getModules();

        $data['assets_css'] = $this->assets_css;
        $data['assets_js'] = \App\Admin::getModuleAssets();

        $this->load->view('admin/index_view', $data);
    }

    /**
     * Logs the user out
     */
    public function logout()
    {
        Sentinel::logout();
        redirect('admin');
    }

    /**
     * Checks if the user is logged in
     */
    public function logged_in()
    {
        $data['return'] = Sentinel::check();
        $this->load->view('admin/request/json', $data);
    }

    /**
     * Gets the login form
     */
    public function loginForm(){
        $data['error'] = '';
        $data['form_action'] = base_url('admin/validate');
        $this->load->view('admin/login_form_view', $data);
    }

    /**
     * Checks the user login
     */
    public function validate(){

        $this->form_validation->set_rules('username', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');

        $data['assets_css'] = $this->assets_css;

        try {

            //Did not pass form validation
            if (!$this->form_validation->run()) {
                throw new InvalidArgumentException(validation_errors());
            }

            $credentials = [
                "email" => $this->input->post("username"),
                "password" => $this->input->post("password"),
            ];

            if ($user = Sentinel::authenticate($credentials, true)) {

                if(!Sentinel::hasAccess(['admin'])) {
                    throw new Exception("No tiene acceso a esta secci&oacute;n");
                }

                if ($this->input->is_ajax_request()) {
                    $data['return'] = "Login success";
                    $this->load->view( 'admin/request/html', $data );
                } else {
                    redirect( 'admin' );
                }

            } else {
                $errors = lang('login_unsuccessful');
            }

        } catch (NotActivatedException $e) {
            $errors = $this->lang->line('account_not_active');
        } catch (ThrottlingException $e) {
            $delay  = $e->getDelay();
            $errors = $this->lang->lineParam('account_blocked', $delay);
        } catch (InvalidArgumentException $e) {
            $errors = $e->getMessage();
        } catch (Exception $e) {
            $errors = $e->getMessage();
        }

        if($this->input->is_ajax_request()) {
            $data['return'] = $errors;
            $this->load->view( 'admin/request/json', $data );
        } else {
            $this->session->set_flashdata('error', $errors);
            redirect( 'admin' );
        }

    }

}
