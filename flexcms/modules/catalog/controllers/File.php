<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:50 AM
 */

namespace catalog;
$_ns = __NAMESPACE__;

class File extends \Catalog implements \AdminInterface {

	public function index()
	{

		$productId = $this->uri->segment(5);
		$fieldId = $this->uri->segment(6);
		$data['items'] = $this->Catalogo->getProductFiles($productId, $fieldId);

		$data['url_rel'] = base_url('catalogo/file/'.$productId);
		$data['url_sort'] = base_url('admin/catalog/file/reorder/'.$productId);
		$data['url_modificar'] = base_url('admin/catalog/file/edit/');
		$data['url_eliminar'] = base_url('admin/catalog/file/delete/');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'product_files';

		$data['url_path'] =  base_url() . 'assets/public/images/catalog/gal_';
		$data['url_upload'] =  base_url() . 'admin/imagen/productoGaleria/' . $productId . '/' . $fieldId;
		$data['method'] =  'productoGaleria/' . $productId . '/' . $fieldId;

		$dimensiones = $this->General->getCropImage(6);
		$data['width'] = $dimensiones->imagenAncho;
		$data['height'] = $dimensiones->imagenAlto;

		$data['idx_id'] = 'productoArchivoId';
		$data['idx_nombre'] = 'productoArchivoNombre';
		$data['idx_extension'] = 'productoArchivoExtension';

		$data['txt_titulo'] = 'Archivos';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax boton importante n1'
		);
		$data['menu'][] = anchor(base_url('admin/catalog/youtube/create/'.$productId . '/' . $fieldId), 'nuevo video youtube', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		//$this->load->view('admin/listado_view', $data);
		$this->load->view('admin/listadoProductoArchivos_view', $data);
	}

	public function create(){}

	public function edit($productoArchivoId)
	{
		$file = $this->Catalogo->getProductFile($productoArchivoId);

		$data['productoId'] = $file->productoId;
		$data['titulo'] = 'Modificar Descarga';
		$data['productoArchivoNombre'] = $file->productoArchivoNombre;
		$data['txt_boton'] = 'Modificar Descarga';
		$data['productoArchivoExtension'] = $file->productoArchivoExtension;
		$data['productoArchivoId'] = $productoArchivoId;
		$data['productoArchivoCoord'] = $file->productoArchivoCoord;
		$data['productoCampoId'] = $file->productoCampoId;
		$data['cropDimensions'] = $this->General->getCropImage(6);
		$data['nuevo'] = '';

		if($file->productoArchivoExtension != '')
		{
			$data['txt_botImagen'] = 'Cambiar Archivo';
			$data['archivoUrl'] = '<a href="' . base_url() . 'docs/catalog/prod_'. $file->productoId . '/' . $file->productoArchivoExtension . '">' . $file->productoArchivoExtension . '</a>';
		}
		else
		{
			$data['txt_botImagen'] = 'Subir Archivo';
			$data['archivoUrl'] = '<a href="#"></a>';
		}

		$data['productoArchivoId'] = $productoArchivoId;
		$data['link'] = base_url('admin/catalog/file/update/' . $productoArchivoId);

		$enabled = '';

		if($file->productoArchivoEnabled)
			$enabled = 'checked="checked"';

		$data['productoArchivoEnabled'] = $enabled;

		/*
		   * TRADUCCIONES
		   */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$descargaTraduccion = $this->Catalogo->getDescargaTranslation($idioma['idiomaDiminutivo'], $productoArchivoId);
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			if($descargaTraduccion)
				$traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion = $descargaTraduccion->productoDescargaTexto;
			else
				$traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion = '';
		}

		$data['traducciones'] = $traducciones;



		//Remove the ?123456 (cache param)
		$ext = preg_replace('/\?+\d{0,}/', '', $data['productoArchivoExtension']);

		//Get the extension
		$extension = pathinfo('./assets/public/images/catalog/gal_' . $data['productoArchivoId'] . '_admin.' . $ext, PATHINFO_EXTENSION);
		if(!$extension) {
			$extension = pathinfo('./assets/public/catalog/gal_' . $ext, PATHINFO_EXTENSION);
		}

		if($extension && strlen($extension) < 9) {

			if(!$extension) {
				$extension = $data['productoArchivoExtension'];
			}



			switch(mb_strtolower($extension)) {

				//Images
				case 'jpg':
				case 'gif':
				case 'png':
				case 'jpeg':

					$data['imagenUrl'] = '<img src="' . base_url() . 'assets/public/images/catalog/gal_' . $data['productoArchivoId'] . '_admin.' . $data['productoArchivoExtension'] . '?' . time() . '" />';
					$data['imagenOrig'] = base_url() . 'assets/public/images/catalog/gal_' . $data['productoArchivoId'] . '_orig.' . $data['productoArchivoExtension'];
					$this->load->view('admin/catalog/product_image_view', $data);
					break;

				//Audio
				case 'mp3':
				case 'ogg':
				case 'mwa':
				case 'wav':
					$data['imagenUrl'] = '';
					$data['imagenOrig'] = '';
					$data['archivoUrl'] = '<audio src="' . base_url() . 'assets/public/files/catalog/' . $data['productoArchivoExtension'] . '" controls ></audio>';
					$this->load->view('admin/catalog/product_audio_view', $data);
					break;

				//Video
				case 'ogv':
				case 'avi':
				case 'wmv':
				case 'mov':
					$data['imagenUrl'] = '';
					$data['imagenOrig'] = '';
					$data['archivoUrl'] = '<video src="' . base_url() . 'assets/public/files/catalog/' . $data['productoArchivoExtension'] . '" controls ></video>';
					$this->load->view('admin/catalog/product_video_view', $data);
					break;

				//Others
				default:
					$data['imagenUrl'] = '';
					$data['imagenOrig'] = '';
					$data['archivoUrl'] = '<a href="' . base_url() . 'assets/public/files/catalog/' . $data['productoArchivoExtension'] . '">' . $data['productoArchivoExtension'] . '</a>';
					$this->load->view('admin/catalog/product_file_view', $data);

			}
		}

		//Probably youtube video
		else {
			$this->load->view('admin/catalog/youtube_view', $data);
		}
		

	}

	public function insert()
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Catalogo->insertProductFile();
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el archivo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function update($id)
	{

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->updateProductFile();
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el archivo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($file_id)
	{
		$descarga = $this->Catalogo->getProductFile($file_id);

		//Eliminamos los archivos
		if($descarga->productoArchivoExtension != '')
		{

			if(file_exists('./docs/catalog/prod_'. $descarga->productoId . '/' . $descarga->productoArchivoExtension))
				unlink('./docs/catalog/prod_'. $descarga->productoId . '/' . $descarga->productoArchivoExtension);

		}

		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$this->Catalogo->deleteProductFile($file_id);
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el archivo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{
		$this->Catalogo->reorderProductFiles($id);
	}
	
}