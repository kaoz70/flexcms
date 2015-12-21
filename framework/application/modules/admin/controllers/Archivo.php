<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archivo extends MY_Controller {
	
	function __construct(){
		parent::__construct();

        $this->load->model('admin/module_model', 'Modulo');
		$this->load->library('Seguridad');
		$this->seguridad->init();
		
	}
	
    public function producto()
    {

        $imagedata = $this->input->post('imagedata');
        $filedata = $this->input->post('filedata');

        $prodId = $this->uri->segment(4, 0);

        $folderpath = "assets/public/files/catalog/prod_" . $prodId;
        $oldFilePath =  $filedata['temp_path'];
        $newFilepath = $folderpath . '/' . $imagedata['name'];

        $response = new stdClass();
        $response->error_code = 0;

        $fileName = $imagedata['name'];

        if(!file_exists($folderpath)) {
            if(!mkdir($folderpath, 755)) {
                $response->error_code = 1;
                $response->error_message = 'no se pudo crear la carpeta';
                $this->load->view('admin/request/json', array('return' => $response));
            }
        }

        $this->moveFile($oldFilePath, $newFilepath, $fileName);

    }

    public function productoGaleria($productoid, $fieldId)
    {

	    $imagedata = $this->input->post('imagedata');
	    $filedata = $this->input->post('filedata');

	    $file_id = $this->uri->segment(6, 0);

	    $name = preg_replace("/_|-|\\+/", ' ', $filedata['filename']);
	    $extension = substr(strrchr($filedata['filename'],'.'),1);
	    $name = str_replace('.' . $extension, '', $name);

	    $folderpath = "assets/public/files/catalog";
	    $oldFilePath =  $filedata['temp_path'];
	    $fileName = time() . '_' . $imagedata['name'];
	    $newFilepath = $folderpath . '/' . $fileName;

	    if(!$file_id) {

		    $this->db->insert('producto_archivos', array(
			    'productoArchivoExtension' => $fileName,
			    'productoId' => $productoid,
			    'productoCampoId' => $fieldId,
			    'productoArchivoNombre' => $name,
		    ));

		    $file_id = $this->db->insert_id();

		    //Add the name to the translations
		    $idiomas = $this->db->get('idioma')->result();
		    foreach ($idiomas as $idioma) {
			    $this->db->insert($idioma->idiomaDiminutivo . '_producto_archivos', array(
				    'productoDescargaId' => $file_id,
			    ));
		    }

	    }

	    $this->moveFile($oldFilePath, $newFilepath, $fileName, $file_id);


        /*$imageId = $this->uri->segment(6, 0);
        $imagedata = (object)$this->input->post('imagedata');
        $filedata = $this->input->post('filedata');
        $imagedata->path = 'assets/public/images/catalog/gal_';
        $time = time();

        if($imageId){
            $imagedata->id = $imageId;
            $images = $this->Modulo->getImages(6);
        }

        //Insert image to DB, because this is from multiple upload method
        else {

            $name = preg_replace("/_|-|\\+/", ' ', $imagedata->name);
            $extension = substr(strrchr($imagedata->name,'.'),1);
            $name = str_replace('.' . $extension, '', $name);

            $this->db->insert('producto_archivos', array(
                'productoArchivoExtension' => $extension . '?' . $time,
                'productoArchivoCoord' => json_encode(array(
                    'top' => 0,
                    'left' => 0,
                    'width' => $imagedata->width,
                    'height' => $imagedata->height,
                    'scale' => 0,
                )),
                'productoId' => $productoid,
                'productoCampoId' => $fieldId,
                'productoArchivoNombre' => $name,
            ));
            $image_id = $this->db->insert_id();

            //Create the translation fields
            $idiomas = $this->Idiomas->getLanguages();
            foreach ($idiomas as $lang) {
                $this->db->insert($lang['idiomaDiminutivo'] . '_producto_archivos', array(
                    'productoDescargaId' => $image_id,
                ));
            }

            $imagedata->id = $image_id;
            $imagedata->image_id = $image_id;

            $images = array();

            $bannerData = new stdClass();
            $bannerData->imagenAlto = $imagedata->cropHeight;
            $bannerData->imagenAncho = $imagedata->cropWidth;
            $bannerData->imagenSufijo = '';
            $bannerData->imagenCrop = 1;

            $images[] = $bannerData;

            $images = array_merge($images, $this->Modulo->getImages(6));

        }

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata, $time);*/

    }

	public function galeria()
	{

		$imagedata = $this->input->post('imagedata');
		$filedata = $this->input->post('filedata');

		$cat_id = $this->uri->segment(4, 0);
		$file_id = $this->uri->segment(5, 0);

		$name = preg_replace("/_|-|\\+/", ' ', $filedata['filename']);
		$extension = substr(strrchr($filedata['filename'],'.'),1);
		$name = str_replace('.' . $extension, '', $name);

		$folderpath = "assets/public/files/downloads";
		$oldFilePath =  $filedata['temp_path'];
		$fileName = time() . '_' . $imagedata['name'];
		$newFilepath = $folderpath . '/' . $fileName;

		if(!$file_id) {

			$this->db->insert('descargas', array(
				'descargaArchivo' => $fileName,
				'descargaCategoriaId' => $cat_id,
				'descargaFecha' => date("Y-m-d H:i:s"),
			));

			$file_id = $this->db->insert_id();

			//Add the name to the translations
			$idiomas = $this->db->get('idioma')->result();
			foreach ($idiomas as $idioma) {
				$this->db->insert($idioma->idiomaDiminutivo . '_descargas', array(
					'descargaId' => $file_id,
					'descargaNombre' => $name,
				));
			}

		}

        $this->moveFile($oldFilePath, $newFilepath, $fileName, $file_id);
		
	}

    public function publicidad()
    {

        $imagedata = $this->input->post('imagedata');
        $filedata = $this->input->post('filedata');

        $folderpath = "assets/public/files/publicidad";
        $oldFilePath =  $filedata['temp_path'];
        $fileName = time() . '_' . $imagedata['name'];
        $newFilepath = $folderpath . '/' . $fileName;

        $this->moveFile($oldFilePath, $newFilepath, $fileName);

    }

	/**
	 * Dummy method. This function doesn't do anything other than return the temp path
	 * @param $list_id
	 */
	public function mailchimp_batch_import($list_id)
	{

		$post = $this->input->post('filedata');

		$response = new stdClass();
		$response->message = 'success';
		$response->path = $post['temp_path'];
		$response->name = $post['temp_path'];

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);
	}

    private function moveFile($oldFilePath, $newFilepath, $fileName, $id = NULL) {

	    $response = new stdClass();
        $response->error_code = 0;
	    $response->message = 'error';
	    $response->image_id = $id;

	    if(file_exists($oldFilePath))
        {
            if(rename($oldFilePath, $newFilepath))
            {

                // Read and write for owner, read for everybody else
                // We set this just in case (in some cases it was creating the file as 600)
                chmod($newFilepath, 0644);

                $response->message = 'success';
                $response->path = $newFilepath;
                $response->name = $fileName;

            }
            else
            {
                $response->error_code = 1;
                $response->error_message = 'No se pudo mover el archivo!';
            }
        }
        else
        {
            $response->error_code = 1;
            $response->error_message = 'No existe el archivo: ' . $oldFilePath;
        }

	    $data['return'] = $response;
	    $this->load->view('admin/request/json', $data);

    }

}
