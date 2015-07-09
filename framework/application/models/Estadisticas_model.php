<?php

class Estadisticas_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function getTotalVisits($allStats = FALSE)
	{
		$visits = new stdClass();

        $this->db->cache_on();

        if($allStats)
        {
            //Visitas totales del dia
            $query = $this->db->query('SELECT COUNT(DISTINCT(estadisticaUserIP)) as hits_day FROM estadisticas WHERE DAY(FROM_UNIXTIME(estadisticaFecha)) = DAY(NOW()) AND MONTH(FROM_UNIXTIME(estadisticaFecha)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(estadisticaFecha)) = YEAR(NOW())');
            $visits->hits_day = $query->row()->hits_day;

            //Visitas totales del mes
            $query = $this->db->query('SELECT COUNT(DISTINCT(estadisticaUserIP)) as hits_month FROM estadisticas WHERE MONTH(FROM_UNIXTIME(estadisticaFecha)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(estadisticaFecha)) = YEAR(NOW())');
            $visits->hits_month = $query->row()->hits_month;

            //Visitas totales del año
            $query = $this->db->query('SELECT COUNT(DISTINCT(estadisticaUserIP)) as hits_year FROM estadisticas WHERE YEAR(FROM_UNIXTIME(estadisticaFecha)) = YEAR(NOW())');
            $visits->hits_year = $query->row()->hits_year;
        }

		//Visitas totales unicas
		$query = $this->db->query('SELECT COUNT(DISTINCT(estadisticaUserIP)) as hits_total FROM estadisticas');
   		$visits->hits_total = $query->row()->hits_total;

        $this->db->cache_off();
		
		return $visits;
	}
	
	public function getMostViewedPages($limit = 10)
	{	
		$this->db->select('estadisticaUrl, count(estadisticaUrl) as count');
		$this->db->group_by('estadisticaUrl');
		$this->db->order_by('count', 'desc');
		$this->db->limit($limit);
		$query = $this->db->get('estadisticas');
   		return $query->result();
	}
	
	

}