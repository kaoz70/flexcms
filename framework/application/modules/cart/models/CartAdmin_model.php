<?php

class CartAdmin_model extends CI_Model
{

    ###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
    // CART ORDERS
    ###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###

    function update_order_details($order_number)
    {
        // Update order status.
        $sql_update = array($this->flexi_cart_admin->db_column('order_summary', 'status') => $this->input->post('update_status'));

        $this->flexi_cart_admin->update_db_order_summary($sql_update, $order_number);

        ### ++++++++++ ###

        // Update shipped and cancelled item quantities.
        foreach($this->input->post('update_details') as $id => $row)
        {
            $sql_update = array();

            // Check that the 'Quantity Shipped' input field was submitted (Incase the field was disabled).
            if (isset($row['quantity_shipped']))
            {
                $sql_update[$this->flexi_cart_admin->db_column('order_details', 'item_quantity_shipped')] = $row['quantity_shipped'];
            }

            // Check that the 'Quantity Cancelled' input field was submitted (Incase the field was disabled).
            if (isset($row['quantity_cancelled']))
            {
                $sql_update[$this->flexi_cart_admin->db_column('order_details', 'item_quantity_cancelled')] = $row['quantity_cancelled'];
            }

            if (! empty($sql_update))
            {
                $this->flexi_cart_admin->update_db_order_details($sql_update, $row['id']);
            }
        }

        echo $this->flexi_cart_admin->get_messages('admin');
    }

    function createUbicacion()
    {

        $sql_insert = array(
            $this->flexi_cart_admin->db_column('location_type', 'name') => '',
            $this->flexi_cart_admin->db_column('location_type', 'parent') => ''
        );

        $this->flexi_cart_admin->insert_db_location_type($sql_insert);

        return $this->db->insert_id();

    }

    function update_location_types($id)
    {

        $sql_update = array(
            $this->flexi_cart_admin->db_column('location_type', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('location_type', 'parent') => $this->input->post('parent_location'),
            'loc_type_temporary' => 0
        );

        $this->flexi_cart_admin->update_db_location_type($sql_update, $id);

        echo $this->input->post('name');
    }

    function getUbicacion($id)
    {
        $query = $this->db
            ->where('loc_type_id', $id)
            ->get('location_type');
        return $query->row();
    }

    function insert_location($location_type_id)
    {

        $sql_insert = array(
            $this->flexi_cart_admin->db_column('locations', 'type') => $location_type_id,
            $this->flexi_cart_admin->db_column('locations', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('locations', 'parent') => $this->input->post('parent_location'),
            $this->flexi_cart_admin->db_column('locations', 'shipping_zone') => $this->input->post('shipping_zone'),
            $this->flexi_cart_admin->db_column('locations', 'tax_zone') => $this->input->post('tax_zone'),
            $this->flexi_cart_admin->db_column('locations', 'status') => $this->input->post('status'),
        );

        $this->flexi_cart_admin->insert_db_location($sql_insert);

    }

    function getSubUbicacion($id)
    {
        $query = $this->db
            ->where('loc_id', $id)
            ->get('locations');
        return $query->row();
    }

    function getSubUbicaciones($parent_id)
    {
        $query = $this->db
            ->where('loc_parent_fk', $parent_id)
            ->get('locations');
        return $query->row();
    }

    function update_location($id)
    {

        $sql_update = array(
            $this->flexi_cart_admin->db_column('locations', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('locations', 'parent') => $this->input->post('parent_location'),
            $this->flexi_cart_admin->db_column('locations', 'shipping_zone') => $this->input->post('shipping_zone'),
            $this->flexi_cart_admin->db_column('locations', 'tax_zone') => $this->input->post('tax_zone'),
            $this->flexi_cart_admin->db_column('locations', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->update_db_location($sql_update, $id);

    }

    function insert_zone()
    {

        $sql_insert = array(
            $this->flexi_cart_admin->db_column('location_zones', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('location_zones', 'description') => $this->input->post('desc'),
            $this->flexi_cart_admin->db_column('location_zones', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->insert_db_location_zone($sql_insert);

    }

    function getZone($id)
    {
        $query = $this->db
            ->where('lzone_id', $id)
            ->get('location_zones');
        return $query->row();
    }

    function update_zone($id)
    {
        $sql_update = array(
            $this->flexi_cart_admin->db_column('location_zones', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('location_zones', 'description') => $this->input->post('desc'),
            $this->flexi_cart_admin->db_column('location_zones', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->update_db_location_zone($sql_update, $id);
    }

    function createShippingOption()
    {

        $sql_insert = array(
            $this->flexi_cart_admin->db_column('shipping_options', 'name') => '',
        );

        return $this->flexi_cart_admin->insert_db_shipping($sql_insert);
    }

    function update_shipping($id)
    {
        $sql_insert = array(
            $this->flexi_cart_admin->db_column('shipping_options', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('shipping_options', 'description') => $this->input->post('desc'),
            $this->flexi_cart_admin->db_column('shipping_options', 'location') => $this->input->post('parent_location'),
            $this->flexi_cart_admin->db_column('shipping_options', 'zone') => $this->input->post('zone'),
            $this->flexi_cart_admin->db_column('shipping_options', 'inc_sub_locations') => $this->input->post('inc_sub_locations'),
            $this->flexi_cart_admin->db_column('shipping_options', 'tax_rate') => $this->input->post('tax_rate'),
            $this->flexi_cart_admin->db_column('shipping_options', 'discount_inclusion') => $this->input->post('discount_inclusion'),
            $this->flexi_cart_admin->db_column('shipping_options', 'status') => $this->input->post('status'),
            'ship_temporal' => 0,
        );

        return $this->flexi_cart_admin->update_db_shipping($sql_insert, $id);
    }

    function getShippingOption($id)
    {
        $query = $this->db
            ->where('ship_id', $id)
            ->get('shipping_options');
        return $query->row();
    }

    function insert_shipping_rate($shipping_id)
    {
        $sql_insert = array(
            $this->flexi_cart_admin->db_column('shipping_rates', 'parent') => $shipping_id,
            $this->flexi_cart_admin->db_column('shipping_rates', 'value') => $this->input->post('value'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'tare_weight') => $this->input->post('tare_weight'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'min_weight') => $this->input->post('min_weight'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'max_weight') => $this->input->post('max_weight'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'min_value') => $this->input->post('min_value'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'max_value') => $this->input->post('max_value'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->insert_db_shipping_rate($sql_insert);
    }

    function getTarifaEnvio($id)
    {
        $query = $this->db
            ->where('ship_rate_id', $id)
            ->get('shipping_rates');
        return $query->row();
    }

    function update_shipping_rate($id)
    {
        $sql_update = array(
            $this->flexi_cart_admin->db_column('shipping_rates', 'value') => $this->input->post('value'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'tare_weight') => $this->input->post('tare_weight'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'min_weight') => $this->input->post('min_weight'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'max_weight') => $this->input->post('max_weight'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'min_value') => $this->input->post('min_value'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'max_value') => $this->input->post('max_value'),
            $this->flexi_cart_admin->db_column('shipping_rates', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->update_db_shipping_rate($sql_update, $id);
    }

    function insert_tax()
    {
        $sql_insert = array(
            $this->flexi_cart_admin->db_column('tax', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('tax', 'location') => $this->input->post('parent_location'),
            $this->flexi_cart_admin->db_column('tax', 'zone') => $this->input->post('zone'),
            $this->flexi_cart_admin->db_column('tax', 'rate') => $this->input->post('rate'),
            $this->flexi_cart_admin->db_column('tax', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->insert_db_tax($sql_insert);
    }

    function getTax($id)
    {
        $query = $this->db
            ->where('tax_id', $id)
            ->get('tax');
        return $query->row();
    }

    function update_tax($id)
    {
        $sql_update = array(
            $this->flexi_cart_admin->db_column('tax', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('tax', 'location') => $this->input->post('parent_location'),
            $this->flexi_cart_admin->db_column('tax', 'zone') => $this->input->post('zone'),
            $this->flexi_cart_admin->db_column('tax', 'rate') => $this->input->post('rate'),
            $this->flexi_cart_admin->db_column('tax', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->update_db_tax($sql_update, $id);
    }

    function insert_discount()
    {
        $this->load->library('form_validation');

        // Set validation rules.
        $this->form_validation->set_rules('insert[type]', 'Discount Type', 'greater_than[0]');
        $this->form_validation->set_rules('insert[method]', 'Discount Method', 'required|greater_than[0]');
        $this->form_validation->set_rules('insert[usage_limit]', 'Usage Limit', 'required');
        $this->form_validation->set_rules('insert[valid_date]', 'Valid Date', 'required');
        $this->form_validation->set_rules('insert[expire_date]', 'Expire Date', 'required');

        // The following fields are not validated, however must be included as done below or their data will not be repopulated by CI.
        $this->form_validation->set_rules('insert[tax_method]');
        $this->form_validation->set_rules('insert[location]');
        $this->form_validation->set_rules('insert[zone]');
        $this->form_validation->set_rules('insert[group]');
        $this->form_validation->set_rules('insert[item]');
        $this->form_validation->set_rules('insert[code]');
        $this->form_validation->set_rules('insert[description]');
        $this->form_validation->set_rules('insert[quantity_required]');
        $this->form_validation->set_rules('insert[quantity_discounted]');
        $this->form_validation->set_rules('insert[value_required]');
        $this->form_validation->set_rules('insert[value_discounted]');
        $this->form_validation->set_rules('insert[recursive]');
        $this->form_validation->set_rules('insert[unique]');
        $this->form_validation->set_rules('insert[void_reward]');
        $this->form_validation->set_rules('insert[force_shipping]');
        $this->form_validation->set_rules('insert[custom_status_1]');
        $this->form_validation->set_rules('insert[custom_status_2]');
        $this->form_validation->set_rules('insert[custom_status_3]');
        $this->form_validation->set_rules('insert[order_by]');

        // Validate fields.
        if ($this->form_validation->run())
        {
            $row = $this->input->post('insert');

            $sql_insert = array(
                $this->flexi_cart_admin->db_column('discounts', 'type') => $row['type'],
                $this->flexi_cart_admin->db_column('discounts', 'method') => $row['method'],
                $this->flexi_cart_admin->db_column('discounts', 'tax_method') => $row['tax_method'],
                $this->flexi_cart_admin->db_column('discounts', 'location') => $row['location'],
                $this->flexi_cart_admin->db_column('discounts', 'zone') => $row['zone'],
                $this->flexi_cart_admin->db_column('discounts', 'group') => $row['group'],
                $this->flexi_cart_admin->db_column('discounts', 'item') => $row['item'],
                $this->flexi_cart_admin->db_column('discounts', 'code') => $row['code'],
                $this->flexi_cart_admin->db_column('discounts', 'description') => $row['description'],
                $this->flexi_cart_admin->db_column('discounts', 'quantity_required') => $row['quantity_required'],
                $this->flexi_cart_admin->db_column('discounts', 'quantity_discounted') => $row['quantity_discounted'],
                $this->flexi_cart_admin->db_column('discounts', 'value_required') => $row['value_required'],
                $this->flexi_cart_admin->db_column('discounts', 'value_discounted') => $row['value_discounted'],
                $this->flexi_cart_admin->db_column('discounts', 'recursive') => $row['recursive'],
                $this->flexi_cart_admin->db_column('discounts', 'non_combinable') => $row['non_combinable'],
                $this->flexi_cart_admin->db_column('discounts', 'void_reward_points') => $row['void_reward'],
                $this->flexi_cart_admin->db_column('discounts', 'force_shipping_discount') => $row['force_shipping'],
                $this->flexi_cart_admin->db_column('discounts', 'custom_status_1') => $row['custom_status_1'],
                $this->flexi_cart_admin->db_column('discounts', 'custom_status_2') => $row['custom_status_2'],
                $this->flexi_cart_admin->db_column('discounts', 'custom_status_3') => $row['custom_status_3'],
                $this->flexi_cart_admin->db_column('discounts', 'usage_limit') => $row['usage_limit'],
                $this->flexi_cart_admin->db_column('discounts', 'valid_date') => $row['valid_date'],
                $this->flexi_cart_admin->db_column('discounts', 'expire_date') => $row['expire_date'],
                $this->flexi_cart_admin->db_column('discounts', 'status') => $row['status'],
                $this->flexi_cart_admin->db_column('discounts', 'order_by') => $row['order_by']
            );

            $this->flexi_cart_admin->insert_db_discount($sql_insert);

            return $this->flexi_cart_admin->get_messages('admin');
        }
        else
        {
            echo validation_errors('<p class="error_msg">', '</p>');
            return FALSE;
        }
    }

    function getDescuento($id)
    {
        $query = $this->db
            ->where('disc_id', $id)
            ->get('discounts');
        return $query->row();
    }

    function update_discount($discount_id)
    {
        $this->load->library('form_validation');

        // Set validation rules.
        $this->form_validation->set_rules('insert[type]', 'Discount Type', 'greater_than[0]');
        $this->form_validation->set_rules('insert[method]', 'Discount Method', 'greater_than[0]');
        $this->form_validation->set_rules('insert[usage_limit]', 'Usage Limit', 'required');
        $this->form_validation->set_rules('insert[valid_date]', 'Valid Date', 'required');
        $this->form_validation->set_rules('insert[expire_date]', 'Expire Date', 'required');

        // The following fields are not validated, however must be included as done below or their data will not be repopulated by CI.
        $this->form_validation->set_rules('insert[tax_method]');
        $this->form_validation->set_rules('insert[location]');
        $this->form_validation->set_rules('insert[zone]');
        $this->form_validation->set_rules('insert[group]');
        $this->form_validation->set_rules('insert[item]');
        $this->form_validation->set_rules('insert[code]');
        $this->form_validation->set_rules('insert[description]');
        $this->form_validation->set_rules('insert[quantity_required]');
        $this->form_validation->set_rules('insert[quantity_discounted]');
        $this->form_validation->set_rules('insert[value_required]');
        $this->form_validation->set_rules('insert[value_discounted]');
        $this->form_validation->set_rules('insert[recursive]');
        $this->form_validation->set_rules('insert[unique]');
        $this->form_validation->set_rules('insert[void_reward]');
        $this->form_validation->set_rules('insert[force_shipping]');
        $this->form_validation->set_rules('insert[custom_status_1]');
        $this->form_validation->set_rules('insert[custom_status_2]');
        $this->form_validation->set_rules('insert[custom_status_3]');
        $this->form_validation->set_rules('insert[order_by]');

        // Validate fields.
        if ($this->form_validation->run())
        {
            $row = $this->input->post('insert');

            $sql_update = array(
                $this->flexi_cart_admin->db_column('discounts', 'type') => $row['type'],
                $this->flexi_cart_admin->db_column('discounts', 'method') => $row['method'],
                $this->flexi_cart_admin->db_column('discounts', 'tax_method') => $row['tax_method'],
                $this->flexi_cart_admin->db_column('discounts', 'location') => $row['location'],
                $this->flexi_cart_admin->db_column('discounts', 'zone') => $row['zone'],
                $this->flexi_cart_admin->db_column('discounts', 'group') => $row['group'],
                $this->flexi_cart_admin->db_column('discounts', 'item') => $row['item'],
                $this->flexi_cart_admin->db_column('discounts', 'code') => $row['code'],
                $this->flexi_cart_admin->db_column('discounts', 'description') => $row['description'],
                $this->flexi_cart_admin->db_column('discounts', 'quantity_required') => $row['quantity_required'],
                $this->flexi_cart_admin->db_column('discounts', 'quantity_discounted') => $row['quantity_discounted'],
                $this->flexi_cart_admin->db_column('discounts', 'value_required') => $row['value_required'],
                $this->flexi_cart_admin->db_column('discounts', 'value_discounted') => $row['value_discounted'],
                $this->flexi_cart_admin->db_column('discounts', 'recursive') => $row['recursive'],
                $this->flexi_cart_admin->db_column('discounts', 'non_combinable') => $row['non_combinable'],
                $this->flexi_cart_admin->db_column('discounts', 'void_reward_points') => $row['void_reward'],
                $this->flexi_cart_admin->db_column('discounts', 'force_shipping_discount') => $row['force_shipping'],
                $this->flexi_cart_admin->db_column('discounts', 'custom_status_1') => $row['custom_status_1'],
                $this->flexi_cart_admin->db_column('discounts', 'custom_status_2') => $row['custom_status_2'],
                $this->flexi_cart_admin->db_column('discounts', 'custom_status_3') => $row['custom_status_3'],
                $this->flexi_cart_admin->db_column('discounts', 'usage_limit') => $row['usage_limit'],
                $this->flexi_cart_admin->db_column('discounts', 'valid_date') => $row['valid_date'],
                $this->flexi_cart_admin->db_column('discounts', 'expire_date') => $row['expire_date'],
                $this->flexi_cart_admin->db_column('discounts', 'status') => $row['status'],
                $this->flexi_cart_admin->db_column('discounts', 'order_by') => $row['order_by']
            );

            $this->flexi_cart_admin->update_db_discount($sql_update, $discount_id);

            return $this->flexi_cart_admin->get_messages('admin');

        }
        else
        {
            echo validation_errors('<p class="error_msg">', '</p>');
            return FALSE;
        }
    }

    function insert_discount_group()
    {

        $sql_insert = array(
            $this->flexi_cart_admin->db_column('discount_groups', 'name') => '',
            $this->flexi_cart_admin->db_column('discount_groups', 'status') => 1
        );

        return $this->flexi_cart_admin->insert_db_discount_group($sql_insert);
    }

    function update_discount_groups($id)
    {
        $sql_update = array(
            $this->flexi_cart_admin->db_column('discount_groups', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('discount_groups', 'status') => $this->input->post('status'),
            'disc_group_temporary' => 0,
        );

        $this->flexi_cart_admin->update_db_discount_group($sql_update, $id);
    }

    function getGrupoDescuento($id)
    {
        $query = $this->db
            ->where('disc_group_id', $id)
            ->get('discount_groups');
        return $query->row();
    }

    function getGrupoDescuentoItems($id)
    {
        $query = $this->db
            ->where('disc_group_item_group_fk', $id)
            ->join('productos', 'productos.productoId = discount_group_items.disc_group_item_item_fk')
            ->join('es_productos', 'es_productos.productoId = productos.productoId')
            ->get('discount_group_items');
        return $query->result();
    }

    function insert_discount_group_items($group_id)
    {

        $ids = json_decode($this->input->post('seccionesAdmin'));

        //Delete any items already there
        $sql_where = array($this->flexi_cart_admin->db_column('discount_group_items', 'group') => $group_id);
        $this->flexi_cart_admin->delete_db_discount_group_item($sql_where);

        //Insert the items
        foreach($ids as $id)
        {
            $sql_insert = array(
                $this->flexi_cart_admin->db_column('discount_group_items', 'group') => $group_id,
                $this->flexi_cart_admin->db_column('discount_group_items', 'item') => $id
            );

            $this->flexi_cart_admin->insert_db_discount_group_item($sql_insert);
        }
    }

    function insert_currency()
    {
        $sql_insert = array(
            $this->flexi_cart_admin->db_column('currency', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('currency', 'exchange_rate') => $this->input->post('exchange_rate'),
            $this->flexi_cart_admin->db_column('currency', 'symbol') => $this->input->post('symbol'),
            $this->flexi_cart_admin->db_column('currency', 'symbol_suffix') => $this->input->post('symbol_suffix'),
            $this->flexi_cart_admin->db_column('currency', 'thousand_separator') => $this->input->post('thousand'),
            $this->flexi_cart_admin->db_column('currency', 'decimal_separator') => $this->input->post('decimal'),
            $this->flexi_cart_admin->db_column('currency', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->insert_db_currency($sql_insert);
    }

    function getMoneda($id)
    {
        $query = $this->db
            ->where('curr_id', $id)
            ->get('currency');

        return $query->row();
    }

    function update_currency($id)
    {
        $sql_update = array(
            $this->flexi_cart_admin->db_column('currency', 'name') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('currency', 'exchange_rate') => $this->input->post('exchange_rate'),
            $this->flexi_cart_admin->db_column('currency', 'symbol') => $this->input->post('symbol'),
            $this->flexi_cart_admin->db_column('currency', 'symbol_suffix') => $this->input->post('symbol_suffix'),
            $this->flexi_cart_admin->db_column('currency', 'thousand_separator') => $this->input->post('thousand'),
            $this->flexi_cart_admin->db_column('currency', 'decimal_separator') => $this->input->post('decimal'),
            $this->flexi_cart_admin->db_column('currency', 'status') => $this->input->post('status')
        );

        $this->flexi_cart_admin->update_db_currency($sql_update, $id);
    }

    function insert_order_status()
    {
        $sql_insert = array(
            $this->flexi_cart_admin->db_column('order_status', 'status') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('order_status', 'cancelled') => $this->input->post('cancelled'),
            $this->flexi_cart_admin->db_column('order_status', 'save_default') => $this->input->post('save_default'),
            $this->flexi_cart_admin->db_column('order_status', 'resave_default') => $this->input->post('resave_default')
        );

        $this->flexi_cart_admin->insert_db_order_status($sql_insert);
    }

    function getEstado($id)
    {
        $query = $this->db
            ->where('ord_status_id', $id)
            ->get('order_status');

        return $query->row();
    }

    function update_order_status($id)
    {
        $sql_update = array(
            $this->flexi_cart_admin->db_column('order_status', 'status') => $this->input->post('name'),
            $this->flexi_cart_admin->db_column('order_status', 'cancelled') => $this->input->post('cancelled'),
            $this->flexi_cart_admin->db_column('order_status', 'save_default') => $this->input->post('save_default'),
            $this->flexi_cart_admin->db_column('order_status', 'resave_default') => $this->input->post('resave_default')
        );

        $this->flexi_cart_admin->update_db_order_status($sql_update, $id);
    }

    function update_defaults()
    {
        $data = $this->input->post('update');

        ###+++++++++++++++++++++++++++++++++###

        // Reset all cart defaults.
        $sql_update = array('curr_default' => 0);
        $this->flexi_cart_admin->update_db_currency($sql_update);

        $sql_update = array('loc_ship_default' => 0, 'loc_tax_default' => 0);
        $this->flexi_cart_admin->update_db_location($sql_update);

        $sql_update = array('ship_default' => 0);
        $this->flexi_cart_admin->update_db_shipping($sql_update);

        $sql_update = array('tax_default' => 0);
        $this->flexi_cart_admin->update_db_tax($sql_update);

        ###+++++++++++++++++++++++++++++++++###

        // Set new cart defaults.
        $sql_update = array('curr_default' => 1);
        $this->flexi_cart_admin->update_db_currency($sql_update, $data['currency']);

        $sql_update = array('loc_ship_default' => 1);
        $this->flexi_cart_admin->update_db_location($sql_update, $data['shipping_location']);

        $sql_update = array('loc_tax_default' => 1);
        $this->flexi_cart_admin->update_db_location($sql_update, $data['tax_location']);

        $sql_update = array('ship_default' => 1);
        $this->flexi_cart_admin->update_db_shipping($sql_update, $data['shipping_option']);

        $sql_update = array('tax_default' => 1);
        $this->flexi_cart_admin->update_db_tax($sql_update, $data['tax_rate']);
    }

    function update_config()
    {
        $data = $this->input->post('update');

        $sql_update = array(
            $this->flexi_cart_admin->db_column('configuration', 'order_number_prefix') => $data['order_number_prefix'],
            $this->flexi_cart_admin->db_column('configuration', 'order_number_suffix') => $data['order_number_suffix'],
            $this->flexi_cart_admin->db_column('configuration', 'increment_order_number') => $data['increment_order_number'],
            $this->flexi_cart_admin->db_column('configuration', 'minimum_order') => $data['minimum_order'],
            $this->flexi_cart_admin->db_column('configuration', 'quantity_decimals') => $data['quantity_decimals'],
            $this->flexi_cart_admin->db_column('configuration', 'increment_duplicate_item_quantity') => $data['increment_duplicate_item_quantity'],
            $this->flexi_cart_admin->db_column('configuration', 'quantity_limited_by_stock') => $data['quantity_limited_by_stock'],
            $this->flexi_cart_admin->db_column('configuration', 'remove_no_stock_items') => $data['remove_no_stock_items'],
            $this->flexi_cart_admin->db_column('configuration', 'auto_allocate_stock') => $data['auto_allocate_stock'],
            $this->flexi_cart_admin->db_column('configuration', 'weight_type') => $data['weight_type'],
            $this->flexi_cart_admin->db_column('configuration', 'weight_decimals') => $data['weight_decimals'],
            $this->flexi_cart_admin->db_column('configuration', 'display_tax_prices') => $data['display_tax_prices'],
            $this->flexi_cart_admin->db_column('configuration', 'price_inc_tax') => $data['price_inc_tax'],
            $this->flexi_cart_admin->db_column('configuration', 'multi_row_duplicate_items') => $data['multi_row_duplicate_items'],
            $this->flexi_cart_admin->db_column('configuration', 'dynamic_reward_points') => $data['dynamic_reward_points'],
            $this->flexi_cart_admin->db_column('configuration', 'reward_point_multiplier') => $data['reward_point_multiplier'],
            $this->flexi_cart_admin->db_column('configuration', 'reward_voucher_multiplier') => $data['reward_voucher_multiplier'],
            $this->flexi_cart_admin->db_column('configuration', 'reward_point_to_voucher_ratio') => $data['reward_point_to_voucher_ratio'],
            $this->flexi_cart_admin->db_column('configuration', 'reward_point_days_pending') => $data['reward_point_days_pending'],
            $this->flexi_cart_admin->db_column('configuration', 'reward_point_days_valid') => $data['reward_point_days_valid'],
            $this->flexi_cart_admin->db_column('configuration', 'reward_voucher_days_valid') => $data['reward_voucher_days_valid'],
            $this->flexi_cart_admin->db_column('configuration', 'save_banned_shipping_items') => $data['save_banned_shipping_items'],
            $this->flexi_cart_admin->db_column('configuration', 'custom_status_1') => $data['custom_status_1'],
            $this->flexi_cart_admin->db_column('configuration', 'custom_status_2') => $data['custom_status_2'],
            $this->flexi_cart_admin->db_column('configuration', 'custom_status_3') => $data['custom_status_3']
        );

        $this->flexi_cart_admin->update_db_config($sql_update);

        // Destroy the current cart and all settings so that new config settings can be set.
        // Note: The 'destroy_cart()' function is apart of the standard library.
        $this->load->library('flexi_cart');
        $this->flexi_cart->destroy_cart();
    }

}