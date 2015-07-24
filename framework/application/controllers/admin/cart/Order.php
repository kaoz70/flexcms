<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 4:16 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class Order extends Cart {



	/**
	 * order_details
	 * Displays all data related to a saved order, including the users billing and shipping details,
	 * the cart contents and the cart summary.
	 * This demo includes an example of indicating to flexi cart which items have been shipped or cancelled since
	 * the order was receieved, flexi cart can then use this data
	 * to manage item stock and user reward points.
	 */
	public function detail()
	{

		$order_number = $this->uri->segment(5);

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
		$data['link'] = base_url('admin/cart/order/update/' . $order_number);

		$this->load->view('admin/cart/pedido_view', $data);

	}

	public function update($id)
	{
		$this->Cart->update_order_details($id);
	}

}