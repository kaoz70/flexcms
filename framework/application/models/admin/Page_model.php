<?php

class Page_model extends CI_Model {

	function getPages($lang = 'es')
	{
		$this->db->cache_on();
		$this->db->join($lang . '_paginas', $lang . '_paginas.paginaId = paginas.id', 'LEFT');
		$this->db->where('temporal', 0);
		$query = $this->db->get('paginas');
		$this->db->cache_off();
        return $query->result_array();
	}
	
	function getTrees()
	{
		return $this->db
			->select('tree_id')
			->group_by('tree_id')
			->order_by('tree_position')
			->get('paginas')->result();
	}
	
	function getPagesForNews()
	{
		$this->db->where('pagina_tipoId', 5);
		$query = $this->db->get('paginas');
        return $query->result_array();
	}
	
	function getPageType()
	{
		$query = $this->db->get('pagina_tipo');
        return $query->result_array();
	}

	function copy_structure()
	{
		$from_page = $this->input->post('from_page');
		$to_page = $this->input->post('to_page');
		$page = $this->getPage((int)$from_page);
		$rows = json_decode($page->estructura);
		$new_modules_ids = array();
		$languages = $this->db->get('idioma')->result();

		if($rows) {

			//Loop through the rows
			foreach ($rows as $row_key => $row) {

				//Loop through the columns
				foreach ($row->columns as $col_key => $col) {

					$mod_ids = array();

					if($col->modules) {
						//Loop through the languages to get the module's language data
						foreach ($languages as $lang) {

							$modulos = $this->db
								->join($lang->idiomaDiminutivo . '_modulos', $lang->idiomaDiminutivo . '_modulos.moduloId = modulos.moduloId')
								->where_in('modulos.moduloId', $col->modules)
								->get('modulos')->result_array();

							//Loop through each module and duplicate them in DB
							foreach ($modulos as $mod) {

								$translations = array(
									'moduloNombre' => $mod['moduloNombre'],
									'moduloHtml' => $mod['moduloHtml'],
								);

								//Remove the old id
								unset($mod['moduloId']);

								//Remove the joined columns so that we can save the module again (avoid the missing columns error)
								unset($mod['moduloNombre']);
								unset($mod['moduloHtml']);
								unset($mod[$lang->idiomaDiminutivo . '_moduloId']);

								$mod['paginaId'] = $to_page; //set the new page
								$this->db->insert('modulos', $mod);
								$mod_ids[] = $new_modules_ids[] = $mod['moduloId'] = $translations['moduloId'] = $this->db->insert_id(); //get and set the new id

								//Save the translations
								$this->db->insert($lang->idiomaDiminutivo . '_modulos', $translations);

							}

						}
					}

					$col->modules = $mod_ids;

				}

			}

			//Delete any old modules from the target page
			$this->db->where('paginaId', $to_page)
				->where_not_in('moduloId', $new_modules_ids) //don't delete the new ones
				->delete('modulos');

			//Save the new structure
			$this->db->where('id', $to_page)
				->update('paginas', array('estructura' => json_encode($rows)));

			return TRUE;

		} else {
			return FALSE;
		}

	}
	
	function addPage()
	{

		$return = new stdClass();

		$node = new PageTree(array('temporal' => 1));
		$node->makeLastChildOf(PageTree::find(1));
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$this->db->insert($dim.'_paginas', array(
				'paginaId' => $node->id,
				'paginaNombre' => '',
				'paginaNombreMenu' => '',
				'paginaNombreURL' => '',
				'paginaKeywords' => '',
				'paginaDescripcion' => '',
				'paginaTitulo' => ''
			));
			
		}

		$return->insert_id = $node->id;

        return $return;
		
	}

	function save_structure($id, $structure)
	{
		$data = array(
			'estructura' => json_encode($structure),
		);

		$this->db->where('id', $id);
        $this->db->update('paginas', $data);
	}
	
	function getPage($id, $lang = 'es')
	{

		$this->db->join('groups', 'groups.id = paginas.paginaVisiblePara', 'LEFT');
		$this->db->join($lang . '_paginas', $lang . '_paginas.paginaId = paginas.id', 'LEFT');

		if(is_int($id)) {
			$this->db->where('paginas.id', $id);
		} else {
			$this->db->where($lang . '_paginas.paginaNombreURL', $id);
		}

		$query = $this->db->get('paginas'); 
		
		return $query->row();
	}
	
	public function getPageTranslation($diminutivo, $paginaId)
	{
		$this->db->where('paginaId', $paginaId);
		$query = $this->db->get($diminutivo.'_paginas');
		return $query->row();
	}
	
	function updatePage($general)
    {
		$id = $this->uri->segment(4);

		//Get the page structure
		$page = $this->getPage((int)$id);
		$current_modules = [];
		$database_modules = [];

		//Get all page modules
		$modules_in_db = $this->db
			->where('paginaId', $id)
			->get('modulos')
			->result();
		foreach ($modules_in_db as $module) {
			$database_modules[] = $module->moduloId;
		}

		if(isset($page->estructura) && $estructura = json_decode($page->estructura)) {
			//Re - format the input structure
			$filas = $this->input->post('fila');
			$filas = array_values((array)json_decode(json_encode($filas), FALSE));

			//Set the rows properties
			foreach ($estructura as $row_key => $row) {
				$estructura[$row_key]->class = $filas[$row_key]->class;
				$estructura[$row_key]->expanded = isset($filas[$row_key]->expanded) ? 1 : 0;

				//Set the columns properties
				foreach ($estructura[$row_key]->columns as $col_key => $col) {
					$estructura[$row_key]->columns[$col_key]->class = $filas[$row_key]->columns[$col_key]->class;
					$estructura[$row_key]->columns[$col_key]->span = $filas[$row_key]->columns[$col_key]->span;
					$estructura[$row_key]->columns[$col_key]->offset = $filas[$row_key]->columns[$col_key]->offset;
					$estructura[$row_key]->columns[$col_key]->pull = $filas[$row_key]->columns[$col_key]->pull;
					$estructura[$row_key]->columns[$col_key]->push = $filas[$row_key]->columns[$col_key]->push;

					$current_modules = array_merge($current_modules, $col->modules);

				}

			}

			//Remove any modules that are in the database but not in the page's structure
			$delete = array_diff($database_modules, $current_modules);

			if($delete) {
				$this->db
					->where_in('moduloId', $delete)
					->delete('modulos');
			}


		} else {
			$estructura = array();
		}
		
		$data = array(
			'paginaClase' => $this->input->post('paginaClase'),
			'paginaEsPopup' => $this->input->post('esPopup'),
			'paginaVisiblePara' => $this->input->post('paginaVisiblePara'),
            'paginaEnabled' => $this->input->post('paginaEnabled'),
			'paginaModuloColumnaId' => $this->input->post('paginaModuloColumnaId'),
            'temporal' => 0,
            'estructura' => json_encode($estructura),
		);
		
		$this->db->where('id', $id);
        $this->db->update('paginas', $data);
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$dataIdioma = array(
				'paginaId' => $id,
				'paginaNombre' => $this->input->post($dim.'_paginaNombre'),
				'paginaNombreMenu' => $this->input->post($dim.'_paginaNombreMenu'),
				'paginaNombreURL' => $general->generateSafeUrl($this->input->post($dim.'_paginaNombreMenu')),
				'paginaKeywords' => $this->input->post($dim.'_paginaKeywords'),
				'paginaDescripcion' => $this->input->post($dim.'_paginaDescripcion'),
				'paginaTitulo' => $this->input->post($dim.'_paginaTitulo')
			);

			//Revisamos si existe
			$this->db->where('paginaId', $id);
			$query = $this->db->get($dim.'_paginas');
			$result = $query->row();
			
			if(count($result) > 0)
			{
				$this->db->where('paginaId', $id);
        		$this->db->update($dim.'_paginas', $dataIdioma);
			}
			else
				$this->db->insert($dim.'_paginas', $dataIdioma);
			
		}
    }

	//Miguel
	public function idiomas()//funcion para saber la cantidad de idioma que existe en DB
	{
		$idiomas = array();	
		
		$query = $this->db->get('idioma');
					
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $row)
			{
				//array con indice del id del idioma y con el diminutivoddel idioma
				//$idiomas[$row->idiomaId] = $row->idiomaDiminutivo;
				$idiomas[$row->idiomaId] = array(
					'diminutivo' => $row->idiomaDiminutivo,
					'nombre' => $row->idiomaNombre
				);
			}
		}else
		{
			$idiomas = 0;
		}
		return $idiomas;
	}
	
	private function getLanguages()
	{
		$query = $this->db->get('idioma');
        return $query->result_array();
	}

}