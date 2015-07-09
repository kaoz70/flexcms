<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {
	 
	function __construct(){
		parent::__construct();
		$this->load->helper('text');
		$this->load->library('image_lib');
		$this->load->model('descargas_model', 'Descargas');
		$this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
		$this->load->model('admin/module_model', 'Modulo');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
	}
	
	/*
	 * CATEGORIAS
	 */
	
	public function categories()
	{

        $root = GalleryTree::allRoot()->first();
        $root->findChildren(999);

        $depth = 0;
        foreach (GalleryTree::allLeaf() as $leaf) {
            if($depth < $leaf->getDepth()) {
                $depth = $leaf->getDepth();
            }
        }

        $data['root_node'] = $root;
        $data['tree_size'] = $depth;

        $data['titulo'] = 'Categorías';

        $data['id'] = 'galeria_tree';

        $data['url_reorganizar'] = base_url('admin/gallery/reorder_category');
        $data['url_rel'] = base_url('admin/gallery/categories');
        $data['edit_url'] = base_url('admin/gallery/edit_category');
        $data['delete_url'] = base_url('admin/gallery/delete_category');
        $data['name'] = 'descargaCategoriaNombre';

        $data['nivel'] = 'nivel3';

        /*
         * Menu
         */
        $data['menu'] = array();

        $atts = array(
            'id' => 'crear',
            'class' => $data['nivel'] . ' ajax boton importante n1'
        );
        $data['menu'][] = anchor(base_url('admin/gallery/create_category'), 'crear nueva categoría', $atts);

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoArbol_view', $data);

	}
	
	public function create_category()
	{
		$data['id'] = $this->Descargas->addCategory($this->cms_general);
		$data['descargaCategoriaTipo'] = '';
		$data['descargaCategoriaNombre'] = '';
		$data['descargaCategoriaClase'] = '';
		$data['descargaCategoriaEnlace'] = '';
		$data['titulo'] = "Crear Categoría";
		$data['link'] = base_url("admin/gallery/update_category");
		$data['txt_boton'] = "crear";
		$data['descargaCategoriaPublicado'] = 'checked="checked"';
        $data['nuevo'] = 'nuevo';

        $data['imagen'] = '';
        $data['removeUrl'] = base_url('admin/gallery/delete_category/' . $data['id']);
        $data['imagenOrig'] = '';
        $data['imagenExtension'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['cropDimensions'] = $this->General->getCropImage(12);
        $data['descargaCategoriaImagenCoord'] = '';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre = '';
		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/gallery/category_view', $data);
	}
	
	public function edit_category($id)
	{
		
		$data = $this->Descargas->getCategory((int)$id, 'es');
        $data['nuevo'] = '';
		$data['titulo'] = "Modificar Categoría";
		$data['link'] = base_url("admin/gallery/update_category");
		$data['txt_boton'] = "modificar";

        $data['imagen'] = '';
        $data['imagenOrig'] = '';
        $data['imagenExtension'] = '';
        $data['txt_botImagen'] = 'Subir Imagen';
        $data['cropDimensions'] = $this->General->getCropImage(12);
        $data['descargaCategoriaImagenCoord'] = urlencode($data['descargaCategoriaImagenCoord']);

        if($data['descargaCategoriaImagen'] != '')
        {
            //Eliminamos el cache del navegador
            $extension = $data['descargaCategoriaImagen'];
            $extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/downloads/cat_' . $data['id'] . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/downloads/cat_' . $data['id'] . '_orig.' . $extension;
            $data['imagenExtension'] = $data['descargaCategoriaImagen'];
        }
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$descargaTraduccion = $this->Descargas->getCategoriaTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            if($descargaTraduccion)
			    $traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre = $descargaTraduccion->descargaCategoriaNombre;
            else
                $traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre = '';
		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/gallery/category_view', $data);
	}
	
	public function update_category()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Descargas->updateCategory($this->cms_general);
            $response->new_id = $this->input->post('descargaCategoriaId');
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la categor&iacute;a!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function insert_category()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Descargas->addCategory($this->cms_general);
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear la categor&iacute;a!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete_category($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $node = GalleryTree::find($id);
            $node->deleteWithChildren();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la categor&iacute;a!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder_category()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $pages = GalleryTree::find(1);
            $pages->mapTree(json_decode($this->input->post('posiciones'), true));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar la categor&iacute;a!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	/*
	 * DESCARGAS
	 */

    public function create_youtube()
    {

        $root = GalleryTree::allRoot()->first();
        $root->findChildren(999);

        $data['categorias'] = $root->getChildren();
        $data['txt_botImagen'] = 'Subir Archivo';
        $data['titulo'] = "Crear Archivo";
        $data['descargaFecha'] = date('Y-m-d');
        $data['link'] = base_url("admin/gallery/insert_youtube");
        $data['txt_boton'] = "crear";
        $data['descargaEnabled'] = 'checked="checked"';
        $data['imagenUrl'] = '';
        $data['imagenOrig'] = '';
        $data['archivoUrl'] = '<a href="#"></a>';
        $data['descargaArchivo'] = '';
        $data['descargaEnlace'] = '';
        $data['descargaCategoriaId'] = '';
        $data['descargaId'] = '';
        $data['categoriaTipoId'] = '';
        $data['nuevo'] = 'nuevo';
        $data['cropDimensions'] = $this->General->getCropImage(8);
        $data['descargaImagenCoord'] = '';

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

        foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
            $traducciones[$idioma['idiomaDiminutivo']]->descargaNombre = '';
        }

        $data['traducciones'] = $traducciones;

        $this->load->view('admin/gallery/video_view', $data);
    }

	/*
	 * Main method to modify download files (gallery)
	 */
	public function edit($id)
	{

		$data = $this->Descargas->getDownload($id);
		
        $root = GalleryTree::allRoot()->first();
        $root->findChildren(999);
		
        $data['categorias'] = $root->getChildren();
		$data['txt_botImagen'] = 'Subir Archivo';
		$data['titulo'] = "Modificar Archivo";
		$data['link'] = base_url("admin/gallery/update");
		$data['txt_boton'] = "modificar";
		$data['descargaEnabled'] = 'checked="checked"';
		$data['archivoUrl'] = '';
		$data['categoriaTipoId'] = 1;
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
        $data['cropDimensions'] = $this->General->getCropImage(8);
        $data['descargaImagenCoord'] = urlencode($data['descargaImagenCoord']);

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
			$descargaTraduccion = $this->Descargas->getDescargaTranslation($idioma['idiomaDiminutivo'], $id);
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$descargaNombre = '';
			
			if(count($descargaTraduccion) > 0){
				$descargaNombre = $descargaTraduccion->descargaNombre;
			}
			
			$traducciones[$idioma['idiomaDiminutivo']]->descargaNombre = $descargaNombre;
		}
		
		$data['traducciones'] = $traducciones;

		//Remove the ?123456 (cache param)
		$ext = preg_replace('/\?+\d{0,}/', '', $data['descargaArchivo']);

		//Get the extension
		$extension = pathinfo('./assets/public/images/downloads/' . $data['descargaId'] . '_admin.' . $ext, PATHINFO_EXTENSION);
		if(!$extension) {
			$extension = pathinfo('./assets/public/files/downloads/' . $ext, PATHINFO_EXTENSION);
		}

        if($extension && strlen($extension) < 6) {

            if(!$extension) {
                $extension = $data['descargaArchivo'];
            }

            switch(mb_strtolower($extension)) {

                //Images
                case 'jpg':
                case 'gif':
                case 'png':
                case 'jpeg':

                    $data['imagenUrl'] = '<img src="' . base_url() . 'assets/public/images/downloads/img_' . $data['descargaId'] . '_admin.' . $data['descargaArchivo'] . '?' . time() . '" />';
                    $data['imagenOrig'] = base_url() . 'assets/public/images/downloads/img_' . $data['descargaId'] . '_orig.' . $data['descargaArchivo'];
                    $this->load->view('admin/gallery/image_view', $data);
                    break;

                //Audio
                case 'mp3':
                case 'ogg':
                case 'mwa':
                case 'wav':
	                $data['imagenUrl'] = '';
	                $data['imagenOrig'] = '';
                    $data['archivoUrl'] = '<audio src="' . base_url() . 'assets/public/files/downloads/' . $data['descargaArchivo'] . '" controls ></audio>';
	                $this->load->view('admin/gallery/file_view', $data);
                    break;

                //Video
                case 'avi':
                case 'wmv':
                case 'mov':
	                $data['imagenUrl'] = '';
	                $data['imagenOrig'] = '';
                    $data['archivoUrl'] = '<video src="' . base_url() . 'assets/public/files/downloads/' . $data['descargaArchivo'] . '" controls ></video>';
	                $this->load->view('admin/gallery/file_view', $data);
                    break;

                //Others
                default:
                    $data['imagenUrl'] = '';
                    $data['imagenOrig'] = '';
                    $data['archivoUrl'] = '<a href="' . base_url() . 'assets/public/files/downloads/' . $data['descargaArchivo'] . '">' . $data['descargaArchivo'] . '</a>';
                    $this->load->view('admin/gallery/file_view', $data);

            }
        }

        //Probably youtube video
        else {
            $this->load->view('admin/gallery/video_view', $data);
        }



	}

    public function insert_youtube()
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Descargas->addDownload($this->cms_general);
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));
    }
	
	public function update()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Descargas->updateDownload($this->cms_general);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el archivo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function delete($id)
	{

		$data = $this->Descargas->getDownload($id);

		//Is a file
		if(!$extension = substr(strrchr($data['descargaArchivo'], '.'), 1)) {
			$extension = $data['descargaArchivo'];//Is an image
		}

		//Delete the file
		switch(mb_strtolower($extension)) {

			//Images
			case 'jpg':
			case 'gif':
			case 'png':
			case 'jpeg':

				//Get the images
				$images = $this->Modulo->getImages(8);

				foreach ($images as $img) {
					if (file_exists('assets/public/images/downloads/img_' . $id . $img->imagenSufijo . '.' . $data['descargaArchivo']))
						unlink('assets/public/images/downloads/img_' . $id . $img->imagenSufijo .  '.' . $data['descargaArchivo']);
				}

				//image
				if (file_exists('assets/public/images/downloads/img_' . $id . '.' . $data['descargaArchivo']))
					unlink('assets/public/images/downloads/img_' . $id . '.' . $data['descargaArchivo']);

				//Admin image
				if (file_exists('assets/public/images/downloads/img_' . $id . '_admin.' . $data['descargaArchivo']))
					unlink('assets/public/images/downloads/img_' . $id . '_admin.' . $data['descargaArchivo']);

				//Original image
				if (file_exists('assets/public/images/downloads/img_' . $id . '_orig.' . $data['descargaArchivo']))
					unlink('assets/public/images/downloads/img_' . $id . '_orig.' . $data['descargaArchivo']);

				//Search image
				if (file_exists('assets/public/images/downloads/img_' . $id . '_search.' . $data['descargaArchivo']))
					unlink('assets/public/images/downloads/img_' . $id . '_search.' . $data['descargaArchivo']);

				break;

			//Videos, audios, files, etc
			default:
				if (file_exists('assets/public/files/downloads/' . $data['descargaArchivo']))
					unlink('assets/public/files/downloads/' . $data['descargaArchivo']);

		}

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Descargas->deleteDownload($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el archivo!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Descargas->reorderDownloads($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar las descargas!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	
}
