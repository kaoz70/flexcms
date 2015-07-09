<?php

class Imagenes_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function get($imagenId)
	{
		$this->db->where('imagenId', $imagenId);
		$query = $this->db->get('imagenes');
        return $query->row();
	}

	public function getImages($seccionId){
		$this->db->where('imagenTemporal', 0);
		$this->db->where('seccionId', $seccionId);
		$this->db->order_by("imagenPosicion", "asc");
		$query = $this->db->get('imagenes');
		return $query->result();
	}

}