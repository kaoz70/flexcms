<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:07 PM
 */

namespace publications;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Image extends \Publication implements \AdminInterface {

	public function index()
	{

		$id = $this->uri->segment(5);
		$data['items'] = $this->Noticias->getAllImages($id);

		$data['url_rel'] = base_url('admin/publications/image/index');
		$data['url_sort'] = base_url('admin/publications/image/reorder/' . $id);
		$data['url_modificar'] = base_url('admin/publications/image/edit');
		$data['url_eliminar'] = base_url('admin/publications/image/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'publicaciones_imagenes';

		$data['idx_id'] = 'publicacionImagenId';
		$data['idx_nombre'] = 'publicacionImagenNombre';

		$data['txt_titulo'] = 'Galer&iacute;a';

		$data['url_path'] =  base_url() . 'assets/public/images/noticias/noticia_' . $id . '_';
		$data['url_upload'] =  base_url() . 'admin/imagen/publicacionGaleria/' . $id;
		$data['method'] =  'publicacionGaleria/' . $id;

		$dimensiones = $this->General->getCropImage(15);
		$data['width'] = $dimensiones->imagenAncho;
		$data['height'] = $dimensiones->imagenAlto;

		$data['nivel'] = 'nivel4';
		$data['list_id'] = 'noticias_images';

		$data['idx_id'] = 'publicacionImagenId';
		$data['idx_nombre'] = 'publicacionImagenNombre';
		$data['idx_extension'] = 'publicacionImagenExtension';

		/*
		 * Menu
		 */
		$data['menu'] = array();
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listadoGaleria_view', $data);

	}

	public function create(){}

	public function edit($id)
	{

		$imagen = $this->Noticias->getImagen($id);

		$data['publicacionImagenId'] = $imagen->publicacionImagenId;
		$data['publicacionImagen'] = '';
		$data['titulo'] = "Modificar Imágen";
		$data['link'] = "admin/publications/image/update/".$imagen->publicacionImagenId;
		$data['txt_boton'] = "modificar";
		$data['imagen'] = '';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['publicacionId'] = $imagen->publicacionId;
		$data['nuevo'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(15);
		$data['imagenCoord'] = urlencode($imagen->publicacionImagenCoord);

		$data['imagen'] = '';


		if($imagen->publicacionImagenExtension != '')
		{
			$data['txt_botImagen'] = 'Cambiar Imagen';
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_admin.' . $imagen->publicacionImagenExtension . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_orig.' . $imagen->publicacionImagenExtension;
			$data['imagenExtension'] = $imagen->publicacionImagenExtension;
		}
		else
		{
			$data['txt_botImagen'] = 'Subir Imagen';
			$data['imagen'] = '';
			$data['imagenExtension'] = '';
			$data['imagenOrig'] = '';
		}

		$data['publicacionImagenNombre'] = $imagen->publicacionImagenNombre;
		$data['publicacionId'] = $imagen->publicacionId;

		$this->load->view('admin/publicaciones/noticiasImagenCrear_view', $data);
	}

	public function insert(){}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Noticias->updateImage();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la imagen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{
		$imagen = $this->Noticias->getImagen($id);

		//Get the images
		$images = $this->Modulo->getImages(15);

		foreach ($images as $img) {
			if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . $img->imagenSufijo . '.' . $imagen->publicacionImagenExtension))
				unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . $img->imagenSufijo . '.' . $imagen->publicacionImagenExtension);
		}

		//image
		if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '.' . $imagen->publicacionImagenExtension))
			unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '.' . $imagen->publicacionImagenExtension);

		//Admin image
		if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_admin.' . $imagen->publicacionImagenExtension))
			unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_admin.' . $imagen->publicacionImagenExtension);

		//Original image
		if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_orig.' . $imagen->publicacionImagenExtension))
			unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_orig.' . $imagen->publicacionImagenExtension);

		//Search image
		if (file_exists('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_search.' . $imagen->publicacionImagenExtension))
			unlink('assets/public/images/noticias/noticia_' . $imagen->publicacionId . '_' . $imagen->publicacionImagenId . '_search.' . $imagen->publicacionImagenExtension);

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Noticias->deleteImage($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la imagen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}

	public function reorder($noticiaId)
	{
		$this->Noticias->reorganizarImagenes($noticiaId);
	}

}