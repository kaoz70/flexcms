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
use App\Utils;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class Admin extends AdminController {

    private $site_config;

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * Initial check
     */
    public function index()
    {

       try {

           $user = Sentinel::getUser();
           $this->site_config = Config::get();
           $data['title'] = $this->site_config['site_name'];
           $data['error'] = $this->session->flashdata('error');

           //User is loged in and is administrator
           if ($user && Sentinel::hasAccess(['admin'])) {
               $this->renderAdmin();
           }

           //User is logged in but not admin
           else if ($user && !Sentinel::hasAccess(['admin'])) {
               $data['error'] = 'Usted no tiene los permisos necesarios para poder entrar a esta secci&oacute;n. <a href="'. base_url() .'">regresar el inicio</a>';
               \App\View::blade(APPPATH . 'modules/admin/views/login', $data);
           }

           //Not loged in
           else {
               \App\View::blade(APPPATH . 'modules/admin/views/login', $data);
           }

       } catch (Exception $e) {

           $data = [
               "heading" => "Error",
               "message" => $e->getMessage(),
           ];

           \App\View::blade(APPPATH . 'views/errors/html/general', $data);

       }

    }

    /**
     * Shows the admin control panel
     */
    private function renderAdmin()
    {

        $data['titulo'] = $this->site_config['site_name'] . " Control Panel";
        $data['user'] = Sentinel::getUser();

        $root = Category::find(1);
        $root->descendants()->get();

        $data['root_node'] = $root;
        $data['visible'] = Content::getEditable(TRUE);
        $data['menu'] = \App\Admin::getModules();

        $data['assets_js'] = \App\Admin::getModuleAssets();

        \App\View::blade(APPPATH . 'modules/admin/views/index', $data);

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

        try {

            //Did not pass form validation
            if (!$this->form_validation->run()) {
                throw new InvalidArgumentException('invalid fields');
            }

            $credentials = [
                "email" => $this->input->post("username"),
                "password" => $this->input->post("password"),
            ];

            if ($user = Sentinel::authenticate($credentials, true)) {

                if(!Sentinel::hasAccess(['admin'])) {
                    throw new Exception("invalid access");
                }

                if ($this->input->is_ajax_request()) {
                    $data['return'] = [
                        'message' => 'Login success'
                    ];
                    $this->load->view( 'admin/request/json', $data );
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

        $data['return'] = [
            'error' => true,
            'message' => $errors
        ];
        $this->load->view( 'admin/request/json', $data );

    }

    public function notifyError()
    {

        $response = new \App\Response();

        //Save a log of the error
        log_message('error', json_encode($this->input->post()));

        //Send an email
        $mail = new \PHPMailer();

        $mail->setFrom('flexcms@dejabu.ec', 'FlexCMS');
        $mail->addAddress('miguel@dejabu.ec', 'Miguel Suarez');     // Add a recipient

        $mail->Subject = '[' . base_url() . '] Notificacion de error';
        $mail->Body = $this->load->view('admin/email/notify_error_view', [
            'data' => $this->input->post()
        ], true);

        if($mail->send()) {
            $response->setMessage('Notificacion enviada correctamente');
        } else {
            $response->setType('warning');
            $response->setMessage('No se pudo enviar el email con los datos del error, pero se guardo un registro');
        }

        $this->load->view(static::RESPONSE_VIEW, [static::RESPONSE_VAR => $response]);

    }

}
