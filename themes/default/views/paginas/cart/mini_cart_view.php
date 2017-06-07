<div id="mini_cart">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        </thead>
        <?php if (! empty($mini_cart_items)) { ?>
            <tbody>
            <?php $i = 0; foreach($mini_cart_items as $row) { $i++; ?>
                <tr>
                    <td>
                        #<?php echo $i; ?>
                    </td>
                    <td>
                        <?php
                        // If an item discount exists.
                        if ($this->flexi_cart->item_discount_status($row['row_id']))
                        {
                            // If the quantity of non discounted items is zero, strike out the standard price.
                            if ($row['non_discount_quantity'] == 0)
                            {
                                echo '<span class="strike">'.$row['price'].'</span><br/>';
                            }
                            // Else, display the quantity of items that are at the standard price.
                            else
                            {
                                echo $row['non_discount_quantity'].' @ '.$row['price'].'<br/>';
                            }

                            // If there are discounted items, display the quantity of items that are at the discount price.
                            if ($row['discount_quantity'] > 0)
                            {
                                echo $row['discount_quantity'].' @ '. $row['discount_price'];
                            }
                        }
                        // Else, display price as normal.
                        else
                        {
                            echo $row['price'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $row['quantity'];?>
                    </td>
                    <td>
                        <?php
                        // If an item discount exists, strike out the standard item total and display the discounted item total.
                        if ($row['discount_quantity'] > 0)
                        {
                            echo '<span class="strike">'.$row['price_total'].'</span><br/>';
                            echo $row['discount_price_total'];
                        }
                        // Else, display item total as normal.
                        else
                        {
                            echo $row['price_total'];
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3">Shipping</th>
                <td>
                    <?php echo $this->flexi_cart->shipping_total();?>
                </td>
            </tr>
            <tr>
                <th colspan="3">Tax</th>
                <td>
                    <?php echo $this->flexi_cart->tax_total();?>
                </td>
            </tr>
            <tr>
                <th colspan="3">Grand Total</th>
                <td><?php echo $this->flexi_cart->total();?></td>
            </tr>
            </tfoot>
        <?php } else { ?>
            <tbody>
            <tr>
                <td colspan="4" class="empty">
                    Shopping cart is empty!
                </td>
            </tr>
            </tbody>
        <?php } ?>
    </table>
    <div id="mini_cart_status">Cart has been updated!</div>
</div>
