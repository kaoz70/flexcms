<?php

class General_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

    //funcion para consultar el último id de cualquier tabla de DB
	public function generarId($tabla)
	{
        $query = $this->db->query("SHOW TABLE STATUS LIKE '$tabla'");
        $result = $query->row();
        $next_increment = $result->Auto_increment;
        return $next_increment;
	}

    public function unique_name($name, $section, $column, $id, $column_id)
    {
        $this->db->where($column, $name);
        $this->db->where($column_id . ' !=', $id);

        return $this->db->get($section)->result();
    }

    public function getCropImage($seccionId){
        $this->db->where('imagenTemporal', 0);
        $this->db->where('seccionId', $seccionId);
        $this->db->order_by('imagenPosicion');
        $query = $this->db->get('imagenes');
        return $query->row();
    }

    public function lockTable($table, $columnName, $resourceId, $userId)
    {

        $data = array(
            'usuarioId' => $userId
        );

        $this->db->where($columnName, $resourceId);
        $this->db->update($table, $data);
    }

}