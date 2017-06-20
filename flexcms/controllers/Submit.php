<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submit extends CI_Controller {

    var $filePath = './language/idioma_';

    function __construct(){

        parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('email');

        $this->load->model('submit_model', 'Submit');
        $this->load->model('mapas_model', 'Mapa');
        $this->load->model('idiomas_model', 'Idiomas');
		$this->load->model('configuracion_model', 'Config');

		$this->m_config = $this->Config->get();
        $this->theme_config = $this->load->set_theme($this->m_config->theme);

    }

    public function index()
    {

    }

    public function contacto()
    {

        if($this->input->post('contacto'))
            $contactoId = $this->input->post('contacto');
        else
        {
            $contacts = $this->Submit->getContacts();
            $contactoId = $contacts[0]->contactoId;
        }

        $idioma = $this->uri->segment(3, '');

        //Load the langue file for the Interface Translations
        $idioma = $this->Idiomas->get($idioma);
		if(!$idioma) {
			show_my_404(current_url(), $this->m_config->theme);
		}
        $this->lang->load('ui', $idioma->idiomaNombre);

        $campos = $this->input->post('campos');

        $contacto = $this->Submit->getContact($contactoId);

        $name = 'Website Mailer';

		if(!$campos) {
			show_my_404(current_url(), $this->m_config->theme);
		}

        $campoIds = array_keys($campos);

        $contactoDeMapa = '';

        $campoArr = array();

        for($i = 0 ; $i < count($campos) ; $i++)
        {

            $row = $this->Submit->getInputName($campoIds[$i]);

            $clase = $row->contactoCampoClase;
            $label = $row->contactoCampoValor;
            $validation = $row->contactoCampoValidacion;
            $obligatorio = $row->contactoCampoRequerido;

            $cont = $campos[$campoIds[$i]];

            //Es contacto de mapa
            if($row->inputTipoId == 8)
            {
                $contactoDeMapa = $this->Mapa->getUbicacion($cont);
                $cont = $contactoDeMapa->mapaUbicacionNombre;
            }

            if(stristr($clase, 'name'))
            {
                $name = $campos[$campoIds[$i]];
            }

            if($obligatorio)
            {
                $this->form_validation->set_rules('campos[' . $campoIds[$i] . ']', $label, 'required');
            }

            if($validation === 'email')
            {
                $this->form_validation->set_rules('campos[' . $campoIds[$i] . ']', $label, 'valid_email');
                $email = $campos[$campoIds[$i]];
            }

            $campoArr[$i] = new stdClass();
            $campoArr[$i]->data = $row;
            $campoArr[$i]->post_data = $cont;

        }

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
	        $data['return'] = $this->lang->line('ui_contact_form_error');
	        $data['return'] .= validation_errors();
	        $this->load->set_admin_theme();
	        $this->load->view('admin/request/html', $data);
        }
        else
        {

            $data['campos'] = $campoArr;

            $output = $this->load->view('email/contact_view', $data, TRUE);

            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            //It has to be an email thats created on the server
            $this->email->from($email, $name);
            $this->email->reply_to($email, $name);

            if($contactoDeMapa == '')
                $this->email->to($contacto->contactoEmail);
            else if ($contactoDeMapa->mapaUbicacionMail != '')
                $this->email->to($contactoDeMapa->mapaUbicacionMail);

            $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
            $this->email->message($output);

            $mailto =  mailto($contacto->contactoEmail, $contacto->contactoEmail);

            if($this->email->send())
	            $data['return'] = $this->lang->line('ui_contact_sent');
            else {
	            $data['return'] = $this->lang->line('ui_contact_error');
	            $data['return'] .= "<p>Si el problema persiste envíe un correo directamente a: $mailto</p>";
	            $data['return'] .= '<p>Detalles del error:</p>';
	            $data['return'] .= $this->email->print_debugger();
            }

	        $this->load->set_admin_theme();
	        $this->load->view('admin/request/html', $data);

        }

    }

}