<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 9/9/15
 * Time: 11:21 AM
 */

namespace Content;

class Main extends \MX_Controller {

	public $autoload = array(
		'libraries' => array('auth/ion_auth'),
	);

	public function init()
	{
		echo 'Content Init';
	}

	public function index($data){

		/*$CI =& get_instance();

		$parseData = array(
			'base_url' => base_url(),
			'asset_url' => $data['theme_asset'] . '/',
		);

		$articles = $CI->Content->getByPage($CI->page_id, $CI->lang);

		$html = $CI->load->view('paginas/content_open_view', $data, true);

		foreach ($articles as $article) {

			$data = $article;

			//Search por any email occurrences and replace with safe emails
			$data['articuloContenido'] = $CI->parser->parse_string(auto_link($article['articuloContenido'], 'email'), $parseData, TRUE);
			$html .= $CI->load->view('paginas/articulo_view', $data, true);

		}

		$html .= $CI->load->view('paginas/content_close_view', $data, true);

		return $html;*/

		echo "Article Here";

	}

}