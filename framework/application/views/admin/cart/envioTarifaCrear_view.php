<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" >

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/ubicaciones/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				
				<fieldset>
					<legend>Tarifa</legend>
                    <input type="text" name="value" class="nombre" value="<?=$valor?>" />
				</fieldset>

                <fieldset>
					<legend>Peso del contenedor (g)</legend>
                    <input type="text" name="tare_weight" value="<?=$tare_weight?>" />
				</fieldset>

                <fieldset>
					<legend>Peso m&iacute;nimo (g)</legend>
                    <input type="text" name="min_weight" value="<?=$min_weight?>" />
				</fieldset>

                <fieldset>
					<legend>Peso m&aacute;ximo (g)</legend>
                    <input type="text" name="max_weight" value="<?=$max_weight?>" />
				</fieldset>

                <fieldset>
					<legend>Valor m&iacute;nimo</legend>
                    <input type="text" name="min_value" value="<?=$min_value?>" />
				</fieldset>

                <fieldset>
					<legend>Valor m&aacute;ximo</legend>
                    <input type="text" name="max_value" value="<?=$max_value?>" />
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

<a href="<?= $link; ?>" data-level="nivel6" data-edit-url="cart/modificar_tarifa_envio/<?=$opcionTarifaId;?>" data-delete-url="cart/eliminar_tarifa_envio/<?=$opcionTarifaId;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>