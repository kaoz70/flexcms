<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends AdminController {

    public function index()
    {
        $data['result_contactos'] = \Contact\Models\Contact::all();
        $data['result_elementos'] = \Contact\Models\Field::where('section', 'contact')->orderBy('position')->get();
        $data['result_direcciones'] = \Contact\Models\Address::orderBy('position')->get();

        $data['titulo'] = 'Contacto';
        $data['txt_nuevoContacto'] = "crear nuevo contacto";
        $data['txt_nuevoElem'] = "crear nuevo campo";
        $data['txt_nuevaDireccion'] = "crear nueva direccion";

        $this->load->view('contact/index_view', $data);
    }

}
