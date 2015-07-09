<?php
class Article_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function getByPage($id, $lang)
	{
		$this->db->join($lang.'_articulos', $lang.'_articulos.articuloId = articulos.articuloId', 'LEFT');
		$this->db->order_by("articuloPosicion", "asc");
		$this->db->where('paginaId', $id);
		$query = $this->db->get('articulos');
		return $query->result_array();
	}

	function all($lang)
	{
		$this->db->join($lang.'_articulos', $lang.'_articulos.articuloId = articulos.articuloId', 'LEFT');
		$this->db->order_by("articuloPosicion", "asc");
		$query = $this->db->get('articulos');
		return $query->result_array();
	}

	/*******************************************************************************************************************
	 * ------------------------------------------------- ADMIN ---------------------------------------------------------
	 ******************************************************************************************************************/

	public function get($id)
	{
		$this->db->where('articuloId', $id);
		$query = $this->db->get('articulos');

		return $query->row_array();
	}

	public function insert()
	{
		$posicion = $this->db->count_all('articulos');

		$data = array(
			'paginaId' => $this->input->post('paginaId'),
			'articuloPosicion' => $posicion + 1,
			'articuloClase' => $this->input->post('articuloClase'),
		);

		$this->db->insert('articulos', $data);

		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$this->db->insert($dim.'_articulos', array(
				'articuloId' => $lastInsertId,
				'articuloTitulo' => $this->input->post($dim.'_articuloTitulo'),
				'articuloContenido' => $this->input->post($dim.'_articuloContenido')
			));

		}

		return $lastInsertId;

	}

	public function update($id)
	{

		$data = array(
			'paginaId' => $this->input->post('paginaId'),
			'articuloClase' => $this->input->post('articuloClase'),
			'articuloHabilitado' => $this->input->post('habilitado'),
		);

		$this->db->where('articuloId', $id);
		$this->db->update('articulos', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'articuloId' => $id,
				'articuloTitulo' => $this->input->post($dim.'_articuloTitulo'),
				'articuloContenido' => $this->input->post($dim.'_articuloContenido')
			);

			//Revisamos si existe
			$this->db->where('articuloId', $id);
			$query = $this->db->get($dim.'_articulos');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('articuloId', $id);
				$this->db->update($dim.'_articulos', $dataIdioma);
			}
			else
				$this->db->insert($dim.'_articulos', $dataIdioma);

		}

	}

	public function delete($id)
	{

		//Lo eliminamos
		$this->db->delete('articulos', array('articuloId' => $id));

		//Obtenemos todos los campos y los ordenamos por posicion
		$this->db->order_by('articuloPosicion', 'asc');
		$query = $this->db->get('articulos');

		//Ordenamos la posicion de todos
		$i = 1;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				$data = array('articuloPosicion' => $i);

				$this->db->where('articuloId', $row->articuloId);
				$this->db->update('articulos', $data);

				$i++;
			}

		}

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {
			$dim = $idioma['idiomaDiminutivo'];
			$this->db->where('articuloId', $id);
			$this->db->delete($dim.'_articulos');
		}
	}

	public function reorder($pageId)
	{

		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$paginas = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos
		$this->db->where('paginaId', $pageId);
		$this->db->order_by("articuloPosicion", "asc");
		$query = $this->db->get('articulos');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las campos segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{

			$data = array('articuloPosicion' => $i + 1);

			$this->db->where('articuloId', $paginas[$i]);
			$this->db->where('paginaId', $pageId);
			$this->db->update('articulos', $data);

		}

	}

	public function getTranslation($diminutivo, $articuloId)
	{
		$this->db->where('articuloId', $articuloId);
		$query = $this->db->get($diminutivo.'_articulos');

		return $query->row();
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}

}