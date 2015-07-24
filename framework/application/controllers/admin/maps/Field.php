<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:01 PM
 */

namespace sliders;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Field extends \Map implements \AdminInterface {

	public function index(){

		$data['items'] = $this->Mapas->getCampos();

		$data['url_rel'] = base_url('admin/maps/field');
		$data['url_sort'] = base_url('admin/maps/field/reorder');
		$data['url_modificar'] = base_url('admin/maps/field/edit');
		$data['url_eliminar'] = base_url('admin/maps/field/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'campos';

		$data['idx_id'] = 'mapaCampoId';
		$data['idx_nombre'] = 'mapaCampoLabel';

		$data['txt_titulo'] = 'Campos';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax importante n1 boton'
		);
		$data['menu'][] = anchor(base_url('admin/mapas/nuevoCampo'), 'crear nuevo campo', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create(){
		$data['mapaCampoId'] = $this->cms_general->generarId('mapas_campos');
		$data['inputs'] = $this->Mapas->getInputs();
		$data['txt_titulo'] = 'Crear Campo';
		$data['txt_boton'] = 'Crear Campo';
		$data['mapaCampoLabel'] = '';
		$data['mapaCampoClase'] = '';
		$data['titulo'] = 'Crear Campo';
		$data['inputId'] = '';
		$data['link'] = base_url('admin/maps/field/insert');
		$data['nuevo'] = 'nuevo';
		$data['mapaCampoPublicado'] = 'checked="cheched"';

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();
		$traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma)
		{
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/maps/campoCrear_view', $data);
	}

	public function edit($id){

		$campo = $this->Mapas->getCampo($id);

		$data['mapaCampoId'] = $campo->mapaCampoId;
		$data['inputs'] = $this->Mapas->getInputs();
		$data['txt_titulo'] = 'Modificar Campo';
		$data['txt_boton'] = 'Modificar Campo';
		$data['mapaCampoClase'] = $campo->mapaCampoClase;
		$data['titulo'] = 'Modificar Campo';
		$data['inputId'] = $campo->inputId;
		$data['link'] = base_url('admin/maps/field/update/'.$campo->mapaCampoId);
		$data['nuevo'] = '';

		if($campo->mapaCampoPublicado)
			$data['mapaCampoPublicado'] = 'checked="cheched"';
		else
			$data['mapaCampoPublicado'] = '';

		/*
		  * TRADUCCIONES
		  */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$campoTraduccion = $this->Mapas->getCampoTranslation($idioma['idiomaDiminutivo'], $campo->mapaCampoId);
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			if($campoTraduccion)
				$traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel = $campoTraduccion->mapaCampoLabel;
			else
				$traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/maps/campoCrear_view', $data);
	}

	public function insert(){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id =$this->Mapas->createCampo();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function update($id){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Mapas->updateCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id){

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Mapas->deleteCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id){
		$this->Mapas->reorderCampos();
	}

}