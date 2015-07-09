<?php

class Faq extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');
		
		$this->load->model('faq_model', 'FAQs');
		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
		
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();
		
	}

	public function edit($id)
	{
		$faq = $this->FAQs->getFAQInfo($id); //Datos de cada FAQ
		$data['titulo'] = "Modificar Pregunta Frecuente";
		$data['txt_boton'] = "Modificar Pregunta";
		$data['link'] = base_url("admin/faq/update/" . $faq->faqId);
		$data['faqId'] = $faq->faqId;
		$data['faqClase'] = $faq->faqClase;
        $data['paginaId'] = $faq->paginaId;
        $data['nuevo'] = '';
        $data['removeUrl'] = '';
		
		if($faq->faqHabilitado == 'on'){
			$data['faqHabilitado'] = 'checked="checked"';
		} else {
			$data['faqHabilitado'] = '';
		}
		
		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();
		
		foreach ($data['idiomas'] as $key => $idioma) {
			
			$translation = $this->FAQs->getTranslation($idioma['idiomaDiminutivo'], $id);
			
			$pregunta = '';
			$respuesta = '';
			
			if(count($translation) > 0){
				$pregunta = $translation->faqPregunta;
				$respuesta = $translation->faqRespuesta;
			}
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->faqPregunta = $pregunta;
			$traducciones[$idioma['idiomaDiminutivo']]->faqRespuesta = $respuesta;
		}
		
		$data['traducciones'] = $traducciones;
		
		$this->load->view('admin/faq_view', $data);
		
	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$response->new_id = $this->FAQs->createFAQ();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al crear el la pregunta!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));
		
	}

	public function update($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->FAQs->updateFAQ($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el la pregunta!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function create($paginaId)
	{

		$data['titulo'] = 'Nueva Pregunta Frecuente';
		$data['txt_boton']='Guardar Pregunta';
		$data['faqHabilitado'] = 'checked="checked"';
        $data['nuevo'] = 'nuevo';
        $data['faqId'] = '';
        $data['link'] = base_url("admin/faq/insert/");
        $data['paginaId'] = $paginaId;

		/*
		 * TRADUCCIONES
		 */
		$data['idiomas'] = $this->Idioma->getLanguages();

        $traducciones = array();

		foreach ($data['idiomas'] as $key => $idioma) {
            $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
			$traducciones[$idioma['idiomaDiminutivo']]->faqPregunta = '';
			$traducciones[$idioma['idiomaDiminutivo']]->faqRespuesta = '';
		}
		
		$data['traducciones'] = $traducciones;
		$data['faqClase'] = '';
		
		$this->load->view('admin/faq_view', $data);
	}
	
	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->FAQs->deleteFAQ($id);
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el la pregunta!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
	public function reorder()
	{

		$response = new stdClass();
		$response->error_code = 0;

		try{
			$this->FAQs->reorderFAQ();
		} catch (Exception $e) {
			$response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar las preguntas!', $e);
		}

		$this->load->view('admin/request/json', array('return' => $response));

	}
	
}