<?php

class Mapas_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function getUbicaciones()
	{
		$this->db->order_by("mapaUbicacionNombre", "asc"); 
		$query = $this->db->get('mapas_ubicaciones');
		return $query->result_array();
	}

	public function getUbicacion($id)
	{
		$this->db->where('mapaUbicacionId', $id);
		$query = $this->db->get('mapas_ubicaciones');

		return $query->row();
	}

	public function getAll()
	{
		$query = $this->db->get('mapas');

		return $query->result_array();
	}

	public function get($id)
	{
		$this->db->where('mapaId', $id);
		$query = $this->db->get('mapas');

		return $query->row();
	}

	public function create()
	{

		$habilitado = 0;
		if($this->input->post('mapaPublicado') == 'on')
			$habilitado = 1;

		$data = array(
			'mapaNombre' => $this->input->post('mapaNombre'),
			'mapaImagen' => $this->input->post('mapaImagen'),
			'mapaPublicado' => $habilitado
		);

		$this->db->insert('mapas', $data);

		return $this->db->insert_id();

		/*$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'articuloId' => $lastInsertId,
				'articuloTitulo' => $this->input->post($dim.'_articuloTitulo'),
				'articuloContenido' => $this->input->post($dim.'_articuloContenido')
			);

			$this->db->insert($dim.'_articulos', $dataIdioma);

		}*/
	}

	public function update($id)
	{

		$habilitado = 0;
		if($this->input->post('mapaPublicado') == 'on')
			$habilitado = 1;

		$data = array(
			'mapaNombre' => $this->input->post('mapaNombre'),
			'mapaImagen' => $this->input->post('mapaImagen') . '?' . time(),
			'mapaPublicado' => $habilitado,
			'mapaImagenCoord' => urldecode($this->input->post('mapaImagenCoord'))
		);

		$this->db->where('mapaId', $id);
		$this->db->update('mapas', $data);

		/*$idiomas = $this->getLanguages();

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

		}*/

	}

	public function delete($id)
	{

		//Lo eliminamos
		$this->db->delete('mapas', array('mapaId' => $id));

		/*
		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {
			$dim = $idioma['idiomaDiminutivo'];
			$this->db->where('articuloId', $id);
			$this->db->delete($dim.'_articulos');
		}*/
	}

	public function createUbicacion()
	{
		$habilitado = 0;
		if($this->input->post('mapaUbicacionPublicado') == 'on')
			$habilitado = 1;

		$data = array(
			'mapaUbicacionNombre' => $this->input->post('mapaUbicacionNombre'),
			'mapaUbicacionX' => $this->input->post('mapaUbicacionX'),
			'mapaUbicacionY' => $this->input->post('mapaUbicacionY'),
			'mapaId' => $this->input->post('mapaId'),
			'mapaUbicacionClase' => $this->input->post('mapaUbicacionClase'),
			'mapaUbicacionImagen' => $this->input->post('mapaUbicacionImagen'),
			'mapaUbicacionImagenCoord' => urldecode($this->input->post('mapaUbicacionImagenCoord')),
			'mapaUbicacionPublicado' => $habilitado
		);

		$this->db->insert('mapas_ubicaciones', $data);
		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();
		$campos = $this->getCampos();

		for($i = 0 ; $i < count($campos) ; $i++){

			$dataIdiomaRel = array(
				'mapaCampoId' => (int)$campos[$i]['mapaCampoId'],
				'mapaUbicacionId' => $lastInsertId
			);
			$this->db->insert('mapa_campo_rel', $dataIdiomaRel);
			$lastCampoInsertId = $this->db->insert_id();

			foreach ($idiomas as $key => $idioma) {

				$dim = $idioma['idiomaDiminutivo'];
				$camposPost = $this->input->post($dim.'_campo');
				$campoIds = array_keys($camposPost);

				$dataIdioma = array(
					'mapaCampoRelId' => $lastCampoInsertId,
					'mapaCampoTexto' => $camposPost[$campoIds[$i]]
				);

				$this->db->insert($dim.'_mapa_campo_rel', $dataIdioma);
			}

		}

		return $lastInsertId;

	}

	public function updateUbicacion($id)
	{
		$habilitado = 0;
		if($this->input->post('mapaUbicacionPublicado') == 'on')
			$habilitado = 1;

		$extension = '';

		if($this->input->post('mapaUbicacionImagen'))
			$extension = $this->input->post('mapaUbicacionImagen') . '?' . time();

		$data = array(
			'mapaUbicacionNombre' => $this->input->post('mapaUbicacionNombre'),
			'mapaUbicacionX' => $this->input->post('mapaUbicacionX'),
			'mapaUbicacionY' => $this->input->post('mapaUbicacionY'),
			'mapaUbicacionClase' => $this->input->post('mapaUbicacionClase'),
			'mapaId' => $this->input->post('mapaId'),
			'mapaUbicacionImagen' => $extension,
			'mapaUbicacionImagenCoord' => urldecode($this->input->post('mapaUbicacionImagenCoord')),
			'mapaUbicacionPublicado' => $habilitado
		);

		$this->db->where('mapaUbicacionId', $id);
		$this->db->update('mapas_ubicaciones', $data);

		$idiomas = $this->getLanguages();
		$campos = $this->getCampos();

		for($i = 0 ; $i < count($campos) ; $i++){

			$this->db->where('mapaCampoId', (int)$campos[$i]['mapaCampoId']);
			$this->db->where('mapaUbicacionId', $id);
			$campoCreado = $this->db->get('mapa_campo_rel')->row();

			if(!$campoCreado){
				$dataIdiomaRel = array(
					'mapaCampoId' => (int)$campos[$i]['mapaCampoId'],
					'mapaUbicacionId' => $id
				);
				$this->db->insert('mapa_campo_rel', $dataIdiomaRel);
				$lastCampoInsertId = $this->db->insert_id();
			} else {
				$lastCampoInsertId = $campoCreado->mapaCampoRelId;
			}

			foreach ($idiomas as $key => $idioma) {

				$dim = $idioma['idiomaDiminutivo'];

				$this->db->where('mapaCampoRelId', $lastCampoInsertId);
				$campoCreadoRel = $this->db->get($dim.'_mapa_campo_rel')->row();

				$camposPost = $this->input->post($dim.'_campo');
				$campoIds = array_keys($camposPost);

				if(!$campoCreadoRel) {

					$dataIdioma = array(
						'mapaCampoRelId' => $lastCampoInsertId,
						'mapaCampoTexto' => $camposPost[$campoIds[$i]]
					);

					$this->db->insert($dim.'_mapa_campo_rel', $dataIdioma);
				} else {
					$dataIdioma = array(
						'mapaCampoTexto' => $camposPost[$campoIds[$i]]
					);

					$this->db->where('mapaCampoRelId', $lastCampoInsertId);
					$this->db->update($dim.'_mapa_campo_rel', $dataIdioma);

				}


			}

		}

	}

	public function deleteUbicacion($id)
	{
		$this->db->delete('mapas_ubicaciones', array('mapaUbicacionId' => $id));
	}

	public function getPositionTranslation($diminutivo, $id, $ubicacionId){
		$this->db->where('mapaCampoId', (int)$id);
		$this->db->where('mapaubicacionId', (int)$ubicacionId);
		$this->db->join($diminutivo.'_mapa_campo_rel', $diminutivo.'_mapa_campo_rel.mapaCampoRelId = mapa_campo_rel.mapaCampoRelId', 'LEFT');
		$query = $this->db->get('mapa_campo_rel');

		return $query->row();
	}

	public function getCampos(){
		$this->db->join('es_mapas_campos', 'es_mapas_campos.mapaCampoId = mapas_campos.mapaCampoId');
		$this->db->join('input', 'input.inputId = mapas_campos.inputId');
		$this->db->join('input_tipo', 'input_tipo.inputTipoId = input.inputTipoId');
		$this->db->order_by('mapaCampoPosition', 'ASC');
		$query = $this->db->get('mapas_campos');
		return $query->result_array();
	}

	public function getCampo($id){
		$this->db->where('mapaCampoId', $id);
		$query = $this->db->get('mapas_campos');
		return $query->row();
	}

	public function getInputs(){
		$this->db->where('input_seccion', 'mapas');
		$query = $this->db->get('input');
		return $query->result();
	}

	public function getTranslation($diminutivo, $articuloId)
	{
		$this->db->where('articuloId', $articuloId);
		$query = $this->db->get($diminutivo.'_articulos');

		return $query->row();
	}

	public function createCampo(){

		$habilitado = 0;
		if($this->input->post('mapaCampoPublicado') == 'on')
			$habilitado = 1;

		$posicion = $this->db->count_all('mapas_campos');

		$data = array(
			'mapaCampoClase' => $this->input->post('mapaCampoClase'),
			'inputId' => $this->input->post('inputId'),
			'mapaCampoPublicado' => $habilitado,
			'mapaCampoPosition' => $posicion + 1
		);

		$this->db->insert('mapas_campos', $data);

		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'mapaCampoId' => $lastInsertId,
				'mapaCampoLabel' => $this->input->post($dim.'_mapaCampoLabel'),
			);

			$this->db->insert($dim.'_mapas_campos', $dataIdioma);

		}

		return $lastInsertId;

	}

	public function updateCampo($id){

		$habilitado = 0;
		if($this->input->post('mapaCampoPublicado') == 'on')
			$habilitado = 1;

		$data = array(
			'mapaCampoClase' => $this->input->post('mapaCampoClase'),
			'inputId' => $this->input->post('inputId'),
			'mapaCampoPublicado' => $habilitado
		);

		$this->db->where('mapaCampoId', $id);
		$this->db->update('mapas_campos', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'mapaCampoLabel' => $this->input->post($dim.'_mapaCampoLabel'),
			);

			$this->db->where('mapaCampoId', $id);
			$this->db->update($dim.'_mapas_campos', $dataIdioma);

		}
	}

	public function deleteCampo($id)
	{
		$this->db->where('mapaCampoId', $id);
		$this->db->delete('mapas_campos');
	}

	public function reorderCampos()
	{

		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('mapas_campos');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'mapaCampoPosition' => $i + 1
			);

			$this->db->where('mapaCampoId', $id[$i]);
			$this->db->update('mapas_campos', $data);

		}

	}

	public function getCampoTranslation($diminutivo, $id)
	{
		$this->db->where('mapaCampoId', $id);
		$query = $this->db->get($diminutivo.'_mapas_campos');

		return $query->row();
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}



}