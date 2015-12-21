<?php
class Publicidad_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get($moduloId)
	{

		$date = date('Y-m-d H:i:s');

		$this->db->where('publicidadFechaInicio <', $date);
		$this->db->where('publicidadFechaFin >', $date);
		$this->db->where('publicidadEnabled', 1);
		$this->db->where('moduloId', $moduloId);
		$query = $this->db->get('publicidad');

		return $query->row();
	}

	public function getByPage($paginaId, $type = 0)
	{

		$date = date('Y-m-d H:i:s');

		$this->db->where('publicidadFechaInicio <', $date);
		$this->db->where('publicidadFechaFin >', $date);
		$this->db->where('publicidadEnabled', 1);
		if($type) {
			$this->db->where('publicidadTipoId', $type);

			if($type === 3) {
				$this->db->like('paginaIds', '%"' . $paginaId . '"%');
			}

		}

		//$this->db->where('paginaId', $paginaId);
		$query = $this->db->get('publicidad');

		return $query->row();
	}







	function getAll()
	{

		$adverts = $this->db
			->join('modulos', 'modulos.moduloId = publicidad.moduloId', 'LEFT')
			->get('publicidad')
			->result_array();

		//Some adverts are in multiple pages, duplicate them so that we can show them in all pages
		foreach ($adverts as $key => $advert) {
			$ids = (array)json_decode($advert['paginaIds']);
			foreach($ids as $key2 => $id) {
				$new = $advert;
				$new['paginaId'] = $id;
				$adverts[] = $new;
				if(!$key2) {
					unset($adverts[$key]);
				}
			}
		}

		//print_r($adverts);

		//Get popup publicidad
		/*$this->db->join('paginas', 'paginas.id = publicidad.paginaId', 'LEFT');
		$this->db->where('publicidadTipoId', 3);
		$query = $this->db->get('publicidad');
		$result = array_merge($result, $query->result_array());*/

		return $adverts;
	}

	function add()
	{

		$data = array(
			'bannerTypeId' => 1,
			'bannerTemporal' => 1
		);

		$this->db->insert('banners', $data);

		return $this->db->insert_id();

	}

	function delete($id)
	{
		$this->db->where('publicidadId', $id);
		$this->db->delete('publicidad');
	}

	function getData($id)
	{
		$this->db->where('publicidadId', $id);
		$query = $this->db->get('publicidad');

		return $query->row();
	}

	public function getTypes()
	{
		$query = $this->db->get('publicidad_tipo');
		return $query->result();
	}

	function insert()
	{

		$habilitado = 0;
		if($this->input->post('publicidadEnabled') == 'on')
			$habilitado = 1;

		$data = array(
			'publicidadNombre' => $this->input->post('publicidadNombre'),
			'publicidadFechaInicio' => $this->input->post('publicidadFechaInicio'),
			'publicidadFechaFin' => $this->input->post('publicidadFechaFin'),
			'publicidadClase' => $this->input->post('publicidadClase'),
			'publicidadArchivo1' => $this->input->post('publicidadArchivo1'),
			'publicidadArchivo2' => $this->input->post('publicidadArchivo2'),
			'publicidadTipoId' => $this->input->post('publicidadTipoId'),
			'publicidadEnabled' => $habilitado,
			'moduloId' => $this->input->post('moduloId'),
			'paginaIds' => json_encode($this->input->post('paginaIds')),
		);

		$this->db->insert('publicidad', $data);

		return $this->db->insert_id();

	}

	function update()
	{

		$id = $this->input->post('publicidadId');

		$habilitado = 0;
		if($this->input->post('publicidadEnabled') == 'on')
			$habilitado = 1;

		$data = array(
			'publicidadNombre' => $this->input->post('publicidadNombre'),
			'publicidadFechaInicio' => $this->input->post('publicidadFechaInicio'),
			'publicidadFechaFin' => $this->input->post('publicidadFechaFin'),
			'publicidadClase' => $this->input->post('publicidadClase'),
			'publicidadArchivo1' => $this->input->post('publicidadArchivo1'),
			'publicidadArchivo2' => $this->input->post('publicidadArchivo2'),
			'publicidadTipoId' => $this->input->post('publicidadTipoId'),
			'publicidadEnabled' => $habilitado,
			'moduloId' => $this->input->post('moduloId'),
			'paginaIds' => json_encode($this->input->post('paginaIds'))
		);

		$this->db->where('publicidadId', $id);
		$this->db->update('publicidad', $data);

	}

	public function reorder($bannerId)
	{

		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$this->db->where('bannerId', $bannerId);
		$query = $this->db->get('banner_images');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'bannerImagenPosicion' => $i + 1
			);

			$this->db->where('bannerImagesId', $id[$i]);
			$this->db->update('banner_images', $data);

		}

	}

}