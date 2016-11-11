<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends AdminController {

    public function index()
    {
        $data['people'] = \Contact\Models\Contact::all();
        $data['forms'] = \Contact\Models\Form::all();

        $data['titulo'] = 'Contacto';
        $data['new_form'] = "crear nuevo formulario";
        $data['new_person'] = "crear nuevo email";

        $this->load->view('contact/index_view', $data);
    }

}
