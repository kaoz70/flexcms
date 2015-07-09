<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

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
					<legend>Ubicaci&oacute;n padre</legend>
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
                    <legend>Zona de envio</legend>
                    <select name="shipping_zone">
                        <option value="0">Sin zona de envio</option>
                        <?php
                        foreach($shipping_zones as $zone) {
                            $id = $zone[$this->flexi_cart_admin->db_column('location_zones', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $ship_id === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $zone[$this->flexi_cart_admin->db_column('location_zones', 'name')]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </fieldset>

                <fieldset>
                    <legend>Zona de impuestos</legend>
                    <select name="tax_zone">
                        <option value="0">Sin zona de impuestos</option>
                        <?php
                        foreach($tax_zones as $zone) {
                            $id = $zone[$this->flexi_cart_admin->db_column('location_zones', 'id')];
                            ?>
                            <option value="<?php echo $id; ?>" <?= $tax_id === $id ? 'selected="selected"' : '' ?>>
                                <?php echo $zone[$this->flexi_cart_admin->db_column('location_zones', 'name')]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </fieldset>

                <label>Activo</label>
                <input type="checkbox" name="status" value="1" <?=$status?>>
				
			</div>

		</div>
	</div>

	<input id="ubicacionId" type="hidden" name="ubicacionId" value="<?=$ubicacionId;?>" />

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel6" data-edit-url="cart/modificar_sub_ubicacion/<?=$loc_id;?>" data-delete-url="cart/eliminar_sub_ubicacion/<?=$loc_id;?>" class="guardar boton importante n1 no_sort selectbox <?=$nuevo?>" ><?=$txt_boton;?></a>