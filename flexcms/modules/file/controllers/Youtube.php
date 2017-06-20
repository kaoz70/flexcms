<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:33 AM
 */

namespace gallery;
$_ns = __NAMESPACE__;

include_once ('File.php');

class Youtube extends File implements \AdminGalleryInterface {

	public function create()
	{

		$root = \GalleryTree::allRoot()->first();
		$root->findChildren(999);

		$data['categorias'] = $root->getChildren();
		$data['txt_botImagen'] = 'Subir Archivo';
		$data['titulo'] = "Crear Archivo";
		$data['descargaFecha'] = date('Y-m-d');
		$data['link'] = base_url("admin/gallery/youtube/insert");
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
			$traducciones[$idioma['idiomaDiminutivo']] = new \stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->descargaNombre = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/gallery/video_view', $data);
	}

	public function insert()
	{
		$response = new \stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Descargas->addDownload($this->cms_general);
			$response->new_id = $id;
		} catch (\Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear la im&aacute;gen!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
	}
	
}