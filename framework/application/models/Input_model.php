<?php

class Input_model extends CI_Model
{

	function getByComponent($component)
	{
		$this->db->where('input_seccion', $component);
		$this->db->order_by('inputTipoContenido', 'asc');
		$query = $this->db->get('input');

		return $query->result();
	}

}