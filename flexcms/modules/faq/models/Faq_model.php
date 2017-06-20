<?php
class Faq_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function getAll($lang)
	{
		$this->db->join($lang.'_faq', $lang.'_faq.faqId = faq.faqId', 'LEFT');
		$this->db->order_by("faqPosicion", "asc");
		$query = $this->db->get('faq');
		return $query->result_array();
	}

	function getByPage($pageId, $lang)
	{
		return $this->db
            ->join($lang.'_faq', $lang.'_faq.faqId = faq.faqId', 'LEFT')
            ->where('paginaId', $pageId)
            ->order_by("faqPosicion", "asc")
            ->get('faq')
            ->result_array();
	}

	public function getFAQInfo($id)
	{
		$this->db->where('faqId', $id);
		$query = $this->db->get('faq');

		return $query->row();

	}

	public function createFAQ()
	{
		$posicion = $this->db->count_all('faq');

		$data = array(
			'faqClase' => $this->input->post('faqClase'),
			'faqHabilitado' => $this->input->post('faqHabilitado'),
			'paginaId' => $this->input->post('paginaId'),
			'faqPosicion' => $posicion + 1
		);

		$this->db->insert('faq',$data);

		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'faqId' => $lastInsertId,
				'faqPregunta' => $this->input->post($dim.'_faqPregunta'),
				'faqRespuesta' => $this->input->post($dim.'_faqRespuesta'),
			);

			$this->db->insert($dim.'_faq', $dataIdioma);

		}

		return $lastInsertId;

	}

	public function updateFAQ($id)
	{

		$data = array(
			'faqClase' => $this->input->post('faqClase'),
			'faqHabilitado' => $this->input->post('faqHabilitado'),
		);

		$this->db->where('faqId', $id);
		$this->db->update('faq', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'faqId' => $id,
				'faqPregunta' => $this->input->post($dim.'_faqPregunta'),
				'faqRespuesta' => $this->input->post($dim.'_faqRespuesta')
			);

			//Revisamos si existe
			$this->db->where('faqId', $id);
			$query = $this->db->get($dim.'_faq');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('faqId', $id);
				$this->db->update($dim.'_faq', $dataIdioma);
			}
			else
				$this->db->insert($dim.'_faq', $dataIdioma);

		}

	}

	public function deleteFAQ($id)
	{

		//Lo eliminamos
		$this->db->delete('faq', array('faqId' => $id));

		//Obtenemos todos los campos y los ordenamos por posicion
		$this->db->order_by('faqPosicion', 'asc');
		$query = $this->db->get('faq');

		//Ordenamos la posicion de todos
		$i = 1;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				$data = array(
					'faqPosicion' => $i
				);

				$this->db->where('faqId', $row->faqId);
				$this->db->update('faq', $data);

				$i++;
			}

		}

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {
			$dim = $idioma['idiomaDiminutivo'];
			$this->db->where('faqId', $id);
			$this->db->delete($dim.'_faq');
		}

	}

	public function reorderFAQ()
	{

		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('faq');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'faqPosicion' => $i + 1
			);

			$this->db->where('faqId', $id[$i]);
			$this->db->update('faq', $data);

		}

	}

	public function getTranslation($diminutivo, $faqId)
	{
		$this->db->where('faqId', $faqId);
		$query = $this->db->get($diminutivo.'_faq');
		return $query->row();
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}

	//Miguel
	public function idiomas()//funcion para saber la cantidad de idioma que existe en DB
	{
		$idiomas = array();

		$query = $this->db->get('idioma');

		if($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $row)
			{
				//array con indice del id del idioma y con el diminutivoddel idioma
				//$idiomas[$row->idiomaId] = $row->idiomaDiminutivo;
				$idiomas[$row->idiomaId] = array(
					'diminutivo' => $row->idiomaDiminutivo,
					'nombre' => $row->idiomaNombre
				);
			}
		}else
		{
			$idiomas = 0;
		}
		return $idiomas;
	}

}