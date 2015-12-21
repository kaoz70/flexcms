<?php
	class Submit_model extends CI_Model
	{
		
		function getInputName($id)
		{
			$this->db->join('input', 'input.inputId = contacto_campos.inputId', 'LEFT');
			$this->db->join('es_contacto_campos', 'es_contacto_campos.contactoCampoId = contacto_campos.contactoCampoId', 'LEFT');
			$this->db->where('contacto_campos.contactoCampoId', $id);
			$this->db->order_by("contactoCampoPosicion", "asc"); 
			$query = $this->db->get('contacto_campos');
       		return $query->row();
		}
		
		function getContact($id)
		{
			$this->db->where('contactoId', $id);
			$query = $this->db->get('contactos');
       		return $query->row();
		}
		
		function getContacts()
		{
			$query = $this->db->get('contactos');
        	return $query->result();
		}
		
	}