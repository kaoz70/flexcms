<?php
class Catalogo_model extends CI_Model
{

    function getProduct($id, $lang='es')
    {
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'LEFT');

        if(is_string($id)){
            $this->db->where($lang.'_productos.productoUrl', $id);
        } else if(is_int($id)) {
            $this->db->where($lang.'_productos.productoId', $id);
        }

        $this->db->where('productoTemporal', 0);
        $this->db->where('productoEnable', 's');
        $query = $this->db->get('productos');
        return $query->row();
    }

    function getProductosRelacionados($palabras, $lang)
    {
        //productos
        $this->db->join('productos', 'producto_categorias.id = productos.categoriaId', 'left');
        $this->db->join($lang . '_productos', $lang . '_productos.productoId = productos.productoId', 'left');
        $this->db->join($lang . '_producto_categorias', $lang . '_producto_categorias.productoCategoriaId = producto_categorias.id', 'left');

        foreach ($palabras as $palabra) {
            $this->db->or_like($lang . '_productos.productoKeywords', trim($palabra));
        }

        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('producto_categorias');
        $productos = $query->result();

        //Campos
        $this->db->where('productoCampoVerModulo', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();


        return $this->procesaProductos($productos, $campos, $lang);
    }

    function getProductTranslation($id, $currentLang, $targetLang)
    {
        $producto = $this->getProduct($id, $currentLang);

        if($producto){
            $producto = $this->getProduct((int)$producto->productoId, $targetLang);
        }

        return $producto;
    }

    public function getProductFields($prodId, $lang)
    {
        //Campos
        //$this->db->select('productoCampoValor, campo_nombreCampo, campoLabel, campoLabelHabilitado, campoClase, inputTipoContenido, campo_muestroProdPedido');
        $this->db->join('producto_campos_rel', 'producto_campos.productoCampoId = producto_campos_rel.productoCampoId', 'LEFT');
        $this->db->join($lang.'_producto_campos_rel', $lang.'_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId', 'LEFT');
        $this->db->join($lang.'_producto_campos', $lang.'_producto_campos.productoCampoId = producto_campos.productoCampoId', 'LEFT');
        $this->db->join('input', 'input.inputId = producto_campos.inputId', 'LEFT');
        $this->db->where('producto_campos_rel.productoId', $prodId);
        $this->db->where('producto_campos.productoCampoHabilitado', 1);
        $this->db->group_by('producto_campos.productoCampoId');
        $this->db->order_by('producto_campos.productoCampoPosicion', 'asc');
        $query = $this->db->get('producto_campos');

        return $query->result();
    }

    public function getProductFiles($productoId, $lang)
    {
        $this->db->join($lang.'_producto_archivos', $lang.'_producto_archivos.productoDescargaId = producto_archivos.productoDescargaId', 'LEFT');
        $this->db->where('productoId', $productoId);
        //$this->db->where('productoDescargaEnabled', 1); //TODO Enable/disable files
        $this->db->order_by('productoArchivoPosicion', 'desc');
        $this->db->group_by('producto_archivos.productoDescargaId');
        $query = $this->db->get('producto_archivos');

        return $query->result();
    }

    public function getProductImages($productoId, $lang)
    {
        //$this->db->select('productoImagenId, productoImagen, productoId, productoImagenNombre, productoImagenDescripcion');
        $this->db->join($lang.'_producto_archivos', $lang.'_producto_archivos.productoImagenId = producto_imagenes.productoImagenId', 'LEFT');
        $this->db->where('productoId', $productoId);
        $this->db->where('productoImagenEnabled', 1);
        $this->db->order_by('productoImagenPosicion', 'ASC');
        $query = $this->db->get('producto_imagenes');

        return $query->result();
    }

    public function getProductVideos($productoId, $lang)
    {
        //$this->db->select('productoImagenId, productoImagen, productoId, productoImagenNombre, productoImagenDescripcion');
        $this->db->join($lang.'_producto_videos', $lang.'_producto_videos.productoVideoId = producto_videos.productoVideoId', 'LEFT');
        $this->db->where('productoId', $productoId);
        $this->db->where('productoVideoEnabled', 1);
        $this->db->order_by('productoVideoPosicion', 'ASC');
        $query = $this->db->get('producto_videos');

        return $query->result();
    }

    public function getProductAudios($productoId, $lang)
    {
        $this->db->where('productoId', $productoId);
        $this->db->where('productoAudioEnabled', 1);
        $this->db->order_by('productoAudioPosicion', 'ASC');
        $query = $this->db->get('producto_audios');

        return $query->result();
    }

    public function getProductLists($productoId, $productoCampoId, $lang)
    {
        //$this->db->select('productoImagenId, productoImagen, productoId, productoImagenNombre, productoImagenDescripcion');
        $this->db->join('producto_campos_listado_predefinido', 'producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido_rel.productoCamposListadoPredefinidoId', 'LEFT');
        $this->db->join($lang.'_producto_campos_listado_predefinido', $lang.'_producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido.productoCamposListadoPredefinidoId', 'LEFT');
        $this->db->where('producto_campos_listado_predefinido_rel.productoId', $productoId);
        $this->db->where('producto_campos_listado_predefinido_rel.productoCampoId', $productoCampoId);
        $this->db->where('productoCamposListadoPredefinidoPublicado', 1);
        $this->db->order_by('productoCamposListadoPredefinidoPosicion', 'ASC');
        $query = $this->db->get('producto_campos_listado_predefinido_rel');

        return $query->result();
    }

    public function getFirsProductOfFirstCategory($lang)
    {
        $result = new stdClass();
        $camposValor = array();

        $this->db->join('producto_categorias', 'producto_categorias.id = productos.categoriaId', 'left');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'left');
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'left');
        $this->db->order_by('productos.productoPosicion', 'asc');
        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('productos');
        $producto = $query->row();

        return $producto;

    }

    public function getProductosDestacados($categoriaId = 'todas', $actualPagePagination, $lang)
    {

        $result = array();

        //productos
        $this->db->join('productos', 'producto_categorias.id = productos.categoriaId', 'left');
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'left');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'left');
        $this->db->order_by('productos.productoPosicion', 'asc');
        if($categoriaId != 'todas')
            $this->db->where('productos.categoriaId', $categoriaId);
        $this->db->where('productos.productoDeldia', 's');
        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('producto_categorias');
        $productos = $query->result();

        //Campos
        $this->db->where('productoCampoVerModulo', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();


        return $this->procesaProductos($productos, $campos, $lang);

    }

    public function getProducts($lang)
    {
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId');
        $this->db->order_by('productoPosicion', 'asc');
        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('productos');
        return $query->result();
    }

    function getProductsByCategory($id = 0, $lang, $isList = false)
    {

        $this->db->from('productos');
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId');
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = productos.categoriaId');
        //if($id != 0)
        $this->db->where('productos.categoriaId', $id);
        $this->db->where('productos.productoEnable', 's');
        $this->db->where('productoTemporal', 0);
        $this->db->group_by('productos.productoId');
        $this->db->order_by('productos.productoPosicion', 'asc');
        $query = $this->db->get();
        $productos = $query->result();

        if($isList)
            $this->db->where('productoCampoVerListado', 1);
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();

        return $this->procesaProductos($productos, $campos, $lang);

    }

    public function getProductsDetailPagination($catId, $prodId)
    {
        $this->db->where('categoriaId', $catId);
        $this->db->where('productoTemporal', 0);
        $this->db->order_by('productoPosicion', 'asc');
        $query = $this->db->get('productos');

        return $query->result();
    }

    function getCategory($id, $lang)
    {
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'LEFT');

        if(is_string($id)){
            $this->db->where('productoCategoriaUrl', $id);
        } else if(is_int($id)) {
            $this->db->where('productoCategoriaId', $id);
        }

        $query = $this->db->get('producto_categorias');
        return $query->row();
    }

    function getCategoryTranslation($productoCategoriaUrl, $currentLang, $targetLang)
    {
        //Get the current category
        $category = $this->getCategory($productoCategoriaUrl, $currentLang);

        //Translated category
        if($category){
            $category = $this->getCategory((int)$category->id, $targetLang);
        }

        return $category;
    }

    function getCategoryById($catId, $lang)
    {
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'LEFT');
        $this->db->where('id', $catId);
        $query = $this->db->get('producto_categorias');
        return $query->row();
    }

    public function getCategories($lang)
    {
        $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = producto_categorias.id', 'LEFT');
        $query = $this->db->get('producto_categorias');
        return $query->result_array();
    }

    public function getConfiguration()
    {
        $query = $this->db->get('producto_configuracion');
        return $query->row();
    }

    public function getProductsData($lang = 'es', $soloDestacados = 0)
    {

        //Get products
        $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'LEFT');

        if($soloDestacados)
            $this->db->where('productoDeldia', 's');

        $this->db->where('productoTemporal', 0);
        $query = $this->db->get('productos');
        $productos = $query->result();

        //Get fields
        $this->db->order_by('productoCampoPosicion', 'ASC');
        $query = $this->db->get('producto_campos');
        $campos = $query->result();

        return $this->procesaProductos($productos, $campos, $lang);

    }

    public function getProductsByIds($ids, $lang, $isList = FALSE, $isPedido = FALSE)
    {

        $productosArr = array();

        if(count($ids) > 0)
        {
            $this->db->join($lang.'_productos', $lang.'_productos.productoId = productos.productoId', 'LEFT');
            $this->db->join($lang.'_producto_categorias', $lang.'_producto_categorias.productoCategoriaId = productos.categoriaId');
            $this->db->where_in('productos.productoId', $ids);
            $this->db->order_by('productoPosicion', 'asc');
            $query = $this->db->get('productos');
            $productos = $query->result();

            //Get fields
            if($isList)
                $this->db->where('productoCampoVerListado', 1);

            if($isPedido)
                $this->db->where('productoCampoVerPedido', 1);

            $this->db->order_by('productoCampoPosicion', 'ASC');
            $query = $this->db->get('producto_campos');
            $campos = $query->result();

            $productosArr = $this->procesaProductos($productos, $campos, $lang);

        }

        return $productosArr;

    }

    public function procesaProductos($productos, $campos, $lang){

        $productosArr = array();

        foreach ($productos as $producto) {
            $productosArr[] = $this->consolidateProduct($producto, $campos, $lang);
        }

        return $productosArr;

    }

    /**
     * Returns a formatted var with all the info we need of a product
     *
     * @param stdClass $producto
     * @param array $campos
     * @param $lang
     * @param stdClass $categoria
     * @return stdClass
     */
    public function consolidateProduct(stdClass $producto, array $campos, $lang, stdClass $categoria = NULL)
    {

        if(!$categoria) {
            $categoria = $this->getCategoryById($producto->categoriaId, $lang);
        }

        $producto->categoria = $categoria;
        $producto->campos = array();
        $producto->imagenes = array();
        $producto->videos = array();
        $producto->audios = array();
        $producto->tablas = array();
        $producto->archivos = array();
        $producto->listado = array();
        $producto->precios = array();

        $textarea_set = FALSE;

        foreach($campos as $campo) {

            $this->db->join('producto_campos_rel', 'producto_campos_rel.productoCampoId = producto_campos.productoCampoId', 'left');
            $this->db->join($lang.'_producto_campos_rel', $lang.'_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId', 'left');
            $this->db->join('input', 'input.inputId = producto_campos.inputId', 'left');
            $this->db->join($lang.'_producto_campos', $lang.'_producto_campos.productoCampoId = producto_campos.productoCampoId', 'left');
            $this->db->where('producto_campos_rel.productoId', $producto->productoId);
            $this->db->where('producto_campos.productoCampoId', $campo->productoCampoId);
            $this->db->where('producto_campos.productoCampoHabilitado', 1);
            $query = $this->db->get('producto_campos');

            $campo = $query->row();

            if(!empty($campo)) {

                $campoNuevo = new stdClass();
                $campoNuevo->nombre = $campo->productoCampoValor;
                $campoNuevo->mostrar_nombre = $campo->productoCampoMostrarNombre;
                $campoNuevo->clase = $campo->productoCampoClase;
                $campoNuevo->campo_id = $campo->productoCampoId;
                $campoNuevo->mostrar_en_pedido = $campo->productoCampoVerPedido;
                $campoNuevo->labelHabilitado = $campo->productoCampoMostrarNombre;
                $campoNuevo->label = $campo->productoCampoValor;

                switch ($campo->inputTipoContenido) {

                    case 'imágenes':
                        $campoNuevo->contenido = $this->getProductImages($producto->productoId, $lang);
                        $producto->imagenes[] = $campoNuevo;
                        break;

                    case 'archivos':

                        $resultado = $this->getProductFiles($producto->productoId, $lang);

                        /*
                         * Generate the file class according to the extension.
                         * TODO: Save the extension on file save so that I dont have to go through this loop all the time.
                         */
                        foreach ($resultado as $archivo) {

                            $ext = mb_strtolower(pathinfo('./docs/downloads/'.$archivo->productoDescargaArchivo, PATHINFO_EXTENSION));

                            switch($ext)
                            {
                                case 'pdf':
                                    $extension = 'pdf';
                                    break;
                                case 'xls':
                                case 'xlsx':
                                    $extension = 'xls';
                                    break;
                                case 'doc':
                                case 'docx':
                                    $extension = 'doc';
                                    break;
                                case 'ppt':
                                case 'pptx':
                                    $extension = 'ppt';
                                    break;
                                case 'jpeg':
                                case 'jpg':
                                    $extension = 'jpg';
                                    break;
                                default:
                                    $extension = 'default';
                                    break;
                            }

                            $archivo->productoDescargaExtension = $extension;

                        }

                        $campoNuevo->contenido = $resultado;
                        $producto->archivos[] = $campoNuevo;

                        break;

                    case 'videos':
                        $campoNuevo->contenido = $this->getProductVideos($producto->productoId, $lang);
                        $producto->videos[] = $campoNuevo;
                        break;

                    case 'audios':
                        $campoNuevo->contenido = $this->getProductAudios($producto->productoId, $lang);
                        $producto->audios[] = $campoNuevo;
                        break;

                    case 'tablas':
                        $campoNuevo->contenido = $campo;
                        $producto->tablas[] = $campoNuevo;
                        break;

                    case 'listado predefinido':
                    case 'listado':
                        $campoNuevo->contenido = $this->getProductLists($producto->productoId, $campo->productoCampoId, $lang);
                        $producto->listado_predefinido[] = $campoNuevo;
                        break;

                    case 'precio':
                        $campoNuevo->contenido = $campo->productoCampoRelContenido;
                        $producto->precios[] = $campoNuevo;
                        break;

                    default:
                        $campoNuevo->contenido = $campo;
                        $producto->campos[] = $campoNuevo;

                        //Check if textarea is already set for Facebook's OpenGraph, we are checking that the first textarea will
                        //hopefully be the products description //TODO: find a better way for this
                        if($campo->inputTipoContenido === 'texto multilinea' AND !$textarea_set) {
                            $data['og_description'] = strip_tags($campo->productoCampoRelContenido);
                            $textarea_set = TRUE;
                        }

                        break;
                }

                $producto->campos_ordenados[] = $campoNuevo;

            }

        }

        return $producto;

    }

}