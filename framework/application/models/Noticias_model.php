<?php
class Noticias_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getAll($disabled = FALSE)
	{
		if($disabled) {
			$this->db->where('publicacionHabilitado', 1);
		}

		$this->db->where('publicacionTemporal', 0);
		$this->db->order_by("publicacionFecha", "desc");
		$query = $this->db->get('publicaciones');
		return $query->result_array();
	}

	function getImagenes($publicacionId)
	{
		$this->db->where('publicacionId', $publicacionId);
		$this->db->order_by("publicacionImagenPosicion", "asc");
		$query = $this->db->get('publicaciones_imagenes');
		return $query->result();
	}

	function get($id, $lang = 'es')
	{
		$this->db->join($lang.'_publicaciones', $lang.'_publicaciones.publicacionId = publicaciones.publicacionId', 'LEFT');

		if(is_string($id)){
			$this->db->where($lang.'_publicaciones.publicacionUrl', $id);
		} else if(is_int($id)) {
			$this->db->where($lang.'_publicaciones.publicacionId', $id);
		}

		$query = $this->db->get('publicaciones');
		return $query->row();
	}

	function getByPage($pageId, $lang = 'es')
	{
		$this->db->join($lang . '_publicaciones', $lang . '_publicaciones.publicacionId = publicaciones.publicacionId', 'left');
		$this->db->where('paginaId', $pageId);
		$this->db->where('publicacionTemporal', 0);
		$this->db->order_by('publicacionFecha', 'desc');
		$query = $this->db->get('publicaciones');
		return $query->result_array();
	}

	public function getLastNews($paginaId, $lang = 'es')
	{
		$this->db->join($lang . '_publicaciones', $lang . '_publicaciones.publicacionId = publicaciones.publicacionId', 'left');
		$this->db->where('publicacionHabilitado', 1);
		$this->db->where('paginaId', $paginaId);
		$this->db->where('publicacionTemporal', 0);
		$this->db->order_by("publicacionFecha", "desc");
		$query = $this->db->get('publicaciones');

		return $query->row();
	}

	public function getConfiguration()
	{
		$query = $this->db->get('noticias_configuracion');
		return $query->row();
	}

	function getAllImages($id)
	{
		$this->db->where('publicacionId', $id);
		$this->db->order_by("publicacionImagenPosicion", "asc");
		$query = $this->db->get('publicaciones_imagenes');
		return $query->result_array();
	}

	function addImage()
	{

		$posicion = $this->db->count_all('publicaciones_imagenes');

		$data = array(
			'publicacionId' => $this->input->post('publicacionId'),
			'publicacionImagenExtension' => $this->input->post('publicacionImagenExtension'),
			'publicacionImagenNombre' => $this->input->post('publicacionImagenNombre'),
			'publicacionImagenCoord' => $this->input->post('publicacionImagenCoord'),
			'publicacionImagenPosicion' => $posicion + 1,
		);

		$this->db->insert('publicaciones_imagenes', $data);

	}

	function getImagen($id)
	{
		$this->db->where('publicacionImagenId', $id);
		$query = $this->db->get('publicaciones_imagenes');

		return $query->row();
	}

	function deleteImage($id)
	{
		$this->db->where('publicacionImagenId', $id);
		$this->db->delete('publicaciones_imagenes');
	}

	function updateImage()
	{

		$id = $this->input->post('publicacionImagenId');

		$data = array(
			'publicacionImagenExtension' => $this->input->post('publicacionImagenExtension'),
			'publicacionImagenNombre' => $this->input->post('publicacionImagenNombre'),
			'publicacionImagenCoord' => urldecode($this->input->post('publicacionImagenCoord')),
		);

		$this->db->where('publicacionImagenId', $id);
		$this->db->update('publicaciones_imagenes', $data);
	}

	public function reorganizarImagenes($noticiaId)
	{

		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$imagenes = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos
		$this->db->where('publicacionId', $noticiaId);
		$this->db->order_by("publicacionImagenPosicion", "asc");
		$query = $this->db->get('publicaciones_imagenes');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las campos segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{
			$data = array('publicacionImagenPosicion' => $i + 1);

			$this->db->where('publicacionImagenId', $imagenes[$i]);
			$this->db->where('publicacionId', $noticiaId);
			$this->db->update('publicaciones_imagenes', $data);

		}

	}

	function add()
	{

		$query = $this->db->get('paginas');
		$pagina = $query->row();

		$data = array(
			'paginaId' => $pagina->id,
			'publicacionTemporal' => 1
		);

		$this->db->insert('publicaciones', $data);

		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'publicacionId' => $lastInsertId,
				'publicacionNombre' => '',
				'publicacionTexto' => '',
				'publicacionUrl' => ''
			);

			$this->db->insert($dim.'_publicaciones', $dataIdioma);

		}

		return $lastInsertId;

	}

	function delete($id)
	{
		$this->db->where('publicacionId', $id);
		$this->db->delete('publicaciones');

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {
			$dim = $idioma['idiomaDiminutivo'];
			$this->db->where('publicacionId', $id);
			$this->db->delete($dim.'_publicaciones');
		}

	}

	function update($general)
	{

		$id = $this->input->post('publicacionId');

		$habilitado = 0;
		if($this->input->post('publicacionHabilitado') == 'on')
			$habilitado = 1;

		$data = array(
			'publicacionClase' => $this->input->post('publicacionClase'),
			'publicacionFecha' => $this->input->post('publicacionFecha'),
			'paginaId' => $this->input->post('paginaId'),
			'publicacionHabilitado' => $habilitado,
			'publicacionImagen' => $this->input->post('publicacionImagen'),
			'publicacionImagenCoord' => urldecode($this->input->post('publicacionImagenCoord')),
			'publicacionTemporal' => 0
		);

		$this->db->where('publicacionId', $id);
		$this->db->update('publicaciones', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'publicacionId' => $id,
				'publicacionNombre' => $this->input->post($dim.'_publicacionNombre'),
				'publicacionLink' => $this->input->post($dim.'_publicacionLink'),
				'publicacionTexto' => $this->input->post($dim.'_publicacionTexto'),
				'publicacionUrl' => $general->generateSafeUrl($this->input->post($dim.'_publicacionNombre'))
			);

			//Revisamos si existe
			$this->db->where('publicacionId', $id);
			$query = $this->db->get($dim.'_publicaciones');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('publicacionId', $id);
				$this->db->update($dim.'_publicaciones', $dataIdioma);
			}
			else
				$this->db->insert($dim.'_publicaciones', $dataIdioma);

		}
	}

	public function getTranslation($diminutivo, $articuloId)
	{
		$this->db->where('publicacionId', $articuloId);
		$query = $this->db->get($diminutivo.'_publicaciones');

		return $query->row();
	}

	public function reorder()
	{

		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('paginas');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{

			$data = array('paginaPosicion' => $i + 1);

			$this->db->where('paginaId', $id[$i]);
			$this->db->update('paginas', $data);

		}

	}

	//Miguel
	public function idiomas()//funcion para saber la cantidad de idioma que existe en DB
	{
		$idiomas = array();

		$query = $this->db->get('idioma');

		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach ($result as $row)
			{
				//array con indice del id del idioma y con el diminutivoddel idioma
				//$idiomas[$row->idiomaId] = $row->idiomaDiminutivo;
				$idiomas[$row->idiomaId] = array(
					'diminutivo' => $row->idiomaDiminutivo,
					'nombre' => $row->idiomaNombre
				);
			}
		}
		else
		{
			$idiomas = 0;
		}
		return $idiomas;
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}

	/*
	 * CONFIG
	 *
	 */

	public function updateConfiguration()
	{

		$checked = 0;

		if($this->input->post('mostrarUltimaNoticiaYNoListado') == 'on')
			$checked = 1;

		$data = array(
			'mostrarUltimaNoticiaYNoListado' => $checked
		);

		$this->db->where('noticiaConfiguracionId', 1);
		$this->db->update('noticias_configuracion', $data);
	}

}