<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:19 PM
 */

namespace maps;
$_ns = __NAMESPACE__;

use stdClass;
use Exception;

class Field extends \Slider implements \AdminInterface {

	public function index()
	{

		$data['items'] = $this->Banners->getCampos();

		$data['url_rel'] = base_url('admin/sliders/field');
		$data['url_sort'] = base_url('admin/sliders/field/reorder/' . 1); // NO param necessary (1)
		$data['url_modificar'] = base_url('admin/sliders/field/edit');
		$data['url_eliminar'] = base_url('admin/sliders/field/delete');
		$data['url_search'] = '';

		$data['search'] = false;
		$data['drag'] = true;
		$data['nivel'] = 'nivel3';
		$data['list_id'] = 'banner_campos';

		$data['idx_id'] = 'bannerCampoId';
		$data['idx_nombre'] = 'bannerCampoNombre';

		$data['txt_titulo'] = 'Editar Template';

		/*
		 * Menu
		 */
		$data['menu'] = array();

		$atts = array(
			'id' => 'crearBanner',
			'class' => $data['nivel'] . ' ajax boton n1'
		);
		$data['menu'][] = anchor(base_url('admin/sliders/field/create'), 'Crear Nuevo Elemento', $atts);
		$data['bottomMargin'] = count($data['menu']) * 34;

		$this->load->view('admin/listado_view', $data);
	}

	public function create()
	{
		$data['titulo'] = 'Nuevo Elemento';
		$data['habilitado']	= 'checked="checked"';

		$data['campoId'] = $this->cms_general->generarId('banner_campos');
		$data['bannerCampoNombre'] = '';
		$data['campo_tipoDato'] = '';
		$data['inputId'] = '';
		$checked = '';
		$data['checked'] = $checked;
		$data['nuevo'] = 'nuevo';

		$data['result'] = $this->Banners->getInputs();
		$data['bannerCampoClase'] = '';
		$data['txt_boton'] = 'Guardar Elemento';
		$data['link']  = base_url('admin/sliders/field/insert');

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->bannerCampoLabel = '';
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/sliders/field_view',$data);
	}

	public function edit($campoId)
	{

		$data['titulo'] = 'Editar Elemento';
		$data['habilitado']	= 'checked="checked"';

		$campo = $this->Banners->getDatosCampo($campoId);

		$data['campoId'] = $campo->bannerCampoId;
		$data['bannerCampoNombre'] = $campo->bannerCampoNombre;
		$data['inputId'] = $campo->inputId;
		$data['nuevo'] = '';

		$checked='';
		if($campo->bannerCampoLabelHabilitado)
			$checked = 'checked="checked"';

		$data['checked'] = $checked;
		$data['result'] = $this->Banners->getInputs();
		$data['bannerCampoClase'] = $campo->bannerCampoClase;

		$data['txt_boton'] = 'Modificar Elemento';
		$data['link'] = base_url('admin/sliders/field/update/' . $campoId);

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

		foreach ($data['idiomas'] as $key => $idioma) {
			$traducciones[$idioma['idiomaDiminutivo']] = $this->Banners->getInputTranslation($idioma['idiomaDiminutivo'], $campoId);
		}

		$data['traducciones'] = $traducciones;

		$this->load->view('admin/sliders/field_view',$data);
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$id = $this->Banners->guardarCampo();
			$response->new_id = $id;
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Banners->updateCampo();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al modificar el campo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Banners->deleteCampo($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el art&iacute;culo!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

	public function reorder($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->Banners->reorderInputs();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}

}