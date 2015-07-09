<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_model extends CI_Model {
	
	// The following method prevents an error occurring when $this->data is modified.
	// Error Message: 'Indirect modification of overloaded property Demo_cart_model::$data has no effect'
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// UPDATE CART
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * demo_update_cart
	 * Get item and shipping data from form inputs, and update the cart.
	 * This example uses the shipping location to update both the carts shipping and tax data.
	 */
	function update_cart()
	{
		$this->load->library('flexi_cart');

		// Get item quantity data.
		$cart_data = $this->input->post('items');

		$settings = array();
		if ($this->input->post('shipping'))
		{
			foreach($this->input->post('shipping') as $type => $value)
			{
				// Update selected Country and State for shipping calculations.
				// !Important Note: We are matching countries and states by their database ID. Therefore it is important that the submitted value is an INT datatype 
				// and not a STRING. The reason for this is that the function used to update locations interprets STRING numbers as zip code locations.
				// A number can be converted to an INT using '(int)' before the variable.
				if (in_array($type, array('country', 'state')))
				{
					$settings['update_shipping_location'][] = (int)$value;
					$settings['update_tax_location'][] = (int)$value;
				}
				// Update selected Country and State for shipping calculations.
				// !Important Note: As US postal (Zip) codes are numeric, and we are trying to match the postal code by name (i.e. '10101'), it is important to ensure
				// the value is a STRING datatype, otherwise the function used to update locations interprets an INT number as a locations database ID.
				// A number can be converted to a STRING using '(string)' before the variable.
				else if ($type == 'postal_code')
				{
					$settings['update_shipping_location'][] = (string)$value;
					$settings['update_tax_location'][] = (string)$value;
				}
				
				// Update shipping option/method for shipping options updated via a database.
				// Database shipping data must be updated using the array key 'update_shipping'.
				// Note: This demo includes examples of updating shipping via a database or via setting manually, read the 'Database and Manual Shipping Data'
				//	section above in the 'view_cart' method to toggle which mode the cart uses.
				else if ($type == 'db_option')
				{
					$settings['update_shipping'] = $value;
				}
				// Update shipping option/method for shipping options set manually. 
				// Manually set shipping data must be updated using the array key 'set_shipping'.
				// Note: This demo includes examples of updating shipping via a database or via setting manually, read the 'Database and Manual Shipping Data'
				//	section above in the 'view_cart' method to toggle which mode the cart uses.
				else if ($type == 'manual_option')
				{
					// The manual shipping id has been submitted (as $value), we now need to obtain the remaining shipping option data.
					$settings['set_shipping'] = $this->demo_manual_shipping_options($value);
				}
			}
		}
				
		// Update the cart with the cart item data and the location and shipping data.
		return $this->flexi_cart->update_cart($cart_data, $settings, FALSE, TRUE);
	}
		
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// ITEM DATA AND INSERT EXAMPLES
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * demo_get_item_data
	 * Get data from a custom item table that is not a part of flexi cart.
	 */
	function demo_get_item_data()
	{
		return $this->db->from('demo_items')
			->join('demo_categories', 'item_cat_fk = cat_id')
			->order_by('item_id')
			->get()
			->result_array();
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###

	/**
	 * insert_item_to_cart
	 * Insert an item to the cart via the 'Add items to cart via a link' page.
	 */
	function insert_item_to_cart()
	{
		$this->load->library('flexi_cart');

        $lang = $this->input->post('idioma', 'es');

        $query = $this->db->select('*')
            ->from('productos')
            ->where('productos.productoId', $this->input->post('productoId'))
            ->join($lang . '_productos', 'productos.productoId = ' . $lang . '_productos.productoId')
            ->get();

        if ($query->num_rows() == 1){

            $producto = $query->row();

            $price = $this->getProductFieldData($producto->productoId, 'precio', $lang);
            $weight = $this->getProductFieldData($producto->productoId, 'peso', $lang);

            //Set the updatable options - Listados predefinidos
            $this->db->where('inputTipoContenido', 'listado predefinido');
            $this->db->where('productoCampoVerPedido', 1);
            $this->db->join('input', 'input.inputId = producto_campos.inputId');
            $this->db->join('input_tipo', 'input.inputTipoId = input_tipo.inputTipoId');
            $this->db->join($lang.'_producto_campos', $lang.'_producto_campos.productoCampoId = producto_campos.productoCampoId', 'left');
            $query = $this->db->get('producto_campos');

            $listado = $query->result();

            $opciones = array();
            $updatable_option_data = array();
            $campos_post = $this->input->post('campo');

            foreach ($listado as $item) {
                $opciones[$item->productoCampoId] = $campos_post[$item->productoCampoId];

                //Get the list items
                $this->db->from('producto_campos_listado_predefinido_rel')
                    ->join('producto_campos_listado_predefinido', 'producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido_rel.productoCamposListadoPredefinidoId')
                    ->join($lang.'_producto_campos_listado_predefinido', $lang.'_producto_campos_listado_predefinido.productoCamposListadoPredefinidoId = producto_campos_listado_predefinido.productoCamposListadoPredefinidoId')
                    ->where('productoId', $producto->productoId)
                    ->where('producto_campos_listado_predefinido.productoCampoId', $item->productoCampoId);
                $query = $this->db->get();
                $resultado = $query->result();

                $updatable_option_data[$item->productoCampoId] = array();

                foreach ($resultado as $list_item) {
                    $updatable_option_data[$item->productoCampoId][] = $list_item->productoCamposListadoPredefinidoTexto;
                }

            }

            $cart_data = array(
                'id' => $this->input->post('productoId'),
                'name' => $producto->productoNombre,
                'quantity' => $this->input->post('cantidad'),
                'price' => $price,
                'weight' => $weight,
                'options' => $opciones,
                'option_data' => $updatable_option_data,
                'image_extension' => $producto->productoImagenExtension,
                //'sku' => 'ITEMSKU116',
                //'user_note' => 'Customer Note'
            );

            // Insert collected data to cart.
            if (isset($cart_data))
            {
                $this->flexi_cart->insert_items($cart_data);
            }
        }

	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	// DISCOUNTS
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * demo_set_discount
	 * Manually set a discount to a cart summary column via the 'Discounts / Surcharges' page.
	 */
	function demo_set_discount($discount_id = FALSE)
	{
		$this->load->library('flexi_cart');

		if (is_numeric($discount_id))
		{
			// By default a '0%' discount is applied to the 'total' column, and is taxed accordingly to the carts current tax settings, with reward points not voided.
			// Therefore, it is only necessary to submit array keys for the values that need to be changed from the default settings.
			// 'calculation' key values:  '1' = Percentage rate discount, method '2' = Flat rate discount, method '3' = New value discount.
		
			// �5 discount on grand total.
			if ($discount_id == 1)
			{
				$discount = array('id' => '5_discount', 'value' => 5, 'column' => 'total', 'calculation' => 2, 'tax_method' => 1, 'description' => '&pound;5 Discount');
			}
			// 10% discount on item total.
			else if ($discount_id == 2)
			{
				$discount = array('id' => '10pc_discount', 'value' => 10, 'column' => 'item_summary_total', 'calculation' => 1, 'tax_method' => 1, 'description' => '10% Discount on Item Total');
			}
			// Free shipping, void reward points.
			// Note: This example also sets a specific id of 'free_ship' for the discount. 
			// By defining an id, the discount can be updated/deleted using that specific id rather than a auto incremental numeric id. 
			else if ($discount_id == 3)
			{
				// !Important: To apply a fixed value discount (In this example '0' for free shipping), a 'calculation' method of '3' must be set.
				$discount = array(
					'id' => 'free_ship', 'value' => 0, 'column' => 'shipping_total', 'calculation' => 3, 'tax_method' => 1, 
					'description' => 'Free Shipping (UK only), but void reward points.', 'void_reward_points' => TRUE
				);
			}

			// Set the discount data.
			return $this->flexi_cart->set_discount($discount);
		}
		
		return FALSE;
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// SURCHARGES
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * demo_set_surcharge
	 * Manually set a surcharge to a cart summary column via the 'Discounts / Surcharges' page.
	 */
	function demo_set_surcharge($surcharge_id = FALSE)
	{
		$this->load->library('flexi_cart');

		// Surcharge values can either be percentage based of fixed rate.
		// To set a percentage based surcharge, the column the surcharge is to be applied to must be submitted as either 'item_summary_total', 'shipping_total' or 'total'.
		// By default a fixed rate '0.00' surcharge is applied to the 'total' column and taxed accordingly to the carts current tax settings.
		// Therefore, it is only necessary to submit array keys for the values that need to be changed from the default settings.

		if (is_numeric($surcharge_id))
		{
			// �5 surcharge on grand total.
			if ($surcharge_id == 1)
			{
				// This example specifies a 10% tax rate on the surcharge.
				$surcharge = array('id' => '5_surcharge', 'value' => 5, 'tax_rate' => 10, 'column' => FALSE, 'description' => '&pound;5.00 surcharge, with 10% tax rate');
			}
			// 2% surcharge on sub-total.
			else if ($surcharge_id == 2)
			{
				// This example specifies no tax on the surcharge.
				$surcharge = array('id' => '2pc_surcharge', 'value' => 2, 'tax_rate' => 0, 'column' => 'item_summary_total', 'description' => '2% Surcharge on Item Summary Total, tax free');
			}
			// 2% surcharge on sub-total.
			else if ($surcharge_id == 3)
			{
				// This example specifies the cart default tax rate on the surcharge by submitting FALSE.
				$surcharge = array('id' => 'giftwrap', 'value' => 10, 'tax_rate' => FALSE, 'column' => FALSE, 'description' => '&pound;10.00 Gift wrap option, with default cart tax rate');
			}
			// 2% surcharge on sub-total.
			else if ($surcharge_id == 4)
			{
				// This example specifies no tax on the surcharge.
				$surcharge = array('id' => 'creditcard', 'value' => 3.5, 'tax_rate' => 0, 'column' => 'total', 'description' => '3.5% credit card surcharge on cart total, tax free');
			}

			return $this->flexi_cart->set_surcharge($surcharge);
		}
		
		return FALSE;
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// TAX
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * demo_update_tax
	 * This example updates the carts tax location and therefore tax rate, independently from the shipping location. 
	 */
	function demo_update_tax()
	{
		$this->load->library('flexi_cart');

		// This example is attempting to update the carts tax location using the id of a country.
		// !Important Note: We are matching the submitted country by its database ID. Therefore it is important that the submitted value is an INT datatype 
		// and not a STRING. The reason for this is that the function used to update locations interprets STRING numbers as zip code locations.
		// A number can be converted to an INT using '(int)' before the variable.
		$location_id = (int)$this->input->post('tax_location');
		
		$this->flexi_cart->update_tax_location($location_id);
		
		// Set a custom status message.
		$this->flexi_cart->set_status_message('Tax successfully updated', 'public');

		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_cart->get_messages());
		
		redirect('standard_library');
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// LOCATIONS
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * demo_manual_shipping_options
	 * This example returns an array of manually defined shipping options, rather than using a database to obtain shipping rate data.
	 * If a '$ship_id' is submitted, only the array of that shipping option will be returned.
	 */
	function demo_manual_shipping_options($ship_id = FALSE)
	{
		$shipping_options =  array(
			1 => array('id' => 1, 'name' => 'Manual Ship Option #1', 'description' => '4-5 days', 'value' => 4.99),
			2 => array('id' => 2, 'name' => 'Manual Ship Option #2', 'description' => '2-3 days', 'value' => 7.99),
			3 => array('id' => 3, 'name' => 'Manual Ship Option #3', 'description' => 'Next day', 'value' => 9.99),
			4 => array('id' => 4, 'name' => 'Manual Ship Option #4 - Free', 'description' => 'Collect', 'value' => 0)
		);
		
		return (isset($shipping_options[$ship_id])) ? $shipping_options[$ship_id] : $shipping_options;
	}	
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// LOAD CART DATA
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * update_loaded_cart_data
	 * When a cart is loaded from saved database cart data, it is possible that item prices may have since changed, the problem this causes is that the cart will
	 * still display the item at the original price, whereas it needs to be updated to the current price.
	 * As flexi cart does not manage item tables, this function has to be custom made to suit each sites requirements. This is an example of how it can be achieved.
	 * Note that cart items including selectable options that affect the price would likely require a more complex query.
	 */
	function update_loaded_cart_data($lang)
	{
		$this->load->library('flexi_cart');

		$update_data = array();
		
		foreach($this->flexi_cart->cart_item_array() as $row_id => $column)
		{
			$sql_where = array('productoId' => $column['id']);
		
			$query = $this->db->get_where('productos', $sql_where);
				
			if ($query->num_rows() == 1)
			{
				$item_data = $query->row();

                $price = $this->getProductFieldData($item_data->productoId, 'precio', $lang);
                $weight = $this->getProductFieldData($item_data->productoId, 'peso', $lang);

				// The array 'key' names are the same as those used for items in the cart array.
				// The 'row_id' is required to identify which cart row needs to be updated.
				$update_data[] = array(
					'row_id' => $row_id,
					'price' => $price,
                    'image_extension' => $item_data->productoImagenExtension,
					'weight' => $weight
				);
			}
		}
		
		if (! empty($update_data))
		{
			// When calling the 'update_data()' function, setting the 3rd parameter as 'TRUE' will force all submitted data to be updated to the cart,
			// regardless of whether the cart column is defined via the config file as an 'updatable column' or not.
			return $this->flexi_cart->update_cart($update_data, FALSE, TRUE);
		}
		
		return FALSE;
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// SAVE CART AND CUSTOMER DETAILS
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * save_order
	 * This example validates data posted from the checkout page and then saves it along with the current cart data as a confirmed order.
	 */
	function save_order($user_id)
	{
		$this->load->library('form_validation');
		$this->load->library('flexi_cart_admin');
		
		// Set validation rules.
		$this->form_validation->set_rules('checkout[billing][name]', 'Nombre', 'required');
		$this->form_validation->set_rules('checkout[billing][add_01]', 'Direcci&oacute;n 1', 'required');
		$this->form_validation->set_rules('checkout[billing][city]', 'Cuidad / Pueblo', 'required');
		$this->form_validation->set_rules('checkout[billing][state]', 'Estado / Provincia', 'required');
		$this->form_validation->set_rules('checkout[billing][post_code]', 'C&oacute;digo postal', 'required');
		$this->form_validation->set_rules('checkout[billing][country]', 'Pa&iacute;s', 'required');
		$this->form_validation->set_rules('checkout[shipping][name]', 'Nombre', 'required');
		$this->form_validation->set_rules('checkout[shipping][add_01]', 'Direcc&iacute;n 1', 'required');
		$this->form_validation->set_rules('checkout[shipping][city]', 'Cuidad / Pueblo ', 'required');
		$this->form_validation->set_rules('checkout[shipping][state]', 'Estado / Provincia', 'required');
		$this->form_validation->set_rules('checkout[shipping][post_code]', 'C&oacute;digo postal', 'required');
		$this->form_validation->set_rules('checkout[shipping][country]', 'Pa&iacute;s', 'required');
		$this->form_validation->set_rules('checkout[email]', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('checkout[phone]', 'Tel&eacute;fono', 'required|min_length[7]');

		// The following fields are not validated, however must be included as done below or their data will not be repopulated by CI.
		$this->form_validation->set_rules('checkout[billing][company]');
		$this->form_validation->set_rules('checkout[billing][add_02]');
		$this->form_validation->set_rules('checkout[shipping][company]');
		$this->form_validation->set_rules('checkout[shipping][add_02]');
		$this->form_validation->set_rules('checkout[comments]');

		$return = new stdClass();
		$return->saved = FALSE;
		$return->message = '';

		// Save checkout order data if valid
		if ($this->form_validation->run())
		{
			$order_data = $this->input->post('checkout');

			// Assign all billing, shipping and contact details to their corresponding database columns.
			// All summary data within the cart array will automatically be saved when using the 'save_order()' function, 
			// provided the corresponding $config['database']['order_summary']['columns']['xxx'] is set via the config file.
			$custom_summary_data = array(
				'ord_user_fk' => $user_id,
				'ord_status' => 2, //TODO: get this from config, parametrize this
				'ord_bill_name' => $order_data['billing']['name'],
				'ord_bill_company' => $order_data['billing']['company'],
				'ord_bill_address_01' => $order_data['billing']['add_01'],
				'ord_bill_address_02' => $order_data['billing']['add_02'],
				'ord_bill_city' => $order_data['billing']['city'],
				'ord_bill_state' => $order_data['billing']['state'],
				'ord_bill_post_code' => $order_data['billing']['post_code'],
				'ord_bill_country' => $order_data['billing']['country'],
				'ord_ship_name' => $order_data['shipping']['name'],
				'ord_ship_company' => $order_data['shipping']['company'],
				'ord_ship_address_01' => $order_data['shipping']['add_01'],
				'ord_ship_address_02' => $order_data['shipping']['add_02'],
				'ord_ship_city' => $order_data['shipping']['city'],
				'ord_ship_state' => $order_data['shipping']['state'],
				'ord_ship_post_code' => $order_data['shipping']['post_code'],
				'ord_ship_country' => $order_data['shipping']['country'],
				'ord_email' => $order_data['email'],
				'ord_phone' => $order_data['phone'],
				'ord_comments' => $order_data['comments']
			);

			// Create an array of any user defined columns that were added to cart items.
			// This example checks to see if any items have a custom column called 'sku' (Example item #116 has this column).
			// Note: This hand coded method of saving custom item data is only required if the custom column has not been defined in the config file
			// via the '$config['cart']['items']['custom_columns']' and '$config['database']['order_details']['custom_columns']' arrays.
			$custom_item_data = array();
			foreach($this->flexi_cart_admin->cart_items(TRUE, FALSE, TRUE) as $row_id => $item)
			{
				// Check if any items in the item array have an 'sku' column.
				if (isset($item['sku']) && ! empty($item['sku']))
				{
					$custom_item_data[$row_id]['ord_det_demo_sku'] = $item['sku'];
				}

				//Remove the cart's quantity from the product's stock
				$this->flexi_cart_lite->get_item_stock_quantity($row_id, TRUE, 0, TRUE);

			}

			// Save cart and customer details.
			$return->saved = TRUE;
			$return->message = $this->flexi_cart_admin->save_order($custom_summary_data, $custom_item_data);
			
		}
		else
		{
			// Set validation errors.
			$return->message =  validation_errors('<div class="alert-box alert">', '</div>');
		}

		return $return;

	}

    /**
     * Get a product field data based on its type: price, weight, etc
     * @param $id
     * @param $type
     * @param $lang
     * @return int
     */
    private function getProductFieldData($id, $type, $lang)
    {
        $this->db->where('inputTipoContenido', $type);
        $this->db->join('input', 'input.inputId = producto_campos.inputId');
        $this->db->join('input_tipo', 'input.inputTipoId = input_tipo.inputTipoId');
        $query = $this->db->get('producto_campos');

        if ($query->num_rows() >= 1){

            $campo = $query->row();

            $this->db->join('producto_campos_rel', 'producto_campos_rel.productoCampoId = producto_campos.productoCampoId', 'left');
            $this->db->join($lang.'_producto_campos_rel', $lang.'_producto_campos_rel.productoCampoRelId = producto_campos_rel.productoCampoRelId', 'left');
            $this->db->join('input', 'input.inputId = producto_campos.inputId', 'left');
            $this->db->join($lang.'_producto_campos', $lang.'_producto_campos.productoCampoId = producto_campos.productoCampoId', 'left');
            $this->db->where('producto_campos_rel.productoId', $id);
            $this->db->where('producto_campos.productoCampoId', $campo->productoCampoId);
            $this->db->where('producto_campos.productoCampoHabilitado', 1);
            $query = $this->db->get('producto_campos');

            $campo_producto = $query->row();

            $item_data = $campo_producto->productoCampoRelContenido;

        } else {
            $item_data = 0;
        }

        return $item_data;

    }

}
/* End of file demo_cart_model.php */
/* Location: ./application/models/demo_cart_model.php */