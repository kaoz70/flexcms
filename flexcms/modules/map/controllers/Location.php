<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:57 AM
 */

namespace maps;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Location extends \Map implements \AdminInterface {

	public function create()
	{
		$data['mapaUbicacionId'] = $this->cms_general->generarId('mapas_ubicaciones');

		$data['mapaUbicacionNombre'] = '';
		$data['mapaUbicacionX'] = '';
		$data['mapaUbicacionY'] = '';
		$data['mapaUbicacionImagen'] = '';
		$data['mapaUbicacionClase'] = '';
		$data['mapaUbicacionPublicado'] = 'checked="checked"';

		$data['titulo'] = 'Nueva Ubicación';
		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['mapaUbicacionImagenCoord'] = '';
		$data['cropDimensions'] = $this->General->getCropImage(11);

		$data['mapaId'] = '';
		$data['mapaNombre'] = 'Seleccionar Mapa';
		$data['nuevo'] = 'nuevo';
		$data['idiomas'] = $this->Idioma->getLanguages();

		$campos = $this->Mapas->getCampos();
		$inputs = array();

		foreach($campos as $row)
		{

			$row['mapaCampoTexto'] = array();

			foreach ($data['idiomas'] as $idioma)
			{
				$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']] = array();
				$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'] = '';
			}

			array_push($inputs,  $row);
		}

		$data['campos'] = $inputs;

		$data['txt_boton'] = 'Crear Ubicación';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/maps/location/insert');

		$this->load->view('admin/maps/location_view', $data);
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->uri->segment(4);
			$response->new_id = $this->Mapas->createUbicacion($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear la ubicaci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function edit($id)
	{
		$ubicacion = $this->Mapas->getUbicacion($id);

		$mapa = $this->Mapas->get($ubicacion->mapaId);

		$data['mapaId'] = $ubicacion->mapaId;
		$data['mapaUbicacionNombre'] = $ubicacion->mapaUbicacionNombre;
		$data['mapaUbicacionId'] = $ubicacion->mapaUbicacionId;
		$data['mapaUbicacionX'] = $ubicacion->mapaUbicacionX;
		$data['mapaUbicacionY'] = $ubicacion->mapaUbicacionY;
		$data['mapaUbicacionImagen'] = $ubicacion->mapaUbicacionImagen;
		$data['mapaUbicacionClase'] = $ubicacion->mapaUbicacionClase;
		$data['mapaUbicacionPublicado'] = '';
		$data['nuevo'] = '';

		if($ubicacion->mapaUbicacionPublicado)
			$data['mapaUbicacionPublicado'] = 'checked="checked"';

		$data['titulo'] = 'Nueva Ubicación';

		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['mapaUbicacionImagenCoord'] = urlencode($ubicacion->mapaUbicacionImagenCoord);
		$data['cropDimensions'] = $this->General->getCropImage(11);

		if($ubicacion->mapaUbicacionImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $ubicacion->mapaUbicacionImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
			$data['imagen'] = '<img src="' . base_url() . 'assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '_admin.' . $extension . '?' . time() . '" />';
			$data['imagenOrig'] = base_url() . 'assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '_orig.' . $extension . '?' . time();
		}


		$data['imagenMapa'] = '';

		if($mapa->mapaImagen != '')
		{
			//Eliminamos el cache del navegador
			$extension = $mapa->mapaImagen;
			$extension = preg_replace('/\?+\d{0,}/', '', $extension);
			$data['imagenMapa'] = base_url() . 'assets/public/images/mapas/mapa_' . $mapa->mapaId . '.' . $extension . '?' . time();
			$path = getcwd() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR .'mapas' . DIRECTORY_SEPARATOR . 'mapa_' . $mapa->mapaId . '.' . $extension;

			$data['imageSize'] = array(0,0);

			if(file_exists($path))
				$data['imageSize'] = getimagesize($path);
		}

		$data['mapaNombre'] = 'Seleccionar Mapa';

		/*
		  * TRADUCCIONES
		  */
		$data['idiomas'] = $this->Idioma->getLanguages();

		$campos = $this->Mapas->getCampos();
		$inputs = array();

		foreach($campos as $row)
		{

			$row['mapaCampoTexto'] = array();

			foreach ($data['idiomas'] as $idioma)
			{

				$mapaTraduccion = $this->Mapas->getPositionTranslation($idioma['idiomaDiminutivo'], $row['mapaCampoId'], $ubicacion->mapaUbicacionId);

				$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']] = array();

				if($mapaTraduccion)
					$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'] = $mapaTraduccion->mapaCampoTexto;
				else
					$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'] = '';
			}

			array_push($inputs,  $row);
		}

		$data['campos'] = $inputs;
		$data['txt_boton'] = 'Modificar Ubicación';
		$data['txt_botImagen'] = 'Subir Imagen';
		$data['link'] = base_url('admin/maps/location/update/' . $ubicacion->mapaUbicacionId);

		$this->load->view('admin/maps/location_view', $data);
	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Mapas->updateUbicacion($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la ubicaci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{

			$ubicacion = $this->Mapas->getUbicacion($id);

			//Eliminamos la imagen
			$imageExtension = preg_replace('/\?+\d{0,}/', '', $ubicacion->mapaUbicacionImagen);

			if(file_exists('./assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '.' . $imageExtension))
				unlink('./assets/public/images/mapas/mapa_ubicacion_' . $ubicacion->mapaUbicacionId . '.' . $imageExtension);

			$this->Mapas->deleteUbicacion($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la ubicaci&oacute;n!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

}