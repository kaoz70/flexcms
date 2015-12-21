<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 780px">

<?php echo form_open(current_url(),  array('class' => 'form'));?>

<div class="field">
    <div class="header">General</div>
    <div class="content">

        <div class="input">
            <fieldset>
                <legend>Pedido</legend>

                <ul class="position_left">
                    <li>
                        <strong class="spacer_125">N&uacute;mero del pedido: </strong>
                        <?php echo $summary_data[$flexi_cart->db_column('order_summary', 'order_number')];?>
                    </li>
                    <li>
                        <span class="spacer_125">Fecha: </span>
                        <?php echo date('jS M Y', strtotime($summary_data[$flexi_cart->db_column('order_summary', 'date')]));?>
                    </li>
                </ul>
                <ul class="position_right">
                    <li>
                        <strong class="spacer_125">Estado:</strong>
                        <?php
                        if ($summary_data[$flexi_cart->db_column('order_status', 'cancelled')] == 1)
                        {
                            echo '<strong class="highlight_red">'.$summary_data[$flexi_cart->db_column('order_status', 'status')].'</strong>';
                        }
                        else
                        {
                            echo $summary_data[$flexi_cart->db_column('order_status', 'status')];
                        }
                        ?>
                    </li>
                </ul>
            </fieldset>
        </div>

        <div class="input">
            <fieldset class="w50">
                <legend>Detalles para la Facturaci&oacute;n</legend>
                <ul>
                    <li><span class="spacer_125">Nombre: </span><?php echo $summary_data['ord_bill_name'];?></li>
                    <li><span class="spacer_125">Direcci&oacute;n 01: </span><?php echo $summary_data['ord_bill_address_01'];?></li>
                    <li><span class="spacer_125">Direcci&oacute;n 02: </span><?php echo $summary_data['ord_bill_address_02'];?></li>
                    <li><span class="spacer_125">Cuidad / Pueblo: </span><?php echo $summary_data['ord_bill_city'];?></li>
                    <li><span class="spacer_125">Estado / Provincia: </span><?php echo $summary_data['ord_bill_state'];?></li>
                    <li><span class="spacer_125">C&oacute;digo postal: </span><?php echo $summary_data['ord_bill_post_code'];?></li>
                    <li><span class="spacer_125">Pa&iacute;s: </span><?php echo $summary_data['ord_bill_country'];?></li>
                </ul>
            </fieldset>
            <fieldset class="w50 r_margin">
                <legend>Detalles para el Envio</legend>
                <ul>
                    <li><span class="spacer_125">Nombre: </span><?php echo $summary_data['ord_ship_name'];?></li>
                    <li><span class="spacer_125">Direcci&oacute;n 01: </span><?php echo $summary_data['ord_ship_address_01'];?></li>
                    <li><span class="spacer_125">Direcci&oacute;n 02: </span><?php echo $summary_data['ord_ship_address_02'];?></li>
                    <li><span class="spacer_125">Cuidad / Pueblo: </span><?php echo $summary_data['ord_ship_city'];?></li>
                    <li><span class="spacer_125">Estado / Provincia: </span><?php echo $summary_data['ord_ship_state'];?></li>
                    <li><span class="spacer_125">C&oacute;digo postal: </span><?php echo $summary_data['ord_ship_post_code'];?></li>
                    <li><span class="spacer_125">Pa&iacute;s: </span><?php echo $summary_data['ord_ship_country'];?></li>
                </ul>
            </fieldset>
        </div>

        <div class="input">
            <fieldset class="w50 parallel_target">
                <legend>Detalles de contacto</legend>
                <ul>
                    <li><span class="spacer_125">Email: </span><?php echo $summary_data['ord_email'];?></li>
                    <li><span class="spacer_125">Tel&eacute;fono: </span><?php echo $summary_data['ord_phone'];?></li>
                    <?php if (! empty($summary_data['ord_comments'])) { ?>
                        <li><span class="spacer_125">Comentarios: </span><?php echo $summary_data['ord_comments'];?></li>
                    <?php } ?>
                </ul>
            </fieldset>
            <fieldset class="w50 r_margin parallel_target">
                <legend>Detalle del pago</legend>
                <ul>
                    <li><span class="spacer_125">Moneda: </span><?php echo $summary_data[$flexi_cart->db_column('order_summary', 'currency_name')];?></li>
                    <li><span class="spacer_125">Tipo de Cambio: </span><?php echo $summary_data[$flexi_cart->db_column('order_summary', 'exchange_rate')];?></li>
                </ul>
            </fieldset>
        </div>

    </div>
</div>

<div class="field">
    <div class="header">Detalle del Pedido</div>
    <div class="content">
        <table id="cart_items">
            <thead>
            <tr>
                <th>Item</th>
                <th class="spacer_100 align_ctr">Precio</th>
                <th class="spacer_100 align_ctr tooltip_trigger"
                    title="Indica la cantidad de items que fueron pedidos.">
                    Cantidad pedida
                </th>
                <th class="spacer_100 align_ctr tooltip_trigger"
                    title="Indica la cantidad de items que han sido marcados como 'Enviados'. Items enviados activan los puntos de recompensa asociados.">
                    Cantidad enviada
                </th>
                <th class="spacer_100 align_ctr tooltip_trigger"
                    title="Indica la cantidas de items que han sido marcados como 'cancelados'. Items cancelados son regresados al stock.">
                    Cantidad cancelada
                </th>
                <th class="spacer_100 align_ctr">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (! empty($item_data)) {
                foreach($item_data as $row) {
                    $order_detail_id = $row[$flexi_cart->db_column('order_details', 'id')];
                    ?>
                    <tr>
                        <td>
                            <input type="hidden" name="update_details[<?php echo $order_detail_id;?>][id]" value="<?php echo $order_detail_id;?>"/>

                            <!-- Item Name -->
                            <?php echo $row[$flexi_cart->db_column('order_details', 'item_name')];?>

                            <!-- Display an item status message if it exists -->
                            <?php
                            echo (! empty($row[$flexi_cart->db_column('order_details', 'item_status_message')])) ?
                                '<br/><span class="highlight_red">'.$row[$flexi_cart->db_column('order_details', 'item_status_message')].'</span>' : NULL;
                            ?>

                            <!-- Display an items options if they exist -->
                            <?php
                            echo (! empty($row[$flexi_cart->db_column('order_details', 'item_options')])) ?
                                '<br/>'.$row[$flexi_cart->db_column('order_details', 'item_options')] : NULL;
                            ?>

                            <!--
                                Display an items user note if it exists
                                Note: This is a optional custom field added to this cart demo and is not defined via the cart config file.
                            -->
                            <?php echo (! empty($row['ord_det_demo_user_note'])) ? '<br/>Note: '.$row['ord_det_demo_user_note'] : NULL; ?>
                        </td>
                        <td class="align_ctr">
                            <?php
                            // If an item discount exists.
                            if ($row[$flexi_cart->db_column('order_details', 'item_discount_quantity')] > 0)
                            {
                                // If the quantity of non discounted items is zero, strike out the standard price.
                                if ($row[$flexi_cart->db_column('order_details', 'item_non_discount_quantity')] == 0)
                                {
                                    echo '<span class="strike">'.$flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_price')], TRUE, 2, TRUE).'</span><br/>';
                                }
                                // Else, display the quantity of items that are at the standard price.
                                else
                                {
                                    echo number_format($row[$flexi_cart->db_column('order_details', 'item_non_discount_quantity')]).' @ '.
                                        $flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_price')], TRUE, 2, TRUE).'<br/>';
                                }

                                // If there are discounted items, display the quantity of items that are at the discount price.
                                if ($row[$flexi_cart->db_column('order_details', 'item_discount_quantity')] > 0)
                                {
                                    echo number_format($row[$flexi_cart->db_column('order_details', 'item_discount_quantity')]).' @ '.
                                        $flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_discount_price')], TRUE, 2, TRUE);
                                }
                            }
                            // Else, display price as normal.
                            else
                            {
                                echo $flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_price')], TRUE, 2, TRUE);
                            }
                            ?>
                        </td>
                        <td class="align_ctr">
                            <?php echo round($row[$flexi_cart->db_column('order_details', 'item_quantity')], 2); ?>
                        </td>
                        <td class="align_ctr">
                            <!--
                                If the status of the order is 'Cancelled', flexi cart functions will not update any submitted 'shipped' and 'cancelled' quantities, until the order is un-cancelled.
                                This demo includes a user interface tweak to disable the select input fields if they cannot be updated.
                            -->
                            <select name="update_details[<?php echo $order_detail_id;?>][quantity_shipped]" class="width_50" <?php echo ($summary_data[$flexi_cart->db_column('order_status', 'cancelled')] == 1) ? 'disabled="disabled"' : NULL; ?>>
                                <option value="0">0</option>
                                <?php $i=0; do { $i++; ?>
                                    <option value="<?php echo $i; ?>" <?php echo set_select('update_details['.$order_detail_id.'][quantity_shipped]', $i, ($row[$flexi_cart->db_column('order_details', 'item_quantity_shipped')] == $i)); ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php } while($i < $row[$flexi_cart->db_column('order_details', 'item_quantity')]); ?>
                            </select>
                        </td>
                        <td class="align_ctr">
                            <!--
                                If the status of the order is 'Cancelled', flexi cart functions will not update any submitted 'shipped' and 'cancelled' quantities, until the order is un-cancelled.
                                This demo includes a user interface tweak to disable the select input fields if they cannot be updated.
                            -->
                            <select name="update_details[<?php echo $order_detail_id;?>][quantity_cancelled]" class="width_50" <?php echo ($summary_data[$flexi_cart->db_column('order_status', 'cancelled')] == 1) ? 'disabled="disabled"' : NULL; ?>>
                                <option value="0">0</option>
                                <?php $i=0; do { $i++;?>
                                    <option value="<?php echo $i; ?>" <?php echo set_select('update_details['.$order_detail_id.'][quantity_cancelled]', $i, ($row[$flexi_cart->db_column('order_details', 'item_quantity_cancelled')] == $i)); ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php } while($i < $row[$flexi_cart->db_column('order_details', 'item_quantity')]); ?>
                            </select>
                        </td>
                        <td class="align_ctr">
                            <?php
                            // If an item discount exists, strike out the standard item total and display the discounted item total.
                            if ($row[$flexi_cart->db_column('order_details', 'item_discount_quantity')] > 0)
                            {
                                echo '<span class="strike">'.$flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_price_total')], TRUE, 2, TRUE).'</span><br/>';
                                echo $flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_discount_price_total')], TRUE, 2, TRUE);
                            }
                            // Else, display item total as normal.
                            else
                            {
                                echo $flexi_cart->format_currency($row[$flexi_cart->db_column('order_details', 'item_price_total')], TRUE, 2, TRUE);
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    // If an item discount exists.
                    if (! empty($row[$flexi_cart->db_column('order_details', 'item_discount_description')])) {
                        ?>
                        <tr class="discount">
                            <td colspan="6">
                                Discount: <?php echo $row[$flexi_cart->db_column('order_details', 'item_discount_description')];?>
                            </td>
                        </tr>
                    <?php } } } else { ?>
                <tr>
                    <td colspan="6" class="empty">
                        <h4>No hay items asocuados a esta orden!</h4>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php if ($summary_data[$flexi_cart->db_column('order_summary', 'item_summary_savings_total')] > 0) { ?>
                <tr class="discount">
                    <th colspan="5">Item Summary Discount Total</th>
                    <td class="align_ctr">
                        <?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'item_summary_savings_total')], TRUE, 2, TRUE);?></td>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="5">Item Summary Total</th>
                <td class="align_ctr"><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'item_summary_total')], TRUE, 2, TRUE);?></td>
            </tr>
            </tfoot>
        </table>

        <ul class="<?php echo ($summary_data[$flexi_cart->db_column('order_status', 'cancelled')] == 1) ? 'order_status_cancelled' : 'order_status_active'; ?>">
            <li>
                <strong class="spacer_125">Update Status:</strong>
                <select name="update_status" class="width_175">
                    <?php
                    foreach($status_data as $status) {
                        $id = $status[$flexi_cart->db_column('order_status', 'id')];
                        ?>
                        <option value="<?php echo $id; ?>" <?php echo set_select('update_status', $id, ($summary_data[$flexi_cart->db_column('order_summary', 'status')] == $id)); ?>>
                            <?php echo $status[$flexi_cart->db_column('order_status', 'status')]; ?>
                        </option>
                    <?php } ?>
                </select>
            </li>
        </ul>
    </div>
</div>

<div class="field">
<div class="header">Resumen del Pedido</div>
    <div class="content">
        <table id="cart_summary">
            <tbody>
            <tr>
                <td>Puntos de recompensa obtenidos</td>
                <td class="spacer_100"><?php echo number_format($summary_data[$flexi_cart->db_column('order_summary', 'total_reward_points')]);?> puntos</td>
            </tr>
            <tr>
                <td>Peso total</td>
                <td><?php echo number_format($summary_data[$flexi_cart->db_column('order_summary', 'total_weight')]);?> gramos</td>
            </tr>
            <tr>
                <td>Envio: <?php echo $summary_data[$flexi_cart->db_column('order_summary', 'shipping_name')];?></td>
                <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'shipping_total')], TRUE, 2, TRUE);?></td>
            </tr>

            <!-- Display discounts -->
            <?php if ($summary_data[$flexi_cart->db_column('order_summary', 'savings_total')] > 0) { ?>
                <tr class="discount">
                    <th>Discount Summary</th>
                    <td>&nbsp;</td>
                </tr>

                <!-- Item discounts -->
                <?php if ($summary_data[$flexi_cart->db_column('order_summary', 'item_summary_savings_total')] > 0) { ?>
                    <tr class="discount">
                        <td>
									<span class="pad_l_20">
										Item discount savings : <?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'item_summary_savings_total')], TRUE, 2, TRUE);?>
									</span>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                <?php } ?>

                <!-- Summary discounts -->
                <?php if ($summary_data[$flexi_cart->db_column('order_summary', 'summary_savings_total')] > 0) { ?>
                    <tr class="discount">
                        <td class="pad_l_20">
                            <?php echo $summary_data[$flexi_cart->db_column('order_summary', 'summary_discount_description')];?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                <?php } ?>

                <!-- Total of all discounts -->
                <tr class="discount">
                    <td>Discount Savings Total</td>
                    <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'savings_total')], TRUE, 2, TRUE);?></td>
                </tr>
            <?php } ?>

            <!-- Display summary of all surcharges -->
            <?php if ($summary_data[$flexi_cart->db_column('order_summary', 'surcharge_total')] > 0) { ?>
                <tr class="surcharge">
                    <th>Surcharge Summary</th>
                    <td>&nbsp;</td>
                </tr>
                <tr class="surcharge">
                    <td class="pad_l_20">
                        <?php echo $summary_data[$flexi_cart->db_column('order_summary', 'surcharge_description')];?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="surcharge">
                    <td>Surcharge Total</td>
                    <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'surcharge_total')], TRUE, 2, TRUE);?></td>
                </tr>
            <?php } ?>

            <!-- Display summary of all reward vouchers -->
            <?php if ($summary_data[$flexi_cart->db_column('order_summary', 'reward_voucher_total')] > 0) { ?>
                <tr class="voucher">
                    <th>Reward Voucher Summary</th>
                    <td>&nbsp;</td>
                </tr>
                <tr class="voucher">
                    <td class="pad_l_20">
                        <?php echo $summary_data[$flexi_cart->db_column('order_summary', 'reward_voucher_description')];?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="voucher">
                    <td>Reward Voucher Total</td>
                    <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'reward_voucher_total')], TRUE, 2, TRUE);?></td>
                </tr>
            <?php } ?>

            <!-- Display refund summary -->
            <?php if ($refund_data[$flexi_cart->db_column('order_details', 'item_price')] > 0) { ?>
                <tr class="refund">
                    <td>
                        Refund Cancelled Items
                        <small>
                            This value is an  <em class="uline">estimate</em> of the orders total refund value, however, it does not include any percentage based surcharges or discounts that may have been applied to the orders summary values. The grand total below does not include this refund.
                        </small>
                    </td>
                    <td>
                        <?php
                        if ($refund_data[$flexi_cart->db_column('order_details', 'item_discount_price')] > 0)
                        {
                            echo $flexi_cart->format_currency($refund_data[$flexi_cart->db_column('order_details', 'item_discount_price')], TRUE, 2, TRUE);
                        }
                        else
                        {
                            echo $flexi_cart->format_currency($refund_data[$flexi_cart->db_column('order_details', 'item_price')], TRUE, 2, TRUE);
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Sub Total (sin impuestos)</th>
                <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'sub_total')], TRUE, 2, TRUE);?></td>
            </tr>
            <tr>
                <th>
                    <?php echo 'Tax @ '.$summary_data[$flexi_cart->db_column('order_summary', 'tax_rate')].'%';?>
                </th>
                <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'tax_total')], TRUE, 2, TRUE);?></td>
            </tr>
            <tr class="grand_total">
                <th>Total</th>
                <td><?php echo $flexi_cart->format_currency($summary_data[$flexi_cart->db_column('order_summary', 'total')], TRUE, 2, TRUE);?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<input type="hidden" class="name" value="<?php echo $summary_data[$flexi_cart->db_column('order_summary', 'order_number')];?>">

<?php echo form_close(); ?>

</div>

<a href="<?= $link; ?>" data-level="nivel2" class="guardar boton importante n1" ><?=$txt_boton;?></a>