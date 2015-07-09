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
					<legend>Locaci&oacute;n</legend>
                    <select name="parent_location">
                        <option value="0">Sin locaci&oacute;n</option>
                        <?php foreach($locations_inline as $location): ?>
                            <option value="<?= $location['loc_id']; ?>" <?= $location_id === $location['loc_id'] ? 'selected="selected"' : '' ?>>
                                <?= $location['loc_name']; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
				</fieldset>

                <fieldset>
                    <legend>Zona</legend>
                        <select name="zone">
                            <option value="0">Sin zona de impuesto</option>
                            <?php
                            foreach($tax_zones as $zone): ?>
                                <option value="<?php echo $id; ?>" <?= $zone_id === $location['loc_type_id'] ? 'selected="selected"' : '' ?>>
                                    <?php echo $zone[$this->flexi_cart_admin->db_column('location_zones', 'name')]; ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </select>
                </fieldset>

                <fieldset>
                    <legend>Tarifa (%)</legend>
                    <input type="text" name="rate" value="<?=$rate?>" />
                </fieldset>

                <div class="input">
                    <label>Activo</label>
                    <input type="checkbox" value="1" name="status" <?=$status?>>
                </div>
				
			</div>

		</div>
	</div>
	

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="cart/modificar_impuesto/<?=$id;?>" data-delete-url="cart/eliminar_impuesto/<?=$id;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>