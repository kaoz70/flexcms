<?php

class Calendar_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function getDays($lang)
	{
		$days = $this->db
			->where('temporal', 0)
			->get('calendar')->result();

		foreach($days as $day) {
			$day->activities = $this->db
				->where('calendar_id', $day->id)
				->where('temporal', 0)
				->where('enabled', 1)
				->get('activities')->result();
		}

		return $days;

	}

	/*******************************************************************************************************************
	 * ------------------------------------------------- ADMIN ---------------------------------------------------------
	 ******************************************************************************************************************/

	public function getAll($page_id = 0)
	{
		if($page_id){
			$this->db->where('page_id', $page_id);
		}
		$this->db->where('temporal', 0);
		$this->db->order_by("date", "asc");
		$query = $this->db->get('calendar');

		return $query->result_array();
	}

	public function get($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('calendar');

		return $query->row_array();
	}

	public function activity($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('activities');

		return $query->row_array();
	}

	public function insert()
	{

		$this->db->insert('calendar',  array(
			'date' => date("Y-m-d H:i:s")
		));

		return $this->db->insert_id();

	}

	public function insert_activity($calendar_id)
	{

		$this->db->insert('activities',  array(
			'time' => date("H:i:s"),
			'calendar_id' => $calendar_id,
		));

		return $this->db->insert_id();

	}

	public function update_activity($id)
	{
		$this->db->where('id', $id);
		$this->db->update('activities',  array(
			'time' => $this->input->post('time'),
			'data' => $this->input->post('data'),
			'temporal' => 0,
		));
	}


	public function delete_activity($id)
	{
		$this->db->delete('activities', array('id' => $id));
	}

	public function update($id)
	{

		$data = array(
			'date' => $this->input->post('date'),
			'class' => $this->input->post('class'),
			'temporal' => 0,
		);

		$this->db->where('id', $id);
		$this->db->update('calendar', $data);

	}

	public function delete($id)
	{
		$this->db->delete('calendar', array('id' => $id));
	}

	public function delete_field($id)
	{
		$this->db->delete('activity_fields', array('id' => $id));
	}

	public function activities($id)
	{
		$this->db->where('calendar_id', $id);
		$this->db->where('temporal', 0);
		$this->db->order_by("time", "asc");
		$query = $this->db->get('activities');

		return $query->result_array();
	}

	public function fields()
	{
		$this->db->join('es_activity_fields', 'es_activity_fields.activity_field_id = activity_fields.id', 'LEFT');
		$this->db->order_by("position", "asc");
		$query = $this->db->get('activity_fields');

		return $query->result_array();
	}

	public function getField($id)
	{
		$this->db->where('activity_fields.id', $id);
		$this->db->join('es_activity_fields', 'es_activity_fields.activity_field_id = activity_fields.id', 'LEFT');
		$this->db->order_by("position", "asc");
		$query = $this->db->get('activity_fields');

		return $query->row_array();
	}

	public function getFieldTranslation($lang, $id)
	{
		$this->db->where('activity_fields.id', $id);
		$this->db->join($lang . '_activity_fields', $lang . '_activity_fields.activity_field_id = activity_fields.id', 'LEFT');
		$this->db->order_by("position", "asc");
		$query = $this->db->get('activity_fields');

		return $query->row();
	}

	public function reorder_fields()
	{
		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$ids = json_decode($this->input->post('posiciones'), true);

		foreach ($ids as $key => $id) {
			$this->db->where('id', $id);
			$this->db->update('activity_fields', array(
				'position' => $key + 1
			));
		}
	}

	public function insert_field()
	{
		$position = $this->db->count_all('activity_fields');

		$this->db->insert('activity_fields',  array(
			'input_id' => $this->input->post('input_id'),
			'class' => $this->input->post('class'),
			'enabled' => $this->input->post('enabled'),
			'position' => $position + 1,
		));

		$insert_id = $this->db->insert_id();

		$query = $this->db->get('idioma');
		$idiomas = $query->result_array();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'activity_field_id' => $insert_id,
				'name' => $this->input->post($dim.'_name'),
			);

			$this->db->insert($dim.'_activity_fields', $dataIdioma);

		}

		return $insert_id;
	}

	public function update_field($id)
	{

		$this->db->where('id', $id);
		$this->db->update('activity_fields',  array(
			'input_id' => $this->input->post('input_id'),
			'class' => $this->input->post('class'),
			'enabled' => $this->input->post('enabled'),
		));

		$query = $this->db->get('idioma');
		$idiomas = $query->result_array();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'name' => $this->input->post($dim.'_name'),
			);

			$this->db->where('activity_field_id', $id);
			$this->db->update($dim.'_activity_fields', $dataIdioma);

		}
	}

}