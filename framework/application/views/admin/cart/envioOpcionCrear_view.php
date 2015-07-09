<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="bottom: 71px;">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/ubicaciones/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				
				<fieldset>
					<legend>Nombre</legend>
                    <input type="text" name="name" class="name" value="<?=$nombre?>" />
				</fieldset>

                <fieldset>
					<legend>Descripcion</legend>
                    <input type="text" name="desc" value="<?=$desc?>" />
				</fieldset>

                <fieldset>
                    <legend>Ubicacion</legend>
                    <select name="parent_location" id="group" class="selectbox">
                        <option value="0">Sin ubicaci&oacute;n padre</option>
                        <?php
                        foreach($locations_inline as $location) {
                            $id = $location[$this->flexi_cart_admin->db_column('locations', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $padre_id === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $location[$this->flexi_cart_admin->db_column('locations', 'name')]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </fieldset>

                <fieldset>
                    <legend>Zona</legend>
                    <select name="zone">
                        <option value="0">No Shipping Zone</option>
                        <?php
                        foreach($shipping_zones as $zone) {
                            $id = $zone[$this->flexi_cart_admin->db_column('location_zones', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?php echo set_select('zone', $id); ?>>
                                <?php echo $zone[$this->flexi_cart_admin->db_column('location_zones', 'name')]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </fieldset>

                <div class="input">
                    <label>Incluir es Sub Locaciones</label>
                    <input type="checkbox" name="inc_sub_locations" value="1" <?php echo set_checkbox('inc_sub_locations', '1'); ?>/>
                </div>

                <fieldset>
                    <legend title="Sets the tax rate charged on the total value of shipping, but not the tax rate of any other values within the cart.">Porcentaje impuestos</legend>
                    <input type="text" name="tax_rate" value="<?php echo set_value('tax_rate');?>" placeholder="Default" class="width_50 validate_decimal"/>
                </fieldset>

                <div class="input">
                    <label title="If checked, sets whether the shipping option can be included in cart discounts. <br/>For example, a 10% discount on the cart value could be excluded from including the shipping value.">Descuento</label>
                    <input type="checkbox" name="discount_inclusion" value="1" <?php echo set_checkbox('discount_inclusion', '1'); ?>/>
                </div>

                <div class="input">
                    <label>Activo</label>
                    <input type="checkbox" value="1" name="status" <?=$status?>>
                </div>
				
			</div>

		</div>
	</div>

	<?= form_close(); ?>
	
</div>

<a href="<?= $link_tarifas; ?>" class="nivel5 ajax boton n2" >Tarifas</a>
<a href="<?= $link; ?>" data-level="nivel4" data-edit-url="cart/modificar_opcion_envio/<?=$opcionEnvioId;?>" data-delete-url="cart/eliminar_opcion_envio/<?=$opcionEnvioId;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>