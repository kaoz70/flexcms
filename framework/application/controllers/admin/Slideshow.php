<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slideshow extends CI_Controller {
	 
	function __construct(){
		parent::__construct();
		$this->load->helper('text');
		
		$this->load->library('image_lib');
		
		$this->load->model('banners_model', 'Banners');
		$this->load->model('admin/page_model', 'Paginas');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
	}
	
	public function index()
	{

        $data['items'] = $this->Banners->getAll();

        $data['url_rel'] = base_url('admin/slideshow');
        $data['url_sort'] = '';
        $data['url_edit'] = base_url('admin/slideshow/edit');
        $data['url_eliminar'] = base_url('admin/slideshow/delete');
        $data['url_modificar'] = base_url('admin/slideshow/edit');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel2';
        $data['list_id'] = 'banners';

        $data['idx_id'] = 'bannerId';
        $data['idx_nombre'] = 'bannerName';

        $data['txt_titulo'] = 'Banners';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' nivel2 ajax boton importante n2'
        );
        $data['menu'][] = anchor(base_url('admin/slideshow/create'), 'crear nuevo banner', $atts);

        $atts = array(
            'id' => 'editarTemplateBanner',
            'class' => $data['nivel'] . ' nivel2 ajax boton n1'
        );
        $data['menu'][] = anchor(base_url('admin/slideshow/fields'), 'editar template', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
	}
	
	public function create()
	{

		$bannerId = $this->Banners->add();
		$data['bannerId'] = $bannerId;
		$data['bannerName'] = '';
		$data['bannerClass'] = '';
		$data['bannerType'] = 'bxSlider';
		$data['bannerEnabled'] = 'checked="checked"';
		$data['banner_config'] = $this->Banners->getTypes();
		$data['config'] = array();
		$data['txt_botImagen'] = 'Subir Imágenes';
		$data['titulo'] = "Crear Banner";
        $data['link'] = base_url("admin/slideshow/update");
		$data['txt_boton'] = "crear";
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/slideshow/delete/'.$bannerId);

		$data['bannerWidth'] = '';
		$data['bannerHeight'] = '';
		
		$this->load->view('admin/slideshow/slideshow_view', $data);
	}
	
	public function edit($id)
	{
		
		$data = $this->Banners->get($id, 'es');
		$data['banner_config'] = $this->Banners->getTypes();
		$config = json_decode($data['config'], TRUE);
		$data['config'] = $config ? $config : array();
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
		$data['titulo'] = "Modificar Banner";
		$data['link'] = base_url("admin/slideshow/update");
		$data['txt_boton'] = "modificar";
		
		$this->load->view('admin/slideshow/slideshow_view', $data);
	}
	
	public function update()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->update();
            $response->new_id = $this->input->post('bannerId');
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el banner!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->delete($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el banner!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

    public function reorder($id)
    {
        $this->Banners->reorder($id);
    }
	
	public function images($bannerId)
	{

        $data['items'] = $this->Banners->getImages($bannerId);

        $data['url_rel'] = base_url('admin/slideshow/images/'.$bannerId);
        $data['url_sort'] = base_url('admin/slideshow/reorder/'.$bannerId);
        $data['url_modificar'] = base_url('admin/slideshow/edit_image/'.$bannerId);
        $data['url_eliminar'] = base_url('admin/slideshow/delete_image/'.$bannerId);
        $data['url_path'] =  base_url() . 'assets/public/images/banners/banner_' . $bannerId . '_';
        $data['method'] =  'banner/' . $bannerId;

		$banner = $this->Banners->get($bannerId, 'es');
		$data['width'] = $banner['bannerWidth'];
		$data['height'] = $banner['bannerHeight'];

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'banner_images';

        $data['idx_id'] = 'bannerImagesId';
        $data['idx_nombre'] = 'bannerImageName';
        $data['idx_extension'] = 'bannerImageExtension';

        $data['txt_titulo'] = 'Imágenes del Banner';

        /*
         * Menu
         */
        $data['menu'] = array();

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoGaleria_view', $data);
	}

	public function edit_image($bannerId, $imageId)
	{

		$image = $this->Banners->getImage($imageId);
		$banner = $this->Banners->get($bannerId, 'es');

		$data['bannerImageEnabled'] = '';

		if($image->bannerImageEnabled)
			$data['bannerImageEnabled'] = 'checked="checked"';

		$data['bannerId'] = $bannerId;
		$data['imageId'] = $imageId;
		$data['titulo'] = "Modificar Imágen";
		$data['bannerImageName'] = $image->bannerImageName;
		$data['bannerImageLink'] = $image->bannerImageLink;
		$data['bannerImageExtension'] = $image->bannerImageExtension;
        $data['bannerImagenCoord'] = urlencode($image->bannerImagenCoord);
		$data['imagen'] = '';
		$data['txt_boton'] = "guardar imágen";
		$data['txt_botImagen'] = "Subir Imágen";
		$data['link'] = base_url('admin/slideshow/update_image/' . $bannerId . '/' . $imageId);
		$camposRes = $this->Banners->getCampos();
		$data['width'] = $banner['bannerWidth'];
		$data['height'] = $banner['bannerHeight'];
        $data['nuevo'] = '';
        $data['removeUrl'] = '';

		$data['imagen'] = '';
        $data['imagenOrig'] = '';

		if($image->bannerImageExtension != '')
		{
			//Eliminamos el cache del navegador
			$extension = $image->bannerImageExtension;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_orig.' . $extension . '?' . time();
		}

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$campos = array();

		foreach ($camposRes as $keyCampo => $campoResult) {

			$campo = new stdClass();
            $campo->inputId = $campoResult['inputId'];
            $campo->bannerCampoId = $campoResult['bannerCampoId'];
            $campo->bannerCampoNombre = $campoResult['bannerCampoNombre'];
            $campo->inputTipoContenido = $campoResult['inputTipoContenido'];

			$traducciones = array();

			foreach ($data['idiomas'] as $key => $idioma) {
                $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
				$traducciones[$idioma['idiomaDiminutivo']]->nombre = $idioma['idiomaNombre'];
                $tx = $this->Banners->getImageTranslation($idioma['idiomaDiminutivo'], $campoResult['bannerCampoId'], $imageId);
                $traducciones[$idioma['idiomaDiminutivo']]->contenido = new stdClass();
                if($tx)
				    $traducciones[$idioma['idiomaDiminutivo']]->contenido->bannerCamposTexto = $tx->bannerCamposTexto;
                else
                    $traducciones[$idioma['idiomaDiminutivo']]->contenido->bannerCamposTexto = '';
			}

			$campo->traducciones = $traducciones;

			array_push($campos, $campo);

		}

		$data['campos'] = $campos;

		$this->load->view('admin/slideshow/bannersImagesCrear_view',$data);
	}

	public function update_image($bannerId, $imageId)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->updateImage($bannerId, $imageId);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el banner!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete_image($bannerId, $imageId)
	{

		$imagen = $this->Banners->getImage($imageId);

		//Eliminamos la imagen
		$imageExtension = preg_replace('/\?+\d{0,}/', '', $imagen->bannerImageExtension);

        //TODO: correctly delete all the images, using a DB query
		if(file_exists('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '.' . $imageExtension))
			unlink('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '.' . $imageExtension);

		if(file_exists('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_thumb.' . $imageExtension))
			unlink('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_thumb.' . $imageExtension);

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Banners->deleteImage($imageId);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la im&aacute;gen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function fields()
	{

        $data['items'] = $this->Banners->getCampos();

        $data['url_rel'] = base_url('admin/slideshow/fields');
        $data['url_sort'] = base_url('admin/slideshow/reorder_fields');
        $data['url_modificar'] = base_url('admin/slideshow/edit_field');
        $data['url_eliminar'] = base_url('admin/slideshow/delete_field');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'banner_campos';

        $data['idx_id'] = 'bannerCampoId';
        $data['idx_nombre'] = 'bannerCampoNombre';

        $data['txt_titulo'] = 'Editar Template';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crearBanner',
            'class' => $data['nivel'] . ' ajax boton n1'
        );
        $data['menu'][] = anchor(base_url('admin/slideshow/create_field'), 'Crear Nuevo Elemento', $atts);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
	}

	public function create_field()
	{
		$data['titulo'] = 'Nuevo Elemento';
		$data['habilitado']	= 'checked="checked"';

		$data['campoId'] = $this->cms_general->generarId('banner_campos');
		$data['bannerCampoNombre'] = '';
		$data['campo_tipoDato'] = '';
		$data['inputId'] = '';
		$checked = '';
		$data['checked'] = $checked;
        $data['nuevo'] = 'nuevo';

		$data['result'] = $this->Banners->getInputs();
		$data['bannerCampoClase'] = '';
		$data['txt_boton'] = 'Guardar Elemento';
		$data['link']  = base_url('admin/slideshow/insert_field');

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->bannerCampoLabel = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/slideshow/field_view',$data);
	}

	public function edit_field($campoId)
	{

		$data['titulo'] = 'Editar Elemento';
		$data['habilitado']	= 'checked="checked"';

		$campo = $this->Banners->getDatosCampo($campoId);

		$data['campoId'] = $campo->bannerCampoId;
		$data['bannerCampoNombre'] = $campo->bannerCampoNombre;
		$data['inputId'] = $campo->inputId;
        $data['nuevo'] = '';
		
		$checked='';
		if($campo->bannerCampoLabelHabilitado)
			$checked = 'checked="checked"';
		
		$data['checked'] = $checked;
		$data['result'] = $this->Banners->getInputs();	
		$data['bannerCampoClase'] = $campo->bannerCampoClase;
		
		$data['txt_boton'] = 'Modificar Elemento';
		$data['link'] = base_url('admin/slideshow/update_field/');
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();
		
		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = $this->Banners->getInputTranslation($idioma['idiomaDiminutivo'], $campoId);
		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/slideshow/field_view',$data);
	}
	
	public function insert_field()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Banners->guardarCampo();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	public function update_field()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->updateCampo();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al modificar el campo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete_field($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->deleteCampo($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function reorder_fields()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->reorderInputs();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
}
