<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MY_Controller {
	
	var $txt_boton = '';
	var $pagina_info = array();
	var $link;
	var $mptt;

	function __construct(){

		parent::__construct();
		$this->load->helper('text');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('admin/module_model', 'Modulo');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');

		$this->load->model('admin/general_model', 'General');
		$this->load->model('noticias_model', 'Noticias');
		$this->load->model('article_model', 'Articles');
		$this->load->model('faq_model', 'Faq');
		$this->load->model('admin/catalogo_model', 'Catalogo');
		$this->load->model('descargas_model', 'Descargas');
		$this->load->model('enlaces_model', 'Enlaces');
		$this->load->model('servicios_model', 'Servicios');
		$this->load->model('calendar_model', 'Calendar');

		// IMPORTANT! This global must be defined BEFORE the flexi cart library is loaded!
		// It is used as a global that is accessible via both models and both libraries, without it, flexi cart will not work.
		$this->flexi = new stdClass;

		// Load 'admin' flexi cart library by default.
		$this->load->library('flexi_cart_admin');

		$this->load->model('admin/cart_model', 'Cart');

		$this->load->library('ion_auth');
		
		$this->load->library('Seguridad');
		$this->load->library('CMS_General');

		$config = $this->Config->get();
		$this->theme = $config->theme;

		$this->seguridad->init();
		
	}

	/**
	 * Show the website's structure
	 */
	public function index()
	{
		$root = PageTree::allRoot()->first();
		$root->findChildren(999);
		$this->load->view('admin/request/html', array(
			'return' => admin_structure_tree($root->getChildren(), $this->Modulo->getContentPages())
		));
	}
	
	public function edit($id)
	{

		//Get the page
		$page = $this->Paginas->getPage((int)$id);

		//Get any content modules
		$content = $this->Modulo->getContentByPage($id);

		$data['txt_titulo'] = $page->paginaNombreMenu;

		foreach ($content as $module) {

			switch ($module->moduloParam1) {

				//Articles
				case 1:

					$data['items'] = $this->Articles->getByPage($id, 'es');
					$data['grupos'] = $this->Modulo->getContentByType(1);

					$data['url_rel'] = base_url('admin/article');
					$data['url_sort'] = base_url('admin/article/reorder/' . $id);
					$data['url_modificar'] = base_url('admin/article/edit');
					$data['url_eliminar'] = base_url('admin/article/delete');
					$data['url_search'] = base_url("admin/search/articles");

					$data['search'] = true;
					$data['drag'] = true;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'article_' . $id;

					$data['idx_nombre'] = 'articuloTitulo';
					$data['idx_id'] = 'articuloId';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton importante n1'
					);

					$data['menu'][] = anchor(base_url('admin/article/create/' . $id), 'crear nuevo artículo', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listado_view', $data);

					break;

				//FAQ
				case 2:

					$data['items'] = $this->Faq->getByPage($id, 'es');

					$data['url_rel'] = base_url('admin/faq');
					$data['url_sort'] = base_url('admin/faq/reorder');
					$data['url_modificar'] = base_url('admin/faq/edit');
					$data['url_eliminar'] = base_url('admin/faq/delete');
					$data['url_search'] = '';

					$data['search'] = false;
					$data['drag'] = true;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'faqs';

					$data['idx_id'] = 'faqId';
					$data['idx_nombre'] = 'faqPregunta';

					$data['txt_titulo'] = 'Preguntas Frecuentes';
					$data['txt_grupoNombre'] = 'Página';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax importante n1 boton'
					);

					$data['menu'][] = anchor(base_url('admin/faq/create/' . $id), 'crear nueva pregunta', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listado_view', $data);

					break;

				//Catalog
				case 4:

					$root = CatalogTree::allRoot()->first();
					$root->findChildren(999);

					$data['root_node'] = $root;

					$data['url_rel'] = base_url('admin/catalog');
					$data['url_search'] = base_url("admin/search/productos");

					$data['urls'] = array(
						'edit' => base_url('admin/catalog/product/edit'),
						'delete' => base_url('admin/catalog/product/delete'),
						'sort' => base_url('admin/catalog/product/reorder'),
					);

					$data['names'] = array(
						'category' => 'productoCategoriaNombre' ,
						'item' => 'productoNombre',
					);

					$data['item_methods'] = array(
						'library' => 'Catalogo' ,
						'method' => 'getProductos',
					);

					$data['search'] = true;
					$data['drag'] = true;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'catalogo';

					$data['idx_nombre'] = 'productoCategoriaNombre';
					$data['idx_grupo_id'] = 'categoriaId';
					$data['idx_item_id'] = 'productoId';
					$data['idx_item_nombre'] = 'productoNombre';

					$data['txt_titulo'] = 'Cat&aacute;logo';
					$data['txt_grupoNombre'] = 'Categoría';


					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton importante n4'
					);
					$data['menu'][] = anchor(base_url('admin/catalog/product/create'), 'crear nuevo producto', $atts);

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton n3'
					);
					$data['menu'][] = anchor(base_url('admin/catalog/category'), 'categor&iacute;as', $atts);

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton n2'
					);
					$data['menu'][] = anchor(base_url('admin/catalog/field'), 'template', $atts);

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton n1'
					);
					$data['menu'][] = anchor(base_url('admin/catalog/config'), 'configuracion', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listadoCategorias_view', $data);

					break;

				//Publications
				case 5:

					$data['items'] = $this->Noticias->getByPage($id, 'es');

					$data['url_rel'] = base_url('admin/publications/publication');
					$data['url_sort'] = '';
					$data['url_modificar'] = base_url('admin/publications/publication/edit');
					$data['url_eliminar'] = base_url('admin/publications/publication/delete');
					$data['url_search'] = base_url("admin/search/publicaciones");

					$data['search'] = true;
					$data['drag'] = false;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'pub_' . $id;

					$data['idx_nombre'] = 'publicacionNombre';
					$data['idx_id'] = 'publicacionId';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton importante n1'
					);

					$data['menu'][] = anchor(base_url('admin/publications/publication/create/' . $id), 'crear nueva publicación', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listado_view', $data);

					break;

				//Gallery
				case 6:

					$root = GalleryTree::allRoot()->first();
					$root->findChildren(999);

					$data['root_node'] = $root;
					$data['dim'] = $this->General->getCropImage(8);

					$data['url_rel'] = base_url('admin/gallery/file');
					$data['url_search'] = base_url("admin/search/galeria");

					$data['urls'] = array(
						'edit' => base_url('admin/gallery/file/edit'),
						'delete' => base_url('admin/gallery/file/delete'),
						'sort' => base_url('admin/gallery/file/reorder'),
					);

					$data['names'] = array(
						'category' => 'descargaCategoriaNombre' ,
						'item' => 'descargaNombre',
					);

					$data['item_methods'] = array(
						'library' => 'Descargas' ,
						'method' => 'getDownloads',
					);

					$data['search'] = true;
					$data['drag'] = true;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'descargas';
					$data['view'] = 'nodes/gallery';

					$data['idx_nombre'] = 'descargaCategoriaNombre';
					$data['idx_grupo_id'] = 'id';
					$data['idx_item_id'] = 'descargaId';
					$data['idx_item_nombre'] = 'descargaNombre';

					$data['txt_titulo'] = 'Galería';
					$data['txt_grupoNombre'] = 'Categoría';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'class' => $data['nivel'] . ' ajax boton_listado primero n1 boton'
					);
					$data['menu'][] = anchor(base_url('admin/gallery/category/index'), 'editar categorías', $atts);

					$atts = array(
						'class' => $data['nivel'] . ' ajax boton_listado importante n2 boton'
					);
					$data['menu'][] = anchor(base_url('admin/gallery/youtube/create'), 'a&ntilde;adir video de youtube', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listadoGallery_view', $data);

					break;

				//Links
				case 10:

					$data['items'] = $this->Enlaces->getAll('es', FALSE);

					$data['url_rel'] = base_url('admin/link');
					$data['url_sort'] = base_url('admin/link/reorder/');
					$data['url_modificar'] = base_url('admin/link/edit/');
					$data['url_eliminar'] = base_url('admin/link/delete/');

					$data['search'] = false;
					$data['drag'] = true;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'link_' . $id;

					$data['idx_nombre'] = 'enlaceTexto';
					$data['idx_id'] = 'enlaceId';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'id' => 'crear',
						'class' => $data['nivel'] . ' ajax boton importante n1'
					);

					$data['menu'][] = anchor(base_url('admin/link/create/' . $id), 'crear nuevo enlace', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listado_view', $data);

					break;

				//Services
				case 12:

					$data['items'] = $this->Servicios->getAll('es', $id);

					$data['url_rel'] = base_url('admin/services/service');
					$data['url_sort'] = base_url('admin/services/service/reorder');
					$data['url_modificar'] = base_url('admin/services/service/edit');
					$data['url_eliminar'] = base_url('admin/services/service/delete');
					$data['url_search'] = base_url("admin/search/servicios");

					$data['search'] = true;
					$data['drag'] = true;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'serv';

					$data['idx_id'] = 'servicioId';
					$data['idx_nombre'] = 'servicioTitulo';

					$data['txt_titulo'] = 'Servicios';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'id' => 'crearBanner',
						'class' => $data['nivel'] . ' ajax boton importante n1'
					);
					$data['menu'][] = anchor(base_url('admin/services/service/create/' . $id), 'Crear Servicio', $atts);
					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listado_view', $data);

					break;

				//Calendar
				case 13:

					$data['items'] = $this->Calendar->getAll();

					$data['url_rel'] = base_url('admin/calendar/day');
					$data['url_sort'] = '';
					$data['url_modificar'] = base_url('admin/calendar/day/edit');
					$data['url_eliminar'] = base_url('admin/calendar/day/delete');
					$data['url_search'] = base_url("admin/search/calendar");

					$data['search'] = true;
					$data['drag'] = false;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = 'calendar';

					$data['idx_id'] = 'id';
					$data['idx_nombre'] = 'date';

					$data['txt_titulo'] = 'Calendario';

					/*
					 * Menu
					 */
					$data['menu'] = array();

					$atts = array(
						'class' => $data['nivel'] . ' ajax boton importante n1'
					);
					$data['menu'][] = anchor(base_url('admin/calendar/day/create'), 'Crear D&iacute;a', $atts);

					//TODO: finish dynamic fields
					/*
					$atts = array(
						'class' => $data['nivel'] . ' ajax boton n1'
					);
					$data['menu'][] = anchor(base_url('admin/calendar/fields'), 'Template', $atts);*/

					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listado_view', $data);

					break;

				//Cart
				case 9:

					// Get an array of all saved orders.
					// Using a flexi cart SQL function, set the order the order data so that dates are listed newest to oldest.
					$this->flexi_cart_admin->sql_order_by($this->flexi_cart_admin->db_column('order_summary', 'date'), 'desc');
					$data['items'] = $this->flexi_cart_admin->get_db_order_array();
					$this->flexi_cart_admin->sql_clear();
					$data['grupos'] = $this->flexi_cart_admin->get_db_order_status_array();;

					$data['url_rel'] = base_url('admin/cart');
					$data['url_sort'] = '';
					$data['url_modificar'] = base_url('admin/cart/order/detail');
					$data['url_eliminar'] = base_url('admin/cart/order/delete');
					$data['url_search'] = base_url("admin/search/cart");

					$data['search'] = true;
					$data['drag'] = false;
					$data['nivel'] = 'nivel2';
					$data['list_id'] = '';

					$data['idx_nombre'] = 'ord_status_description';
					$data['idx_grupo_id'] = 'ord_status_id';
					$data['idx_grupo_id_alt'] = 'ord_status';
					$data['idx_item_id'] = 'ord_order_number';
					$data['idx_item_nombre'] = 'ord_order_number';

					$data['txt_titulo'] = 'Carrito de Compras';
					$data['txt_grupoNombre'] = 'Estado';

					/*
					 * Menu
					 */
					$data['menu'] = array();
					$data['menu'][] = anchor(base_url('admin/cart/location/index'), 'locaciones y Zonas', array('class' => $data['nivel'] . ' ajax boton n7'));
					$data['menu'][] = anchor(base_url('admin/cart/shipping/index'), 'Envios e Impuestos', array('class' => $data['nivel'] . ' ajax boton n6'));
					$data['menu'][] = anchor(base_url('admin/cart/discount/index'), 'Descuentos', array('class' => $data['nivel'] . ' ajax boton n5'));
					//TODO entender que hace esto: http://haseydesign.com/flexi-cart/admin_library/user_reward_points
					//$data['menu'][] = anchor(base_url('admin/cart/recompensas'), 'Puntos de Recompensa', array('class' => $data['nivel'] . ' ajax boton n5'));
					//$data['menu'][] = anchor(base_url('admin/cart/vouchers'), 'Vouchers', array('class' => $data['nivel'] . ' ajax boton n4'));
					$data['menu'][] = anchor(base_url('admin/cart/currency/index'), 'Monedas', array('class' => $data['nivel'] . ' ajax boton n4'));
					$data['menu'][] = anchor(base_url('admin/cart/status/index'), 'Estados', array('class' => $data['nivel'] . ' ajax boton n3'));
					$data['menu'][] = anchor(base_url('admin/cart/defaults/edit'), 'Valores por defecto', array('class' => $data['nivel'] . ' ajax boton n2'));
					$data['menu'][] = anchor(base_url('admin/cart/config/edit'), 'Configuraci&oacute;n', array('class' => $data['nivel'] . ' ajax boton n1'));

					$data['bottomMargin'] = count($data['menu']) * 34;

					$this->load->view('admin/listadoAgrupado_view', $data);

					break;

			}

		}

	}

}
