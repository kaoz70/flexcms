<?php

class Module_model extends CI_Model {


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function tipos()
	{
        $this->db->order_by('moduloTipoGrupo', 'ASC');
        $this->db->order_by('moduloTipoNombre', 'ASC');
		$query = $this->db->get('modulo_tipo');
        return $query->result();
	}

    public function getImages($seccionId){
        $this->db->where('imagenTemporal', 0);
        $this->db->where('seccionId', $seccionId);
        $this->db->order_by("imagenPosicion", "asc");
        $query = $this->db->get('imagenes');
        return $query->result();
    }

	/**
	 * Gets the content page by module type
	 * @param $type
	 * @return mixed
	 */
	public function get_page_by_type($type)
	{
		$this->db->join('modulos', 'paginas.id = modulos.paginaId', 'left');
		$this->db->join('es_paginas', 'es_paginas.paginaId = paginas.id', 'left');
		$this->db->where('modulos.moduloParam1', $type);
		$this->db->where('paginaModuloTipoId', 8); // 8 is content module type id
		$query = $this->db->get('paginas');
		return $query->result();
	}

	/**
	 * Gets the page by module type
	 * @param $type
	 * @return mixed
	 */
	public function get_page_by_module_type($type)
	{
		$this->db->join('modulos', 'paginas.id = modulos.paginaId', 'left');
		$this->db->join('es_modulos', 'es_modulos.moduloId = modulos.moduloId', 'left');
		$this->db->join('es_paginas', 'es_paginas.paginaId = paginas.id', 'left');
		$this->db->where('paginaModuloTipoId', $type);
		$query = $this->db->get('paginas');
		return $query->result();
	}

	/**
	 * Get all content pages (the ones that can be edited)
	 */
	public function getContentPages()
	{
		$this->db->join('modulos', 'paginas.id = modulos.paginaId', 'left');
		$this->db->join('es_paginas', 'es_paginas.paginaId = paginas.id', 'left');
		$this->db->where_in('modulos.moduloParam1', array(1, 2, 4, 5, 6, 10, 12, 9, 13));
		$this->db->where('paginaModuloTipoId', 8); // 8 is content module type id
		$query = $this->db->get('paginas');

		$return = array();

		foreach($query->result() as $page){
			$return[] = $page->id;
		}

		return $return;
	}

	public function catalogoProductosDestacados()
	{
		//$this->db->join('es_producto_categorias', 'es_producto_categorias.productoCategoriaId = producto_categorias.id', 'LEFT');
		$this->db->where('productoDeldia', 's');
		$query = $this->db->get('productos');
        return $query->result();
	}
	
	public function createModule($page_id, $typeId)
	{
		$data = array(
			'paginaModuloTipoId' => $typeId,
			'paginaId' => $page_id,
		);
		
		$this->db->insert('modulos', $data);
		
		$insertId = $this->db->insert_id();
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			$insertData = array(
				'moduloId' => $insertId
			);
			
			$this->db->insert($idioma['idiomaDiminutivo'].'_modulos', $insertData);
		}

		return $insertId;
	}
	
	public function updateModule($moduleData)
	{
		
		if($moduleData->showTitle == true)
			$moduleData->showTitle = 1;
		else {
			$moduleData->showTitle = 0;
		}
		
		if($moduleData->paginacion == true)
			$moduleData->paginacion = 1;
		else {
			$moduleData->paginacion = 0;
		}

        if($moduleData->habilitado == true)
            $moduleData->habilitado = 1;
        else {
            $moduleData->habilitado = 0;
        }
		
		$data = array(
			'moduloParam1' => $moduleData->param1,
			'moduloParam2' => $moduleData->param2,
			'moduloParam3' => $moduleData->param3,
			'moduloParam4' => (int)$moduleData->param4,
			'moduloMostrarTitulo' => $moduleData->showTitle,
			'moduloClase' =>  $moduleData->clase,
			'moduloVerPaginacion' =>  $moduleData->paginacion,
			'moduloHabilitado' =>  $moduleData->habilitado,
			'moduloVista' =>  $moduleData->vista,
		);

		$this->db->where('moduloId', (int)$moduleData->id);
		$this->db->update('modulos', $data);
		
		$idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            foreach ($moduleData->name as $key => $name) {
                if($name->diminutivo == $idioma['idiomaDiminutivo'])
                {
                    if (count($moduleData->html) > 0)
                    {
                        foreach ($moduleData->html as $html)
                        {
                            if($name->diminutivo == $html->idioma)
                            {
                                $dataIdioma = array(
                                    'moduloNombre' => $name->valor,
                                    'moduloHtml' => $html->valor
                                );

                                $this->db->where('moduloId', (int)$moduleData->id);
                                $this->db->update($idioma['idiomaDiminutivo'].'_modulos', $dataIdioma);
                            }
                        }
                    }

                    else{
                        $dataIdioma = array(
                            'moduloNombre' => $name->valor
                        );

                        $this->db->where('moduloId', (int)$moduleData->id);
                        $this->db->update($idioma['idiomaDiminutivo'].'_modulos', $dataIdioma);
                    }


                }
            }

        }
		
		
	}
	
	public function getModulesByPage($pageId)
	{
		
		$this->db->where('paginaId', $pageId);
		$query = $this->db->get('modulos');
		
		$modulos = $query->result();
		
        return $modulos;
	}

	public function removeModule($id)
	{
		$this->db->where('moduloId', $id);
		$this->db->delete('modulos');
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			$this->db->where('moduloId', $id);
			$this->db->delete($idioma['idiomaDiminutivo'].'_modulos');
		}
		
	}

	public function getModule($id)
	{
		$modulo = $this->db
			->where('moduloId', $id)
			->get('modulos')
			->row();

		if(!$modulo){
			return NULL;
		}

		$idiomas = $this->getLanguages();
		$traducciones = array();
		
		foreach ($idiomas as $key => $idioma) {
			$this->db->where('moduloId', $modulo->moduloId);
			$query = $this->db->get($idioma['idiomaDiminutivo'].'_modulos');

			$traducciones[$idioma['idiomaDiminutivo']] = $query->row();
		}

        $modulo->traducciones = $traducciones;
		
        return $modulo;
	}
	
	public function getContentByType($typeId)
	{
		$this->db->join('modulos', 'paginas.id = modulos.paginaId', 'left');
		$this->db->join('es_paginas', 'es_paginas.paginaId = paginas.id', 'left');
		$this->db->where('modulos.moduloParam1', $typeId);
		$this->db->where('modulos.paginaModuloTipoId', 8);
		$this->db->group_by('paginas.id');
		$query = $this->db->get('paginas');
		
        return $query->result_array();
	}

	public function getContentByPage($page_id)
	{
		$this->db->where('paginaId', $page_id);
		$this->db->where('modulos.paginaModuloTipoId', 8);
		$query = $this->db->get('modulos');

        return $query->result();
	}

	private function getLanguages()
	{
		$query = $this->db->get('idioma');
        return $query->result_array();
	}
	
}