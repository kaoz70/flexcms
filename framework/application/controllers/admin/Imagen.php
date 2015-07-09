<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imagen extends CI_Controller {

    //Final image quality: 1% - 100%, recommended 80%
    var $quality = 80;

	function __construct(){
		parent::__construct();

        $this->load->model('admin/module_model', 'Modulo');
        $this->load->model('idiomas_model', 'Idiomas');

        $this->load->library('image_moo');
		$this->load->library('Seguridad');
		$this->seguridad->init();

	}

	public function enlace()
	{

		$id = $this->uri->segment(4, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
		$imagedata->id = $id;
		$imagedata->path = 'assets/public/images/enlaces/enlace_';

        $images = $this->Modulo->getImages(1);

        $manipulationdata = $this->createManipulationData($images);

		$this->imageManipulation($imagedata, $filedata, $manipulationdata);

	}

    public function servicio()
    {

        $id = $this->uri->segment(4, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->id = $id;
        $imagedata->path = 'assets/public/images/servicios/servicio_';

        $images = $this->Modulo->getImages(10);

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

	public function servicioGaleria()
	{

		$servicioId = $this->uri->segment(4, 0);
		$imageid = $this->uri->segment(5, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
		$filedata = $this->input->post('filedata');
		$imagedata->path = 'assets/public/images/servicios/gal_';
		$time = time();

		if($imageid){
			$imagedata->id = $servicioId . '_' . $imageid;
			$images = $this->Modulo->getImages(16);
		}

		//Insert image to DB, because this is from multiple upload method
		else {

			$name = preg_replace("/_|-|\\+/", ' ', $imagedata->name);
			$extension = substr(strrchr($imagedata->name,'.'),1);
			$name = str_replace('.' . $extension, '', $name);

			$position = $this->db
				->select_max('posicion')
				->where('servicio_id', $servicioId)
				->get('servicios_imagenes')->row();

			$this->db->insert('servicios_imagenes', array(
				'extension' => $extension . '?' . $time,
				'coords' => json_encode(array(
					'top' => 0,
					'left' => 0,
					'width' => $imagedata->width,
					'height' => $imagedata->height,
					'scale' => 0,
				)),
				'servicio_id' => $servicioId,
				'nombre' => $name,
				'posicion' => $position->posicion + 1,
			));
			$image_id = $this->db->insert_id();

			$imagedata->id = $servicioId . '_' . $image_id;
			$imagedata->image_id = $image_id;

			$images = array();

			$bannerData = new stdClass();
			$bannerData->imagenAlto = $imagedata->cropHeight;
			$bannerData->imagenAncho = $imagedata->cropWidth;
			$bannerData->imagenSufijo = '';
			$bannerData->imagenCrop = 1;

			$images[] = $bannerData;

			$images = array_merge($images, $this->Modulo->getImages(16));

		}

		$manipulationdata = $this->createManipulationData($images);

		$this->imageManipulation($imagedata, $filedata, $manipulationdata, $time);

	}

	public function publicacion()
	{

		$id = $this->uri->segment(4, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
		$imagedata->id = $id;
		$imagedata->path = 'assets/public/images/noticias/noticia_';

        $images = $this->Modulo->getImages(2);

        $manipulationdata = $this->createManipulationData($images);

		$this->imageManipulation($imagedata, $filedata, $manipulationdata);

	}

    public function publicacionGaleria()
    {

        $id = $this->uri->segment(4, 0);
        $imageid = $this->uri->segment(5, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->path = 'assets/public/images/noticias/noticia_';

	    if($imageid){
		    $imagedata->id = $id . '_' . $imageid;
		    $images = $this->Modulo->getImages(15);
	    }

	    //Insert image to DB, because this is from multiple upload method
	    else {

		    $name = preg_replace("/_|-|\\+/", ' ', $imagedata->name);
		    $extension = substr(strrchr($imagedata->name,'.'),1);
		    $name = str_replace('.' . $extension, '', $name);

		    $this->db->insert('publicaciones_imagenes', array(
			    'publicacionImagenExtension' => $extension,
			    'publicacionImagenCoord' => json_encode(array(
				    'top' => 0,
				    'left' => 0,
				    'width' => $imagedata->width,
				    'height' => $imagedata->height,
				    'scale' => 0,
			    )),
			    'publicacionId' => $id,
			    'publicacionImagenNombre' => $name,
		    ));
		    $image_id = $this->db->insert_id();
		    $imagedata->id = $id . '_' . $image_id;
		    $imagedata->image_id = $image_id;

		    $images = array();

		    $bannerData = new stdClass();
		    $bannerData->imagenAlto = $imagedata->cropHeight;
		    $bannerData->imagenAncho = $imagedata->cropWidth;
		    $bannerData->imagenSufijo = '';
		    $bannerData->imagenCrop = 1;

		    $images[] = $bannerData;

		    $images = array_merge($images, $this->Modulo->getImages(15));

	    }

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

	public function banner()
	{

		$bannerid = $this->uri->segment(4, 0);
		$imageid = $this->uri->segment(5, 0);
		$filedata = $this->input->post('filedata');
		$imagedata = json_decode($this->input->post('imagedata'));
		$imagedata->path = 'assets/public/images/banners/banner_';
		$time = time();

		if($imageid){
			$imagedata->id = $bannerid . '_' . $imageid;
		}

		//Insert image to DB, because this is from multiple upload method
		else {

			$name = preg_replace("/_|-|\\+/", ' ', $imagedata->name);
			$extension = substr(strrchr($imagedata->name,'.'),1);
			$name = str_replace('.' . $extension, '', $name);

			$this->db->insert('banner_images', array(
				'bannerImageExtension' => $extension . '?' . $time,
				'bannerImagenCoord' => json_encode(array(
					'top' => 0,
					'left' => 0,
					'width' => $imagedata->width,
					'height' => $imagedata->height,
					'scale' => 0,
				)),
				'bannerId' => $bannerid,
				'bannerImageName' => $name,
				'bannerImageTemporal' => 0,
			));
			$image_id = $this->db->insert_id();
			$imagedata->id = $bannerid . '_' . $image_id;
			$imagedata->image_id = $image_id;
		}

		$images = array();

		$bannerData = new stdClass();
		$bannerData->imagenAlto = $imagedata->cropHeight;
		$bannerData->imagenAncho = $imagedata->cropWidth;
		$bannerData->imagenSufijo = '';
		$bannerData->imagenCrop = 1;

		$images[] = $bannerData;

		$images = array_merge($images, $this->Modulo->getImages(3));

		$manipulationdata = $this->createManipulationData($images);

		//Reorder from largest to smallest
		usort($manipulationdata, function($a, $b)
		{
			return $a->width < $b->width;
		});

		$this->imageManipulation($imagedata, $filedata, $manipulationdata, $time);

	}

	public function producto()
	{

		$id = $this->uri->segment(4, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
		$imagedata->id = $id;
		$imagedata->path = 'assets/public/images/catalog/prod_';

        $images = $this->Modulo->getImages(5);

        $manipulationdata = $this->createManipulationData($images);

		$this->imageManipulation($imagedata, $filedata, $manipulationdata);

	}

	public function productoGaleria()
	{

		$productoid = $this->uri->segment(4, 0);
		$imageid = $this->uri->segment(5, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
		$imagedata->path = 'assets/public/images/catalog/gal_';
		$time = time();

		if($imageid){
			$imagedata->id = $productoid . '_' . $imageid;
			$images = $this->Modulo->getImages(6);
		}

		//Insert image to DB, because this is from multiple upload method
		else {

			$name = preg_replace("/_|-|\\+/", ' ', $imagedata->name);
			$extension = substr(strrchr($imagedata->name,'.'),1);
			$name = str_replace('.' . $extension, '', $name);

			$this->db->insert('producto_imagenes', array(
				'productoImagen' => $extension . '?' . $time,
				'productoImagenCoord' => json_encode(array(
					'top' => 0,
					'left' => 0,
					'width' => $imagedata->width,
					'height' => $imagedata->height,
					'scale' => 0,
				)),
				'productoId' => $productoid,
				'productoImagenNombre' => $name,
			));
			$image_id = $this->db->insert_id();

			//Create the translation fields
			$idiomas = $this->Idiomas->getLanguages();
			foreach ($idiomas as $lang) {
				$this->db->insert($lang['idiomaDiminutivo'] . '_producto_imagenes', array(
					'productoImagenId' => $image_id,
				));
			}

			$imagedata->id = $productoid . '_' . $image_id;
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

		$this->imageManipulation($imagedata, $filedata, $manipulationdata, $time);

	}

	public function catalogoCategoria()
	{

		$id = $this->uri->segment(4, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
		$imagedata->id = $id;
		$imagedata->path = 'assets/public/images/catalog/cat_';

        $images = $this->Modulo->getImages(7);

        $manipulationdata = $this->createManipulationData($images);

		$this->imageManipulation($imagedata, $filedata, $manipulationdata);

	}

	public function galeria()
	{

		$cat_id = $this->uri->segment(4, 0);
		$image_id = $this->uri->segment(5, 0);
		$imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->path = 'assets/public/images/downloads/img_';
		$time = time();

		if(isset($imagedata->edit) && $imagedata->edit && $image_id) {
			$imagedata->id = $image_id;
			$images = $this->Modulo->getImages(8);
		}

		//Insert image to DB, because this is from multiple upload method
		else {

			$name = preg_replace("/_|-|\\+/", ' ', $imagedata->name);
			$extension = substr(strrchr($imagedata->name,'.'),1);
			$name = str_replace('.' . $extension, '', $name);

			$this->db->insert('descargas', array(
				'descargaArchivo' => $extension . '?' . $time,
				'descargaImagenCoord' => json_encode(array(
					'top' => 0,
					'left' => 0,
					'width' => $imagedata->width,
					'height' => $imagedata->height,
					'scale' => 0,
				)),
				'descargaCategoriaId' => $cat_id,
				'descargaFecha' => date("Y-m-d H:i:s"),
			));
			$image_id = $this->db->insert_id();
			$imagedata->id = $image_id;
			$imagedata->image_id = $image_id;

			//Add the name to the translations
			$idiomas = $this->Idiomas->getLanguages();
			foreach ($idiomas as $idioma) {
				$this->db->insert($idioma['idiomaDiminutivo'] . '_descargas', array(
					'descargaId' => $image_id,
					'descargaNombre' => $name,
				));
			}

			$images = array();

			$bannerData = new stdClass();
			$bannerData->imagenAlto = $imagedata->cropHeight;
			$bannerData->imagenAncho = $imagedata->cropWidth;
			$bannerData->imagenSufijo = '';
			$bannerData->imagenCrop = 1;

			$images[] = $bannerData;

			$images = array_merge($images, $this->Modulo->getImages(8));

		}

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata, $time);

	}

	public function theme($folder)
	{

		$imagedata = json_decode($this->input->post('imagedata'));
		$filedata = $this->input->post('filedata');
		$imagedata->path = 'themes/' . $folder . '/images/fondos/';
		$tempPath = $filedata['temp_path'];

		$response = new stdClass();
		$response->code = -1;
		$response->name = $imagedata->name;
		$response->image_id = $imagedata->name;
		$response->modify_link = FALSE;

		if(file_exists($tempPath))
		{

			$theme = json_decode(file_get_contents('themes/' . $folder . '/theme.json'));
			$response->extension = pathinfo($imagedata->path . $imagedata->name, PATHINFO_EXTENSION);
			$new_path = $imagedata->path . $imagedata->name;

			if(rename($tempPath, $new_path))
			{

				$time = time();

				// Read and write for owner, read for everybody else
				// We set this just in case (in some cases it was creating the file as 600)
				chmod($new_path, 0644);

				$fondos = array();
				foreach (new \FilesystemIterator('./themes/' . $folder . '/images/fondos/') as $file) {
					$fondos[] = $file->getFilename();
				}
				$theme->config->backgrounds = $fondos;
				file_put_contents('themes/' . $folder . '/theme.json', json_encode($theme));

				//Optimize image
				$this->image_moo
					->load($new_path)
					->set_jpeg_quality($this->quality)
					->save($new_path, true);

				//Generate the CSS
				$CSS = $this->load->view('admin/themes/background_css_view', array(
					'backgrounds' => $theme->config->backgrounds,
					'theme' => $folder,
				), TRUE);
				//Save the CSS to the file
				file_put_contents('themes/' . $folder . '/css/generated/background.css', $CSS);

				$response->path = $new_path . '?' . $time;
				$response->code = 1;
				$response->error_message = $this->image_moo->display_errors();
				$response->message = 'success';

			}

			else
			{
				$response->message = 'No se pudo mover el archivo subido';
			}

		}

		else {
			$response->message = 'El archivo subido no existe';
		}

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);

	}

    public function galeriaCategoria(){

        $id = $this->uri->segment(4, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->id = $id;
        $imagedata->path = 'assets/public/images/downloads/cat_';

        $images = $this->Modulo->getImages(12);

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

    public function mapas()
    {

        $id = $this->uri->segment(4, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->id = $id;
        $imagedata->path = 'assets/public/images/mapas/mapa_';

        $images = $this->Modulo->getImages(4);

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

    public function mapaUbicacion()
    {

        $id = $this->uri->segment(4, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->id = $id;
        $imagedata->path = 'assets/public/images/mapas/mapa_ubicacion_';

        $images = $this->Modulo->getImages(11);

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

    public function usuario()
    {

        $id = $this->uri->segment(4, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->id = $id;
        $imagedata->path = 'assets/public/images/usuarios/usuario_';

        $images = $this->Modulo->getImages(13);

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

    public function contactoDireccion()
    {

        $id = $this->uri->segment(4, 0);
        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->id = $id;
        $imagedata->path = 'assets/public/images/contacto/dir_';

        $images = $this->Modulo->getImages(14);

        $manipulationdata = $this->createManipulationData($images);

        $this->imageManipulation($imagedata, $filedata, $manipulationdata);

    }

    public function contenido()
    {

        $imagedata = json_decode($this->input->post('imagedata'));
        $filedata = $this->input->post('filedata');
        $imagedata->path = 'assets/public/images/ui/';
        $tempPath = $filedata['temp_path'];

        $response = new stdClass();
        $response->code = -1;

        if(file_exists($tempPath))
        {

            $biggestpath = $imagedata->path . $imagedata->name;

            if(rename($tempPath, $biggestpath))
            {

                // Read and write for owner, read for everybody else
                // We set this just in case (in some cases it was creating the file as 600)
                chmod($biggestpath, 0644);

                $response->path = base_url() . $imagedata->path . $imagedata->name . '?' . time();
                $response->code = 1;
                $response->message = 'success';
            }

            else
            {
                $response->message = 'No se pudo mover el archivo subido';
            }

        }

        else {
            $response->message = 'El archivo subido no existe';
        }

	    $data['return'] = $response;
	    $this->load->view('admin/request/json', $data);

    }

	/*
	 * FUNCIONES GENERALES
	 */

    private function createManipulationData($images){
        $manipulationdata = array();

        /*
         * Create the images from the database
         */
        foreach ($images as $conf){

            $image = new stdClass();
            $image->sufix = $conf->imagenSufijo;
            $image->width = $conf->imagenAncho;
            $image->height = $conf->imagenAlto;
            $image->crop = (bool)$conf->imagenCrop;

            $manipulationdata[] = $image;

        }

        /*
         * Create the image to show in the admin window
         */
        $admin_image = new stdClass();
        $admin_image->sufix = '_admin';
        $admin_image->width = 600;
        $admin_image->height = 600;
        $admin_image->crop = FALSE;

        $manipulationdata[] = $admin_image;

        /*
         * Create the search image to show in the searchbox results
         */
        $search_image = new stdClass();
        $search_image->sufix = '_search';
        $search_image->width = 50;
        $search_image->height = 50;
        $search_image->crop = FALSE;

        $manipulationdata[] = $search_image;

        return $manipulationdata;
    }

	private function imageManipulation($imagedata, $filedata, $manipulationdata, $time = '')
	{

        if($time === '')
            $time = time();

        $response = new stdClass();
        $response->code = -1;
		$response->modify_link = TRUE;

		if(isset($imagedata->image_id))
            $response->image_id = $imagedata->image_id;

        if(isset($imagedata->edit) && $imagedata->edit){
            $origPath = $imagedata->path . $imagedata->id . '_orig.' . preg_replace('/\?+\d{0,}/', '', $imagedata->name);
            $this->createImages($manipulationdata, $imagedata, $origPath);

            $response->path = $imagedata->path . $imagedata->id . '_admin.' . preg_replace('/\?+\d{0,}/', '', $imagedata->name) . '?' . $time;
            $response->code = 1;
            $response->message = 'success';

        } else {

            if($imagedata->name != '')
            {

                $tempPath = $filedata['temp_path'];
                $origPath = $imagedata->path . $imagedata->id . '_orig.' . pathinfo($imagedata->name, PATHINFO_EXTENSION);

                if(file_exists($tempPath))
                {

                    if(rename($tempPath, $origPath))
                    {

                        // Read and write for owner, read for everybody else
                        // We set this just in case (in some cases it was creating the file as 600)
                        chmod($origPath, 0644);

                        $this->createImages($manipulationdata, $imagedata, $origPath);
                        $response->path = $imagedata->path . $imagedata->id . '_admin.' . pathinfo($imagedata->name, PATHINFO_EXTENSION) . '?' . $time;
                        $response->code = 1;
                        $response->message = 'success';

                    }
                    else {
                        $response->code  = 102;
                        $response->message = 'No se puede mover: ' . $tempPath . ' a: ' . $origPath;
                    }

                }
                else {
                    $response->code  = 101;
                    $response->message = 'El archivo no existe: ' . $tempPath;
                }

            } else {
                $response->code  = 103;
                $response->message = 'No se puede obtener el nombre del archivo';
            }
        }

		$data['return'] = $response;
		$this->load->view('admin/request/json', $data);

	}

    private function createImages($manipulationdata, $imagedata, $origPath){

        foreach ($manipulationdata as $key => $data) {

            //Revomemos la cadena al final de la extension del archivo (se lo usa para el cache en el backend)
            if (isset($imagedata->edit) && $imagedata->edit) {
                $path = $imagedata->path . $imagedata->id . $data->sufix . '.' . preg_replace('/\?+\d{0,}/', '', $imagedata->name);
            }

            //Si es imagen nueva creamos el archivo (duplicamos)
            else {
                $path = $imagedata->path . $imagedata->id . $data->sufix . '.' . pathinfo($imagedata->name, PATHINFO_EXTENSION);
                $this->moverImagen($origPath, $path);
            }

            if ($data->crop) {
                //Escalamos la imagen con los datos del crop window
                $this->redimensionarImagen($origPath, $path, $imagedata->width, $imagedata->height);
                //Cortamos la imagen
                $this->cortarImagen($path, $imagedata->top, $imagedata->left, $imagedata->cropWidth, $imagedata->cropHeight);
            }
            else {

                if(isset($imagedata->edit) && $imagedata->edit) {
                    $path = $imagedata->path . $imagedata->id . $data->sufix . '.' . preg_replace('/\?+\d{0,}/', '', $imagedata->name);
                    $oldPath = $imagedata->path . $imagedata->id . $manipulationdata[0]->sufix . '.' . preg_replace('/\?+\d{0,}/', '', $imagedata->name);
                }
                else {
                    $path = $imagedata->path . $imagedata->id . $data->sufix . '.' . pathinfo($imagedata->name, PATHINFO_EXTENSION);
                    $oldPath = $imagedata->path . $imagedata->id . $manipulationdata[0]->sufix . '.' . pathinfo($imagedata->name, PATHINFO_EXTENSION);
                }

                $this->redimensionarImagenProporcional($oldPath, $path, $data->width, $data->height);

            }

        }
    }

	private function redimensionarImagen($path, $newPath, $width, $height)
	{

        $this->image_moo
            ->load($path)
            ->set_jpeg_quality($this->quality)
            ->stretch((int)$width,(int)$height)
            ->save($newPath, true);

        return $this->image_moo->display_errors();

	}

    private function redimensionarImagenProporcional($path, $newPath, $width, $height)
    {

        $this->image_moo
            ->load($path)
            ->set_jpeg_quality($this->quality)
            ->resize((int)$width,(int)$height)
            ->save($newPath, true);

        return $this->image_moo->display_errors();

    }

	private function cortarImagen($path, $top, $left, $width, $height)
	{

        $coords = array(
            'x1' => $left,
            'x2' =>  $width + $left,
            'y1' =>  $top,
            'y2' =>  $height + $top
        );

        $this->image_moo
            ->load($path)
            ->set_background_colour("#FFF")
            ->set_jpeg_quality($this->quality)
            ->crop($coords['x1'], $coords['y1'], $coords['x2'], $coords['y2'])
            ->save($path, true);

		$data['return'] = $this->image_moo->display_errors();
		$this->load->view('admin/request/html', $data);

	}

	private function moverImagen($from, $to, $eliminar = false)
	{
		if (copy($from, $to))
		{
			if($eliminar)
  				unlink($from);
		}
	}


}
