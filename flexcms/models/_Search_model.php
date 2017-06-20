<?php
class Search_model extends CI_Model
{

    var $m_maxChars = 50;

    public function articulos($value='', $lang = 'es')
    {
        $this->db->select('paginas.id as pagina_id, articulos.articuloId as id, articuloTitulo, articuloContenido, paginaClase, articuloClase, paginaNombre, paginaNombreURL');
        $this->db->join($lang.'_articulos', $lang.'_articulos.articuloId = articulos.articuloId', 'LEFT');
        $this->db->where("(`articulos`.`articuloHabilitado` =  'on')
                AND  (`articuloTitulo`  LIKE '%".$value."%'
                OR  `articuloContenido`  LIKE '%".$value."%')");
        $this->db->join('paginas', 'paginas.id = articulos.paginaId', 'LEFT');
        $this->db->join($lang.'_paginas', $lang.'_paginas.paginaId = paginas.id', 'LEFT');
        $query = $this->db->get('articulos');
        return $query->result();
    }

    public function faq($value='', $lang = 'es')
    {
        $this->db->join($lang.'_faq', $lang.'_faq.faqId = faq.faqId', 'LEFT');
        $this->db->where("(`faq`.`faqHabilitado` =  'on')
                AND  (`faqPregunta`  LIKE '%".$value."%'
                OR  `faqRespuesta`  LIKE '%".$value."%')");
        $query = $this->db->get('faq');
        return $query->result();
    }

    public function publicaciones($value='', $lang = 'es', $paginaId)
    {
        $this->db->select('publicaciones.publicacionId as id, publicaciones.paginaId, publicacionFecha, publicacionImagen, publicacionNombre, publicacionTexto, publicacionUrl, paginaNombreURL');
        $this->db->join($lang.'_publicaciones', $lang.'_publicaciones.publicacionId = publicaciones.publicacionId', 'LEFT');
        $this->db->join('paginas', 'paginas.id = publicaciones.paginaId', 'LEFT');
        $this->db->join($lang.'_paginas', 'paginas.id = ' . $lang . '_paginas.paginaId', 'LEFT');
        $this->db->where("(`publicaciones`.`paginaId` =  '".$paginaId."'
                AND `publicaciones`.`publicacionHabilitado` =  1)
                AND  (`publicacionNombre`  LIKE '%".$value."%'
                OR  `publicacionTexto`  LIKE '%".$value."%')");
        $query = $this->db->get('publicaciones');

        return $query->result();
    }

    public function productos($value='', $lang = 'es')
    {
        $value = explode(' ', $value);

        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'LEFT');
        $this->db->join('producto_campos_rel', 'producto_campos_rel.productoId = productos.productoId');
        $this->db->join($lang.'_producto_campos_rel', $lang.'_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId');
        $this->db->join('producto_categorias', 'producto_categorias.id = productos.categoriaId');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = productos.categoriaId');

        foreach ($value as $val) {
            $this->db->like('productoNombre', $val);
            $this->db->or_like('productoCampoRelContenido', $val);
        }

        $this->db->group_by('productos.productoId');
        $query = $this->db->get('productos');


        return $query->result();
    }

    public function descargas($value='', $lang = 'es')
    {
        $this->db->select('descargas.descargaId as id, descargaArchivo, descargaCategoriaId, descargaEnlace, descargaUrl, descargaFecha, descargaNombre');
        $this->db->join($lang.'_descargas', $lang.'_descargas.descargaId = descargas.descargaId', 'LEFT');
        $this->db->where("(`descargas`.`descargaEnabled` =  1)
                AND  (`descargaNombre`  LIKE '%".$value."%')");
        $query = $this->db->get('descargas');
        return $query->result();
    }

    public function getPageById($id, $lang = 'es')
    {
        $this->db->join('pagina_filas', 'pagina_filas.paginaFilaId = modulos.paginaFilaId', 'left');
        $this->db->join('paginas', 'paginas.id = pagina_filas.paginaId', 'left');
        $this->db->join($lang.'_paginas', $lang.'_paginas.paginaId = paginas.id', 'left');
        $this->db->where('paginas.id', $id);
        $query = $this->db->get('modulos');
        return $query->row();
    }

    public function catalogFilters($filters, $lang)
    {

        $campos = $filters->campos;
        $categorias = $filters->categorias;

        $resultArr = array();

        foreach ($campos as $key => $value) {

            $this->db->select('inputTipoId, productoCampoId')
                ->from('producto_campos')
                ->join('input', 'input.inputId = producto_campos.inputId', 'LEFT')
                ->where('productoCampoId', $value->id);

            $type = $this->db->get()->row();

            if($type->inputTipoId == 12){ //Es listado

                $this->db->select('productos.productoId')
                    ->from('productos')
                    ->join('producto_campos_listado_predefinido_rel', 'producto_campos_listado_predefinido_rel.productoId = productos.productoId')
                    ->join('producto_campos_listado_predefinido', 'producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido_rel.productoCamposListadoPredefinidoId')
                    ->join($lang.'_producto_campos_listado_predefinido', $lang.'_producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido.productoCamposListadoPredefinidoId')
                    ->where('productoTemporal', 0)
                    ->where('productoEnable', 's');

                if(isset($value->filters)) //Template normal
                {
                    foreach ($value->filters as $valueFilter) {
                        $this->db->or_like('productoCamposListadoPredefinidoTexto', $valueFilter);
                    }
                }

                $productos = $this->db->get()->result();

                $resultArr[$value->id] = $productos;

            } else {
                $this->db->select('productoId');
                $this->db->join($lang.'_producto_campos_rel', $lang.'_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId', 'LEFT');

                if(isset($value->filters))
                {
                    foreach ($value->filters as $key => $valueFilter) {
                        $this->db->or_like('productoCampoRelContenido', $valueFilter);
                    }
                }
                else
                {
                    $this->db->where('productoCampoId', $value->id);
                }

                $this->db->group_by('productoId');

                $query = $this->db->get('producto_campos_rel');

                $result =  $query->result();

                $resultArr[$value->id] = $result;
            }



        }

        if(count($categorias) > 0)
        {
            $this->db->select('productos.productoId');
            $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'LEFT');
            $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = productos.categoriaId', 'LEFT');
            $this->db->where_in('productoCategoriaUrl', $categorias);
            $query = $this->db->get('productos');
            $productos = $query->result();

            array_push($resultArr, $productos);
        }


        return $resultArr;

    }

    function servicios($query)
    {
        $this->db->select('servicios.servicioId as id');
        $this->db->join('es_servicios', 'es_servicios.servicioId = servicios.servicioId', 'LEFT');
        $this->db->like('servicioTitulo', $query);
        $query = $this->db->get('servicios');
        return $query->result();
    }

}