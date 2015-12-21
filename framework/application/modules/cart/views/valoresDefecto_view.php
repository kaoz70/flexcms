<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/estados/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">

                <fieldset>
                    <legend>Moneda</legend>
                    <small>Define la moneda por defecto de los precios cuando un usuario visita el sitio por primera vez.</small>
                    <ul>
                        <li>
                            <label for="currency">Moneda por defecto</label>
                            <select id="currency" name="update[currency]"
                                    title="Set the default currency that cart values are displayed as."
                                >
                                <option value="0"> - Seleccione Moneda por Defecto - </option>
                                <?php
                                foreach($currency_data as $currency) {
                                    $id = $currency[$this->flexi_cart_admin->db_column('currency', 'id')];
                                    $default = $default_currency[$this->flexi_cart_admin->db_column('currency', 'id')];
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo set_select('update[currency]', $id, ($default == $id)); ?>>
                                        <?php echo $currency[$this->flexi_cart_admin->db_column('currency', 'name')]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </li>
                    </ul>
                </fieldset>

                <fieldset>
                    <legend>Envio</legend>
                    <small>Define la ubicacion de envío por defecto y método de envío que se despliega cuando un usuario entra por primera vez al sitio.</small>
                    <ul>
                        <li>
                            <label for="shipping_location">Ubicaci&oacute;n de Envio por Defecto</label>
                            <select id="shipping_location" name="update[shipping_location]"
                                    title="Set the default location that shipping options and rates are displayed for."
                                >
                                <option value="0"> - Seleccione Ubicaci&oacute;n de envio por defecto - </option>
                                <?php
                                foreach($locations_inline as $location) {
                                    $id = $location[$this->flexi_cart_admin->db_column('locations', 'id')];
                                    $default = $default_ship_location[$this->flexi_cart_admin->db_column('locations', 'id')];
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo set_select('update[shipping_location]', $id, ($default == $id)); ?>>
                                        <?php echo $location[$this->flexi_cart_admin->db_column('locations', 'name')]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </li>
                        <li>
                            <label for="shipping_option">Opci&oacute;n de Envio por Defecto</label>
                            <select id="shipping_option" name="update[shipping_option]"
                                    title="Set the default shipping option that is displayed."
                                >
                                <option value="0"> - Seleccione la Opci&oacute;n de Envio por Defecto - </option>
                                <?php
                                foreach($shipping_data as $option) {
                                    $id = $option[$this->flexi_cart_admin->db_column('shipping_options', 'id')];
                                    $default = $default_ship_option[$this->flexi_cart_admin->db_column('shipping_options', 'id')];
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo set_select('update[shipping_option]', $id, ($default == $id)); ?>>
                                        <?php echo $option[$this->flexi_cart_admin->db_column('shipping_options', 'name')]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </li>
                    </ul>
                </fieldset>

                <fieldset>
                    <legend>Impuestos</legend>
                    <small>Define la ubicaci&oacute;n de impuestos e impuesto por defecto cuando un usuario entra por primera vez al sitio.</small>
                    <ul>
                        <li>
                            <label for="tax_location">Ubicaci&oacute;n de Impuestos por Defecto</label>
                            <select id="tax_location" name="update[tax_location]" class="width_250 tooltip_trigger"
                                    title="Set the default location that the cart tax rate is based on."
                                >
                                <option value="0"> - Seleccione la Ubicaci&oacute;n de Impuestos por Defecto - </option>
                                <?php
                                foreach($locations_inline as $location) {
                                    $id = $location[$this->flexi_cart_admin->db_column('locations', 'id')];
                                    $default = $default_tax_location[$this->flexi_cart_admin->db_column('locations', 'id')];
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo set_select('update[tax_location]', $id, ($default == $id)); ?>>
                                        <?php echo $location[$this->flexi_cart_admin->db_column('locations', 'name')]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </li>
                        <li>
                            <label for="tax_rate">Impuesto por Defecto</label>
                            <select id="tax_rate" name="update[tax_rate]" class="width_250 tooltip_trigger"
                                    title="Select the default tax rate that is displayed."
                                >
                                <option value="0"> - Seleecione el Impuesto por Defecto - </option>
                                <?php
                                foreach($tax_data as $tax_rate) {
                                    $id = $tax_rate[$this->flexi_cart_admin->db_column('tax', 'id')];
                                    $default = $default_tax_rate[$this->flexi_cart_admin->db_column('tax', 'id')];
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo set_select('update[tax_rate]', $id, ($default == $id)); ?>>
                                        <?php echo $tax_rate[$this->flexi_cart_admin->db_column('tax', 'name')]; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </li>
                    </ul>
                </fieldset>

			</div>

		</div>
	</div>

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" class="guardar boton importante n1" ><?=$txt_boton;?></a>