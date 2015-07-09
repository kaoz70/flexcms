<?php
class Contact_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getContacts($lang)
	{
		$this->db->join($lang.'_contactos', $lang.'_contactos.contactoId = contactos.contactoId', 'LEFT');
		$query = $this->db->get('contactos');
		return $query->result();
	}

	function getContactoInputs($lang)
	{
		$this->db->order_by("contactoCampoPosicion", "asc");
		$this->db->join($lang.'_contacto_campos', $lang.'_contacto_campos.contactoCampoId = contacto_campos.contactoCampoId', 'LEFT');
		$this->db->join('input', 'input.inputId = contacto_campos.inputId', 'LEFT');
		$query = $this->db->get('contacto_campos');
		return $query->result();
	}

	function getDirecciones($lang)
	{
		$this->db->cache_on();
		$this->db->join($lang.'_contacto_direcciones', $lang.'_contacto_direcciones.contactoDireccionId = contacto_direcciones.contactoDireccionId', 'LEFT');
		$query = $this->db->get('contacto_direcciones');
		$this->db->cache_off();
		return $query->result();
	}

	function getFormElements()
	{
		//$this->db->where('campo_nombreTabla', 'contacto');
		$this->db->select('contacto_campos.contactoCampoId AS contactoCampoId, contactoCampoValor');
		$this->db->join('es_contacto_campos', 'es_contacto_campos.contactoCampoId = contacto_campos.contactoCampoId', 'LEFT');
		$this->db->order_by('contactoCampoPosicion', 'asc');
		$query = $this->db->get('contacto_campos');

		return $query->result();
	}

	function getSocial()
	{
		$query = $this->db->get('contacto_redsocial');
		return $query->result();
	}

	function addContact()
	{

		$data = array(
			'contactoEmail' => $this->input->post('contactoEmail')
		);

		$this->db->insert('contactos', $data);
		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'contactoId' => $lastInsertId,
				'contactoNombre' => $this->input->post($dim.'_contactoNombre')
			);

			$this->db->insert($dim.'_contactos', $dataIdioma);

		}

		return $lastInsertId;

	}

	function addDireccion()
	{

		$posicion = $this->db->count_all('contacto_direcciones');

		$data = array(
			'contactoDireccionNombre' => $this->input->post('contactoDireccionNombre'),
			'contactoDireccionPosicion' => $posicion + 1,
			'contactoDireccionImagen' => $this->input->post('contactoDireccionImagen'),
			'contactoDireccionCoord' => urldecode($this->input->post('contactoDireccionCoord')),
		);

		$this->db->insert('contacto_direcciones', $data);
		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'contactoDireccionId' => $lastInsertId,
				'contactoDireccion' => $this->input->post($dim.'_contactoDireccion')
			);

			$this->db->insert($dim.'_contacto_direcciones', $dataIdioma);

		}

		return $lastInsertId;

	}

	function getContact($id)
	{
		$this->db->where('contactoId', $id);
		$query = $this->db->get('contactos');

		return $query->row();
	}

	function getDireccion($id)
	{
		$this->db->where('contactoDireccionId', $id);
		$query = $this->db->get('contacto_direcciones');

		return $query->row();
	}

    function getAddressById($ids, $lang)
    {
        return $this->db
            ->where_in('contacto_direcciones.contactoDireccionId', $ids)
            ->join($lang.'_contacto_direcciones', $lang.'_contacto_direcciones.contactoDireccionId = contacto_direcciones.contactoDireccionId', 'LEFT')
            ->get('contacto_direcciones')
            ->result();
    }

	function updateContact($id)
	{

		$data = array(
			'contactoEmail' => $this->input->post('contactoEmail')
		);

		$this->db->where('contactoId', $id);
		$this->db->update('contactos', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'contactoNombre' => $this->input->post($dim.'_contactoNombre')
			);

			$this->db->where('contactoId', $id);
			$this->db->update($dim.'_contactos', $dataIdioma);

		}

	}

	function updateDireccion($id)
	{

		$data = array(
			'contactoDireccionNombre' => $this->input->post('contactoDireccionNombre'),
			'contactoDireccionImagen' => $this->input->post('contactoDireccionImagen'),
			'contactoDireccionCoord' => urldecode($this->input->post('contactoDireccionCoord')),
		);

		$this->db->where('contactoDireccionId', $id);
		$this->db->update('contacto_direcciones', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'contactoDireccion' => $this->input->post($dim.'_contactoDireccion')
			);

			//Check if it already exists
			$trans = $this->db
				->where('contactoDireccionId', $id)
				->get($dim.'_contacto_direcciones');

			if($trans->result()){
				$this->db->where('contactoDireccionId', $id);
				$this->db->update($dim.'_contacto_direcciones', $dataIdioma);
			} else {
				$dataIdioma['contactoDireccionId'] = $id;
				$this->db->insert($dim.'_contacto_direcciones', $dataIdioma);
			}



		}

	}

	function deleteContact($id)
	{
		$this->db->where('contactoId', $id);
		$this->db->delete('contactos');

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];
			$this->db->where('contactoId', $id);
			$this->db->delete($dim.'_contactos');
		}

	}

	function deleteDireccion($id){
		$this->db->where('contactoDireccionId', $id);
		$this->db->delete('contacto_direcciones');
	}

	function addSocial($id)
	{
		$this->rsId = $id;
		$this->rsLink = $this->input->post('rsLink');
		$this->rsNombre = $this->input->post('rsNombre');
		$this->rsClase = $this->input->post('rsClase');

		$this->db->insert('contacto_redsocial', $this);
	}

	function getSocialInfo($id)
	{
		$this->db->where('rsId', $id);
		$query = $this->db->get('contacto_redsocial');

		return $query->row();
	}

	function updateSocial($id)
	{
		$this->rsNombre = $this->input->post('rsNombre');
		$this->rsLink = $this->input->post('rsLink');
		$this->rsClase = $this->input->post('rsClase');

		$this->db->where('rsId', $id);
		$this->db->update('contacto_redsocial', $this);
	}

	function deleteSocial($id)
	{
		$this->db->where('rsId', $id);
		$this->db->delete('contacto_redsocial');
	}

	public function saveSettings()
	{
		$this->db->where('id', 1);
		$this->db->update('configuracion', $this);
	}

	public function getSettings()
	{
		$this->db->where('id', 1);
		$query = $this->db->get('configuracion');

		return $query->row();
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

	/***********PARA CONTACTOS ********************/
	/*funciones para crear,eliminar, modificar */
	//obtener Entradas del Formulario
	function getInForm()
	{

		$this->db->order_by('contactoCampoPosicion', 'asc');
		$query = $this->db->get('contacto_campos');

		if($query->num_rows() > 0)
		{
			$result = $query->result();
		}else
		{
			$result = 0;
		}
		return $result;
	}
	//obtener datos de un campo
	function getDatosInForm($id)
	{
		$this->db->where('contactoCampoId', $id);
		$query = $this->db->get('contacto_campos');

		return $query->row();
	}
	//obtener Inputs
	function getInputs()
	{
		$this->db->where('input_seccion', 'contacto');
		$this->db->order_by('inputTipoContenido', 'asc');
		$query = $this->db->get('input');

		return $query->result();
	}
	//guardar campo
	function guardarInForm()
	{

		$contactoCampoRequerido = 0;
		if($this->input->post('contactoCampoRequerido') == 'on') {
			$contactoCampoRequerido = 1;
		}

		$data = array(
			'inputId' => $this->input->post('inputId'),
			'contactoCampoClase' => $this->input->post('contactoCampoClase'),
			'contactoCampoValidacion' => $this->input->post('contactoCampoValidacion'),
			'contactoCampoRequerido' => $contactoCampoRequerido
		);

		$this->db->insert('contacto_campos', $data);
		$lastInsertId = $this->db->insert_id();

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'contactoCampoId' => $lastInsertId,
				'contactoCampoValor' => $this->input->post($dim.'_contactoCampoValor'),
				'contactoCampoPlaceholder' => $this->input->post($dim.'_contactoCampoPlaceholder')
			);

			$this->db->insert($dim.'_contacto_campos', $dataIdioma);

		}

		return $lastInsertId;

	}
	//actualizar CAmpo
	public function updateForm()
	{

		$contactoCampoRequerido = 0;
		if($this->input->post('contactoCampoRequerido') == 'on') {
			$contactoCampoRequerido = 1;
		}

		$data = array(
			'inputId' => $this->input->post('inputId'),
			'contactoCampoClase' => $this->input->post('contactoCampoClase'),
			'contactoCampoValidacion' => $this->input->post('contactoCampoValidacion'),
			'contactoCampoRequerido' => $contactoCampoRequerido
		);

		$contactoCampoId = $this->input->post('contactoCampoId');

		$this->db->where('contactoCampoId', $contactoCampoId);
		$this->db->update('contacto_campos', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$this->db->where('contactoCampoId', $contactoCampoId);
			$query = $this->db->get($dim.'_contacto_campos');

			if($query->row()){
				$dataIdioma = array(
					'contactoCampoValor' => $this->input->post($dim.'_contactoCampoValor'),
					'contactoCampoPlaceholder' => $this->input->post($dim.'_contactoCampoPlaceholder')
				);

				$this->db->where('contactoCampoId', $contactoCampoId);
				$this->db->update($dim.'_contacto_campos', $dataIdioma);
			} else {
				$dataIdioma = array(
					'contactoCampoId' => $contactoCampoId,
					'contactoCampoValor' => $this->input->post($dim.'_contactoCampoValor'),
					'contactoCampoPlaceholder' => $this->input->post($dim.'_contactoCampoPlaceholder')
				);

				$this->db->insert($dim.'_contacto_campos', $dataIdioma);
			}

		}

	}
	//borrar campo
	public function deleteForm($id)
	{
		$this->db->where('contactoCampoId', $id);
		$this->db->delete('contacto_campos');

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$this->db->where('contactoCampoId', $id);
			$this->db->delete($dim.'_contacto_campos');

		}
	}

	public function reorderInputs()
	{
		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('contacto_campos');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'contactoCampoPosicion' => $i + 1
			);

			$this->db->where('contactoCampoId', $id[$i]);
			$this->db->update('contacto_campos', $data);

		}
	}

	public function reorderDirecciones()
	{
		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos del FAQ
		$query = $this->db->get('contacto_direcciones');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $numCampos ; $i++){

			$data = array(
				'contactoDireccionPosicion' => $i + 1
			);

			$this->db->where('contactoDireccionId', $id[$i]);
			$this->db->update('contacto_direcciones', $data);

		}
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
		return $query->result_array();
	}
	public function getCampoTranslation($diminutivo, $contactoCampoId)
	{
		$this->db->where('contactoCampoId', $contactoCampoId);
		$query = $this->db->get($diminutivo.'_contacto_campos');

		return $query->row();
	}

	public function getContactoTranslation($diminutivo, $contactoId)
	{
		$this->db->where('contactoId', $contactoId);
		$query = $this->db->get($diminutivo.'_contactos');

		return $query->row();
	}

	public function getDireccionTranslation($diminutivo, $direccionId)
	{
		$this->db->where('contactoDireccionId', $direccionId);
		$query = $this->db->get($diminutivo.'_contacto_direcciones');

		return $query->row();
	}

}