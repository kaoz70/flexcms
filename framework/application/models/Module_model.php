<?php

class Module_model extends CI_Model {


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /*
     * MODULE TYPES
     */

    public function publicaciones($paginaId)
    {
        $this->db->where('paginaId', $paginaId);
        $query = $this->db->get('publicaciones');
        return $query->result();
    }

    public function banners()
    {
        $query = $this->db->get('banners');
        return $query->result();
    }

    public function catalogoCategorias()
    {
        $query = $this->db->get('producto_categorias');
        return $query->result();
    }

    public function html($id, $lang)
    {
        $this->db->where('moduloId', $id);
        $query = $this->db->get($lang.'_modulos');
        return $query->row();
    }

    public function articulo($id, $lang)
    {
        $this->db->where('articulos.articuloId', $id);
        $this->db->join($lang . '_articulos', $lang . '_articulos.articuloId = articulos.articuloId', 'LEFT');
        $query = $this->db->get('articulos');

        return $query->row();
    }

    //FILTROS
    public function productoFiltros($lang)
    {

        $fieldsArr = array();

        $this->db->select('producto_campos.productoCampoId, productoCampoClase, productoCampoValor, inputTipoId');
        $this->db->join($lang . '_producto_campos', $lang . '_producto_campos.productoCampoId = producto_campos.productoCampoId', 'LEFT');
        $this->db->join('input', 'input.inputId = producto_campos.inputId', 'LEFT');
        $this->db->where('productoCampoVerFiltro', 1);
        $this->db->where_in('inputTipoId', array(1, 3, 9, 12));
        $this->db->order_by('productoCampoPosicion', 'asc');
        $query = $this->db->get('producto_campos');

        $fields = $query->result();

        foreach ($fields as $value) {

            $fieldObj = new stdClass();
            $fieldObj->productoCampoId = $value->productoCampoId;
            $fieldObj->productoCampoClase = $value->productoCampoClase;
            $fieldObj->productoCampoValor = $value->productoCampoValor;

            // Si es que es un listado
            if($value->inputTipoId == 12) {
                $fieldObj->productoFiltros = $this->getProductListFields($value->productoCampoId, $lang);
            }

            //Texto, texto multilinea
            else
            {
                $fieldObj->productoFiltros = $this->getProductUniqueFields($value->productoCampoId, $lang);
            }

            array_push($fieldsArr, $fieldObj);
        }

        return $fieldsArr;
    }

    private function getProductUniqueFields($id, $lang)
    {
        $this->db->join($lang . '_producto_campos_rel', $lang . '_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId', 'LEFT');
        $this->db->distinct();
        $this->db->select('productoCampoRelContenido');
        $this->db->where('producto_campos_rel.productoCampoId', $id);
        $query = $this->db->get('producto_campos_rel');

        return $query->result();
    }

    private function getProductListFields($id, $lang)
    {
        $this->db->join($lang . '_producto_campos_listado_predefinido', $lang . '_producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido.productoCamposListadoPredefinidoId', 'LEFT');
        $this->db->distinct();
        $this->db->select('productoCamposListadoPredefinidoTexto as productoCampoRelContenido');
        $this->db->where('producto_campos_listado_predefinido.productoCampoId', $id);
        $query = $this->db->get('producto_campos_listado_predefinido');

        return $query->result();
    }


    /*
     * MODULES
     */

    public function getPageModules($pageId, $lang)
    {

        $this->db->select('modulos.moduloId, moduloHabilitado, moduloVista, moduloNombre, moduloHtml, paginaModuloTipoId, moduloParam1, moduloParam2, moduloParam3, moduloParam4, moduloMostrarTitulo, moduloVerPaginacion, moduloClase');
        $this->db->join($lang.'_modulos', $lang.'_modulos.moduloId = modulos.moduloId', 'LEFT');
        $this->db->where('paginaId', $pageId);
        //$this->db->where('moduloHabilitado', 1);
        $this->db->group_by('modulos.moduloId');
        $query = $this->db->get('modulos');
        return $query->result();
    }

    public function getModule($id)
    {
        $this->db->where('moduloId', $id);
        $query = $this->db->get('modulos');
        return $query->row();
    }

    public function getContentModule($id)
    {

		$this->db->cache_on();

        $this->db->where('paginaModuloTipoId', 8);
        $this->db->where('moduloParam1', $id);
        $query = $this->db->get('modulos');

		$this->db->cache_off();

        return $query->row();
    }

    public function getPageByType($typeId, $lang)
    {

		$this->db->cache_on();

        $this->db->join('paginas', 'paginas.id = modulos.paginaId', 'left');
        $this->db->join($lang . '_paginas', $lang . '_paginas.paginaId = paginas.id', 'left');
        $this->db->where('modulos.paginaModuloTipoId', 8);
        $this->db->where('modulos.moduloParam1', $typeId);
        $query = $this->db->get('modulos');

		$this->db->cache_off();

        return $query->row();
    }

    public function getPagesByType($typeId, $lang = 'es')
    {
        $this->db->select('paginas.id, paginaNombre, paginaNombreURL');
        $this->db->join('paginas', 'paginas.id = modulos.paginaId', 'left');
        $this->db->join($lang.'_paginas', $lang.'_paginas.paginaId = paginas.id', 'left');
        $this->db->where('modulos.paginaModuloTipoId', 8);
        $this->db->where('modulos.moduloParam1', $typeId);
        $query = $this->db->get('modulos');
        return $query->result();
    }

    public function getPageType($pageId, $lang)
    {
        $this->db->join('paginas', 'paginas.id = modulos.paginaId', 'left');
        $this->db->join($lang . '_paginas', $lang . '_paginas.paginaId = paginas.id', 'left');
        $this->db->where('modulos.paginaModuloTipoId', 8);
        $this->db->where('paginas.id', $pageId);
        $query = $this->db->get('modulos');
        return $query->row();
    }

    //Pagination
    public function getItemsForPublicaciones($pageId, $numItems, $actualPagePagination, $lang)
    {
        $this->db->where('paginaId', $pageId);
        $this->db->where('publicaciones.publicacionTemporal', 0);
        $this->db->order_by('publicacionFecha', 'desc');
        $this->db->limit($numItems, $actualPagePagination);
        $this->db->join($lang.'_publicaciones', $lang.'_publicaciones.publicacionId = publicaciones.publicacionId', 'LEFT');
        $query = $this->db->get('publicaciones');
        return $query->result();
    }

    public function getItemsForCatalog($categoriaId, $numItems, $actualPagePagination, $lang)
    {
        $this->db->where('productos.categoriaId', $categoriaId);
        $this->db->where('productos.productoTemporal', 0);
        $this->db->order_by('productoPosicion', 'asc');
        $this->db->limit($numItems, $actualPagePagination);
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'LEFT');
        $this->db->join('producto_categorias', 'producto_categorias.id = productos.categoriaId', 'LEFT');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'LEFT');
        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('productos');
        $productos = $query->result();

        //Campos
        $this->db->where('productoCampoVerModulo', 1);
        $this->db->where('productoCampoHabilitado', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();

        $CI =& get_instance();
        $CI->load->model('catalogo_model');
        return $CI->catalogo_model->procesaProductos($productos, $campos, $lang);

    }

    public function getItemsForFAQ($pageId, $numItems, $actualPagePagination, $lang)
    {
        $this->db->where('paginaId', $pageId);
        $this->db->order_by('faqPosicion', 'ASC');
        $this->db->limit($numItems, $actualPagePagination);
        $this->db->join($lang.'_faq', $lang.'_faq.faqId = faq.faqId', 'LEFT');
        $query = $this->db->get('faq');
        return $query->result();
    }

    public function getItemsForEnlaces($pageId, $numItems, $actualPagePagination, $lang)
    {
        $this->db->order_by('enlacePosicion', 'ASC');
        $this->db->limit($numItems, $actualPagePagination);
        $this->db->join($lang.'_enlaces', $lang.'_enlaces.enlaceId = enlaces.enlaceId', 'LEFT');
        $this->db->where('paginaId', $pageId);
        $query = $this->db->get('enlaces');
        return $query->result();
    }

    public function getItemsForGaleria($catId, $numItems, $actualPagePagination, $lang)
    {
        $this->db->order_by('descargaPosicion', 'ASC');
        $this->db->where('descargaCategoriaId', $catId);
        $this->db->limit($numItems, $actualPagePagination);
        $this->db->join($lang.'_descargas', $lang.'_descargas.descargaId = descargas.descargaId', 'LEFT');
        $query = $this->db->get('descargas');
        return $query->result();
    }

    public function getItemsForProductosDestacados($categoriaId = 'todas', $numItems, $actualPagePagination, $lang)
    {

        //productos
        $this->db->join('productos', 'producto_categorias.id = productos.categoriaId', 'left');
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'left');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'left');
        $this->db->order_by('productos.productoPosicion', 'asc');
        if($categoriaId != 'todas')
            $this->db->where('productos.categoriaId', $categoriaId);
        $this->db->where('productos.productoDeldia', 's');
        $this->db->where('productoTemporal', 0);
        $this->db->limit($numItems, $actualPagePagination);
        $query = $this->db->get('producto_categorias');
        $productos = $query->result();

        //Campos
        $this->db->where('productoCampoVerModulo', 1);
        $this->db->where('productoCampoHabilitado', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();

        $CI =& get_instance();
        $CI->load->model('catalogo_model');
        return $CI->catalogo_model->procesaProductos($productos, $campos, $lang);

    }

    public function getItemsForProductosAzar($categoriaId = 'todas', $numItems, $actualPagePagination, $lang)
    {

        $subcategorias = array();

        //Get all subcategories.
        //TODO currently 1 level, fix multilevel and add checkbox to select or not the levels
        if($categoriaId != 'todas') {

            $tree = CatalogTree::find($categoriaId);
            $tree->lang = $lang;
            $tree->findChildren(9999);

            $subcategorias = $tree->getChildren();

        }

        //Get all the products
        $this->db->select('productoId');
        if($categoriaId != 'todas') {

            foreach ($subcategorias as $categoria) {
                $this->db->or_where('productos.categoriaId', $categoria->categoriaId);
            }

            $this->db->where('productos.categoriaId', $categoriaId);
        }

        $this->db->where('productoTemporal', 0);

        $query = $this->db->get('productos');

        $productosTodos = $query->result_array();

        //Get random numbers as array
        $randArr = $this->UniqueRandomNumbersWithinRange(0,count($productosTodos) - 1,$numItems);
        $randIdsArr = array();

        foreach ($randArr as $id) {
			if(isset($productosTodos[$id])) {
				array_push($randIdsArr, $productosTodos[$id]['productoId']);
			}
        }

		if(count($randIdsArr) === 0) {
			return [];
		}

        //productos
        $this->db->join('productos', 'producto_categorias.id = productos.categoriaId', 'left');
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'left');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'left');
        $this->db->order_by('productos.productoPosicion', 'asc');
        $this->db->where_in('productos.productoId', $randIdsArr);
        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('producto_categorias');
        $productos = $query->result();

        //Campos
        $this->db->where('productoCampoVerModulo', 1);
        $this->db->where('productoCampoHabilitado', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();

        $CI =& get_instance();
        $CI->load->model('catalogo_model');
        return $CI->catalogo_model->procesaProductos($productos, $campos, $lang);

    }

    public function getItemsForProductosDestacadosAzar($categoriaId = 'todas', $numItems, $actualPagePagination, $lang)
    {

        //Get all the products
        $this->db->select('productoId');
        if($categoriaId != 'todas')
            $this->db->where('productos.categoriaId', $categoriaId);
        $this->db->where('productos.productoDeldia', 's');
        $this->db->where('productoTemporal', 0);

        $query = $this->db->get('productos');

        $productosTodos = $query->result_array();

        //Get random numbers as array
        $randArr = $this->UniqueRandomNumbersWithinRange(0,count($productosTodos) - 1,$numItems);
        $randIdsArr = array();

		foreach ($randArr as $id) {
			if(isset($productosTodos[$id])) {
				array_push($randIdsArr, $productosTodos[$id]['productoId']);
			}
		}

		if(count($randIdsArr) === 0) {
			return [];
		}

        //productos
        $this->db->join('productos', 'producto_categorias.id = productos.categoriaId', 'left');
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'left');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'left');
        $this->db->order_by('productos.productoPosicion', 'asc');
        $this->db->where_in('productos.productoId', $randIdsArr);
        $this->db->where('productos.productoDeldia', 's');
        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('producto_categorias');
        $productos = $query->result();

        //Campos
        $this->db->where('productoCampoVerModulo', 1);
        $this->db->where('productoCampoHabilitado', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();

        $CI =& get_instance();
        $CI->load->model('catalogo_model');
        return $CI->catalogo_model->procesaProductos($productos, $campos, $lang);

    }

    public function getItemsForServicios($numItems, $actualPagePagination, $lang, $paginaId, $destacados = FALSE)
    {
        $this->db->order_by('servicioPosicion', 'ASC');
        $this->db->limit($numItems, $actualPagePagination);
		$this->db->where('servicioTemporal', 0);
		$this->db->where('paginaId', $paginaId);

		if($destacados) {
			$this->db->where('servicioDestacado', 1);
		}

        $this->db->join($lang.'_servicios', $lang.'_servicios.servicioId = servicios.servicioId', 'LEFT');
        $query = $this->db->get('servicios');

		$servicios = $query->result();

		//Get the images
		foreach($servicios as $serv) {
			$serv->imagenes = $this->db->where('servicio_id', $serv->servicioId)->get('servicios_imagenes')->result();
		}

		return $servicios;

    }

    private function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    public function getArticles($paginaId)
    {
        $this->db->order_by("articuloPosicion", "asc");
        $this->db->where('paginaId', $paginaId);
        $query = $this->db->get('articulos');
        return $query->result();
    }

    public function getPublicaciones($pageId, $lang)
    {
        $this->db->join($lang.'_publicaciones', $lang.'_publicaciones.publicacionId = publicaciones.publicacionId', 'left');
        $this->db->join('paginas', 'publicaciones.paginaId = paginas.id', 'left');
        $this->db->join($lang.'_paginas', $lang.'_paginas.paginaId = paginas.id', 'left');
        $this->db->where('paginas.id', (int)$pageId);
        $this->db->order_by("publicacionFecha, publicaciones.publicacionId", "desc");
        $query = $this->db->get('publicaciones');

        return $query->result_array();
    }

    public function getFAQ($pageId, $lang)
    {
        $this->db->join($lang.'_faq', $lang.'_faq.faqId = faq.faqId', 'LEFT');
        $this->db->join('paginas', 'faq.paginaId = paginas.id', 'left');
        $this->db->join($lang.'_paginas', $lang.'_paginas.paginaId = paginas.id', 'left');
        $this->db->where('paginas.id', (int)$pageId);
        $this->db->order_by("faqPosicion", "ASC");
        $query = $this->db->get('faq');

        return $query->result_array();
    }

    public function getEnlaces($pageId, $lang)
    {
        $this->db->join($lang.'_enlaces', $lang.'_enlaces.enlaceId = enlaces.enlaceId', 'LEFT');
        $this->db->order_by("enlacePosicion", "ASC");
        $query = $this->db->get('enlaces');

        return $query->result_array();
    }

    public function getGaleria($catId)
    {
        $this->db->where('descargaCategoriaId', (int)$catId);
        $this->db->order_by("descargaPosicion", "ASC");
        $query = $this->db->get('descargas');

        return $query->result_array();
    }

    public function getMapa($mapaId, $lang)
    {

        $this->db->where('mapaId', (int)$mapaId);
        $query = $this->db->get('mapas');
        $mapa = $query->row();

        if(count($mapa) > 0)
        {
            $this->db->where('mapaId', (int)$mapaId);
            $query = $this->db->get('mapas_ubicaciones');
            $ubicaciones = $query->result();

            //Campos
            $ubicacionArrIds = array();

            foreach ($ubicaciones as $ubicacion) {
                $ubicacionArrIds[] = $ubicacion->mapaUbicacionId;
            }

            $this->db->join('mapas_campos', 'mapas_campos.mapaCampoId = mapa_campo_rel.mapaCampoId');
            $this->db->join($lang.'_mapas_campos', $lang.'_mapas_campos.mapaCampoId = mapas_campos.mapaCampoId');
            $this->db->join($lang.'_mapa_campo_rel', $lang.'_mapa_campo_rel.mapaCampoRelId = mapa_campo_rel.mapaCampoRelId');
            $this->db->where_in('mapaUbicacionId', $ubicacionArrIds);
            $this->db->order_by('mapaCampoPosition', 'ASC');
            $query = $this->db->get('mapa_campo_rel');

            $campos = $query->result();

            foreach ($ubicaciones as $ubicacion) {
                foreach ($campos as $campo) {
                    if($campo->mapaUbicacionId === $ubicacion->mapaUbicacionId) {
                        $ubicacion->campos[] = $campo;
                    }
                }
            }

            $mapa->ubicaciones = $ubicaciones;
        }

        return $mapa;
    }

}