<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Article {

	public function __construct()
	{
		$CI =& get_instance();
		$CI->load->model('article_model', 'Articles');
	}

	public function create($pagina_id, $data, $idioma)
	{
		$CI =& get_instance();

		$parseData = array(
			'base_url' => base_url(),
			'asset_url' => $data['theme_asset'] . '/',
		);

		$articles = $CI->Articles->getByPage($pagina_id, $idioma);

		$html = $CI->load->view('paginas/content_open_view', $data, true);

		foreach ($articles as $article) {

			$data = $article;

			//Search por any email occurrences and replace with safe emails
			$data['articuloContenido'] = $CI->parser->parse_string(auto_link($article['articuloContenido'], 'email'), $parseData, TRUE);
			$html .= $CI->load->view('paginas/articulo_view', $data, true);

		}

		$html .= $CI->load->view('paginas/content_close_view', $data, true);

		return $html;

	}

}

/* End of file CMS_Article.php */