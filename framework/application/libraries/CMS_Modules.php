<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Modules {

	private $m_cache_time = 3000;

	public function __construct()
	{
	   // parent::__construct();

		$CI =& get_instance();
		$CI->load->model('banners_model', 'Banners');
		$CI->load->model('catalogo_model', 'Catalog');
		$CI->load->model('module_model', 'Modulos');
		$CI->load->model('galeria_model', 'Galeria');
		$CI->load->model('descargas_model', 'Descargas');
		$CI->load->model('admin/page_model', 'Page');
		$CI->load->model('noticias_model', 'Noticias');
		$CI->load->model('enlaces_model', 'Enlaces');
		$CI->load->model('contact_model', 'Contact');
		$CI->load->model('faq_model', 'Faq');
		$CI->load->model('servicios_model', 'Servicios');
		$CI->load->model('home_model', 'Home');
		$CI->load->model('publicidad_model', 'Publicidad');
		$CI->load->model('imagenes_model', 'Imagenes');
		$CI->load->model('configuracion_model', 'Config');

		$CI->load->library('pagination');

		//Load the cache driver with a fallback option
		$CI->load->driver('cache', array(
			'adapter' => 'memcached',
			'backup' => 'file',
			'key_prefix' => url_title(base_url() . uri_string())
		));

	}

	/**************************
	 * BEGIN: MODULES
	 *************************/
	
	/**
	 * @param $page
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function publicaciones($page, $module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();
		$noticiaId =  $CI->uri->segment(3, '');

		if($noticiaId == '')
		{
			$noticia = $CI->Noticias->getLastNews($page->paginaId, $idioma);

			if(count($noticia) > 0)
				$noticiaId = $noticia->publicacionId;
		}

		//Obenenemos la pagina de la que proviene la publicacion
		$noticiaPagina = $CI->Modulos->getPublicaciones($module->moduloParam1, $idioma);

		$cantidad = 1;

		if($module->moduloParam2 != '')
			$cantidad = $module->moduloParam2;

		$noticiasCant = count($CI->Noticias->getByPage($module->moduloParam1, $idioma));
		$noticiasCol = $CI->Modulos->getItemsForPublicaciones($module->moduloParam1, $cantidad, 0, $idioma);

		$moduleData['pagination'] = '';

		if($module->moduloVerPaginacion)
		{
			$pag_config = array();
			$pag_config['base_url'] = base_url('ajax/module/publicaciones/'.$idioma.'/'.$module->moduloId);
			$pag_config['total_rows'] =$noticiasCant;
			$pag_config['per_page'] = $module->moduloParam2;
			$pag_config['uri_segment'] = 5;

			$CI->pagination->initialize($pag_config);
			$pagination = $CI->pagination->create_links();

			$moduleData['pagination'] = $pagination;
		}

		$moduleData['noticias'] = $noticiasCol;
		$moduleData['paginacionPaginaActual'] = '';

		if(count($noticiaPagina) != 0)
			$moduleData['paginaNoticiaUrl'] = $noticiaPagina[0]['paginaNombreURL'];
		else
			$moduleData['paginaNoticiaUrl'] = '';

		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_publicacion ' . $moduleData['moduloClase'];

		$imagen = $CI->Imagenes->get($module->moduloParam3);
		$moduleData['imageSize'] = $imagen->imagenSufijo;

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/publicaciones/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $paginaUrl
	 * @param $data
	 * @return string
	 */
	public function productosCategoria($module, $moduleData, $idioma, $paginaUrl, $data)
	{
		$CI =& get_instance();

		//Obtenemos la pagina de catalogo
		$catalogoPagina = $CI->Modulos->getPageByType(4, $idioma);

		if(count($catalogoPagina) > 0)
		{
			$productosCant = count($CI->Catalog->getProductsByCategory($module->moduloParam1, $idioma));
			$productosModPag = $CI->Modulos->getItemsForCatalog($module->moduloParam1, $module->moduloParam2, 0, $idioma);

			$moduleData['pagination'] = '';

			if($module->moduloVerPaginacion)
			{
				$pag_config = array();
				$pag_config['base_url'] = base_url('ajax/module/catalogoCategoria/'.$idioma.'/'.$module->moduloId);
				$pag_config['total_rows'] =$productosCant;
				$pag_config['per_page'] = $module->moduloParam2;
				$pag_config['uri_segment'] = 6;

				$CI->pagination->initialize($pag_config);
				$pagination = $CI->pagination->create_links();

				$moduleData['pagination'] = $pagination;
			}

			$imagen = $CI->Imagenes->get($module->moduloParam3);
			$moduleData['imageSize'] = $imagen->imagenSufijo;

			$moduleData['productos'] = $productosModPag;

			$moduleData['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
			$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
			$moduleData['class'] = 'mod_catalogoCategoria ' . $moduleData['moduloClase'];
			$moduleData['categoria'] = $tree = CatalogTree::find($module->moduloParam1);

			$html = $CI->load->view('modulos/header_view', $moduleData, true);
			$html .= $CI->load->view('modulos/catalogo/product/' . $module->moduloVista, $moduleData, true);
			$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

			return $html;
		}
		else {
			return '<div data-alert class="alert-box alert">Cree una pagina de cat&aacute;logo para poder ver este m&oacute;dulo</div>';
		}


	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $paginaUrl
	 * @param $data
	 * @return string
	 */
	public function productosDestacados($module, $moduleData, $idioma, $paginaUrl, $data)
	{
		$CI =& get_instance();

		$paginacionPaginaActual = $CI->uri->segment(3, '');
		
		$catalogoPagina = $CI->Modulos->getPageByType(4, $idioma);

		if(count($catalogoPagina) == 0)
		{
			return '<div data-alert class="alert-box alert">Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Cat&aacute;logo</strong> para poder ver este m&oacute;dulo</div>';
		}

		$productosCant = count($CI->Catalog->getProductosDestacados($module->moduloParam1, $paginacionPaginaActual, $idioma));

		$productosModPag = $CI->Modulos->getItemsForProductosDestacados($module->moduloParam1, $module->moduloParam2, $paginacionPaginaActual, $idioma);

		$moduleData['pagination'] = '';

		if($module->moduloVerPaginacion)
		{

			$pag_config['base_url'] = base_url('ajax/module/productosDestacados/'.$idioma.'/'.$module->moduloId);
			$pag_config['total_rows'] =$productosCant;
			$pag_config['per_page'] = $module->moduloParam2;
			$pag_config['uri_segment'] = 6;

			$CI->pagination->initialize($pag_config);
			$pagination = $CI->pagination->create_links();
		
			$moduleData['pagination'] = $pagination;
			
		}

		$imagen = $CI->Imagenes->get($module->moduloParam3);
		$moduleData['imageSize'] = $imagen->imagenSufijo;

		$moduleData['productos'] = $productosModPag;
		$moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

		$moduleData['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_catalogoProductosDestacados ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/catalogo/product/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @return string
	 */
	public function productosDestacadosAzar($module, $moduleData, $idioma)
	{
		$CI =& get_instance();
		$moduloId = $CI->uri->segment(3, '');

		$paginacionPaginaActual = '';

		if($module->moduloId == $moduloId)
			$paginacionPaginaActual = $CI->uri->segment(4, '');

		$catalogoPagina = $CI->Modulos->getPageByType(4, $idioma);

		if(count($catalogoPagina) == 0)
		{
			return '<div data-alert class="alert-box alert">Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Cat&aacute;logo</strong> para poder ver este m&oacute;dulo</div>';
		}

		$cantidad = 1;

		if($module->moduloParam2 != '')
			$cantidad = $module->moduloParam2;

		$productosModPag = $CI->Modulos->getItemsForProductosDestacadosAzar($module->moduloParam1, $cantidad, $paginacionPaginaActual, $idioma);
		$imagen = $CI->Imagenes->get($module->moduloParam3);

		$moduleData['imageSize'] = $imagen->imagenSufijo;
		$moduleData['titulo'] = $module->moduloNombre;
		$moduleData['productos'] = $productosModPag;
		$moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

		$moduleData['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_catalogoProductosDestacadosAzar ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/catalogo/product/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $paginaUrl
	 * @param $data
	 * @return string
	 */
	public function productosMenu($module, $moduleData, $idioma, $paginaUrl, $data)
	{
		$CI =& get_instance();
		$catalogoPagina = $CI->Modulos->getPageByType(4, $idioma);
		$moduleData['menu'] = $this->createSpecialMenu('CatalogTree', $idioma, $catalogoPagina, $CI->m_breadcrumbs['catalog']['path']);
		$moduleData['show_products'] = (bool)$module->moduloParam1;
		$moduleData['page'] = $catalogoPagina->paginaNombreURL;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_catalogoMenu ' . $moduleData['moduloClase'];
		$moduleData['module'] = $module;

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/catalogo/menu/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function html($module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();

		$html = $CI->Modulos->html($module->moduloId, $idioma);

		$parseData = array(
			'base_url' => base_url(),
			'asset_url' => $data['theme_asset'] . '/',
		);

		//Search por any email occurrences and replace with safe emails
		$moduleData['html'] = $CI->parser->parse_string(auto_link($html->moduloHtml, 'email'), $parseData, TRUE);
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_html ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/html_view', $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function facebook($module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();
		$moduleData['appId'] = $module->moduloParam1;
		$moduleData['url'] = $module->moduloParam2;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_facebook ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/facebook/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	public function twitter($module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();
		$moduleData['user'] =  $module->moduloParam2;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_twitter ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/twitter/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function banner($module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();
		$banner = $CI->Banners->get($module->moduloParam1, $idioma);

		if($banner) {

			$moduleData['banner'] = $banner;
			$moduleData['images'] = $banner['images'];
			$moduleData['width'] = $banner['bannerWidth'];
			$moduleData['height'] = $banner['bannerHeight'];
			$moduleData['class'] = 'mod_banner ' . $moduleData['moduloClase'];
			$moduleData['banner_class'] = $banner['bannerClass'];
			$moduleData['viewTitle'] =  $module->moduloMostrarTitulo;
			$moduleData['config'] =  $banner['config'];

			$html = $CI->load->view('modulos/header_view', $moduleData, true);
			$html .= $CI->load->view('modulos/banners/' . $banner['bannerType'] . '/' . $module->moduloVista, $moduleData, true);
			$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

			return $html;
		}
		else{
			return '<div data-alert class="alert-box alert">Cree al menos un banner y aseg&uacute;rece que esta asignado en el modulo</div>';
		}


	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $page
	 * @param $data
	 * @return mixed
	 */
	public function titulo($module, $moduleData, $page, $data)
	{
		$CI =& get_instance();

		$moduleData['titulo'] = $page->paginaNombre;
		$moduleData['class'] = $moduleData['moduloClase'];

		return $CI->load->view('modulos/titulo/' . $module->moduloVista, $moduleData, true);
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $paginaUrl
	 * @param $data
	 * @return string
	 */
	public function faq($module, $moduleData, $idioma, $paginaUrl, $data)
	{
		$CI =& get_instance();
		$moduloId = $CI->uri->segment(3, '');

		$paginacionPaginaActual = '';

		if($module->moduloId == $moduloId)
			$paginacionPaginaActual = $CI->uri->segment(4, '');

		$faqPagina = $CI->Modulos->getFAQ($module->moduloParam1, $idioma);
		$pagina = $CI->Modulos->getPageByType(2, $idioma);

		$faqCant = count($CI->Faq->getByPage($module->moduloParam1, $idioma));
		$faqCol = $CI->Modulos->getItemsForFAQ($module->moduloParam1, $module->moduloParam2, $paginacionPaginaActual, $idioma);

		$moduleData['pagination'] = '';

		if($module->moduloVerPaginacion)
		{
			$pag_config['base_url'] = base_url('ajax/module/faq/'.$idioma.'/'.$module->moduloId);
			$pag_config['total_rows'] =$faqCant;
			$pag_config['per_page'] = $module->moduloParam2;
			$pag_config['uri_segment'] = 6;

			$CI->pagination->initialize($pag_config);
			$pagination = $CI->pagination->create_links();

			$moduleData['pagination'] = $pagination;
		}

		$moduleData['faq'] = $faqCol;
		$moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

		if(count($faqPagina) != 0)
			$moduleData['paginaFaqUrl'] = $pagina->paginaNombreURL;
		else
			$moduleData['paginaFaqUrl'] = '';
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_faq ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/faq/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $paginaUrl
	 * @param $data
	 * @return string
	 */
	public function enlaces($module, $moduleData, $idioma, $paginaUrl, $data)
	{
		$CI =& get_instance();

		$enlacesPagina = $CI->Modulos->getEnlaces($module->moduloParam1, $idioma);
		$pagina = $CI->Modulos->getPageByType(10, $idioma);

		if(count($pagina) > 0)
		{
			$enlacesCant = count($CI->Enlaces->getByPage($module->moduloParam1, $idioma));
			$enlacesCol = $CI->Modulos->getItemsForEnlaces($module->moduloParam1, $module->moduloParam2, 0, $idioma);

			$moduleData['pagination'] = '';

			if($module->moduloVerPaginacion)
			{
				//$pag_config['base_url'] = base_url() . $idioma .'/' . $paginaUrl;
				$pag_config['base_url'] = base_url('ajax/module/enlaces/'.$idioma.'/'.$module->moduloId);
				$pag_config['total_rows'] =$enlacesCant;
				$pag_config['per_page'] = $module->moduloParam2;
				$pag_config['uri_segment'] = 6;

				$CI->pagination->initialize($pag_config);
				$pagination = $CI->pagination->create_links();

				$moduleData['pagination'] = $pagination;
			}

			$moduleData['enlaces'] = $enlacesCol;
			$moduleData['paginacionPaginaActual'] = 0;

			if(count($enlacesPagina) != 0)
				$moduleData['paginaEnlacesUrl'] = $pagina->paginaNombreURL;
			else
				$moduleData['paginaEnlacesUrl'] = '';
			$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
			$moduleData['class'] = 'mod_enlaces ' . $moduleData['moduloClase'];

			$imagen = $CI->Imagenes->get($module->moduloParam3);
			$moduleData['imageSize'] = $imagen->imagenSufijo;
			$moduleData['id'] = $module->moduloId;

			$html = $CI->load->view('modulos/header_view', $moduleData, true);
			$html .= $CI->load->view('modulos/enlaces/' . $module->moduloVista, $moduleData, true);
			$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

			return $html;
		}

		else
		{
			return '<div data-alert class="alert-box alert">Cree una p&aacute;gina de Enlaces para poder ver este m&oacute;dulo</div>';
		}


	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $paginaUrl
	 * @param $data
	 * @return string
	 */
	public function galeria($module, $moduleData, $idioma, $paginaUrl, $data)
	{
		$CI =& get_instance();
		$CI->load->library('ion_auth');

		$galeriaPagina = $CI->Modulos->getGaleria($module->moduloParam1);
		$categoriaGaleria = $CI->Galeria->getCategoria($module->moduloParam1);

		if($categoriaGaleria->descargaCategoriaPrivada && !$CI->ion_auth->logged_in())
			return;

		$pagina = $CI->Modulos->getPageByType(6, $idioma);

		$cantidad = 1;

		if($module->moduloParam2 != '')
			$cantidad = $module->moduloParam2;

		$galeriaCant = count($CI->Galeria->getByCategory($module->moduloParam1));
		$galeriaCol = $CI->Modulos->getItemsForGaleria($module->moduloParam1, $cantidad, 0, $idioma);

		$moduleData['pagination'] = '';

		if($module->moduloVerPaginacion)
		{
			$pag_config['base_url'] = base_url('ajax/module/galeria/'.$idioma.'/'.$module->moduloId);
			$pag_config['total_rows'] =$galeriaCant;
			$pag_config['per_page'] = $module->moduloParam2;
			$pag_config['uri_segment'] = 6;

			$CI->pagination->initialize($pag_config);
			$pagination = $CI->pagination->create_links();

			$moduleData['pagination'] = $pagination;
		}

		$moduleData['galeria'] = $galeriaCol;

		if(count($galeriaPagina) != 0)
		{
			if(count($pagina) > 0)
				$moduleData['paginaGaleriaUrl'] = $pagina->paginaNombreURL;
			else
			{
				return '<div data-alert class="alert-box alert">Cree una p&aacute;gina de Galeria para poder ver este m&oacute;dulo</div>';
			}

		}
		else
			$moduleData['paginaGaleriaUrl'] = '';
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_galeria ' . $moduleData['moduloClase'];

		$imagen = $CI->Imagenes->get($module->moduloParam3);
		$moduleData['imageSize'] = $imagen->imagenSufijo;

		$html = $CI->load->view('modulos/header_view', $moduleData, true);

		$moduleData['imagenes'] = array();
		$moduleData['videos'] = array();
		$moduleData['archivos'] = array();

		foreach($galeriaCol as $descarga){

			$esImagen = preg_match('/jpg|jpeg|png|gif/',mb_strtolower($descarga->descargaArchivo));

			if($esImagen) {
				$moduleData['imagenes'][] = $descarga;
			} else if (!$esImagen && strpos($descarga->descargaArchivo, '.') === false) {
				$moduleData['videos'][] = $descarga;
			} else if (!$esImagen && strpos($descarga->descargaArchivo, '.')) {
				$moduleData['archivos'][] = $descarga;
			}

		}

		if(!empty($moduleData['imagenes']))
			$html .= $CI->load->view('modulos/galeria/imagenes_view', $moduleData, true);

		if(!empty($moduleData['archivos']))
			$html .= $CI->load->view('modulos/galeria/documentos_view', $moduleData, true);

		if(!empty($moduleData['videos']))
			$html .= $CI->load->view('modulos/galeria/videos_view', $moduleData, true);

		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;

	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function mapa($module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();
		$mapa = $CI->Modulos->getMapa($module->moduloParam1, $idioma);

		if(count($mapa) > 0)
		{
			$moduleData['mapa'] = $mapa;

			$imageWidth = 0;
			$imageHeight = 0;

			$CI->benchmark->mark('mapa_start');
			if($mapa->mapaImagen != '')
			{

				$extension = preg_replace('/\?+\d{0,}/', '', $mapa->mapaImagen);
				$path = getcwd() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR .'mapas' . DIRECTORY_SEPARATOR . 'mapa_' . $mapa->mapaId . '.' . $extension;

				if( ! file_exists($path)) {
					return '<div data-alert class="alert-box alert">La im&aacute;gen del mapa ya no existe, por favor cargue otra</div>';
				}

				$imageSize = getimagesize($path);
				$imageWidth = $imageSize[0];
				$imageHeight = $imageSize[1];
			}
			$CI->benchmark->mark('mapa_end');

			$moduleData['imageWidth'] = $imageWidth;
			$moduleData['imageHeight'] = $imageHeight;

			$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
			$moduleData['class'] = 'mod_mapa ' . $moduleData['moduloClase'];

			$html = $CI->load->view('modulos/header_view', $moduleData, true);
			$html .= $CI->load->view('modulos/mapa/' . $module->moduloVista, $moduleData, true);
			$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

			return $html;
		}
		else
		{
			return '<div data-alert class="alert-box alert">Cree un mapa para poder ver este m&oacute;dulo</div>';
		}

	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function productoFiltros($module, $moduleData, $idioma, $data)
	{
		$CI =& get_instance();
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_filtros ' . $moduleData['moduloClase'];
		$moduleData['filtros'] = $CI->Modulos->productoFiltros($idioma);
		$moduleData['page'] = $CI->Modulos->getPageByType(4, $idioma)->paginaNombreURL;
		$moduleData['productosDestacados'] = $module->moduloParam1;
		$moduleData['categorias'] = $CI->Catalog->getCategories($idioma);

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/catalogo/filtros/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $currentPage
	 * @param $data
	 * @return string
	 */
	public function menu($module, $moduleData, $idioma, $currentPage, $data)
	{
		$CI =& get_instance();

		$data_m['menu'] = $this->createMenu($idioma);
		$data_m['class'] = 'mod_menu ' . $moduleData['moduloClase'];
		$data_m['nombre'] = $module->moduloNombre;
		$data_m['viewTitle'] = $module->moduloMostrarTitulo;
		$data_m['titulo'] = $module->moduloNombre;

		$html = $CI->load->view('modulos/header_view', $data_m, true);
		$html .= $CI->load->view('modulos/menu/' . $module->moduloVista, $data_m, true);
		$html .= $CI->load->view('modulos/footer_view', $data_m, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function productosAzar($module, $moduleData, $idioma, $data)
	{

		$CI =& get_instance();
		$moduloId = $CI->uri->segment(3, '');

		$paginacionPaginaActual = '';

		if($module->moduloId == $moduloId)
			$paginacionPaginaActual = $CI->uri->segment(4, '');

		$catalogoPagina = $CI->Modulos->getPageByType(4, $idioma);

		if(count($catalogoPagina) == 0)
		{
			return '<div data-alert class="alert-box alert">Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Cat&aacute;logo</strong> para poder ver este m&oacute;dulo</div>';
		}

		$cantidad = 1;

		if($module->moduloParam2 != '')
			$cantidad = $module->moduloParam2;

		$productosModPag = $CI->Modulos->getItemsForProductosAzar($module->moduloParam1, $cantidad, $paginacionPaginaActual, $idioma);
		$imagen = $CI->Imagenes->get($module->moduloParam3);

		$moduleData['imageSize'] = $imagen->imagenSufijo;
		$moduleData['titulo'] = $module->moduloNombre;
		$moduleData['productos'] = $productosModPag;
		$moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

		$moduleData['paginaCatalogoUrl'] = $catalogoPagina->paginaNombreURL;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_catalogoProductosAzar ' . $moduleData['moduloClase'];

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/catalogo/product/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function contacto($module, $moduleData, $idioma, $data)
	{

		$CI =& get_instance();

		$moduleData['campos'] = $CI->Contact->getContactoInputs($idioma);
		$moduleData['contactos'] = $CI->Contact->getContacts($idioma);
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['titulo'] = $module->moduloNombre;
		$moduleData['class'] = 'mod_contacto ' . $moduleData['moduloClase'];

		//Set the javascript messages
		$moduleData['ui_contact_error'] = $CI->lang->line('ui_contact_error');
		$moduleData['ui_contact_sending'] = $CI->lang->line('ui_contact_sending');

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/contacto/formulario/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function articulo($module, $moduleData, $idioma, $data)
	{

		$CI =& get_instance();

		$moduleData['articulo'] = $CI->Modulos->articulo($module->moduloParam1, $idioma);

		$articlePage = $CI->Page->getPage((int)$moduleData['articulo']->paginaId, $idioma);

		$moduleData['link'] = base_url($idioma . '/' . $articlePage->paginaNombreURL);
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_articulo ' . $moduleData['moduloClase'];

		$parseData = array(
			'base_url' => base_url()
		);
		$moduleData['articulo']->articuloContenido = auto_link($CI->parser->parse_string($moduleData['articulo']->articuloContenido, $parseData, TRUE), 'email');

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/articulo/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @return string
	 */
	public function servicios($module, $moduleData, $idioma)
	{

		$CI =& get_instance();

		$paginacionPaginaActual = $CI->uri->segment(6, '');

		$servicioPagina = $CI->Modulos->getPageByType(12, $idioma);

		if(count($servicioPagina) == 0)
		{
			return '<div data-alert class="alert-box alert">Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Servicios</strong> para poder ver este m&oacute;dulo</div>';
		}

		$serviciosCant = count($CI->Servicios->getAll($idioma, $module->moduloParam1));

		$serviciosModPag = $CI->Modulos->getItemsForServicios($module->moduloParam2, $paginacionPaginaActual, $idioma, $module->moduloParam1);

		$moduleData['pagination'] = '';

		if($module->moduloVerPaginacion)
		{

			$pag_config['base_url'] = base_url('ajax/module/servicios/'.$idioma.'/'.$module->moduloId);
			$pag_config['total_rows'] =$serviciosCant;
			$pag_config['per_page'] = $module->moduloParam2;
			$pag_config['uri_segment'] = 6;

			$CI->pagination->initialize($pag_config);
			$pagination = $CI->pagination->create_links();

			$moduleData['pagination'] = $pagination;

		}

		$imagen = $CI->Imagenes->get($module->moduloParam3);
		$moduleData['imageSize'] = $imagen->imagenSufijo;

		$moduleData['servicios'] = $serviciosModPag;
		$moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

		$moduleData['paginaServiciosUrl'] = $servicioPagina->paginaNombreURL;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_servicios ' . $moduleData['moduloClase'];
		$moduleData['titulo'] = $module->moduloNombre;

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/servicios/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @return string
	 */
	public function serviciosDestacados($module, $moduleData, $idioma)
	{

		$CI =& get_instance();

		$paginacionPaginaActual = $CI->uri->segment(3, '');

		$servicioPagina = $CI->Page->getPage((int)$module->moduloParam1, $idioma);

		if(count($servicioPagina) == 0)
		{
			return '<div data-alert class="alert-box alert">Agregue una <strong>Pagina</strong> con un modulo de <strong>Contenido de tipo Servicios</strong> para poder ver este m&oacute;dulo</div>';
		}

		$serviciosCant = count($CI->Servicios->getAll($idioma, $module->moduloParam1, TRUE));

		$serviciosModPag = $CI->Modulos->getItemsForServicios($module->moduloParam2, $paginacionPaginaActual, $idioma, $module->moduloParam1, TRUE);

		$moduleData['pagination'] = '';

		if($module->moduloVerPaginacion)
		{

			$pag_config['base_url'] = base_url('ajax/module/servicios/'.$idioma.'/'.$module->moduloId);
			$pag_config['total_rows'] =$serviciosCant;
			$pag_config['per_page'] = $module->moduloParam2;
			$pag_config['uri_segment'] = 6;

			$CI->pagination->initialize($pag_config);
			$pagination = $CI->pagination->create_links();

			$moduleData['pagination'] = $pagination;

		}

		$imagen = $CI->Imagenes->get($module->moduloParam3);
		$moduleData['imageSize'] = $imagen->imagenSufijo;

		$moduleData['servicios'] = $serviciosModPag;
		$moduleData['paginacionPaginaActual'] = $paginacionPaginaActual;

		$moduleData['paginaServiciosUrl'] = $servicioPagina->paginaNombreURL;
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['class'] = 'mod_servicios ' . $moduleData['moduloClase'];
		$moduleData['titulo'] = $module->moduloNombre;

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/servicios/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $page
	 * @param $lang
	 * @param $paths
	 * @return string
	 */
	public function breadcrumbs($module, $page, $lang, $paths)
	{

		$CI =& get_instance();
		$return = '';

		$data['pagina_url'] = $page->paginaNombreURL;
		$data['breadcrumbs'] = $paths;
		$data['lang'] = $lang;
		$data['viewTitle'] = $module->moduloMostrarTitulo;
		$data['class'] = 'mod_breadcrumbs ' . $module->moduloClase;
		$data['titulo'] = $module->moduloNombre;

		$return .= $CI->load->view('modulos/header_view', $data, true);
		$return .= $CI->load->view('modulos/breadcrumbs_view', $data, true);
		$return .= $CI->load->view('modulos/footer_view', $data, true);

		return $return;

	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function direcciones($module, $moduleData, $idioma, $data)
	{

		$CI =& get_instance();

		$moduleData['direcciones'] = $CI->Contact->getDirecciones($idioma);
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['titulo'] = $module->moduloNombre;
		$moduleData['class'] = 'mod_direcciones ' . $moduleData['moduloClase'];

        $parseData = array(
            'base_url' => base_url(),
            'asset_url' => $data['theme_asset'] . '/',
        );

        foreach($moduleData['direcciones'] as $dir) {
            $dir->contactoDireccion = $CI->parser->parse_string(auto_link($dir->contactoDireccion, 'email'), $parseData, TRUE);
        }

		$imagen = $CI->Imagenes->get($module->moduloParam3);
		$moduleData['imageSize'] = $imagen->imagenSufijo;

		$html = $CI->load->view('modulos/header_view', $moduleData, true);
		$html .= $CI->load->view('modulos/contacto/direcciones/' . $module->moduloVista, $moduleData, true);
		$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

		return $html;
	}

	/**
	 * @param $module
	 * @param $moduleData
	 * @param $idioma
	 * @param $data
	 * @return string
	 */
	public function publicidad($module, $moduleData, $idioma, $data)
	{

		$CI =& get_instance();

		$publicidad = $CI->Publicidad->get($module->moduloId);
		$moduleData['viewTitle'] = $module->moduloMostrarTitulo;
		$moduleData['titulo'] = $module->moduloNombre;
		$moduleData['class'] = 'mod_publicidad ' . $moduleData['moduloClase'];

		if($publicidad){

			$html = $CI->load->view('modulos/header_view', $moduleData, true);

			switch($publicidad->publicidadTipoId){
				case 1: //Normal
					$moduleData['archivo'] = $publicidad->publicidadArchivo1;
					$html .= $this->loadView($publicidad->publicidadArchivo1, $moduleData);
					break;
				case 2: //Expandible
					$moduleData['archivo'] = $publicidad->publicidadArchivo1;
					$html .= $this->loadView($publicidad->publicidadArchivo1, $moduleData);
					$moduleData['archivo'] = $publicidad->publicidadArchivo2;
					$html .= $this->loadView($publicidad->publicidadArchivo2, $moduleData);
					break;
			}

			$html .= $CI->load->view('modulos/footer_view', $moduleData, true);

			return $html;

		}

	}

	/**************************
	 * END: MODULES
	 *************************/

	/*
	 * GENERAL FUNCTIONS
	 */

	/**
	 * @param $file
	 * @param $moduleData
	 * @return string
	 */
	public function loadView($file, $moduleData){

		$CI =& get_instance();

		$extension = mb_strtolower(pathinfo('./assets/public/files/publicidad/' . $file, PATHINFO_EXTENSION));

		if(!$extension) {
			$extension = $file;
		}

		switch($extension) {

			//Images
			case 'jpg':
			case 'gif':
			case 'png':
			case 'jpeg':
				$html = $CI->load->view('modulos/publicidad/imagen_view', $moduleData, true);
				break;

			//Audio
			case 'mp3':
			case 'ogg':
			case 'mwa':
			case 'wav':
			$html = $CI->load->view('modulos/publicidad/audio_view', $moduleData, true);
				break;

			//Flash
			case 'swf':
				$html = $CI->load->view('modulos/publicidad/flash_view', $moduleData, true);
				break;

			//Video
			case 'avi':
			case 'wmv':
			case 'mov':
				$html = $CI->load->view('modulos/publicidad/video_view', $moduleData, true);
				break;

			//Others
			default:
				$html = 'Unknown Format';

		}

		return $html;

	}


	/**
	 * Creates the menu and the path to the active node
	 * @return array
	 */
	public function createMenu($idioma)
	{
		$CI =& get_instance();
		$path = $CI->m_breadcrumbs['page']['path'];

		$tree = $this->_getCachedTree($idioma . '_pages_', 'PageTree::allRoot', $idioma);

		//Get the Catalog Content module to check whether to show catalog category submenu in the catalog menu item
		$catalogModule = $CI->Modulos->getContentModule(4);

		//Check to see if its the catalog page and and if we want to show the category list in the menu item
		$catalog = array();
		if($catalogModule && $catalogModule->moduloParam2) {
			$catalogoPagina = $CI->Modulos->getPageByType(4,$idioma);
			$catalog = $this->createSpecialMenu('CatalogTree', $idioma, $catalogoPagina, $CI->m_breadcrumbs['catalog']['path']);
		}

		//Get the Gallery Content module to check whether to show the category submenu in the gallery menu item
		$galleryModule = $CI->Modulos->getContentModule(6);

		//Check to see if its the gallery page and and if we want to show the category list in the menu item
		$gallery = array();
		if($galleryModule && $galleryModule->moduloParam2) {
			$galleryPagina = $CI->Modulos->getPageByType(6,$idioma);
			$gallery = $this->createSpecialMenu('GalleryTree', $idioma, $galleryPagina, $CI->m_breadcrumbs['gallery']['path']);
		}

		return compact('tree', 'path', 'catalog', 'gallery');
	}

	public function createSpecialMenu($class, $idioma, $page, $path)
	{
		$tree = $this->_getCachedTree($idioma . '_' . $class . '_', $class . '::allRoot', $idioma);
		return compact('tree', 'path', 'page');
	}

	/**
	 * Checks if the Tree node is cached, and returns it, or else query again
	 *
	 * @param $key
	 * @param $method
	 * @param $lang
	 * @param array $params
	 * @return mixed
	 */
	private function _getCachedTree($key, $method, $lang, $params = array())
	{

		$CI =& get_instance();

		if ( ! $tree = $CI->cache->get($key)) {
			$tree = call_user_func_array($method, $params)->first();
			$tree->lang = $lang;
			$tree->findChildren(999);
			if(ENVIRONMENT !== 'development') {
				$CI->cache->save($key, $tree, $this->m_cache_time);
			}
		}

		return $tree;

	}

}

/* End of file Modules.php */