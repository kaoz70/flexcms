<?php

class Config_model extends CI_Model {

	protected $table = 'config';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function get()
	{
		$result = new stdClass();
		$query = $this->db->get($this->table);

		foreach ($query->result() as $row) {
			$result->{$row->key} = $row->value;
		}

		return $result;
	}

	function item($key)
	{
		$result = new stdClass();
		$item = $this->db
			->where('key', $key)
			->get($this->table)
			->row();
		$result->{$item->key} = $item->value;
		return $result;
	}

	function siteIndex($lang)
	{
		$conf = $this->get();
		$query = $this->db
			->join('translations', 'translations.parent_id = content.id', 'LEFT')
			->where('language_id', $lang)
			->where('content.id', $conf->index_page_id)
			->get('content');
		return $query->row();
	}

	function getSecciones ($grupo = '') {
		$this->db->select('admin_secciones.adminSeccionId as adminSeccionId, adminSeccionNombre, adminSeccionController, desc');

		if($grupo != '') {
			$this->db->where('admin_usuarios_secciones.grupoId', $grupo);
		}

		$this->db->where('view_menu', 1);
		$this->db->join('admin_usuarios_secciones', 'admin_usuarios_secciones.adminSeccionId = admin_secciones.adminSeccionId', 'LEFT');
		$this->db->order_by('adminSeccionPosicion', 'ASC');
		$query = $this->db->get('admin_secciones');
		return $query->result();
	}

	function getSeccionesImagenes () {
		$this->db->join('admin_secciones', 'admin_secciones.adminSeccionId = imagenes_secciones.adminSeccionId', 'LEFT');
		$query = $this->db->get('imagenes_secciones');
		return $query->result();
	}

	function getImagenes()
	{
		$this->db->where('imagenTemporal', 0);
		$this->db->join('imagenes_secciones', 'imagenes_secciones.imagenSeccionId = imagenes.seccionId');
		$this->db->order_by("imagenPosicion", "asc");
		$query = $this->db->get('imagenes');
		return $query->result();
	}

	function createImagen()
	{

		$this->db->where('seccionId', $this->input->post('seccionId'));
		$query = $this->db->get('imagenes');

		$posicion = count($query->result());

		$data = array(
			'seccionId' => $this->input->post('seccionId'),
			'imagenSufijo' => $this->input->post('imagenSufijo'),
			'imagenAncho' => $this->input->post('imagenAncho'),
			'imagenAlto' => $this->input->post('imagenAlto'),
			'imagenCrop' => (int)$this->input->post('imagenCrop'),
			'imagenNombre' => $this->input->post('imagenNombre'),
			'imagenPosicion' => $posicion + 1,
		);

		$this->db->insert('imagenes', $data);

		return $this->db->insert_id();

	}

	function actualizarImagen($id)
	{

		$data = array(
			'seccionId' => $this->input->post('seccionId'),
			'imagenSufijo' => $this->input->post('imagenSufijo'),
			'imagenAncho' => $this->input->post('imagenAncho'),
			'imagenAlto' => $this->input->post('imagenAlto'),
			'imagenCrop' => (int)$this->input->post('imagenCrop'),
			'imagenNombre' => $this->input->post('imagenNombre'),
		);

		$this->db->where('imagenId', $id);
		$this->db->update('imagenes', $data);

	}

	function eliminarImagen($id)
	{
		$this->db->where('imagenId', $id);
		$this->db->delete('imagenes');
	}

	function getImagen($id)
	{
		$this->db->where('imagenId', $id);
		$this->db->join('imagenes_secciones', 'imagenes_secciones.imagenSeccionId = imagenes.seccionId');
		$query = $this->db->get('imagenes');
		return $query->row();
	}

	function reorganizarImagenes($seccionId){
		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$paginas = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos
		$this->db->where('seccionId', $seccionId);
		$this->db->order_by("imagenPosicion", "asc");
		$query = $this->db->get('imagenes');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las campos segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{

			$data = array('imagenPosicion' => $i + 1);

			$this->db->where('imagenId', $paginas[$i]);
			$this->db->where('seccionId', $seccionId);
			$this->db->update('imagenes', $data);

		}
	}
	
	//Miguel
	public function getDefaultLanguage()
	{
		$this->db->where('idiomaId', 0);
		$query = $this->db->get('idioma');
		return $query->row();
	}
	
	function getTwitter()
	{
		$query = $this->db->get('twitter_keys');
		return $query->row();
	}
	
	function getFacebook()
	{
		$query = $this->db->get('facebook_keys');
		return $query->row();
	}
	
	function save()
	{

		foreach ($this->input->post() as $key => $value) {

			if($key != 'csrf_test') {
				$this->db->set('value', $value);
				$this->db->where('key', $key);
				$this->db->update($this->table);
			}

		}

	}


	function guardarSecciones()
	{
		//Seciones visibles para el cliente
		$seccionesIds = json_decode($this->input->post('seccionesAdmin'));

		//Eliminamos las secciones
		$this->db->where('grupoId', 1);
		$this->db->delete('admin_usuarios_secciones');

		//Volvemos a añadir las secciones
		foreach ($seccionesIds as $secId)
		{
			$data = array(
				'grupoId' => 1,
				'adminSeccionId' => $secId
			);
			$this->db->insert('admin_usuarios_secciones', $data);
		}

	}

}