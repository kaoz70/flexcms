<?php
class Descargas_model extends CI_Model
{

	public function getCategory($id, $lang)
	{
		if(is_string($id)){
			$this->db->where('descargaCategoriaUrl', $id);
		} else if(is_int($id)) {
			$this->db->where('descargas_categorias.id', $id);
		}

		$this->db->join($lang.'_descargas_categorias', $lang.'_descargas_categorias.descargaCategoriaId = descargas_categorias.id', 'LEFT');
		$query = $this->db->get('descargas_categorias');
		return $query->row_array();
	}

	public function getCategoryTranslation($id, $currentLang, $targetLang)
	{

		$cat = $this->getCategory($id, $currentLang);

		if($cat){
			$cat = $this->getCategory((int)$cat->id, $targetLang);
		}

		return $cat;
	}

	public function getFirstCategory()
	{
		$this->db->limit(1);
		$query = $this->db->get('descargas_categorias');

		return $query->row();
	}

	public function getDownloads($id, $lang)
	{
		$this->db->join($lang.'_descargas', $lang.'_descargas.descargaId = descargas.descargaId');
		$this->db->join('descargas_categorias', 'descargas_categorias.id = descargas.descargaCategoriaId', 'LEFT');
		$this->db->join($lang.'_descargas_categorias', $lang.'_descargas_categorias.descargaCategoriaId = descargas_categorias.id', 'LEFT');

		if(is_string($id)){
			$this->db->where('descargaCategoriaUrl', $id);
		} else if(is_int($id)) {
			$this->db->where('descargas_categorias.id', $id);
		}

		$this->db->order_by("descargaPosicion", "asc");
		$query = $this->db->get('descargas');

		return $query->result_array();
	}

	public function getDownloadsByDate($catId, $year, $month)
	{
		$this->db->where('descargaCategoriaId', $catId);
		$this->db->where('YEAR(descargaFecha)', $year);
		$this->db->where('MONTH(descargaFecha)', $month);
		$this->db->where('temporal', 0);
		$this->db->order_by("descargaPosicion", "asc");
		$query = $this->db->get('descargas');
		return $query->result();
	}

	public function getDates($id)
	{
		$this->db->where('descargaCategoriaId', $id);
		$this->db->where('temporal', 0);
		$this->db->order_by("YEAR(descargaFecha)", "desc");
		$this->db->group_by('YEAR(descargaFecha)');
		$query = $this->db->get('descargas');
		return $query->result();
	}


	/*******************************************************************************************************************
	 * ------------------------------------------------- ADMIN ---------------------------------------------------------
	 ******************************************************************************************************************/

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	/*
	 * CATEGORIES
	 */

	function getCategories()
	{
		$this->db->select('descargas_categorias.id AS id, descargaCategoriaNombre');
		$this->db->join('es_descargas_categorias', 'es_descargas_categorias.descargaCategoriaId = descargas_categorias.id');
		$query = $this->db->get('descargas_categorias');
		return $query->result_array();
	}

	function getCategoryType()
	{
		$query = $this->db->get('descargas_categoria_tipo');
		return $query->result_array();
	}

	function addCategory($general)
	{

		$node = new GalleryTree(array('temporal' => 1));
		$node->makeLastChildOf(GalleryTree::find(1));

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'descargaCategoriaId' => $node->id,
				'descargaCategoriaNombre' => $this->input->post($dim.'_descargaCategoriaNombre'),
				'descargaCategoriaUrl' => $general->generateSafeUrl($this->input->post($dim.'_descargaCategoriaNombre'))
			);

			$this->db->insert($dim.'_descargas_categorias', $dataIdioma);

		}

		return $node->id;

	}

	function deleteCategory($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('descargas_categorias');

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$this->db->where('descargaCategoriaId', $id);
			$this->db->delete($dim . '_descargas_categorias');

		}

	}

	function updateCategory($general)
	{

		$id = $this->input->post('descargaCategoriaId');

		$habilitado = 0;
		if($this->input->post('descargaCategoriaPublicado') == 'on')
			$habilitado = 1;

		$data = array(
			'descargaCategoriaClase' => $this->input->post('descargaCategoriaClase'),
			'descargaCategoriaEnlace' => $this->input->post('descargaCategoriaEnlace'),
			'descargaCategoriaPublicado' => $habilitado,
			'temporal' => 0,
			'descargaCategoriaImagen' => $this->input->post('descargaCategoriaImagen'),
			'descargaCategoriaImagenCoord' => urldecode($this->input->post('descargaCategoriaImagenCoord')),
		);


		$this->db->where('id', $id);
		$this->db->update('descargas_categorias', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'descargaCategoriaNombre' => $this->input->post($dim.'_descargaCategoriaNombre'),
				'descargaCategoriaUrl' => $general->generateSafeUrl($this->input->post($dim.'_descargaCategoriaNombre'))
			);

			//Revisamos si existe
			$this->db->where('descargaCategoriaId', $id);
			$query = $this->db->get($dim.'_descargas_categorias');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('descargaCategoriaId', $id);
				$this->db->update($dim.'_descargas_categorias', $dataIdioma);
			}
			else {
				$dataIdioma['descargaCategoriaId'] = $id;
				$this->db->insert($dim.'_descargas_categorias', $dataIdioma);
			}

		}

	}


	/*
	 * DOWNLOADS
	 */

	function getDownload($id)
	{
		$this->db->join('descargas_categorias', 'descargas_categorias.id = descargas.descargaCategoriaId', 'LEFT');
		$this->db->where('descargaId', $id);
		$query = $this->db->get('descargas');

		return $query->row_array();
	}

	public function addDownload($general)
	{

		$posicion = $this->db->count_all('descargas');
		$filename = $this->input->post('fileName');

		if($filename != '' && preg_match('/\?+\d{0,}/', $filename)){
			$extension = preg_replace('/\?+\d{0,}/', '', $filename);
			$extension .= '?' . time();
		} else {
			$extension = $filename;
		}

		$data = array(
			'descargaCategoriaId' => $this->input->post('descargaCategoriaId'),
			'descargaFecha' => $this->input->post('descargaFecha'),
			'descargaArchivo' => $extension,
			'descargaEnlace' => $this->input->post('descargaEnlace'),
			'descargaEnabled' => $this->input->post('descargaEnabled') == 'on' ? 1 : 0,
			'descargaPosicion' => $posicion,
			'descargaImagenCoord' => urldecode($this->input->post('descargaImagenCoord'))
		);

		$this->db->insert('descargas', $data);
		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'descargaId' => $lastInsertId,
				'descargaNombre' => $this->input->post($dim.'_descargaNombre'),
				'descargaUrl' => $general->generateSafeUrl($this->input->post($dim.'_descargaNombre'))
			);

			$this->db->insert($dim.'_descargas', $dataIdioma);

		}

		return $lastInsertId;

	}

	public function updateDownload($general)
	{
		$id = $this->input->post('descargaId');

		$habilitado = 0;
		if($this->input->post('descargaEnabled') == 'on')
			$habilitado = 1;

		$filename = $this->input->post('fileName');

		if($filename != '' && preg_match('/\?+\d{0,}/', $filename)){
			$extension = preg_replace('/\?+\d{0,}/', '', $filename);
			$extension .= '?' . time();
		} else {
			$extension = $filename;
		}

		$posicion = $this->db->count_all('descargas');

		$data = array(
			'descargaCategoriaId' => $this->input->post('descargaCategoriaId'),
			'descargaFecha' => $this->input->post('descargaFecha'),
			'descargaArchivo' => $extension,
			'descargaEnlace' => $this->input->post('descargaEnlace'),
			'descargaEnabled' => $habilitado,
			'descargaPosicion' => $posicion,
			'descargaImagenCoord' => urldecode($this->input->post('descargaImagenCoord'))
		);

		$this->db->where('descargaId', $id);
		$this->db->update('descargas', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'descargaNombre' => $this->input->post($dim.'_descargaNombre'),
				'descargaUrl' => $general->generateSafeUrl($this->input->post($dim.'_descargaNombre'))
			);

			//Revisamos si existe
			$this->db->where('descargaId', $id);
			$query = $this->db->get($dim.'_descargas');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('descargaId', $id);
				$this->db->update($dim.'_descargas', $dataIdioma);
			}
			else
			{
				$dataIdioma['descargaId'] = $id;
				$this->db->insert($dim.'_descargas', $dataIdioma);
			}

		}
	}

	public function deleteDownload($id)
	{
		$this->db->where('descargaId', $id);
		$this->db->delete('descargas');

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$this->db->where('descargaId', $id);
			$this->db->delete($dim . '_descargas');

		}

	}

	public function reorderDownloads($pageId)
	{
		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$paginas = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos
		$this->db->where('descargaCategoriaId', $pageId);
		$this->db->order_by("descargaPosicion", "asc");
		$query = $this->db->get('descargas');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las campos segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{

			$data = array('descargaPosicion' => $i + 1);

			$this->db->where('descargaId', $paginas[$i]);
			$this->db->where('descargaCategoriaId', $pageId);
			$this->db->update('descargas', $data);
		}
	}

	public function getCategoriaTranslation($diminutivo, $descargaCategoriaId)
	{
		$this->db->where('descargaCategoriaId', $descargaCategoriaId);
		$query = $this->db->get($diminutivo.'_descargas_categorias');

		return $query->row();
	}

	public function getDescargaTranslation($diminutivo, $descargaId)
	{
		$this->db->where('descargaId', $descargaId);
		$query = $this->db->get($diminutivo.'_descargas');

		return $query->row();
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}

}