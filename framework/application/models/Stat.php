<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 4:49 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Stat extends Model {

	public function getTotal($allStats = FALSE)
	{
		$visits = new stdClass();

		$this->db->cache_on();

		if($allStats)
		{
			//Visitas totales del dia
			$query = $this->db->query('SELECT COUNT(DISTINCT(ip)) as hits_day FROM estadisticas WHERE DAY(FROM_UNIXTIME(date)) = DAY(NOW()) AND MONTH(FROM_UNIXTIME(date)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(date)) = YEAR(NOW())');
			$visits->hits_day = $query->row()->hits_day;

			//Visitas totales del mes
			$query = $this->db->query('SELECT COUNT(DISTINCT(ip)) as hits_month FROM estadisticas WHERE MONTH(FROM_UNIXTIME(date)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(date)) = YEAR(NOW())');
			$visits->hits_month = $query->row()->hits_month;

			//Visitas totales del año
			$query = $this->db->query('SELECT COUNT(DISTINCT(ip)) as hits_year FROM estadisticas WHERE YEAR(FROM_UNIXTIME(date)) = YEAR(NOW())');
			$visits->hits_year = $query->row()->hits_year;
		}

		//Visitas totales unicas
		$query = $this->db->query('SELECT COUNT(DISTINCT(ip)) as hits_total FROM estadisticas');
		$visits->hits_total = $query->row()->hits_total;

		$this->db->cache_off();

		return $visits;
	}

	public function getMostViewed($limit = 10)
	{
		$this->db->select('estadisticaUrl, count(estadisticaUrl) as count');
		$this->db->group_by('estadisticaUrl');
		$this->db->order_by('count', 'desc');
		$this->db->limit($limit);
		$query = $this->db->get('estadisticas');
		return $query->result();
	}

}