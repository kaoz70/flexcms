<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cart extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->database();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('text');
		
        // IMPORTANT! This global must be defined BEFORE the flexi cart library is loaded!
        // It is used as a global that is accessible via both models and both libraries, without it, flexi cart will not work.
        $this->flexi = new stdClass;

        // Load 'admin' flexi cart library by default.
        $this->load->library('flexi_cart_admin');

        $this->load->model('admin/cart_model', 'Cart');

        $this->load->library('ion_auth');
		$this->load->library('Seguridad');
        $this->load->library('CMS_General');

		$this->seguridad->init();

	}

    /**
     * order_details
     * Displays all data related to a saved order, including the users billing and shipping details, the cart contents and the cart summary.
     * This demo includes an example of indicating to flexi cart which items have been shipped or cancelled since the order was receieved, flexi cart can then use this data
     * to manage item stock and user reward points.
     */
    public function detalle()
	{

        $order_number = $this->uri->segment(4);

		$data['titulo'] = 'Detalle del pedido';

		$data['order_id'] = $order_number;
		$data['flexi_cart'] = $this->flexi_cart_admin;

        // Get the row array of the order filtered by the order number in the url.
        $sql_where = array(
            $this->flexi_cart_admin->db_column('order_summary', 'order_number') => $order_number
        );
        $data['summary_data'] = $this->flexi_cart_admin->get_db_order_summary_row_array(FALSE, $sql_where);

        // Get an array of all order details related to the above order, filtered by the order number in the url.
        $sql_where = array(
            $this->flexi_cart_admin->db_column('order_details', 'order_number') => $order_number
        );
        $data['item_data'] = $this->flexi_cart_admin->get_db_order_detail_array(FALSE, $sql_where);

        // Get an array of all order statuses that can be set for an order.
        // The data is then to be displayed via a html select input to allow the user to update the orders status.
        $data['status_data'] = $this->flexi_cart_admin->get_db_order_status_array();

        // Get the row array of any refund data that may be available for the order, filtered by the order number in the url.
        $data['refund_data'] = $this->flexi_cart_admin->get_refund_summary_row_array($order_number);

		$data['txt_boton'] = 'Modificar Pedido';
		$data['link'] = base_url('admin/cart/actualizar/' . $order_number);

		$this->load->view('admin/cart/pedido_view', $data);

	}

	public function actualizar()
	{
        $this->Cart->update_order_details($this->uri->segment(4));
	}

    public function ubicaciones()
    {
        $data['items'] = $this->flexi_cart_admin->get_db_location_type_array(FALSE, 'loc_type_temporary = 0');

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_ubicacion');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_ubicacion');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel3';
        $data['list_id'] = 'ubicaciones';

        $data['idx_id'] = 'loc_type_id';
        $data['idx_nombre'] = 'loc_type_name';

        $data['txt_titulo'] = 'Tipos de Locacion';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nueva_ubicacion'), 'crear nueva locaci&oacute;n', array('class' => $data['nivel'] . ' ajax importante n2 boton'));
        $data['menu'][] = anchor(base_url('admin/cart/zonas'), 'zonas de envio', array('class' => $data['nivel'] . ' ajax n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nueva_ubicacion()
    {

        $ubicacionId = $this->Cart->createUbicacion();

        $data['ubicacionId'] = $ubicacionId;
        $data['titulo'] = 'Nueva Locaci&oacute;n';
        $data['habilitado'] = 'checked="checked"';
        $data['nuevo'] = 'nuevo';
        $data['removeUrl'] = base_url('admin/cart/eliminar_ubicacion/' . $ubicacionId);

        $data['nombre'] = '';
        $data['padre_id'] = '';

        $data['txt_boton'] = 'Crear Ubicaci&oacute;n';
        $data['link'] = base_url('admin/cart/actualizar_ubicacion/' . $ubicacionId);
        $data['link_ubicaciones'] = base_url('admin/cart/sub_ubicaciones/' . $ubicacionId);

        $data['ubicaciones'] = $this->flexi_cart_admin->get_db_location_type_array();

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->get_db_location_type_array(FALSE, 'loc_type_temporary = 0');

        $this->load->view('admin/cart/ubicacionCrear_view', $data);
    }

    public function actualizar_ubicacion()
    {
        $this->Cart->update_location_types($this->uri->segment(4));
    }

    public function modificar_ubicacion()
    {
        $ubicacion = $this->Cart->getUbicacion($this->uri->segment(4));

        $data['ubicacionId'] = $ubicacion->loc_type_id;
        $data['titulo'] = 'Modificar Ubicaci&oacute;n';
        $data['habilitado'] = 'checked="checked"';
        $data['nuevo'] = '';
        $data['removeUrl'] = '';

        $data['txt_boton'] = 'Actualizar Ubicaci&oacute;n';
        $data['link'] = base_url('admin/cart/actualizar_ubicacion/' . $ubicacion->loc_type_id);
        $data['link_ubicaciones'] = base_url('admin/cart/sub_ubicaciones/' . $ubicacion->loc_type_id);
        $data['nombre'] = $ubicacion->loc_type_name;
        $data['padre_id'] = $ubicacion->loc_type_parent_fk;

        $data['ubicaciones'] = $this->flexi_cart_admin->get_db_location_type_array();

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->get_db_location_type_array(FALSE, 'loc_type_temporary = 0');

        $this->load->view('admin/cart/ubicacionCrear_view', $data);
    }

    public function eliminar_ubicacion()
    {
        $this->flexi_cart_admin->delete_db_location_type($this->uri->segment(4), TRUE);
	    $this->load->view('admin/request/html', array('return' => 'success'));
    }

    public function sub_ubicaciones()
    {
        $location_type_id = $this->uri->segment(4);

        // Get an array of all locations filtered by the id in the url.
        $sql_where = array($this->flexi_cart_admin->db_column('locations', 'type') => $location_type_id);
        $data['items'] = $this->flexi_cart_admin->get_db_location_array(FALSE, $sql_where);

        //Data
        $ubicacion = $this->Cart->getUbicacion($this->uri->segment(4));
        $padre = $this->Cart->getUbicacion($ubicacion->loc_type_parent_fk);

        $sql_where = array($this->flexi_cart_admin->db_column('locations', 'type') => $ubicacion->loc_type_parent_fk);
        $data['grupos'] = $this->flexi_cart_admin->get_db_location_array(FALSE, $sql_where);

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_sub_ubicacion');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_sub_ubicacion');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel6';
        $data['list_id'] = 'sub_ubicaciones';

        $data['txt_titulo'] = 'Sub locaciones';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nueva_sub_ubicacion/' . $location_type_id), 'crear nueva sub locaci&oacute;n', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        if($padre){
            $data['idx_nombre'] = 'loc_name';
            $data['idx_grupo_id'] = 'loc_id';
            $data['idx_grupo_id_alt'] = 'loc_parent_fk';
            $data['idx_item_id'] = 'loc_id';
            $data['idx_item_nombre'] = 'loc_name';
            $data['txt_grupoNombre'] = $padre->loc_type_name;
            $this->load->view('admin/listadoAgrupado_view', $data);
        } else {
            $data['idx_id'] = 'loc_id';
            $data['idx_nombre'] = 'loc_name';
            $this->load->view('admin/listado_view', $data);
        }


    }

    public function nueva_sub_ubicacion()
    {

        $location_type_id = $this->uri->segment(4);

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get arrays of all shipping and tax zones.
        $data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
        $data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

        // Get the row array of the location type filtered by the id in the url.
        $sql_where = array($this->flexi_cart_admin->db_column('location_type', 'id') => $location_type_id);
        $data['location_type_data'] = $this->flexi_cart_admin->get_db_location_type_row_array(FALSE, $sql_where);

        $data['padre_id'] = '';
        $data['ship_id'] = '';
        $data['tax_id'] = '';

        $data['titulo'] = 'Crear Sub locaci&oacute;n';
        $data['nuevo'] = 'nuevo';
        $data['ubicacionId'] = $location_type_id;
        $data['loc_id'] = $this->cms_general->generarId('locations');
        $data['nombre'] = '';
        $data['link'] = base_url('admin/cart/insertar_sub_ubicacion/' . $location_type_id);
        $data['txt_boton'] = 'Guardar';
        $data['status'] = 'checked="checked"';

        $this->load->view('admin/cart/subUbicacionCrear_view', $data);
    }

    public function insertar_sub_ubicacion()
    {
        $this->Cart->insert_location($this->uri->segment(4));
    }

    public function modificar_sub_ubicacion()
    {

        $location = $this->Cart->getSubUbicacion($this->uri->segment(4));
        $location_type_id = $location->loc_type_fk;

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get arrays of all shipping and tax zones.
        $data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
        $data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

        // Get the row array of the location type filtered by the id in the url.
        $sql_where = array($this->flexi_cart_admin->db_column('location_type', 'id') => $location_type_id);
        $data['location_type_data'] = $this->flexi_cart_admin->get_db_location_type_row_array(FALSE, $sql_where);

        $data['titulo'] = 'Crear Sub locaci&oacute;n';
        $data['nuevo'] = '';
        $data['ubicacionId'] = $location_type_id;
        $data['nombre'] = $location->loc_name;
        $data['link'] = base_url('admin/cart/actualizar_sub_ubicacion/' . $location->loc_id);
        $data['txt_boton'] = 'Actualizar';
        $data['loc_id'] = $location->loc_id;

        $data['padre_id'] = $location->loc_parent_fk;
        $data['ship_id'] = $location->loc_ship_zone_fk;
        $data['tax_id'] = $location->loc_tax_zone_fk;

        if($location->loc_status)
            $data['status'] = 'checked="checked"';
        else
            $data['status'] = '';

        $this->load->view('admin/cart/subUbicacionCrear_view', $data);
    }

    public function actualizar_sub_ubicacion()
    {
        $this->Cart->update_location($this->uri->segment(4));
    }

    public function eliminar_sub_ubicacion()
    {
        $this->flexi_cart_admin->delete_db_location($this->uri->segment(4));
	    $this->load->view('admin/request/html', array('return' => 'success'));
    }

    public function zonas()
    {
        $data['items'] = $this->flexi_cart_admin->get_db_location_zone_array();

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_zona');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_zona');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'zonas';

        $data['idx_id'] = 'lzone_id';
        $data['idx_nombre'] = 'lzone_name';

        $data['txt_titulo'] = 'Zonas';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nueva_zona'), 'crear nueva zona', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nueva_zona()
    {
        $data['titulo'] = 'Nueva Zona';
        $data['nuevo'] = 'nuevo';
        $data['zonaId'] = $this->cms_general->generarId('location_zones');
        $data['nombre'] = '';
        $data['checked'] = 'checked="checked"';
        $data['desc'] = '';
        $data['link'] = base_url('admin/cart/insertar_zona');
        $data['txt_boton'] = 'Guardar';
        $data['status'] = 'checked="checked"';

        $this->load->view('admin/cart/zonaCrear_view', $data);
    }

    public function insertar_zona()
    {
        $this->Cart->insert_zone();
    }

    public function modificar_zona()
    {

        $zona = $this->Cart->getZone($this->uri->segment(4));

        $data['titulo'] = 'Modificar Zona';
        $data['nuevo'] = '';
        $data['zonaId'] = $zona->lzone_id;
        $data['nombre'] = $zona->lzone_name;
        if($zona->lzone_status)
            $data['checked'] = 'checked="checked"';
        else
            $data['checked'] = '';
        $data['desc'] = $zona->lzone_description;
        $data['link'] = base_url('admin/cart/actualizar_zona/' . $zona->lzone_id);
        $data['txt_boton'] = 'Modificar';
        $data['status'] = 'checked="checked"';

        $this->load->view('admin/cart/zonaCrear_view', $data);
    }

    public function actualizar_zona()
    {
        $this->Cart->update_zone($this->uri->segment(4));
    }

    public function eliminar_zona()
    {
        $this->flexi_cart_admin->delete_db_location_zone($this->uri->segment(4));
	    $this->load->view('admin/request/html', array('return' => 'success'));
    }

    public function envios(){

        $sql_where = array('ship_temporal' => 0);
        $data['items'] = $this->flexi_cart_admin->get_db_shipping_array(FALSE, $sql_where);

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_opcion_envio');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_opcion_envio');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'zonas';

        $data['idx_id'] = 'ship_id';
        $data['idx_nombre'] = 'ship_name';

        $data['txt_titulo'] = 'Opciones de envio';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nueva_opcion_envio'), 'crear nueva opci&oacute;n de envio', array('class' => $data['nivel'] . ' ajax importante n2 boton'));
        $data['menu'][] = anchor(base_url('admin/cart/impuestos'), 'impuestos', array('class' => $data['nivel'] . ' ajax n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nueva_opcion_envio()
    {

        $id = $this->Cart->createShippingOption();

        // Get an array of location data formatted with all sub-locations displayed 'tiered' into the location type groups, so locations can be listed
        // over multiple html select menus.
        //$data['locations_tiered'] = $this->flexi_cart_admin->locations_tiered();
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get an array of all shipping zones.
        $data['shipping_zones'] = $this->flexi_cart_admin->location_zones('shipping');

        $data['titulo'] = 'Nueva Opci&oacute;n de Envio';
        $data['removeUrl'] = base_url('admin/cart/eliminar_opcion_envio/' . $id);
        $data['nuevo'] = 'nuevo';
        $data['opcionEnvioId'] = $id;
        $data['nombre'] = '';
        $data['desc'] = '';
        $data['link'] = base_url('admin/cart/actualizar_opcion_envio/' . $id);
        $data['link_tarifas'] = base_url('admin/cart/tarifas_envio/' . $id);
        $data['txt_boton'] = 'Guardar';
        $data['status'] = 'checked="checked"';
        $data['padre_id'] = '';

        $this->load->view('admin/cart/envioOpcionCrear_view', $data);
    }

    public function actualizar_opcion_envio()
    {
        $this->Cart->update_shipping($this->uri->segment(4));
    }

    public function modificar_opcion_envio()
    {

        $shipping = $this->Cart->getShippingOption($this->uri->segment(4));

        // Get an array of location data formatted with all sub-locations displayed 'tiered' into the location type groups, so locations can be listed
        // over multiple html select menus.
        //$data['locations_tiered'] = $this->flexi_cart_admin->locations_tiered();
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get an array of all shipping zones.
        $data['shipping_zones'] = $this->flexi_cart_admin->location_zones('shipping');

        $data['titulo'] = 'Opci&oacute;n de Envio';
        $data['removeUrl'] = '';
        $data['nuevo'] = '';
        $data['opcionEnvioId'] = $shipping->ship_id;
        $data['nombre'] = $shipping->ship_name;
        $data['desc'] = $shipping->ship_description;
        $data['link'] = base_url('admin/cart/actualizar_opcion_envio/' . $shipping->ship_id);
        $data['link_tarifas'] = base_url('admin/cart/tarifas_envio/' . $shipping->ship_id);
        $data['txt_boton'] = 'Modificar';
        $data['padre_id'] = $shipping->ship_location_fk;

        if($shipping->ship_status)
            $data['status'] = 'checked="checked"';
        else
            $data['status'] = '';

        $this->load->view('admin/cart/envioOpcionCrear_view', $data);
    }

    public function eliminar_opcion_envio()
    {
        if($this->flexi_cart_admin->delete_db_shipping($this->uri->segment(4), TRUE)) {
	        $this->load->view('admin/request/html', array('return' => 'success'));
        } else {
	        $this->load->view('admin/request/html', array('return' => 'no se pudo eliminar'));
        }
    }

    function tarifas_envio()
    {

        $shipping_id = $this->uri->segment(4);

        // Get an array of all shipping rates filtered by the id in the url.
        $sql_where = array($this->flexi_cart_admin->db_column('shipping_rates', 'parent') => $shipping_id);
        $data['items'] = $this->flexi_cart_admin->get_db_shipping_rate_array(FALSE, $sql_where);

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_tarifa_envio');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_tarifa_envio');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel6';
        $data['list_id'] = 'tarifa_envio';

        $data['idx_id'] = 'ship_rate_id';
        $data['idx_nombre'] = 'ship_rate_value';

        $data['txt_titulo'] = 'Tarifas de envio';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nueva_tarifa_envio/' . $this->uri->segment(4)), 'crear nueva tarifa de envio', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);

    }

    function nueva_tarifa_envio()
    {
        $id = $this->cms_general->generarId('shipping_rates');

        $data['titulo'] = 'Nueva Tarifa de Envio';
        $data['nuevo'] = 'nuevo';
        $data['opcionTarifaId'] = $id;

        $data['valor'] = '0.00';
        $data['tare_weight'] = '0';
        $data['min_weight'] = '0';
        $data['max_weight'] = '9999';
        $data['min_value'] = '0.00';
        $data['max_value'] = '9999.00';
        $data['status'] = 'checked="checked"';

        $data['link'] = base_url('admin/cart/insertar_tarifa_envio/' . $this->uri->segment(4));
        $data['txt_boton'] = 'Guardar';

        $this->load->view('admin/cart/envioTarifaCrear_view', $data);
    }

    function insertar_tarifa_envio()
    {
        $this->Cart->insert_shipping_rate($this->uri->segment(4));
    }

    function modificar_tarifa_envio()
    {

        $tarifa = $this->Cart->getTarifaEnvio($this->uri->segment(4));

        $data['titulo'] = 'Nueva Tarifa de Envio';
        $data['nuevo'] = '';
        $data['opcionTarifaId'] = $tarifa->ship_rate_id;

        $data['valor'] = $tarifa->ship_rate_value;
        $data['tare_weight'] = $tarifa->ship_rate_tare_wgt;
        $data['min_weight'] = $tarifa->ship_rate_min_wgt;
        $data['max_weight'] = $tarifa->ship_rate_max_wgt;
        $data['min_value'] = $tarifa->ship_rate_min_value;
        $data['max_value'] = $tarifa->ship_rate_max_value;

        if($tarifa->ship_rate_status)
            $data['status'] = 'checked="checked"';
        else
            $data['status'] = '';

        $data['link'] = base_url('admin/cart/actualizar_tarifa_envio/' . $tarifa->ship_rate_id);
        $data['txt_boton'] = 'Guardar';

        $this->load->view('admin/cart/envioTarifaCrear_view', $data);
    }

    function actualizar_tarifa_envio()
    {
        $this->Cart->update_shipping_rate($this->uri->segment(4));
    }

    function impuestos()
    {
        $data['items'] = $this->flexi_cart_admin->get_db_tax_array();

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_impuesto');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_impuesto');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel5';
        $data['list_id'] = 'impuestos';

        $data['idx_id'] = 'tax_id';
        $data['idx_nombre'] = 'tax_name';

        $data['txt_titulo'] = 'Impuestos';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nuevo_impuesto'), 'crear nuevo impuesto', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    function nuevo_impuesto()
    {
        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get arrays of all shipping and tax zones.
        $data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
        $data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

        $data['location_id'] = '';
        $data['zone_id'] = '';
        $data['rate'] = '';

        $data['titulo'] = 'Crear Impuesto';
        $data['nuevo'] = 'nuevo';
        $data['id'] =  $this->cms_general->generarId('tax');
        $data['nombre'] = '';
        $data['link'] = base_url('admin/cart/insertar_impuesto');
        $data['txt_boton'] = 'Guardar';
        $data['status'] = 'checked="checked"';

        $this->load->view('admin/cart/impuestoCrear_view', $data);
    }

    public function insertar_impuesto()
    {
        $this->Cart->insert_tax();
    }

    public function modificar_impuesto()
    {

        $impuesto = $this->Cart->getTax($this->uri->segment(4));

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get arrays of all shipping and tax zones.
        $data['shipping_zones'] = $this->flexi_cart_admin->get_db_location_zone_array();
        $data['tax_zones'] = $this->flexi_cart_admin->location_zones('tax');

        $data['location_id'] = $impuesto->tax_location_fk;
        $data['zone_id'] = $impuesto->tax_zone_fk;
        $data['rate'] = $impuesto->tax_rate;

        $data['titulo'] = 'Modificar Impuesto';
        $data['nuevo'] = '';
        $data['id'] =  $impuesto->tax_id;
        $data['nombre'] = $impuesto->tax_name;
        $data['link'] = base_url('admin/cart/actualizar_impuesto/' . $impuesto->tax_id);
        $data['txt_boton'] = 'Guardar';

        if($impuesto->tax_status)
            $data['status'] = 'checked="checked"';
        else
            $data['status'] = '';

        $this->load->view('admin/cart/impuestoCrear_view', $data);
    }

    public function actualizar_impuesto()
    {
        $this->Cart->update_tax($this->uri->segment(4));
    }

    public function eliminar_impuesto()
    {
        if($this->flexi_cart_admin->delete_db_tax($this->uri->segment(4))) {
	        $this->load->view('admin/request/html', array('return' => 'success'));
        } else {
	        $this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
        }
    }

    public function descuentos()
    {

        $data['items'] = $this->flexi_cart_admin->get_db_discount_array(FALSE);

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_descuento');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_descuento');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'descuentos';

        $data['idx_id'] = 'disc_id';
        $data['idx_nombre'] = 'disc_description';

        $data['txt_titulo'] = 'Descuentos';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nuevo_descuento'), 'crear nuevo descuento', array('class' => $data['nivel'] . ' ajax importante n2 boton'));
        $data['menu'][] = anchor(base_url('admin/cart/grupos_descuentos'), 'grupos de descuento', array('class' => $data['nivel'] . ' ajax n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nuevo_descuento()
    {

        $discount_id = $this->cms_general->generarId('discounts');

        $this->load->model('admin/catalogo_model');

        $this->data['titulo'] = 'Nuevo Descuento';
        $this->data['nuevo'] = 'nuevo';
        $this->data['discount_id'] =  $discount_id;

        $this->data['link'] = base_url('admin/cart/insertar_descuento/' . $this->uri->segment(4));
        $this->data['txt_boton'] = 'Crear';

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $this->data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get an array of all zones.
        $this->data['zones'] = $this->flexi_cart_admin->location_zones();

        // Get an array of all discount types.
        $this->data['discount_types'] = $this->flexi_cart_admin->get_db_discount_type_array();

        // Get an array of all discount methods.
        $this->data['discount_methods'] = $this->flexi_cart_admin->get_db_discount_method_array();

        // Get an array of all discount tax methods.
        $this->data['discount_tax_methods'] = $this->flexi_cart_admin->get_db_discount_tax_method_array();

        // Get an array of all discount groups.
        $sql_where = array(
            'disc_group_temporary' => 0
        );
        $this->data['discount_groups'] = $this->flexi_cart_admin->get_db_discount_group_array(FALSE, $sql_where);

        // Get an array of all product items.
        $this->data['items'] = $this->catalogo_model->getProductos();

        // Get the row array of the discount filtered by the id in the url.
        //$sql_where = array($this->flexi_cart_admin->db_column('discounts', 'id') => $discount_id);
        //$this->data['discount_data'] = $this->flexi_cart_admin->get_db_discount_row_array(FALSE, $sql_where);

        $this->data['type'] = '';
        $this->data['method'] = '';
        $this->data['tax_method'] = '';
        $this->data['location'] = '';
        $this->data['zone'] = '';
        $this->data['group'] = '';
        $this->data['item'] = '';
        $this->data['code'] = '';
        $this->data['description'] = '';
        $this->data['quantity_required'] = '';
        $this->data['quantity_discounted'] = '';
        $this->data['value_required'] = '';
        $this->data['value_discounted'] = '';
        $this->data['recursive'] = '';
        $this->data['non_combinable'] = '';
        $this->data['void_reward'] = '';
        $this->data['force_shipping'] = '';
        $this->data['custom_status_1'] = '';
        $this->data['custom_status_2'] = '';
        $this->data['custom_status_3'] = '';
        $this->data['usage_limit'] = '';
        $this->data['valid_date'] = '';
        $this->data['expire_date'] = '';
        $this->data['status'] = 1;
        $this->data['order_by'] = '';

        $this->load->view('admin/cart/descuentoCrear_view', $this->data);

    }

    public function insertar_descuento()
    {
        $this->Cart->insert_discount();
    }

    public function modificar_descuento()
    {

        $descuento = $this->Cart->getDescuento($this->uri->segment(4));

        $discount_id = $descuento->disc_id;

        $this->load->model('admin/catalogo_model');

        $this->data['titulo'] = 'Modificar Descuento';
        $this->data['nuevo'] = '';
        $this->data['discount_id'] =  $discount_id;

        $this->data['link'] = base_url('admin/cart/actualizar_descuento/' . $this->uri->segment(4));
        $this->data['txt_boton'] = 'Modificar';

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $this->data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get an array of all zones.
        $this->data['zones'] = $this->flexi_cart_admin->location_zones();

        // Get an array of all discount types.
        $this->data['discount_types'] = $this->flexi_cart_admin->get_db_discount_type_array();

        // Get an array of all discount methods.
        $this->data['discount_methods'] = $this->flexi_cart_admin->get_db_discount_method_array();

        // Get an array of all discount tax methods.
        $this->data['discount_tax_methods'] = $this->flexi_cart_admin->get_db_discount_tax_method_array();

        // Get an array of all discount groups.
        $sql_where = array(
            'disc_group_temporary' => 0
        );
        $this->data['discount_groups'] = $this->flexi_cart_admin->get_db_discount_group_array(FALSE, $sql_where);

        // Get an array of all product items.
        $this->data['items'] = $this->catalogo_model->getProductos();

        $this->data['type'] = $descuento->disc_type_fk;
        $this->data['method'] = $descuento->disc_method_fk;
        $this->data['tax_method'] = $descuento->disc_tax_method_fk;
        $this->data['location'] = $descuento->disc_location_fk;
        $this->data['zone'] = $descuento->disc_zone_fk;
        $this->data['group'] = $descuento->disc_group_fk;
        $this->data['item'] = $descuento->disc_item_fk;
        $this->data['code'] = $descuento->disc_code;
        $this->data['description'] = $descuento->disc_description;
        $this->data['quantity_required'] = $descuento->disc_quantity_required;
        $this->data['quantity_discounted'] = $descuento->disc_quantity_discounted;
        $this->data['value_required'] = $descuento->disc_value_required;
        $this->data['value_discounted'] = $descuento->disc_value_discounted;
        $this->data['recursive'] = $descuento->disc_recursive;
        $this->data['non_combinable'] = $descuento->disc_non_combinable_discount;
        $this->data['void_reward'] = $descuento->disc_void_reward_points;
        $this->data['force_shipping'] = $descuento->disc_force_ship_discount;
        $this->data['custom_status_1'] = $descuento->disc_custom_status_1;
        $this->data['custom_status_2'] = $descuento->disc_custom_status_2;
        $this->data['custom_status_3'] = $descuento->disc_custom_status_3;
        $this->data['usage_limit'] = $descuento->disc_usage_limit;
        $this->data['valid_date'] = $descuento->disc_valid_date;
        $this->data['expire_date'] = $descuento->disc_expire_date;
        $this->data['status'] = $descuento->disc_status;
        $this->data['order_by'] = $descuento->disc_order_by;

        // Get the row array of the discount filtered by the id in the url.
        //$sql_where = array($this->flexi_cart_admin->db_column('discounts', 'id') => $discount_id);
        //$this->data['discount_data'] = $this->flexi_cart_admin->get_db_discount_row_array(FALSE, $sql_where);

        $this->load->view('admin/cart/descuentoCrear_view', $this->data);
    }

    public function actualizar_descuento()
    {
        $this->Cart->update_discount($this->uri->segment(4));
    }

    public function eliminar_descuento()
    {
        if($this->flexi_cart_admin->delete_db_discount($this->uri->segment(4))) {
	        $this->load->view('admin/request/html', array('return' => 'success'));
        } else {
	        $this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
        }
    }

    public function grupos_descuentos()
    {
        $sql_where = array(
            'disc_group_temporary' => 0
        );
        $data['items'] = $this->flexi_cart_admin->get_db_discount_group_array(FALSE, $sql_where);

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_grupo_descuento');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_grupo_descuento');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel5';
        $data['list_id'] = 'grupo_descuentos';

        $data['idx_id'] = 'disc_group_id';
        $data['idx_nombre'] = 'disc_group';

        $data['txt_titulo'] = 'Grupos de Descuento';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nuevo_grupo_descuento'), 'nuevo grupo de descuento', array('class' => $data['nivel'] . ' ajax importante n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nuevo_grupo_descuento()
    {

        $data['titulo'] = 'Crear Grupo Descuento';
        $data['nuevo'] = 'nuevo';
        $data['id'] =  $this->Cart->insert_discount_group();
        $data['nombre'] = '';
        $data['removeUrl'] = base_url('admin/cart/eliminar_grupo_descuento/' . $data['id']);
        $data['link'] = base_url('admin/cart/actualizar_grupo_descuento/' . $data['id']);
        $data['txt_boton'] = 'Guardar';
        $data['status'] = 'checked="checked"';

        $this->load->view('admin/cart/grupoDescuentoCrear_view', $data);
    }

    public function modificar_grupo_descuento()
    {

        $grupo = $this->Cart->getGrupoDescuento($this->uri->segment(4));

        $data['titulo'] = 'Grupo Descuento';
        $data['nuevo'] = '';
        $data['id'] =  $grupo->disc_group_id;
        $data['nombre'] = $grupo->disc_group;
        $data['removeUrl'] = '';
        $data['link'] = base_url('admin/cart/actualizar_grupo_descuento/' . $data['id']);
        $data['txt_boton'] = 'Guardar';

        if($grupo->disc_group_status)
            $data['status'] = 'checked="checked"';
        else
            $data['status'] = '';

        $this->load->view('admin/cart/grupoDescuentoCrear_view', $data);
    }

    public function actualizar_grupo_descuento()
    {
        $this->Cart->update_discount_groups($this->uri->segment(4));
    }

    public function eliminar_grupo_descuento()
    {
        if($this->flexi_cart_admin->delete_db_discount_group($this->uri->segment(4))) {
	        $this->load->view('admin/request/html', array('return' => 'success'));
        } else {
	        $this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
        }
    }

    public function modificar_items_grupo_descuento()
    {

        $this->load->model('admin/catalogo_model', 'Catalogo');

        $data['titulo'] = 'Productos';
        $data['txt_guardar'] = 'Guardar';
        $data['grupo_id'] = $this->uri->segment(4);

        $data['items_seleccionados'] = $this->Cart->getGrupoDescuentoItems($this->uri->segment(4));
        $data['items_todos'] = $this->menuSubcategories(0, $data['items_seleccionados']);

        $seccionesAdmin = $data['items_seleccionados'];

        $seccionesAdminArr = array();

        foreach($seccionesAdmin as $sec)
        {
            array_push($seccionesAdminArr, $sec->disc_group_item_id);
        }

        $data['seccionesAdmin'] = htmlspecialchars(json_encode($seccionesAdminArr));

        $this->load->view('admin/cart/gruposDescuentoItems_view.php', $data);

    }

    public function actualizar_items_grupo_descuento()
    {
        $this->Cart->insert_discount_group_items($this->uri->segment(4));
    }

    //TODO entender que hace esto: http://haseydesign.com/flexi-cart/admin_library/user_reward_points
    /*public function recompensas()
    {

    }

    public function vouchers()
    {

    }*/

    public function monedas()
    {

        $data['items'] = $this->flexi_cart_admin->get_db_currency_array();

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_moneda');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_moneda');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'descuentos';

        $data['idx_id'] = 'curr_id';
        $data['idx_nombre'] = 'curr_name';

        $data['txt_titulo'] = 'Monedas';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nueva_moneda'), 'nueva moneda', array('class' => $data['nivel'] . ' ajax n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nueva_moneda()
    {

        $data['nombre'] = '';
        $data['exchange_rate'] = '';
        $data['symbol'] = '';
        $data['thousand'] = '.';
        $data['decimal'] = ',';

        $data['titulo'] = 'Crear Moneda';
        $data['nuevo'] = 'nuevo';
        $data['id'] =  $this->cms_general->generarId('currency');
        $data['nombre'] = '';
        $data['link'] = base_url('admin/cart/insertar_moneda');
        $data['txt_boton'] = 'Guardar';
        $data['status'] = 'checked="checked"';

        $this->load->view('admin/cart/monedaCrear_view', $data);
    }

    public function insertar_moneda()
    {
        $this->Cart->insert_currency();
    }

    public function modificar_moneda()
    {

        $moneda = $this->Cart->getMoneda($this->uri->segment(4));

        $data['nombre'] = $moneda->curr_name;
        $data['exchange_rate'] = $moneda->curr_exchange_rate;
        $data['symbol'] = $moneda->curr_symbol;
        $data['thousand'] = $moneda->curr_thousand_separator;
        $data['decimal'] = $moneda->curr_decimal_separator;

        $data['titulo'] = 'Crear Moneda';
        $data['nuevo'] = '';
        $data['id'] =  $moneda->curr_id;
        $data['link'] = base_url('admin/cart/actualizar_moneda');
        $data['txt_boton'] = 'Guardar';

        if($moneda->curr_status)
            $data['status'] = 'checked="checked"';
        else
            $data['status'] = '';

        $this->load->view('admin/cart/monedaCrear_view', $data);
    }

    public function actualizar_moneda()
    {
        $this->Cart->update_currency($this->uri->segment(4));
    }

    public function eliminar_moneda()
    {
        if($this->flexi_cart_admin->delete_db_currency($this->uri->segment(4))) {
	        $this->load->view('admin/request/html', array('return' => 'success'));
        } else {
	        $this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
        }
    }

    public function estados()
    {
        $data['items'] = $this->flexi_cart_admin->get_db_order_status_array();

        $data['url_rel'] = base_url('admin/cart');
        $data['url_sort'] = '';
        $data['url_modificar'] = base_url('admin/cart/modificar_estado');
        $data['url_eliminar'] = base_url('admin/cart/eliminar_estado');
        $data['url_search'] = '';

        $data['search'] = false;
        $data['drag'] = false;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'descuentos';

        $data['idx_id'] = 'ord_status_id';
        $data['idx_nombre'] = 'ord_status_description';

        $data['txt_titulo'] = 'Estados';

        /*
         * Menu
         */
        $data['menu'] = array();
        $data['menu'][] = anchor(base_url('admin/cart/nuevo_estado'), 'nuevo estado', array('class' => $data['nivel'] . ' ajax n1 boton'));

        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listado_view', $data);
    }

    public function nuevo_estado()
    {
        $data['nombre'] = '';
        $data['cancelled'] = '';
        $data['save_default'] = '';
        $data['resave_default'] = '.';

        $data['titulo'] = 'Crear Estado';
        $data['nuevo'] = 'nuevo';
        $data['id'] =  $this->cms_general->generarId('order_status');
        $data['nombre'] = '';
        $data['link'] = base_url('admin/cart/insertar_estado');
        $data['txt_boton'] = 'Guardar';

        $this->load->view('admin/cart/estadoCrear_view', $data);
    }

    public function insertar_estado()
    {
        $this->Cart->insert_order_status();
    }

    public function modificar_estado()
    {

        $estado = $this->Cart->getEstado($this->uri->segment(4));

        $data['nombre'] = $estado->ord_status_description;
        $data['cancelled'] = $estado->ord_status_cancelled ? 'checked="checked"' : '';
        $data['save_default'] = $estado->ord_status_save_default ? 'checked="checked"' : '';
        $data['resave_default'] = $estado->ord_status_resave_default ? 'checked="checked"' : '';

        $data['titulo'] = 'Modificar Estado';
        $data['nuevo'] = '';
        $data['id'] =   $estado->ord_status_id;
        $data['link'] = base_url('admin/cart/actualizar_estado/' . $estado->ord_status_id);
        $data['txt_boton'] = 'Guardar';

        $this->load->view('admin/cart/estadoCrear_view', $data);
    }

    public function actualizar_estado()
    {
        $this->Cart->update_order_status($this->uri->segment(4));
    }

    public function eliminar_estado()
    {
        if($this->flexi_cart_admin->delete_db_order_status($this->uri->segment(4))) {
	        $this->load->view('admin/request/html', array('return' => 'success'));
        } else {
	        $this->load->view('admin/request/html', array('return' => 'No se pudo eliminar'));
        }
    }

    public function valores_defecto()
    {

        $data['titulo'] = 'Valores por Defecto';
        $data['link'] = base_url('admin/cart/actualizar_valores_defecto');
        $data['txt_boton'] = 'Actualizar';

        // Get an array of location data formatted with all sub-locations displayed 'inline', so all locations can be listed in one html select menu.
        // Alternatively, the location data could have been formatted with all sub-locations displayed 'tiered' into the location type groups.
        $data['locations_inline'] = $this->flexi_cart_admin->locations_inline();

        // Get an array of all currencies.
        $data['currency_data'] = $this->flexi_cart_admin->get_db_currency_array();

        // Get an array of all shipping options.
        $sql_where = array('ship_temporal' => 0);
        $data['shipping_data'] = $this->flexi_cart_admin->get_db_shipping_array(FALSE, $sql_where);

        // Get an array of all tax rate.
        $data['tax_data'] = $this->flexi_cart_admin->get_db_tax_array();

        // Get current cart defaults.
        $data['default_currency'] = $this->flexi_cart_admin->get_db_currency_row_array(FALSE, array('curr_default' => 1));
        $data['default_ship_location'] = $this->flexi_cart_admin->get_db_location_row_array(FALSE, array('loc_ship_default' => 1));
        $data['default_tax_location'] = $this->flexi_cart_admin->get_db_location_row_array(FALSE, array('loc_tax_default' => 1));
        $data['default_ship_option'] = $this->flexi_cart_admin->get_db_shipping_row_array(FALSE, array('ship_default' => 1));
        $data['default_tax_rate'] = $this->flexi_cart_admin->get_db_tax_row_array(FALSE, array('tax_default' => 1));

        $this->load->view('admin/cart/valoresDefecto_view', $data);
    }

    public function actualizar_valores_defecto()
    {
        $this->Cart->update_defaults();
    }

    public function config()
    {

        $data['titulo'] = 'Configuraci&oacute;n';
        $data['link'] = base_url('admin/cart/actualizar_configuracion');
        $data['txt_boton'] = 'Actualizar';

        // Get the row array of the config table.
        $data['config'] = $this->flexi_cart_admin->get_db_config_row_array();

        $this->load->view('admin/cart/configuracion_view', $data);
    }

    public function actualizar_configuracion()
    {
        $this->Cart->update_config();
    }

    /*------------------------------------------------------------------------------------------------------------
     *------------------------------------------------------------------------------------------------------------
     *-----------------------------------------------------------------------------------------------------------*/

    /**
     * Build the HTML for the category groups
     */
    function menuSubcategories($root_id = 0, $current_items = array())
    {
        $this->html  = array();
        $this->items = $this->Catalogo->getCategories();

        foreach ( $this->items as $item )
            $children[$item['categoriaPadre']][] = $item;

        // loop will be false if the root has no children (i.e., an empty menu!)
        $loop = !empty( $children[$root_id] );

        // initializing $parent as the root
        $parent = $root_id;
        $parent_stack = array();

        // HTML wrapper for the menu (open)
        $this->html[] = '';

        while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
        {

            if ( $option === false )
            {
                $parent = array_pop( $parent_stack );

                // HTML for menu item containing childrens (close)
                $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
                $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
            }
            elseif ( !empty( $children[$option['value']['categoriaId']] ) )
            {
                $tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

                //Get category items
                $items = $this->Catalogo->getProductos($option['value']['categoriaId']);

                $itemHtml = $this->generateItemHtml($items, $option['value']['categoriaId'], $tab, $current_items);

                // HTML for menu item containing childrens (open)
                $this->html[] = sprintf(
                    '%1$s<li class="pagina field">',
                    $tab // %1$s = tabulation
                );
                $this->html[] = $tab . "\t" . '<h3 class="header">Categoría: '.$option['value']['productoCategoriaNombre'].'</h3>';
                $this->html[] = $tab . $itemHtml;
                $this->html[] = $tab . "\t" . '<ul id="list_'.$option['value']['categoriaId'].'" class="sorteable content" data-sort="admin/descargas/reorganizarDescargas/'.$option['value']['categoriaPadre'].'">';

                array_push( $parent_stack, $option['value']['categoriaPadre'] );
                $parent = $option['value']['categoriaId'];
            }
            else
            {

                $tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

                $items = $this->Catalogo->getProductos($option['value']['categoriaId']);

                $itemHtml = $this->generateItemHtml($items, $option['value']['categoriaId'], $tab, $current_items);

                // HTML for menu item with no children (aka "leaf")
                $this->html[] = sprintf(
                    '%1$s<li class="pagina field">',
                    $tab // %1$s = tabulation
                );

                $this->html[] = $tab . "\t\t\t" . '<h3 class="header">Categoría: '.$option['value']['productoCategoriaNombre'].'</h3>';
                $this->html[] = $tab . $itemHtml;
                $this->html[] = $tab . "\t\t" . '</li>';

            }

        }

        // HTML wrapper for the menu (close)
        $this->html[] = '';

        return implode( "\r\n", $this->html );
    }

    function generateItemHtml($items, $catId, $tab, $current_items){

        //remove any duplicated items
        foreach($items as $key => $item){
            foreach ($current_items as $i){
                if($item['productoId'] === $i->productoId){
                    unset($items[$key]);
                }
            }
        }

        $itemHtml = $tab . '<ul id="p_list_'.$catId.'" class="sorteable content secciones" >' . PHP_EOL;
        foreach($items as $item){
            $itemHtml .= $tab . '<li class="listado drag" id="'.$item['productoId'].'">' . PHP_EOL;
            $itemHtml .= $tab . "\t" . '<div class="mover">mover</div>' . PHP_EOL;
            $itemHtml .= $tab . "\t" . '<a class="nombre modificar nivel2" href="'.base_url('admin/catalogo/modificarProducto/'.$item['productoId']).'">'.$item['productoNombre'].'</a>' . PHP_EOL;
            $itemHtml .= $tab . "\t" . '<a href="'.base_url('admin/catalogo/eliminarProducto/'.$item['productoId']).'" class="eliminar" >eliminar</a>' . PHP_EOL;
            $itemHtml .= $tab . '</li>' . PHP_EOL;
        }
        $itemHtml .= $tab . "\t" . '</ul>' . PHP_EOL;

        return $itemHtml;
    }

}