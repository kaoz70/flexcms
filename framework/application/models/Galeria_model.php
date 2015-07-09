<?php
	class Galeria_model extends CI_Model
	{
	
		function getCategoria($catId)
		{
			$this->db->where('id', $catId);
            //$this->db->where('temporal', 0);
			$query = $this->db->get('descargas_categorias');
       		return $query->row();
		}
		
		function getByCategory($catId)
		{
			$this->db->where('descargaCategoriaId', $catId);
			$this->db->order_by('descargaPosicion', 'desc');
			$query = $this->db->get('descargas');
			return $query->result();
		}
		
	}