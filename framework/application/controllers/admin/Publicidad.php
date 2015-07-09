<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publicidad extends CI_Controller {
	 
	function __construct(){
		parent::__construct();

		$this->load->model('publicidad_model', 'Publicidad');
		$this->load->model('admin/module_model', 'Modulo');
		$this->load->model('admin/page_model', 'Pagina');

		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();

        date_default_timezone_set('America/Guayaquil');
		
	}
	
	public function index()
	{

        $data['grupos'] = $this->Pagina->getPages();
        $data['items'] = $this->Publicidad->getAll();

        $data['url_rel'] = base_url('admin/publicidad');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/publicidad/modificar');
        $data['url_eliminar'] = base_url('admin/publicidad/eliminar');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'publicidad';
        $data['txt_grupoNombre'] = 'Página';
        $data['txt_titulo'] = 'Publicidad';

        $data['idx_id'] = 'publicidadId';
        $data['idx_nombre'] = 'paginaNombreMenu';

        $data['idx_grupo_id'] = 'paginaId';
        $data['idx_item_id'] = 'publicidadId';
        $data['idx_item_nombre'] = 'publicidadNombre';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearPublicidadNormal',
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n3'
        );
        $data['menu'][] = anchor(base_url('admin/publicidad/crearNormal'), 'crear publicidad normal', $atts);

        $atts = array(
            'id' => 'crearPublicidadExpandible',
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n2'
        );
        $data['menu'][] = anchor(base_url('admin/publicidad/crearExpandible'), 'crear publicidad expandible', $atts);

        $atts = array(
            'id' => 'crearPublicidadPopup',
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/publicidad/crearPopup'), 'crear publicidad popup', $atts);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoAgrupado_view', $data);
	}

	public function crearNormal()
	{

		$publicidadId = $this->cms_general->generarId('publicidad');
		$data['publicidadId'] = $publicidadId;
		$data['publicidadNombre'] = '';
		$data['publicidadArchivo1'] = '';
		$data['publicidadClase'] = '';
		$data['moduloId'] = '';
		$data['publicidadEnabled'] = 'checked="checked"';

		$modulos = $this->Modulo->get_page_by_module_type(25);
		$paginas = $this->Pagina->getPages();

        $data['ubicaciones'] = $this->formatUbicaciones($paginas, $modulos);

        $data['publicidadFechaInicio'] = date('Y-m-d H:i:s');

        $d = new DateTime($data['publicidadFechaInicio']);
        $d->modify('next month');
        $data['publicidadFechaFin'] = $d->format('Y-m-d H:i:s');

		$data['txt_botImagen'] = 'Subir Archivo';
		$data['archivoUrl'] = '<a href="#"></a>';
		$data['titulo'] = "Crear Publicidad";
        $data['link'] = base_url("admin/publicidad/insertar");
		$data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/publicidad/eliminar/'.$publicidadId);

		$this->load->view('admin/publicidad/publicidadCrearNormal_view', $data);
	}

    public function crearExpandible()
    {

        $publicidadId = $this->cms_general->generarId('publicidad');
        $data['publicidadId'] = $publicidadId;
        $data['publicidadNombre'] = '';
        $data['publicidadArchivo1'] = '';
        $data['publicidadArchivo2'] = '';
        $data['publicidadClase'] = '';
        $data['moduloId'] = '';
        $data['publicidadEnabled'] = 'checked="checked"';

        $modulos = $this->Modulo->get_page_by_module_type(25);
        $paginas = $this->Pagina->getPages();

        $data['ubicaciones'] = $this->formatUbicaciones($paginas, $modulos);

        $data['publicidadFechaInicio'] = date('Y-m-d H:i:s');

        $d = new DateTime($data['publicidadFechaInicio']);
        $d->modify('next month');
        $data['publicidadFechaFin'] = $d->format('Y-m-d H:i:s');

        $data['txt_botImagen1'] = 'Subir Imagen Peque&ntilde;a';
        $data['txt_botImagen2'] = 'Subir Archivo Expandido';
        $data['archivoUrl1'] = '<a href="#"></a>';
        $data['archivoUrl2'] = '<a href="#"></a>';
        $data['titulo'] = "Crear Publicidad";
        $data['link'] = base_url("admin/publicidad/insertar");
        $data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/publicidad/eliminar/'.$publicidadId);

        $this->load->view('admin/publicidad/publicidadCrearExpandible_view', $data);
    }

    public function crearPopup()
    {

        $publicidadId = $this->cms_general->generarId('publicidad');
        $data['publicidadId'] = $publicidadId;
        $data['publicidadNombre'] = '';
        $data['publicidadArchivo1'] = '';
        $data['publicidadClase'] = '';
        $data['paginaId'] = '';
        $data['publicidadEnabled'] = 'checked="checked"';

        $data['paginas'] = $this->Pagina->getPages();

        $data['publicidadFechaInicio'] = date('Y-m-d H:i:s');

        $d = new DateTime($data['publicidadFechaInicio']);
        $d->modify('next month');
        $data['publicidadFechaFin'] = $d->format('Y-m-d H:i:s');

        $data['txt_botImagen'] = 'Subir Archivo';
        $data['archivoUrl'] = '<a href="#"></a>';
        $data['titulo'] = "Crear Publicidad";
        $data['link'] = base_url("admin/publicidad/insertar");
        $data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/publicidad/eliminar/'.$publicidadId);

        $this->load->view('admin/publicidad/publicidadCrearPopup_view', $data);
    }
	
	public function modificar()
	{

		$id = $this->uri->segment(4);
		$publicidad = $this->Publicidad->getData($id);

        $publicidadId = $this->cms_general->generarId('publicidad');
        $data['publicidadId'] = $publicidad->publicidadId;
        $data['publicidadNombre'] = $publicidad->publicidadNombre;
        $data['publicidadArchivo1'] = $publicidad->publicidadArchivo1;
        $data['publicidadArchivo2'] = $publicidad->publicidadArchivo2;
        $data['publicidadClase'] = $publicidad->publicidadClase;
        $data['moduloId'] = $publicidad->moduloId;
        $data['paginaId'] = $publicidad->paginaId;
        $data['publicidadEnabled'] = '';

        if($publicidad->publicidadEnabled)
            $data['publicidadEnabled'] = 'checked="checked"';

        $modulos = $this->Modulo->get_page_by_module_type(25);
        $paginas = $this->Pagina->getPages();

        $data['ubicaciones'] = $this->formatUbicaciones($paginas, $modulos);
        $data['paginas'] = $paginas;

        $data['publicidadFechaInicio'] = $publicidad->publicidadFechaInicio;
        $data['publicidadFechaFin'] = $publicidad->publicidadFechaFin;

        $data['txt_botImagen'] = 'Subir Archivo';
        $data['archivoUrl'] = '';
        $data['titulo'] = "Modificar Publicidad";
        $data['link'] = base_url("admin/publicidad/actualizar");
        $data['txt_boton'] = "modificar";
        $data['nuevo'] = '';
        $data['removeUrl'] = base_url('admin/publicidad/eliminar/'.$publicidadId);

        switch($publicidad->publicidadTipoId){
            case 1:
                $data['archivoUrl'] = $this->generateTag($publicidad->publicidadArchivo1);
                $this->load->view('admin/publicidad/publicidadCrearNormal_view', $data);
                break;
            case 2:
                $data['archivoUrl1'] = $this->generateTag($publicidad->publicidadArchivo1);
                $data['archivoUrl2'] = $this->generateTag($publicidad->publicidadArchivo2);
                $data['txt_botImagen1'] = 'Subir Imagen Peque&ntilde;a';
                $data['txt_botImagen2'] = 'Subir Archivo Expandido';
                $this->load->view('admin/publicidad/publicidadCrearExpandible_view', $data);
                break;
            case 3:
                $data['archivoUrl'] = $this->generateTag($publicidad->publicidadArchivo1);
                $this->load->view('admin/publicidad/publicidadCrearPopup_view', $data);
        }

	}

    private function generateTag($file){
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
                $tag = '<img src="' . base_url() . 'assets/public/files/publicidad/' . $file . '?' . time() . '" />';
                break;

            //Audio
            case 'mp3':
            case 'ogg':
            case 'mwa':
            case 'wav':
                $tag = '<audio src="' . base_url() . 'assets/public/files/publicidad/' . $file . '" controls ></audio>';
                break;

            //Flash
            case 'swf':
                $path = base_url() . 'assets/public/files/publicidad/' . $file;
                $tag = "<object width=\"100\" height=\"100\">
                    <param name=\"movie\" value=\"$path\">
                    <embed src=\"$path\" width=\"100\" height=\"100\">
                    </embed>
                </object>";
                break;

            //Video
            case 'avi':
            case 'wmv':
            case 'mov':
                $tag = '<video src="' . base_url() . 'assets/public/files/publicidad/' . $file . '" controls ></video>';
                break;

            //Others
            default:
                $tag = '<a href="' . base_url() . 'assets/public/files/publicidad/' . $file . '">' . $file . '</a>';

        }

        return $tag;

    }

    private function formatUbicaciones($paginas, $modulos, $unset = TRUE){
        $ubicaciones = array();

        foreach($paginas as $key => $pagina) {

            $pag = new stdClass();
            $pag->id = $pagina['paginaId'];
            $pag->nombre = $pagina['paginaNombreMenu'];
            $pag->modulos = array();

            $ubicaciones[$key] = $pag;

            foreach($modulos as $modulo) {
                if($pagina['paginaId'] === $modulo->paginaId){
                    $mod = new stdClass();
                    $mod->id = $modulo->moduloId;
                    $mod->nombre = $modulo->moduloNombre ? $modulo->moduloNombre : '[sin nombre]';
                    $ubicaciones[$key]->modulos[] = $mod;
                }
            }
        }

        //Remove the empty pages
        foreach($ubicaciones as $key => $ubicacion){
            if(empty($ubicacion->modulos) && $unset){
                unset($ubicaciones[$key]);
            }
        }
        return $ubicaciones;
    }

    public function insertar()
    {

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id = $this->Publicidad->insert();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al añadir la publicidad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

    }
	
	public function actualizar()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Publicidad->update();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la publicidad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function eliminar()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$this->Publicidad->delete($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la publicidad!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function images($bannerId='')
	{

        $data['items'] = $this->Banners->getImages($bannerId);

        $data['url_rel'] = base_url('admin/slideshows/images/'.$bannerId);
        $data['url_sort'] = base_url('admin/slideshows/reorganizar/'.$bannerId);
        $data['url_modificar'] = base_url('admin/slideshows/modificarImagen/'.$bannerId);
        $data['url_eliminar'] = base_url('admin/slideshows/eliminarImagen/'.$bannerId);
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'banner_images';

        $data['idx_id'] = 'bannerImagesId';
        $data['idx_nombre'] = 'bannerImageName';

        $data['txt_titulo'] = 'Imágenes del Banner';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax boton n1'
        );
        $data['menu'][] = anchor(base_url('admin/slideshows/crearImagen/'.$bannerId), 'subir nueva imagen', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
	}
	
	public function reorganizar()
	{
		$bannerId = $this->uri->segment(4);
		$this->Banners->reorder($bannerId);
		$this->images($bannerId);
	}

}
