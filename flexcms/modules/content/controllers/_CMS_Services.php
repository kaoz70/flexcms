<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Services {

	public function __construct()
	{
		$CI =& get_instance();
		$CI->load->model('servicios_model', 'Servicios');
		$CI->load->model('imagenes_model', 'Images');
	}

	public function create($paginaId, $data, $idioma, $module)
	{

		$CI =& get_instance();
		$id = $CI->uri->segment(3);
		$data['paginaServiciosUrl'] = $CI->uri->segment(2);

		$pagina_actual = (int)$CI->uri->segment(3);

		$ret = new stdClass();

		$ret->html = '';

		$parseData = array(
			'base_url' => base_url()
		);

		/*
		 * LISTADO DE SERVICIOS
		 */

		if(!$id OR $pagina_actual != 0) {


			if($module->moduloVerPaginacion) {

				$noticiasCant = count($CI->Servicios->getByPage((int)$paginaId, $idioma));

				$pag_config = array();
				$pag_config['base_url'] = base_url($idioma.'/'.$CI->uri->segment(2).'/');
				$pag_config['total_rows'] =$noticiasCant;
				$pag_config['per_page'] = $module->moduloParam4;
				$pag_config['uri_segment'] = 3;

				$CI->pagination->initialize($pag_config);
				$pagination = $CI->pagination->create_links();

				$data['pagination'] = $pagination;

				$servicios = $CI->Servicios->getPaginated($paginaId, $module->moduloParam4, $pagina_actual, $idioma);
			} else {
				$data['pagination'] = '';
				$servicios = $CI->Servicios->getByPage($paginaId, $idioma);
			}

			foreach ($servicios as $servicio) {
				$servicio->servicioTexto = $CI->parser->parse_string($servicio->servicioTexto, $parseData, TRUE);
				$servicio->imagenes = $CI->db->where('servicio_id', $servicio->servicioId)->get('servicios_imagenes')->result();
			}

			$data['servicios'] = $servicios;

			$ret->html .= $CI->load->view('paginas/servicios/servicios_listado_view', $data, true);
		}

		/*
		 * DETALLE DEL SERVICIO
		 */
		else {
			$servicio = $CI->Servicios->get($id, $idioma, $data['page']->paginaId);

			if (! $servicio) {
				show_my_404(base_url($idioma . '/' . $CI->m_currentPage), $CI->m_config->theme);
			}

			$servicio->servicioTexto = $CI->parser->parse_string($servicio->servicioTexto, $parseData, TRUE);
			$data['servicio'] = $servicio;
			$data['servicios'] = $CI->Servicios->getByPage($paginaId, $idioma);

			$data['og_title'] = $servicio->servicioTitulo;
			$data['og_description'] = $data['meta_description'] = $servicio->servicioDescripcion ? $servicio->servicioDescripcion : word_limiter(strip_tags($servicio->servicioTexto), 15);

			$data['meta_title'] = $servicio->servicioMetaTitulo;
			$data['meta_keywords'] = $servicio->servicioKeywords;

			//Get the image used for Facebook's OpenGraph
			if($servicio->servicioImagen) {
				$image = $CI->Images->getImages(10);
				$data['og_image'] = base_url('assets/public/images/servicios/servicio_' . $servicio->servicioId . $image[0]->imagenSufijo . '.' . $servicio->servicioImagen);
			}

			$ret->html .= $CI->load->view('paginas/servicios/servicio_detalle_view', $data, true);
		}

		$ret->data = $data;

		return $ret;

	}

}

/* End of file CMS_Servicios.php */