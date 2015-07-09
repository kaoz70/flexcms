<?php
class Enlaces_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function getAll($lang, $enabled = TRUE)
	{
		$this->db->join($lang.'_enlaces', $lang.'_enlaces.enlaceId = enlaces.enlaceId', 'LEFT');
		if($enabled) {
			$this->db->where('enlacePublicado', 1);
		}
		$this->db->order_by("enlacePosicion", "ASC");
		$query = $this->db->get('enlaces');
		return $query->result_array();
	}

	function getByPage($id, $lang)
	{
		$this->db->join($lang.'_enlaces', $lang.'_enlaces.enlaceId = enlaces.enlaceId', 'LEFT');
		$this->db->where('enlacePublicado', 1);
		$this->db->where('paginaId', $id);
		$this->db->order_by("enlacePosicion", "ASC");
		$query = $this->db->get('enlaces');
		return $query->result();
	}

	/*******************************************************************************************************************
	 * ------------------------------------------------- ADMIN ---------------------------------------------------------
	 ******************************************************************************************************************/

	public function getInfo($id)
	{
		$this->db->where('enlaceId', $id);
		$query = $this->db->get('enlaces');

		return $query->row();

	}

	public function create()
	{

		$data = array(
			'paginaId' => $this->input->post('paginaId'),
			'enlaceLink' => $this->input->post('enlaceLink'),
			'enlaceImagen' => $this->input->post('enlaceImagen'),
			'enlaceImagenCoord' => urldecode($this->input->post('enlaceImagenCoord')),
			'enlaceClase' => $this->input->post('enlaceClase'),
			'enlacePublicado' => $this->input->post('enlacePublicado') == 'on' ? 1 : 0,
		);

		$this->db->insert('enlaces', $data);
		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'enlaceId' => $lastInsertId,
				'enlaceTexto' => $this->input->post($dim.'_enlaceTexto'),
			);

			$this->db->insert($dim.'_enlaces', $dataIdioma);

		}

		return $lastInsertId;

	}

	public function update($id)
	{

		$publicado = 0;
		if($this->input->post('enlacePublicado') == 'on')
			$publicado = 1;

		$extension = $this->input->post('enlaceImagen');

		if($extension)
			$extension = preg_replace('/\?+\d{0,}/', '', $extension) . '?' . time();

		$data = array(
			'paginaId' => $this->input->post('paginaId'),
			'enlaceLink' => $this->input->post('enlaceLink'),
			'enlaceImagen' => $extension,
			'enlaceImagenCoord' => urldecode($this->input->post('enlaceImagenCoord')),
			'enlaceClase' => $this->input->post('enlaceClase'),
			'enlacePublicado' => $publicado,
		);

		$this->db->where('enlaceId', $id);
		$this->db->update('enlaces', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'enlaceTexto' => $this->input->post($dim.'_enlaceTexto')
			);

			//Revisamos si existe
			$this->db->where('enlaceId', $id);
			$query = $this->db->get($dim.'_enlaces');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('enlaceId', $id);
				$this->db->update($dim.'_enlaces', $dataIdioma);
			}
			else {
				$dataIdioma['enlaceId'] = $id;
				$this->db->insert($dim.'_enlaces', $dataIdioma);
			}

		}

	}

	public function delete($id)
	{

		$this->db->where('enlaceId', $id);
		$query = $this->db->get('enlaces');
		$posicion = $query->row()->enlacePosicion;

		//Lo eliminamos
		$this->db->delete('enlaces', array('enlaceId' => $id));

		//Obtenemos todos los campos y los ordenamos por posicion
		$this->db->order_by('enlacePosicion', 'asc');
		$query = $this->db->get('enlaces');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos la posicion de todos
		$i = 1;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data = array(
					'enlacePosicion' => $i
				);

				$this->db->where('enlaceId', $row->enlaceId);
				$this->db->update('enlaces', $data);

				$i++;
			}

		}

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$this->db->where('enlaceId', $id);
			$this->db->delete($dim . '_enlaces');

		}

	}

	public function reorder()
	{

		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$ids = json_decode($this->input->post('posiciones'), true);

		foreach ($ids as $key => $id) {
			$this->db->where('enlaceId', $id);
			$this->db->update('enlaces', array(
				'enlacePosicion' => $key + 1
			));
		}

	}

	public function getEnlaceTranslation($diminutivo, $enlaceId)
	{
		$this->db->where('enlaceId', $enlaceId);
		$query = $this->db->get($diminutivo.'_enlaces');

		return $query->row();
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}

}