<?php //TODO Delete this
	class Home_model extends CI_Model
	{
		
		
		//Miguel
		function getPages()
		{
			$this->db->order_by("paginaPosicion", "asc");
			$query = $this->db->get('paginas');
       		return $query->result();
		}

		//Miguel
		public function getDefaultLanguage()
		{
			$this->db->where('idiomaId', 0);
			$query = $this->db->get('idioma');
       		return $query->row();
		}
		
	}