<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/ubicaciones/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

        <fieldset>
            <legend>Type / Location</legend>
            <ul class="position_left">
                <li class="info_req">
                    <label for="discount_type">Discount Type:</label>
                    <select id="discount_type" name="insert[type]" class="validate-custom-required emptyValue:'0' width_200 tooltip_trigger"
                            title="<strong>Field Required</strong><br/> Sets whether the discount is an item or summary discount, or a reward voucher."
                        >
                        <option value="0"> - Select Discount Type - </option>
                        <?php
                        foreach($discount_types as $row) {
                            $id = $row[$this->flexi_cart_admin->db_column('discount_types', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $type === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $row[$this->flexi_cart_admin->db_column('discount_types', 'type')];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
                <li class="info_req">
                    <label for="discount_method">Discount Method:</label>
                    <select id="discount_method" name="insert[method]" class="validate-custom-required emptyValue:'0' width_200 tooltip_trigger"
                            title="<strong>Field Required</strong><br/> Set which cart value to apply the discount to."
                        >
                        <option value="0" class="parent_id_0"> - Select Discount Method - </option>
                        <?php
                        foreach($discount_methods as $row) {
                            $id = $row[$this->flexi_cart_admin->db_column('discount_methods', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" class="parent_id_<?php echo $row[$this->flexi_cart_admin->db_column('discount_methods', 'type')];?>" <?= $method === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $row[$this->flexi_cart_admin->db_column('discount_methods', 'method')];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
                <li>
                    <label for="discount_tax_method">Tax Appliance Method:</label>
                    <select id="discount_tax_method" name="insert[tax_method]" class="width_200 tooltip_trigger"
                            title="Set how tax should be applied to the discount."
                        >
                        <option value="0"> - Select Tax Method - </option>
                        <option value="0">Carts Default Tax Method</option>
                        <?php
                        foreach($discount_tax_methods as $row) {
                            $id = $row[$this->flexi_cart_admin->db_column('discount_tax_methods', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $tax_method === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $row[$this->flexi_cart_admin->db_column('discount_tax_methods', 'method')];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
            </ul>
            <ul class="position_right">
                <li>
                    <label for="discount_location">Location:</label>
                    <select id="discount_location" name="insert[location]" class="width_200 tooltip_trigger"
                            title="Set the location that the discount is applied to."
                        >
                        <option value="0"> - All Locations - </option>
                        <?php
                        foreach($locations_inline as $row) {
                            $id = $row[$this->flexi_cart_admin->db_column('locations', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $location === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $row[$this->flexi_cart_admin->db_column('locations', 'name')];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
                <li>
                    <label for="discount_zone">Zone:</label>
                    <select id="discount_zone" name="insert[zone]" class="width_200 tooltip_trigger"
                            title="Set the zone that the discount is applied to. <br/>Note: If a location is set, it has priority over a zone rule."
                        >
                        <option value="0"> - All Zones - </option>
                        <?php
                        foreach($zones as $row) {
                            $id = $row[$this->flexi_cart_admin->db_column('location_zones', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $zone === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $row[$this->flexi_cart_admin->db_column('location_zones', 'name')];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Target Group / Item</legend>
            <ul class="position_left">
                <li>
                    <label for="discount_group">Apply Discount to Group:</label>
                    <select id="discount_group" name="insert[group]" class="width_200 tooltip_trigger"
                            title="Set the discount to apply if an item in a particular discount group is added to the cart."
                        >
                        <option value="0"> - Not applied to a Group - </option>
                        <?php
                        foreach($discount_groups as $row) {
                            $id = $row[$this->flexi_cart_admin->db_column('discount_groups', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $group === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $row[$this->flexi_cart_admin->db_column('discount_groups', 'name')];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
            </ul>
            <ul class="position_right">
                <li>
                    <label for="discount_item">Apply Discount to Item:</label>
                    <select id="discount_item" name="insert[item]" class="width_200 tooltip_trigger"
                            title="Set the discount to apply if a particular item is added to the cart."
                        >
                        <option value="0"> - Not applied to an Item - </option>
                        <?php foreach($items as $row) {?>
                            <option value="<?php echo $row['productoId']; ?>" <?= $item === $row['productoId'] ? 'selected="selected"' : '' ?>>
                                <?php echo $row['productoNombre'];?>
                            </option>
                        <?php } ?>
                    </select>
                </li>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Code / Description</legend>
            <ul class="position_left">
                <li>
                    <label for="discount_code">Code:</label>
                    <input type="text" id="discount_code" name="insert[code]" value="<?= $code ?>" class="width_200 tooltip_trigger"
                           title="Set the code required to apply the discount. Leave blank if the discount is activated via item quantities or values."
                        />
                </li>
            </ul>
            <ul class="position_right">
                <li>
                    <label for="discount_desc">Description:</label>
                    <textarea id="discount_desc" name="insert[description]" class="required name width_200 tooltip_trigger"
                              title="A short description of the discount that is displayed to the customer."
                        ><?=$description?></textarea>
                </li>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Requirements / Discount</legend>
            <ul class="position_left">
                <li>
                    <label for="discount_qty_req">Quantity Required to Activate:</label>
                    <input type="text" id="discount_qty_req" name="insert[quantity_required]" value="<?=$quantity_required?>" class="width_100 validate_integer tooltip_trigger"
                           title="Set the quantity of items required to activate the discount.<br/> For example, for a 'buy 5 get 2 free' discount, the quantity would be 7 (5+2)."
                        />
                </li>
                <li>
                    <label for="discount_qty_disc">Discount Quantity:</label>
                    <input type="text" id="discount_qty_disc" name="insert[quantity_discounted]" value="<?=$quantity_discounted?>" class="width_100  validate_integer tooltip_trigger"
                           title="Set the quantity of items that the discount is applied to.<br/> For example, for a 'buy 5 get 2 free' discount, the quantity would be 2."
                        />
                </li>
            </ul>
            <ul class="position_right">
                <li>
                    <label for="discount_value_req">Value Required to Activate:</label>
                    <input type="text" id="discount_value_req" name="insert[value_required]" value="<?=$value_required?>" class="width_100 validate_decimal tooltip_trigger"
                           title="Set the value required to active the discount.<br/> For item discounts, the value is the total value of the discountable items.<br/> For summary discounts, the value is the cart total."
                        />
                </li>
                <li>
                    <label for="discount_value_disc">Discount Value:</label>
                    <input type="text" id="discount_value_disc" name="insert[value_discounted]" value="<?=$value_discounted?>" class="width_100 validate_decimal tooltip_trigger"
                           title="Set the value of the discount that is applied.<br/> For percentage discounts, this value is used as the discount percentage.<br/> For 'flat fee' and 'new value' discounts, this is the discounted currency value."
                        />
                </li>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Functionality</legend>
            <ul class="position_left">
                <li>
                    <label for="discount_recursive">Discount Recursive:</label>
                    <input type="hidden" name="insert[recursive]" value="0"/>
                    <input type="checkbox" id="discount_recursive" name="insert[recursive]" value="1" <?=$recursive?> class="tooltip_trigger"
                           title="If checked, the discount can be repeated multiples times to the same cart.<br/> For example, if checked, a 'Buy 1, get 1 free' discount can be reapplied if 2, 4, 6 (etc) items are added to the cart.<br/> If not checked, the discount is only applied for the first 2 items."
                        />
                </li>
                <li>
                    <label for="discount_non_combinable">Non Combinable Discount:</label>
                    <input type="hidden" name="insert[non_combinable]" value="0"/>
                    <input type="checkbox" id="discount_non_combinable" name="insert[non_combinable]" value="1" <?=$non_combinable?> class="tooltip_trigger"
                           title="If checked, the discount cannot be and combined and used with any other discounts or reward vouchers."
                        />
                </li>
            </ul>
            <ul class="position_right">
                <li>
                    <label for="discount_void_reward">Void Reward Points:</label>
                    <input type="hidden" name="insert[void_reward]" value="0"/>
                    <input type="checkbox" id="discount_void_reward" name="insert[void_reward]" value="1" <?=$void_reward?> class="tooltip_trigger"
                           title="If checked, any reward points earnt from items within the cart will be reset to zero whilst the discount is used."
                        />
                </li>
                <li>
                    <label for="discount_force_shipping">Force Shipping Discount:</label>
                    <input type="hidden" name="insert[force_shipping]" value="0"/>
                    <input type="checkbox" id="discount_force_shipping" name="insert[force_shipping]" value="1" <?=$force_shipping?> class="tooltip_trigger"
                           title="If checked, the discount value will be 'forced' on the carts shipping option calculations, even if the selected shipping option has not been set as being discountable."
                        />
                </li>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Custom Cart Statuses</legend>
            <ul>
                <li>
                    <small>
                        Three individual custom cart statuses can be set to affect whether discounts become active.<br/>
                        The custom statuses can contain any string or integer values, if the value then matches the the custom status of a discount, then provided all other discount conditions are also matched, the discount is activated.
                    </small>
                    <small>
                        For example, a custom status could check whether a user is logged in, by default it is set to false (0), when a user then logs in, the status could be set to true (1) which would then enable the discount.
                    </small>
                </li>
                <li>
                    <label for="discount_custom_status_1">Custom Status #1:</label>
                    <input type="text" id="discount_custom_status_1" name="insert[custom_status_1]" value="<?=$custom_status_1?>" class="width_75"/>
                </li>
                <li>
                    <label for="discount_custom_status_2">Custom Status #2:</label>
                    <input type="text" id="discount_custom_status_2" name="insert[custom_status_2]" value="<?=$custom_status_2?>" class="width_75"/>
                </li>
                <li>
                    <label for="discount_custom_status_3">Custom Status #3:</label>
                    <input type="text" id="discount_custom_status_3" name="insert[custom_status_3]" value="<?=$custom_status_3?>" class="width_75"/>
                </li>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Usage Status / Validity</legend>
            <ul class="position_left">
                <li class="info_req">
                    <label for="discount_usage_limit">Usage Limit:</label>
                    <input type="text" id="discount_usage_limit" name="insert[usage_limit]" value="<?=$usage_limit?>" class="required width_100 validate_integer tooltip_trigger"
                           title="<strong>Field Required</strong><br/>Set the number of times remaining that the discount can be used."
                        />
                </li>
                <li class="info_req">
                    <label for="discount_valid_date">Valid Date (yyyy-mm-dd):</label>
                    <input type="text" id="discount_valid_date" name="insert[valid_date]" value="<?=$valid_date?><?php echo set_value('insert[valid_date]', date('Y-m-d'));?>" maxlength="10" class="fecha width_100 tooltip_trigger"
                           title="<strong>Field Required</strong><br/>Set the start date that the discount is valid from."
                        />
                </li>
                <li class="info_req">
                    <label for="discount_expire_date">Expire Date (yyyy-mm-dd):</label>
                    <input type="text" id="discount_expire_date" name="insert[expire_date]" value="<?=$expire_date?><?php echo set_value('insert[expire_date]', date('Y-m-d', strtotime('3 Month')));?>" maxlength="10" class="fecha width_100 tooltip_trigger"
                           title="<strong>Field Required</strong><br/>Set the expiry date that the discount is valid until."
                        />

                </li>
            </ul>
            <ul class="position_right">
                <li>
                    <label for="discount_status">Active Status:</label>
                    <input type="checkbox" <?=$status ? 'checked="checked"' : ''?> id="discount_status" name="insert[status]" value="1" <?=$status?> class="tooltip_trigger"
                           title="If checked, the discount will be set as 'active'."
                        />
                </li>
                <li>
                    <label for="discount_order_by">Order By:</label>
                    <input type="text" id="discount_order_by" name="insert[order_by]" value="<?=$order_by?>" class="width_100 validate_integer tooltip_trigger"
                           title="Set the order that the discount is applied to the cart if other discounts are active. The lower the number, the higher priority."
                        />
                </li>
            </ul>
        </fieldset>

		</div>
	</div>
	
	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel4" data-edit-url="cart/modificar_descuento/<?=$discount_id;?>" data-delete-url="cart/eliminar_descuento/<?=$discount_id;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    initDatePicker();
</script>