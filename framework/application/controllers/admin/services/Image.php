<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:14 PM
 */

namespace services;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Image extends \Service implements \AdminInterface {

	public function index()
	{

		$id = $this->uri->segment(5);
		$data['items'] = $this->Servicios->getImages($id);

		$data['url_rel'] = base_url('admin/services/image/index/'.$id);
		$data['url_sort'] = base_url('admin/services/image/reorder/'.$id);
		$data['url_modificar'] = base_url('admin/services/image/edit/');
		$data['url_eliminar'] = base_url('admin/services/image/delete/');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'servicio_images';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'nombre';

		$data['txt_titulo'] = 'Imágenes';

		$data['url_path'] =  base_url() . 'assets/public/images/servicios/gal_' . $id . '_';
		$data['url_upload'] =  base_url() . 'admin/imagen/servicioGaleria/' . $id;
		$data['method'] =  'servicioGaleria/' . $id;

		$dimensiones = $this->General->getCropImage(16);
		$data['width'] = $dimensiones->imagenAncho;
		$data['height'] = $dimensiones->imagenAlto;

		$data['nivel'] = 'nivel5';
		$data['list_id'] = 'servicio_galeria_images';

		$data['idx_id'] = 'id';
		$data['idx_nombre'] = 'nombre';
		$data['idx_extension'] = 'extension';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listadoGaleria_view', $data);
	}

	public function edit($imagenId)
	{
		$image = $this->Servicios->getImage($imagenId);

		$data['id'] = $image->id;
		$data['servicio_id'] = $image->servicio_id;
		$data['titulo'] = 'Modificar Imágen';
		$data['nombre'] = $image->nombre;
		$data['txt_boton'] = 'Modificar Imágen';
		$data['productoImagen'] = $image->extension;
		$data['nuevo'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(16);
		$data['coords'] = urlencode($image->coords);

		if($image->extension != '')
		{
			$data['txt_botImagen'] = 'Cambiar Imágen';
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $imagenId . '_admin.' . $image->extension . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/servicios/gal_' . $image->servicio_id . '_' . $imagenId . '_orig.' . $image->extension;
		}
		else
		{
			$data['txt_botImagen'] = 'Subir Imagen';
			$data['imagen'] = '';
			$data['imagenOrig'] = '';
		}

		$data['productoImagenId'] = $imagenId;
		$data['link'] = base_url('admin/services/image/update/' . $image->id);

		$this->load->view('admin/servicios/imagen_view', $data);
	}

	public function reorder($id)
	{
		$this->Servicios->reorderImages();
	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Servicios->updateImage($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la im&aacute;gen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			//Get the images
			$images = $this->Modulo->getImages(16);
			$this->Servicios->deleteImage($id, $images);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la im&aacute;gen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

}