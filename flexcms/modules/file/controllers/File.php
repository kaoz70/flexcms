<?php

namespace gallery;
$_ns = __NAMESPACE__;

use GalleryTree;
use stdClass;
use Exception;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends \MY_Controller implements \AdminInterface {
	 
	function __construct(){
		parent::__construct();
		$this->load->helper('text');
		$this->load->library('image_lib');
		$this->load->model('descargas_model', 'Descargas');
		$this->load->model('idiomas_model', 'Idioma');
        $this->load->model('admin/general_model', 'General');
		$this->load->model('admin/module_model', 'Modulo');
		
        $this->load->library('CMS_General');

	}

	public function index(){}
	
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
		$data['link'] = base_url("admin/gallery/file/update/" . $id);
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

	public function insert(){}

	public function create(){}

	public function update($id)
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
