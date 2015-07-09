<?php

class Catalogo_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function getItems()
	{
		//$this->db->order_by("articuloPosicion", "asc");
		$query = $this->db->get('productos');

		return $query->result_array();
	}

	public function getCategories()
	{
		//$this->db->select('producto_categorias.id AS productoCategoriaId, productoCategoriaNombre');
		$this->db->join('es_producto_categorias', 'es_producto_categorias.productoCategoriaId = producto_categorias.id', 'LEFT');
		$query = $this->db->get('producto_categorias');

		return $query->result_array();
	}

	public function getCategory($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('producto_categorias');

		return $query->row();
	}

	public function updateCategory($general)
	{
		$id = $this->uri->segment(4);

		$data = array(
			'categoriaImagen' => $this->input->post('categoriaImagen'),
            'categoriaImagenCoord' => urldecode($this->input->post('categoriaImagenCoord')),
            'temporal' => 0
		);

		$this->db->where('id', $id);
		$this->db->update('producto_categorias', $data);
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];

            $this->db->where('productoCategoriaId', $id);
            $query = $this->db->get($dim.'_producto_categorias');

            if($query->result()){

                $dataIdioma = array(
                    'productoCategoriaNombre' => $this->input->post($dim.'_productoCategoriaNombre'),
                    'productoCategoriaDescripcion' => $this->input->post($dim.'_productoCategoriaDescripcion'),
                    'productoCategoriaUrl' => $general->generateSafeUrl($this->input->post($dim.'_productoCategoriaNombre'))
                );

                $this->db->where('productoCategoriaId', $id);
                $this->db->update($dim.'_producto_categorias', $dataIdioma);
            } else {

                $dataIdioma = array(
                    'productoCategoriaId' => $id,
                    'productoCategoriaNombre' => $this->input->post($dim.'_productoCategoriaNombre'),
                    'productoCategoriaDescripcion' => $this->input->post($dim.'_productoCategoriaDescripcion'),
                    'productoCategoriaUrl' => $general->generateSafeUrl($this->input->post($dim.'_productoCategoriaNombre'))
                );

                $this->db->insert($dim.'_producto_categorias', $dataIdioma);

            }
			

			

			
		}

	}

	public function insertCategory($general)
	{

		$node = new CatalogTree(array('temporal' => 1));
		$node->makeLastChildOf(CatalogTree::find(1));
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$dataIdioma = array(
				'productoCategoriaId' => $node->id,
				'productoCategoriaNombre' => $this->input->post($dim.'_productoCategoriaNombre'),
				'productoCategoriaDescripcion' => $this->input->post($dim.'_productoCategoriaDescripcion'),
                'productoCategoriaUrl' => $general->generateSafeUrl($this->input->post($dim.'_productoCategoriaNombre'))
			);
			
			$this->db->insert($dim.'_producto_categorias', $dataIdioma);
			
		}

        return $node->id;
		
	}

	//Miguel
	public function idiomas()//funcion para saber la cantidad de idioma que existe en
	// DB
	{
		$idiomas = array();

		$query = $this->db->get('idioma');

		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach ($result as $row)
			{
				//array con indice del id del idioma y con el diminutivoddel idioma
				//$idiomas[$row->idiomaId] = $row->idiomaDiminutivo;
				$idiomas[$row->idiomaId] = array(
					'diminutivo' => $row->idiomaDiminutivo,
					'nombre' => $row->idiomaNombre
				);
			}
		}
		else
		{
			$idiomas = 0;
		}
		return $idiomas;
	}

	/*funciones para crear,eliminar, modificar campos de producto*/
	//obtener campo
	function getCampos()
	{
		$this->db->select('producto_campos.productoCampoId AS productoCampoId, productoCampoValor');
		$this->db->join('es_producto_campos', 'es_producto_campos.productoCampoId = producto_campos.productoCampoId');
		$this->db->order_by('productoCampoPosicion', 'ASC');
		$query = $this->db->get('producto_campos');

		return $query->result_array();
	}

	//obtener datos de un campo
	function getDatosCampo($campoId)
	{
		$this->db->where('productoCampoId', $campoId);
		$query = $this->db->get('producto_campos');
		return $query->row();
	}

	//obtener Inputs
	function getInputs()
	{
		$this->db->where('input_seccion', 'producto');
		$this->db->order_by('inputTipoContenido', 'asc');
		$query = $this->db->get('input');

		return $query->result();
	}

	//guardar campo
	function guardarCampo($general)
	{
        $productoCampoMostrarNombre = 0;
		$productoCampoVerModulo = 0;
		$productoCampoVerListado = 0;
		$productoCampoVerPedido = 0;
		$productoCampoHabilitado = 0;
		$productoCampoVerFiltro = 0;

        if ($this->input->post('productoCampoMostrarNombre') == 'on')
            $productoCampoMostrarNombre = 1;
		
		if ($this->input->post('productoCampoVerModulo') == 'on')
			$productoCampoVerModulo = 1;
			
		if ($this->input->post('productoCampoVerListado') == 'on')
			$productoCampoVerListado = 1;
		
		if ($this->input->post('productoCampoVerPedido') == 'on')
			$productoCampoVerPedido = 1;
		
		if ($this->input->post('productoCampoVerFiltro') == 'on')
			$productoCampoVerFiltro = 1;
			
		if ($this->input->post('productoCampoHabilitado') == 'on')
			$productoCampoHabilitado = 1;
		
		$data = array(
			'inputId' => $this->input->post('inputId'),
            'productoCampoMostrarNombre' => $productoCampoMostrarNombre,
			'productoCampoVerModulo' => $productoCampoVerModulo,
			'productoCampoVerListado' => $productoCampoVerListado,
			'productoCampoVerPedido' => $productoCampoVerPedido,
			'productoCampoVerFiltro' => $productoCampoVerFiltro,
			'productoCampoHabilitado' => $productoCampoHabilitado,
			'productoCampoClase' => $this->input->post('productoCampoClase'),
			'productoCampoPosicion' => $general->generarId('producto_campos')
		);

		$this->db->insert('producto_campos', $data);
		$lastInsertId = $this->db->insert_id();
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$dataIdioma = array(
				'productoCampoId' => $lastInsertId,
				'productoCampoValor' => $this->input->post($dim.'_productoCampoValor')
			);
			
			$this->db->insert($dim.'_producto_campos', $dataIdioma);
			
		}
		
		//Create fields for each existing product if there are
		$productos = $this->getProductos();

		foreach ($productos as $key => $producto) {
			$dataCampo = array(
				'productoId' => $producto->id,
				'productoCampoId' => $lastInsertId
			);
			
			$this->db->insert('producto_campos_rel', $dataCampo);
			$lastInsertCampoId = $this->db->insert_id();
			
			foreach ($idiomas as $key => $idioma) {
			
				$dim = $idioma['idiomaDiminutivo'];
				
				$dataIdiomaCampo = array(
					'productoCampoRelId' => $lastInsertCampoId,
					'productoCampoRelContenido' => ''
				);
				
				$this->db->insert($dim.'_producto_campos_rel', $dataIdiomaCampo);
				
			}
			
		}

        return $lastInsertId;

	}

	//actualizar CAmpo
	public function updateCampo($general)
	{
		$campoId = $this->uri->segment(4);

        $productoCampoMostrarNombre = 0;
		$productoCampoVerModulo = 0;
		$productoCampoVerListado = 0;
		$productoCampoVerPedido = 0;
		$productoCampoHabilitado = 0;
		$productoCampoVerFiltro = 0;

        if ($this->input->post('productoCampoMostrarNombre') == 'on')
            $productoCampoMostrarNombre = 1;
		
		if ($this->input->post('productoCampoVerModulo') == 'on')
			$productoCampoVerModulo = 1;
			
		if ($this->input->post('productoCampoVerListado') == 'on')
			$productoCampoVerListado = 1;
		
		if ($this->input->post('productoCampoVerPedido') == 'on')
			$productoCampoVerPedido = 1;
		
		if ($this->input->post('productoCampoVerFiltro') == 'on')
			$productoCampoVerFiltro = 1;
			
		if ($this->input->post('productoCampoHabilitado') == 'on')
			$productoCampoHabilitado = 1;
		
		$data = array(
			'inputId' => $this->input->post('inputId'),
            'productoCampoMostrarNombre' => $productoCampoMostrarNombre,
			'productoCampoVerModulo' => $productoCampoVerModulo,
			'productoCampoVerListado' => $productoCampoVerListado,
			'productoCampoVerPedido' => $productoCampoVerPedido,
			'productoCampoVerFiltro' => $productoCampoVerFiltro,
			'productoCampoHabilitado' => $productoCampoHabilitado,
			'productoCampoClase' => $this->input->post('productoCampoClase'),
			'productoCampoPosicion' => $general->generarId('producto_campos')
		);

		$this->db->where('productoCampoId', $campoId);
		$this->db->update('producto_campos', $data);
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$dataIdioma = array(
				'productoCampoId' => $campoId,
				'productoCampoValor' => $this->input->post($dim.'_productoCampoValor')
			);
			
			//Revisamos si existe
			$this->db->where('productoCampoId', $campoId);
			$query = $this->db->get($dim.'_producto_campos');
			$result = $query->row();
			
			if(count($result) > 0)
			{
				$this->db->where('productoCampoId', $campoId);
        		$this->db->update($dim.'_producto_campos', $dataIdioma);
			}
			else
				$this->db->insert($dim.'_producto_campos', $dataIdioma);
			
		}

		
	}

	//borrar campo
	function deleteCampo()
	{
		$id = $this->uri->segment(4);
		$this->db->where('productoCampoId', $id);
		$this->db->delete('producto_campos');
	}

	/******producto*************/
	//obtener productos
	function getProductos($catId = false)
	{
		$this->db->select('es_productos.productoId AS id, productoNombre, categoriaId');
		$this->db->join('es_productos', 'es_productos.productoId = productos.productoId', 'LEFT');
        $this->db->where('productoTemporal', 0);
        if($catId)
            $this->db->where('categoriaId', $catId);
		$this->db->order_by('productoPosicion', 'asc');
		$query = $this->db->get('productos');

		$result = $query->result();
		return $result;
	}

	//obtener categorias
	function getCategorias()
	{
		$consulta = "SELECT id,categoriaNombre FROM producto_categorias ORDER BY categoriaNombre asc";
		$query = $this->db->query($consulta);
		return $query->result();
	}

	//obtener datos de un campo
	function getDatosProducto($id)
	{
		$query = $this->db->get_where('productos', array('productoId' => $id));
		return $query->row();
	}

	function getProductoCampoRel()
	{
		$productoId = $this->uri->segment(4);
		return $this->db
			->where("productoId", $productoId)
			->get("producto_campos")
			->result();
	}

	//guardar campo
	function guardarProducto($general)
	{

        $categoria = $this->db->get('producto_categorias')->row();

        if(count($categoria) === 0) {
            $data['return'] = 'Necesita primero crear una categoría';
            $this->output->set_status_header('405');
            $this->load->view('admin/request/html', $data);
            return false;
        }


        /*
         * Inserto producto temporal
         */
		$data = array(
            'categoriaId' => $categoria->id,
			'productoTemporal' => 1
		);

		$this->db->insert('productos', $data);
		$lastInsertId = $this->db->insert_id();
		
		$idiomas = $this->getLanguages();

        /*
         * Inserto los datos temporales
         */
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$dataIdioma = array(
				'productoId' => $lastInsertId,
				'productoNombre' => ''
			);
			
			$this->db->insert($dim.'_productos', $dataIdioma);
			
		}

		//inserto relacion campos_producto
		$result = $this->camposEntradas();

		foreach ($result as $row)
		{
			if ($row->inputTipoNombre != 'imagen')
			{
				
				$data = array(
					'productoId' => $lastInsertId,
					'productoCampoId' => $row->productoCampoId,
				);

				$this->db->insert('producto_campos_rel', $data);
				$lastInsertInputId = $this->db->insert_id();
				
				$idiomas = $this->getLanguages();
		
				foreach ($idiomas as $key => $idioma) {
					
					$dim = $idioma['idiomaDiminutivo'];
					
					$dataIdioma = array(
						'productoCampoRelId' => $lastInsertInputId,
						'productoCampoRelContenido' => ''
					);
					
					$this->db->insert($dim.'_producto_campos_rel', $dataIdioma);
					
				}
			}
		}

        return $lastInsertId;

	}

	//actualizar CAmpo
    public function updateProducto($general)
    {

        $productoImagen = trim($this->input->post('productoImagen'));

        $productoDeldia = $this->input->post('productoDeldia');

        if ($productoDeldia != 's')
        {
            $productoDeldia = 'n';
        }

        $productoEnable = $this->input->post('productoEnable');

        if ($productoEnable != 's')
        {
            $productoEnable = 'n';
        }

        $extension = '';

        if($productoImagen != '')
        {
            $extension = preg_replace('/\?+\d{0,}/', '', $productoImagen) . '?' . time();
        }

        $data = array(
            'productoDeldia' => $productoDeldia,
            'categoriaId' => $this->input->post('categoriaId'),
			'stock_quantity' => $this->input->post('stock_quantity'),
			'weight' => $this->input->post('weight'),
            'productoEnable' => $productoEnable,
            'productoImagenExtension' => $extension,
            'productoImagenCoord' => urldecode($this->input->post('productoImagenCoord')),
            'productoTemporal' => 0
        );

        $productoId = $this->input->post('productoId');

        $this->db->where('productoId', $productoId);
        $this->db->update('productos', $data);

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $this->db->where('productoId', $productoId);
            $query = $this->db->get($dim.'_productos');

            if($query->result()){
                $dataIdioma = array(
                    'productoNombre' => $this->input->post($dim.'_productoNombre'),
                    'productoKeywords' => $this->input->post($dim.'_productoKeywords'),
                    'productoDescripcion' => $this->input->post($dim.'_productoDescripcion'),
                    'productoMetaTitulo' => $this->input->post($dim.'_productoMetaTitulo'),
                    'productoUrl' => $general->generateSafeUrl($this->input->post($dim.'_productoNombre'))
                );

                $this->db->where('productoId', $productoId);
                $this->db->update($dim.'_productos', $dataIdioma);
            } else {

                $dataIdioma = array(
                    'productoId' => $productoId,
                    'productoNombre' => $this->input->post($dim.'_productoNombre'),
                    'productoKeywords' => $this->input->post($dim.'_productoKeywords'),
					'productoDescripcion' => $this->input->post($dim.'_productoDescripcion'),
					'productoMetaTitulo' => $this->input->post($dim.'_productoMetaTitulo'),
                    'productoUrl' => $general->generateSafeUrl($this->input->post($dim.'_productoNombre'))
                );

                $this->db->insert($dim.'_productos', $dataIdioma);
            }

        }

        //inserto relacion campos_producto
        $result = $this->camposEntradas();

        foreach ($result as $row)
        {
            if ($row->inputTipoNombre != 'imagen')
            {

                $this->db->where('productoCampoId', $row->productoCampoId);
                $this->db->where('productoId', $productoId);
                $query = $this->db->get('producto_campos_rel');
                $campoProducto = $query->row();

                $idiomas = $this->getLanguages();

                foreach ($idiomas as $idioma) {

                    $dim = $idioma['idiomaDiminutivo'];

                    $this->db->where('productoCampoRelId', $campoProducto->productoCampoRelId);
                    $query = $this->db->get($dim.'_producto_campos_rel');

                    if($query->result()) {
                        $dataIdioma = array(
                            'productoCampoRelContenido' => $this->input->post($dim . '_' .$row->productoCampoId)
                        );

                        $this->db->where('productoCampoRelId', $campoProducto->productoCampoRelId);
                        $this->db->update($dim.'_producto_campos_rel', $dataIdioma);
                    } else {
                        $dataIdioma = array(
                            'productoCampoRelId' => $campoProducto->productoCampoRelId,
                            'productoCampoRelContenido' => $this->input->post($dim . '_' .$row->productoCampoId)
                        );

                        $this->db->insert($dim.'_producto_campos_rel', $dataIdioma);
                    }
                }
            }
        }

    }

	function deleteProducto()
	{
		$this->db->where('productoId', $this->uri->segment(4));
		$this->db->delete('productos');
	}

	public function reorderProducts($categoriaId)
	{

		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$productos = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos
		$this->db->where('categoriaId', $categoriaId);
		$this->db->order_by("productoPosicion", "asc");
		$query = $this->db->get('productos');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las campos segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{
			$data = array('productoPosicion' => $i + 1);

            if (array_key_exists($i, $productos)) {
                $this->db->where('productoId', $productos[$i]);
                $this->db->where('categoriaId', $categoriaId);
                $this->db->update('productos', $data);
            }

		}

	}

	//consulto campos y entradas
	function camposEntradas($productoId = NULL)
	{
		$this->db->select('producto_campos.productoCampoId AS productoCampoId, inputTipoContenido, inputTipoNombre, productoCampoValor');
		$this->db->join('es_producto_campos', 'es_producto_campos.productoCampoId = producto_campos.productoCampoId', 'left');
		$this->db->join('input', 'input.inputId = producto_campos.inputId', 'left');
		$this->db->join('input_tipo', 'input.inputTipoId = input_tipo.inputTipoId', 'left');
		$this->db->where('productoCampoHabilitado', 1);
		$this->db->order_by('producto_campos.productoCampoPosicion', 'asc');
		$query = $this->db->get('producto_campos');
		return $query->result();
	}

	function camposEntradaValor($productoId, $campoId, $dim)
	{
		$this->db->select('producto_campos.productoCampoId AS productoCampoId, inputTipoContenido, inputTipoNombre, productoCampoValor, productoCampoRelContenido');
		$this->db->join('producto_campos_rel', 'producto_campos_rel.productoCampoId = producto_campos.productoCampoId', 'left');
		$this->db->join($dim . '_producto_campos_rel', $dim . '_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId', 'left');
		$this->db->join('es_producto_campos', 'es_producto_campos.productoCampoId = producto_campos.productoCampoId', 'left');
		$this->db->join('input', 'input.inputId = producto_campos.inputId', 'left');
		$this->db->join('input_tipo', 'input.inputTipoId = input_tipo.inputTipoId', 'left');
		$this->db->where('producto_campos_rel.productoId', $productoId);
		$this->db->where('producto_campos.productoCampoId', $campoId);
		$this->db->order_by('producto_campos.productoCampoPosicion', 'asc');
		$query = $this->db->get('producto_campos');
		return $query->row();
	}

	public function reorderTemplateElements()
	{

		//Obtenemos el string que viene del ajax en JSON y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todos los campos
		$query = $this->db->get('producto_campos');

		//Obtenemos el numero de campos totales
		$numCampos = $query->num_rows();

		//Ordenamos las campos segun el orden del arreglo de IDs
		for ($i = 0; $i < $numCampos; $i++)
		{
			$data = array('productoCampoPosicion' => $i + 1);

			$this->db->where('productoCampoId', $id[$i]);
			$this->db->update('producto_campos', $data);

		}

	}

	/*
	 * PRODUCT IMAGES
	 */

	public function getProductImages($productId = '')
	{
		$this->db->where('productoId', $productId);
		$this->db->order_by('productoImagenPosicion', 'asc');
		$query = $this->db->get('producto_imagenes');

		return $query->result_array();
	}
	
	public function getProductImage($productImageId)
	{
		$this->db->where('productoImagenId', $productImageId);
		$query = $this->db->get('producto_imagenes');

		return $query->row();
	}
	
	public function insertProductImage()
	{
		
		$productoImagenEnabled = 0;
        $extension = '';
		
		if($this->input->post('productoImagenEnabled') == 'on')
			$productoImagenEnabled = 1;

        if($this->input->post('productoImagen') != '')
            $extension = preg_replace('/\?+\d{0,}/', '', $this->input->post('productoImagen')) . '?' . time();

		$data = array(
            'productoImagen' => $extension,
			'productoId' => $this->input->post('productoId'),
			'productoImagenNombre' => $this->input->post('productoImagenNombre'),
            'productoImagenCoord' => $this->input->post('productoImagenCoord'),
			'productoImagenEnabled' => $productoImagenEnabled
		);

		$this->db->insert('producto_imagenes', $data);
		$lastInsertId = $this->db->insert_id();
		
		$idiomas = $this->getLanguages();
		
		foreach ($idiomas as $key => $idioma) {
			
			$dim = $idioma['idiomaDiminutivo'];
			
			$dataIdioma = array(
				'productoImagenId' => $lastInsertId,
				'productoImagenTexto' => $this->input->post($dim.'_productoImagenDescripcion'),
			);
			
			$this->db->insert($dim.'_producto_imagenes', $dataIdioma);
			
		}

        return $lastInsertId;
		
	}
	
	public function updateProductImage()
	{
		$productoImagenEnabled = 0;
		
		if($this->input->post('productoImagenEnabled') == 'on')
			$productoImagenEnabled = 1;

        if($this->input->post('productoImagen') != '')
            $extension = preg_replace('/\?+\d{0,}/', '', $this->input->post('productoImagen'));

		$data = array(
            'productoImagen' => $extension . '?' . time(),
			'productoImagenNombre' => $this->input->post('productoImagenNombre'),
			'productoImagenEnabled' => $productoImagenEnabled,
            'productoImagenCoord' => urldecode($this->input->post('productoImagenCoord')),
		);

		$this->db->where('productoImagenId', $this->input->post('productoImagenId'));
		$this->db->update('producto_imagenes', $data);
		
		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'productoImagenTexto' => $this->input->post($dim.'_productoImagenDescripcion'),
			);

			$image = $this->db
				->where('productoImagenId', $this->input->post('productoImagenId'))
				->get($dim.'_producto_imagenes');

			if($image->row()){
				$this->db->where('productoImagenId', $this->input->post('productoImagenId'));
				$this->db->update($dim.'_producto_imagenes', $dataIdioma);
			} else {
				$dataIdioma['productoImagenId'] = $this->input->post('productoImagenId');
				$this->db->insert($dim.'_producto_imagenes', $dataIdioma);
			}

		}
		
	}

	public function deleteProductImage($id='')
	{
		$this->db->where('productoImagenId', $id);
		$this->db->delete('producto_imagenes');
	}

    public function reorderProductImages($productId)
    {

        //Obtenemos el string que viene del ajax en JSON y lo transformamos a array
        $posiciones = json_decode($this->input->post('posiciones'), true);

        //Obtenemos todos los campos
        $this->db->where('productoId', $productId);
        $this->db->order_by("productoImagenPosicion", "asc");
        $query = $this->db->get('producto_imagenes');

        //Obtenemos el numero de campos totales
        $numCampos = $query->num_rows();

        //Ordenamos las campos segun el orden del arreglo de IDs
        for ($i = 0; $i < $numCampos; $i++)
        {
            $data = array('productoImagenPosicion' => $i + 1);

            $this->db->where('productoImagenId', $posiciones[$i]);
            $this->db->update('producto_imagenes', $data);

        }

    }

    /*
    * PRODUCT FILES
    */

    public function getProductFiles($productId = '')
    {
        $this->db->where('productoId', $productId);
        $this->db->order_by('productoDescargaPosicion', 'asc');
        $query = $this->db->get('producto_descargas');

        return $query->result_array();
    }

    public function getProductFile($productFileId)
    {
        $this->db->where('productoDescargaId', $productFileId);
        $query = $this->db->get('producto_descargas');

        return $query->row();
    }

    public function insertProductFile()
    {

        $productoDescargaEnabled = 0;

        if($this->input->post('productoDescargaEnabled') == 'on')
            $productoDescargaEnabled = 1;

        $data = array(
            'productoDescargaArchivo' => $this->input->post('productoDescargaArchivo'),
            'productoId' => $this->input->post('productoId'),
            'productoDescargaNombre' => $this->input->post('productoDescargaNombre'),
            'productoDescargaEnabled' => $productoDescargaEnabled
        );

        $this->db->insert('producto_descargas', $data);
        $lastInsertId = $this->db->insert_id();

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $dataIdioma = array(
                'productoDescargaId' => $lastInsertId,
                'productoDescargaTexto' => $this->input->post($dim.'_productoDescargaDescripcion'),
            );

            $this->db->insert($dim.'_producto_descargas', $dataIdioma);

        }

        return $lastInsertId;

    }

    public function updateProductFile()
    {
        $productoDescargaEnabled = 0;

        if($this->input->post('productoDescargaEnabled') == 'on')
            $productoDescargaEnabled = 1;

        $data = array(
            'productoDescargaArchivo' => $this->input->post('productoDescargaArchivo'),
            'productoDescargaNombre' => $this->input->post('productoDescargaNombre'),
            'productoDescargaEnabled' => $productoDescargaEnabled
        );

        $this->db->where('productoDescargaId', $this->input->post('productoDescargaId'));
        $this->db->update('producto_descargas', $data);

        $idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];

			$dataIdioma = array(
				'productoDescargaTexto' => $this->input->post($dim.'_productoDescargaDescripcion'),
			);

			$file = $this->db
				->where('productoDescargaId', $this->input->post('productoDescargaId'))
				->get($dim.'_producto_descargas');

			if($file->row()){
				$this->db->where('productoDescargaId', $this->input->post('productoDescargaId'));
				$this->db->update($dim.'_producto_descargas', $dataIdioma);
			} else {
				$dataIdioma['productoDescargaId'] = $this->input->post('productoDescargaId');
				$this->db->insert($dim.'_producto_descargas', $dataIdioma);
			}


		}

    }

    public function deleteProductFile($id='')
    {
        $this->db->where('productoDescargaId', $id);
        $this->db->delete('producto_descargas');
    }

    public function reorderProductFiles($productId)
    {

        //Obtenemos el string que viene del ajax en JSON y lo transformamos a array
        $posiciones = json_decode($this->input->post('posiciones'), true);

        //Obtenemos todos los campos
        $this->db->where('productoId', $productId);
        $this->db->order_by("productoDescargaPosicion", "asc");
        $query = $this->db->get('producto_descargas');

        //Obtenemos el numero de campos totales
        $numCampos = $query->num_rows();

        //Ordenamos las campos segun el orden del arreglo de IDs
        for ($i = 0; $i < $numCampos; $i++)
        {
            $data = array('productoDescargaPosicion' => $i + 1);

            $this->db->where('productoDescargaId', $posiciones[$i]);
            $this->db->update('producto_descargas', $data);

        }

    }


    /*
	 * PRODUCT VIDEOS
	 */

    public function getProductVideos($productId = '')
    {
        $this->db->where('productoId', $productId);
        $this->db->order_by('productoVideoPosicion', 'asc');
        $query = $this->db->get('producto_videos');

        return $query->result_array();
    }

    public function getProductVideo($productVideoId)
    {
        $this->db->where('productoVideoId', $productVideoId);
        $query = $this->db->get('producto_videos');

        return $query->row();
    }

    public function insertProductVideo()
    {

        $productoVideoEnabled = 0;

        if($this->input->post('productoVideoEnabled') == 'on')
            $productoVideoEnabled = 1;

        $data = array(
            'productoId' => $this->input->post('productoId'),
            'productoVideo' => $this->input->post('productoVideo'),
            'productoVideoNombre' => $this->input->post('productoVideoNombre'),
            'productoVideoEnabled' => $productoVideoEnabled
        );

        $this->db->insert('producto_videos', $data);
        $lastInsertId = $this->db->insert_id();

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $dataIdioma = array(
                'productoVideoId' => $lastInsertId,
                'productoVideoTexto' => $this->input->post($dim.'_productoVideoDescripcion'),
            );

            $this->db->insert($dim.'_producto_videos', $dataIdioma);

        }

        return $lastInsertId;


    }

    public function updateProductVideo()
    {
        $productoVideoEnabled = 0;

        if($this->input->post('productoVideoEnabled') == 'on')
            $productoVideoEnabled = 1;

        $data = array(
            'productoVideo' => $this->input->post('productoVideo'),
            'productoVideoNombre' => $this->input->post('productoVideoNombre'),
            'productoVideoEnabled' => $productoVideoEnabled
        );

        $this->db->where('productoVideoId', $this->input->post('productoVideoId'));
        $this->db->update('producto_videos', $data);

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $dataIdioma = array(
                'productoVideoTexto' => $this->input->post($dim.'_productoVideoDescripcion'),
            );

            $this->db->where('productoVideoId', $this->input->post('productoVideoId'));
            $this->db->update($dim.'_producto_videos', $dataIdioma);

        }

    }

    public function deleteProductVideo($id='')
    {
        $this->db->where('productoVideoId', $id);
        $this->db->delete('producto_videos');
    }

    public function reorderProductVideos($productId)
    {

        //Obtenemos el string que viene del ajax en JSON y lo transformamos a array
        $posiciones = json_decode($this->input->post('posiciones'), true);

        //Obtenemos todos los campos
        $this->db->where('productoId', $productId);
        $this->db->order_by("productoVideoPosicion", "asc");
        $query = $this->db->get('producto_videos');

        //Obtenemos el numero de campos totales
        $numCampos = $query->num_rows();

        //Ordenamos las campos segun el orden del arreglo de IDs
        for ($i = 0; $i < $numCampos; $i++)
        {
            $data = array('productoVideoPosicion' => $i + 1);

            $this->db->where('productoVideoId', $posiciones[$i]);
            $this->db->update('producto_videos', $data);

        }

    }


    /*
	 * PRODUCT AUDIOS
	 */

    public function getProductAudios($productId = '')
    {
        $this->db->where('productoId', $productId);
        $this->db->order_by('productoAudioPosicion', 'asc');
        $query = $this->db->get('producto_audios');

        return $query->result_array();
    }

    public function getProductAudio($id)
    {
        $this->db->where('productoAudioId', $id);
        $query = $this->db->get('producto_audios');

        return $query->row();
    }

    public function insertProductAudio()
    {

        $productoAudioEnabled = 0;

        if($this->input->post('productoAudioEnabled') == 'on')
            $productoAudioEnabled = 1;

        $this->db->from('producto_audios');
        $this->db->where('productoId', $this->input->post('productoId'));
        $query = $this->db->get();
        $rowcount = $query->num_rows();

        $data = array(
            'productoAudioExtension' => $this->input->post('productoAudioExtension'),
            'productoId' => $this->input->post('productoId'),
            'productoAudioNombre' => $this->input->post('productoAudioNombre'),
            'productoAudioPosicion' => $rowcount + 1,
            'productoAudioEnabled' => $productoAudioEnabled
        );

        $this->db->insert('producto_audios', $data);

        return $this->db->insert_id();

    }

    public function updateProductAudio()
    {
        $productoAudioEnabled = 0;

        if($this->input->post('productoAudioEnabled') == 'on')
            $productoAudioEnabled = 1;

        $data = array(
            'productoAudioExtension' => $this->input->post('productoAudioExtension'),
            'productoAudioNombre' => $this->input->post('productoAudioNombre'),
            'productoAudioEnabled' => $productoAudioEnabled
        );

        $this->db->where('productoAudioId', $this->input->post('productoAudioId'));
        $this->db->update('producto_audios', $data);

    }

    public function deleteProductAudio($id='')
    {
        $this->db->where('productoAudioId', $id);
        $this->db->delete('producto_audios');
    }

    public function reorderProductAudios($productId)
    {

        //Obtenemos el string que viene del ajax en JSON y lo transformamos a array
        $posiciones = json_decode($this->input->post('posiciones'), true);

        //Obtenemos todos los campos
        $this->db->where('productoId', $productId);
        $this->db->order_by("productoAudioPosicion", "asc");
        $query = $this->db->get('producto_audios');

        //Obtenemos el numero de campos totales
        $numCampos = $query->num_rows();

        //Ordenamos las campos segun el orden del arreglo de IDs
        for ($i = 0; $i < $numCampos; $i++)
        {
            $data = array('productoAudioPosicion' => $i + 1);

            $this->db->where('productoAudioId', $posiciones[$i]);
            $this->db->update('producto_audios', $data);

        }

    }

    /*
     * PRODUCTO LISTADO PREDEFINIDO
     */
    function getItemsPredefinidos($id) {
        $this->db->join('producto_campos', 'producto_campos.productoCampoId = producto_campos_listado_predefinido.productoCampoId', 'LEFT');
        $this->db->join('es_producto_campos_listado_predefinido', 'es_producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido.productoCamposListadoPredefinidoId', 'LEFT');
        $this->db->where('producto_campos_listado_predefinido.productoCampoId', $id);
        $this->db->order_by('productoCamposListadoPredefinidoPosicion', 'ASC');
        $query = $this->db->get('producto_campos_listado_predefinido');
        return $query->result_array();
    }

    function getItemPredefinido($id) {
        $this->db->join('producto_campos', 'producto_campos.productoCampoId = producto_campos_listado_predefinido.productoCampoId', 'LEFT');
        $this->db->join('es_producto_campos', 'es_producto_campos.productoCampoId = producto_campos.productoCampoId', 'LEFT');
        $this->db->where('producto_campos_listado_predefinido.productoCamposListadoPredefinidoId', $id);
        $query = $this->db->get('producto_campos_listado_predefinido');
        return $query->row();
    }

    function getItemsPredefinidosProducto($id, $campoId) {
        $this->db->join('producto_campos_listado_predefinido', 'producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido_rel.productoCamposListadoPredefinidoId', 'LEFT');
        $this->db->join('es_producto_campos_listado_predefinido', 'es_producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido.productoCamposListadoPredefinidoId', 'LEFT');
        $this->db->where('producto_campos_listado_predefinido_rel.productoId', $id);
        $this->db->where('producto_campos_listado_predefinido_rel.productoCampoId', $campoId);
        $this->db->group_by('producto_campos_listado_predefinido_rel.productoCamposListadoPredefinidoId');
        $query = $this->db->get('producto_campos_listado_predefinido_rel');
        return $query->result();
    }

    function insertarItemPredefinido(){
        $productoCamposListadoPredefinidoPublicado = 0;

        $posicion = $this->db->count_all('producto_campos_listado_predefinido');

        if($this->input->post('productoCamposListadoPredefinidoPublicado') == 'on')
            $productoCamposListadoPredefinidoPublicado = 1;

        $data = array(
            'productoCampoId' => $this->input->post('productoCampoId'),
            'productoCamposListadoPredefinidoClase' => $this->input->post('productoCamposListadoPredefinidoClase'),
            'productoCamposListadoPredefinidoPublicado' => $productoCamposListadoPredefinidoPublicado,
            'productoCamposListadoPredefinidoPosicion' => $posicion + 1
        );

        $this->db->insert('producto_campos_listado_predefinido', $data);
        $lastInsertId = $this->db->insert_id();

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $dataIdioma = array(
                'productoCamposListadoPredefinidoId' => $lastInsertId,
                'productoCamposListadoPredefinidoTexto' => $this->input->post($dim.'_productoCamposListadoPredefinidoTexto'),
            );

            $this->db->insert($dim.'_producto_campos_listado_predefinido', $dataIdioma);

        }

        return $lastInsertId;

    }

    function actualizarItemPredefinido(){

        $productoCamposListadoPredefinidoPublicado = 0;
        if($this->input->post('productoCamposListadoPredefinidoPublicado') == 'on')
            $productoCamposListadoPredefinidoPublicado = 1;

        $data = array(
            'productoCamposListadoPredefinidoClase' => $this->input->post('productoCamposListadoPredefinidoClase'),
            'productoCamposListadoPredefinidoPublicado' => $productoCamposListadoPredefinidoPublicado
        );

        $this->db->where('productoCamposListadoPredefinidoId', $this->input->post('productoCamposListadoPredefinidoId'));
        $this->db->update('producto_campos_listado_predefinido', $data);

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $this->db->where('productoCamposListadoPredefinidoId', $this->input->post('productoCamposListadoPredefinidoId'));
            $query = $this->db->get($dim.'_producto_campos_listado_predefinido');

            if($query->result()) {
                $dataIdioma = array(
                    'productoCamposListadoPredefinidoTexto' => $this->input->post($dim.'_productoCamposListadoPredefinidoTexto')
                );

                $this->db->where('productoCamposListadoPredefinidoId', $this->input->post('productoCamposListadoPredefinidoId'));
                $this->db->update($dim.'_producto_campos_listado_predefinido', $dataIdioma);
            } else {
                $dataIdioma = array(
                    'productoCamposListadoPredefinidoId' => $this->input->post('productoCamposListadoPredefinidoId'),
                    'productoCamposListadoPredefinidoTexto' => $this->input->post($dim.'_productoCamposListadoPredefinidoTexto')
                );

                $this->db->insert($dim.'_producto_campos_listado_predefinido', $dataIdioma);
            }

        }
    }

    function eliminarListadoPredefinido($id){
        $this->db->where('productoCamposListadoPredefinidoId', $id);
        $this->db->delete('producto_campos_listado_predefinido');
    }

    function reorganizarItemsPredefinidos($campoId)
    {

        //Obtenemos el string que viene del ajax en JSON y lo transformamos a array
        $posiciones = json_decode($this->input->post('posiciones'), true);

        //Obtenemos todos los campos
        $this->db->where('productoCampoId', $campoId);
        $this->db->order_by("productoCamposListadoPredefinidoPosicion", "asc");
        $query = $this->db->get('producto_campos_listado_predefinido');

        //Obtenemos el numero de campos totales
        $numCampos = $query->num_rows();

        //Ordenamos las campos segun el orden del arreglo de IDs
        for ($i = 0; $i < $numCampos; $i++)
        {
            $data = array('productoCamposListadoPredefinidoPosicion' => $i + 1);

            $this->db->where('productoCamposListadoPredefinidoId', $posiciones[$i]);
            $this->db->update('producto_campos_listado_predefinido', $data);

        }

    }

    function guardarListadoPredefinido()
    {
        //Seciones visibles para el cliente
        $seccionesIds = json_decode($this->input->post('seccionesAdmin'));

        //Eliminamos las secciones
        $this->db->where('productoId', $this->input->post('productoId'));
        $this->db->where('productoCampoId', $this->input->post('productoCampoId'));
        $this->db->delete('producto_campos_listado_predefinido_rel');

        //Volvemos a añadir las secciones
        foreach ($seccionesIds as $secId)
        {
            $data = array(
                'productoId' => (int)$this->input->post('productoId'),
                'productoCampoId' => (int)$this->input->post('productoCampoId'),
                'productoCamposListadoPredefinidoId' => (int)$secId
            );
            $this->db->insert('producto_campos_listado_predefinido_rel', $data);
        }

    }

    private function getLanguages()
	{
		$query = $this->db->get('idioma');
        return $query->result_array();
	}

	public function getCategoriaTranslation($diminutivo, $categoriaId)
	{
		$this->db->where('productoCategoriaId', $categoriaId);
		$query = $this->db->get($diminutivo.'_producto_categorias');
		
		return $query->row();
	}
	
	public function getProductoTranslation($diminutivo, $productoId)
	{
		$this->db->where('productoId', $productoId);
		$query = $this->db->get($diminutivo.'_productos');
		
		return $query->row();
	}

	public function getCampoTranslation($diminutivo, $campoId)
	{
		$this->db->where('productoCampoId', $campoId);
		$query = $this->db->get($diminutivo.'_producto_campos');
		
		return $query->row();
	}
	
	public function getImageTranslation($diminutivo, $imageId)
	{
		$this->db->where('productoImagenId', $imageId);
		$query = $this->db->get($diminutivo.'_producto_imagenes');

		return $query->row();
	}

    public function getVideoTranslation($diminutivo, $videoId)
    {
        $this->db->where('productoVideoId', $videoId);
        $query = $this->db->get($diminutivo.'_producto_videos');

        return $query->row();
    }

    public function getDescargaTranslation($diminutivo, $imageId)
    {
        $this->db->where('productoDescargaId', $imageId);
        $query = $this->db->get($diminutivo.'_producto_descargas');

        return $query->row();
    }

    public function getCampoListadoPredefinidoTranslation($diminutivo, $id)
    {
        $this->db->where('productoCamposListadoPredefinidoId', $id);
        $query = $this->db->get($diminutivo.'_producto_campos_listado_predefinido');

        return $query->row();
    }

	/*
	 * CONFIG
	 *
	 */

	public function getConfiguration()
	{
		$query = $this->db->get('producto_configuracion');
		return $query->row();
	}
	
	public function updateConfiguration()
	{
		
		$checked = 0;
		
		if($this->input->post('productoMostarProductoInicio') == 'on')
			$checked = 1;
		
		$data = array(
			'productoMostarProductoInicio' => $checked
		);
		
		$this->db->where('productoConfiguracionId', 1);
		$this->db->update('producto_configuracion', $data);
	}

}