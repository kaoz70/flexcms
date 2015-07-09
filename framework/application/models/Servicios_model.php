<?php
class Servicios_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function getAll($lang, $paginaId, $destacados = FALSE)
	{
		$this->db->join($lang.'_servicios', $lang.'_servicios.servicioId = servicios.servicioId', 'LEFT');
		$this->db->order_by("servicioPosicion", "asc");
		$this->db->where('servicioTemporal', 0);
		$this->db->where('paginaId', $paginaId);
		if($destacados) {
			$this->db->where('servicioDestacado', 1);
		}
		$query = $this->db->get('servicios');

		$servicios = $query->result_array();

		//Get the images
		foreach($servicios as $serv) {
			$serv['imagenes'] = $this->db->where('servicio_id', $serv['servicioId'])->get('servicios_imagenes')->result();
		}

		return $servicios;

	}

	function getByPage($pageId, $lang, $destacados = FALSE)
	{
		$this->db->join($lang . '_servicios', $lang . '_servicios.servicioId = servicios.servicioId', 'LEFT');
		$this->db->where('paginaId', $pageId);
		$this->db->where('servicioTemporal', 0);

		if($destacados) {
			$this->db->where('servicioDestacado', 1);
		}

		$this->db->order_by('servicioPosicion', 'asc');
		$query = $this->db->get('servicios');

		$servicios = $query->result();

		//Get the images
		foreach($servicios as $serv) {
			$serv->imagenes = $this->db->where('servicio_id', $serv->servicioId)->get('servicios_imagenes')->result();
		}

		return $servicios;
	}

	function get($id, $lang, $paginaId = FALSE)
	{

		$this->db->join($lang.'_servicios', $lang.'_servicios.servicioId = servicios.servicioId', 'LEFT');

		if(is_string($id)){
			$this->db->where('servicioUrl', $id);
		} else if(is_int($id)) {
			$this->db->where('servicios.servicioId', $id);
		}

		if($paginaId) {
			$this->db->where('servicios.paginaId', $paginaId);
		}

		$query = $this->db->get('servicios');
		$servicio = $query->row();

		if(!$servicio) return FALSE;

		$servicio->imagenes = $this->db->where('servicio_id', $servicio->servicioId)->get('servicios_imagenes')->result();

		return $servicio;

	}

	public function getPaginated($pageId, $numItems, $actualPagePagination, $lang)
	{
		$this->db->where('paginaId', $pageId);
		$this->db->where('servicios.servicioTemporal', 0);
		$this->db->order_by('servicioPosicion', 'asc');
		$this->db->limit($numItems, $actualPagePagination);
		$this->db->join($lang.'_servicios', $lang.'_servicios.servicioId = servicios.servicioId', 'LEFT');
		$query = $this->db->get('servicios');
		return $query->result();
	}

	public function create($general)
	{
		$posicion = $this->db->count_all('servicios');

		$data = array(
			'servicioClase' => $this->input->post('servicioClase'),
			'servicioPublicado' => 1,
			'servicioImagen' => $this->input->post('servicioImagen'),
			'servicioPosicion' => $posicion + 1,
			'servicioTemporal' => 1,
			'servicioImagenCoord' => urldecode($this->input->post('servicioImagenCoord'))
		);

		$this->db->insert('servicios', $data);

		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'servicioId' => $lastInsertId,
				'servicioTitulo' => $this->input->post($dim.'_servicioTitulo'),
				'servicioTexto' => $this->input->post($dim.'_servicioTexto'),
				'servicioKeywords' => $this->input->post($dim.'_servicioKeywords'),
				'servicioDescripcion' => $this->input->post($dim.'_servicioDescripcion'),
				'servicioMetaTitulo' => $this->input->post($dim.'_servicioMetaTitulo'),
				'servicioUrl' => $general->generateSafeUrl($this->input->post($dim.'_servicioTitulo'))
			);

			$this->db->insert($dim.'_servicios', $dataIdioma);

		}

		return $lastInsertId;

	}

	public function update($id, $general)
	{

		$habilitado = 0;
		if($this->input->post('servicioPublicado') == 'on')
			$habilitado = 1;

		$data = array(
			'servicioClase' => $this->input->post('servicioClase'),
			'paginaId' => $this->input->post('paginaId'),
			'servicioPublicado' => $habilitado,
			'servicioDestacado' => (int) $this->input->post('servicioDestacado'),
			'servicioImagen' => $this->input->post('servicioImagen'),
			'servicioImagenCoord' => urldecode($this->input->post('servicioImagenCoord')),
			'servicioTemporal' => 0,
		);

		$this->db->where('servicioId', $id);
		$this->db->update('servicios', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'servicioId' => $id,
				'servicioTitulo' => $this->input->post($dim.'_servicioTitulo'),
				'servicioTexto' => $this->input->post($dim.'_servicioTexto'),
				'servicioKeywords' => $this->input->post($dim.'_servicioKeywords'),
				'servicioDescripcion' => $this->input->post($dim.'_servicioDescripcion'),
				'servicioMetaTitulo' => $this->input->post($dim.'_servicioMetaTitulo'),
				'servicioUrl' => $general->generateSafeUrl($this->input->post($dim.'_servicioTitulo'))
			);

			//Revisamos si existe
			$this->db->where('servicioId', $id);
			$query = $this->db->get($dim.'_servicios');
			$result = $query->row();

			if(count($result) > 0)
			{
				$this->db->where('servicioId', $id);
				$this->db->update($dim.'_servicios', $dataIdioma);
			}
			else
				$this->db->insert($dim.'_servicios', $dataIdioma);

		}

	}

	public function delete($id)
	{

		$this->db->where('servicioId', $id);
		$this->db->delete('servicios');

		//Obtenemos todos los campos y los ordenamos por posicion
		$this->db->order_by('servicioPosicion', 'asc');
		$query = $this->db->get('servicios');

		//Ordenamos la posicion de todos
		$i = 1;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{

				$data = array('servicioPosicion' => $i);

				$this->db->where('servicioId', $row->servicioId);
				$this->db->update('servicios', $data);

				$i++;
			}

		}

	}

	public function reorder($pageId)
	{

		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('servicios');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'servicioPosicion' => $i + 1
			);

			$this->db->where('servicioId', $id[$i]);
			$this->db->update('servicios', $data);

		}

	}

	public function getTranslation($diminutivo, $articuloId)
	{
		$this->db->where('servicioId', $articuloId);
		$query = $this->db->get($diminutivo.'_servicios');

		return $query->row();
	}

	public function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}

	public function getImages($id)
	{
		return $this->db
			->where('servicio_id', $id)
			->order_by('posicion')
			->get('servicios_imagenes')
			->result_array();
	}

	public function getImage($id)
	{
		return $this->db
			->where('id', $id)
			->get('servicios_imagenes')
			->row();
	}

	public function updateImage($id)
	{

		$extension = preg_replace('/\?+\d{0,}/', '', $this->input->post('extension'));

		$this->db->where('id', $id)
			->update('servicios_imagenes', array(
				'nombre' => $this->input->post('nombre'),
				'extension' => $extension . '?' . time(),
				'coords' => urldecode($this->input->post('coords')),
			));
	}

	public function reorderImages()
	{
		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('servicios_imagenes');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'posicion' => $i + 1
			);

			$this->db->where('id', $id[$i]);
			$this->db->update('servicios_imagenes', $data);

		}

	}

	public function deleteImage($id, $images)
	{
		$image = $this->getImage($id);
		$this->db->where('id', $id)->delete('servicios_imagenes');

		$extension = preg_replace('/\?+\d{0,}/', '', $image->extension);

		foreach ($images as $img) {
			if(file_exists('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . $img->imagenSufijo . '.' . $extension))
				unlink('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . $img->imagenSufijo . '.' . $extension);
		}

		//image
		if (file_exists('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '.' . $extension))
			unlink('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '.' . $extension);

		//Admin image
		if (file_exists('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '_admin.' . $extension))
			unlink('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '_admin.' . $extension);

		//Original image
		if (file_exists('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '_orig.' . $extension))
			unlink('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '_orig.' . $extension);

		//Search image
		if (file_exists('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '_search.' . $extension))
			unlink('assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $id . '_search.' . $extension);

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

}