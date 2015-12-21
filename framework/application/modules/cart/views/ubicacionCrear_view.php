<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
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
					<legend>Tipo de ubicaci&oacute;n</legend>
                    <input type="text" name="name" class="name" value="<?=$nombre?>" />
				</fieldset>

                <fieldset>
					<legend>Ubicaci&oacute;n padre</legend>
                    <select name="parent_location">
                        <option value="0">Sin ubicaci&oacute;n padre</option>
                        <?php foreach($locations_inline as $location): ?>
                            <option value="<?= $location['loc_type_id']; ?>" <?= $padre_id === $location['loc_type_id'] ? 'selected="selected"' : '' ?>>
                                <?= $location['loc_type_name']; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
				</fieldset>
				
			</div>

		</div>
	</div>
	
	<input id="ubicacionId" type="hidden" name="ubicacionId" value="<?=$ubicacionId;?>" />
	
	
	<?= form_close(); ?>
	
</div>

<a href="<?= $link_ubicaciones; ?>" class="nivel5 ajax boton n2" >Sub Ubicaciones</a>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="cart/modificar_ubicacion/<?=$ubicacionId;?>" data-delete-url="cart/eliminar_ubicacion/<?=$ubicacionId;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>