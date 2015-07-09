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
                    <input type="text" name="name" class="name required" value="<?=$nombre?>" />
				</fieldset>

                <fieldset>
					<legend>Ratio de conversi&oacute;n</legend>
                    <input type="text" name="exchange_rate" class="required" value="<?=$exchange_rate?>" />
				</fieldset>

                <fieldset>
					<legend>S&iacute;mbolo</legend>
                    <input type="text" name="symbol" class="required" value="<?=$symbol?>" />
				</fieldset>

                <fieldset>
					<legend>Separador de Mil</legend>
                    <input type="text" name="thousand" class="required" value="<?=$thousand?>" />
				</fieldset>

                <fieldset>
					<legend>Separador Decimal</legend>
                    <input type="text" name="decimal" class="required" value="<?=$decimal?>" />
				</fieldset>

                <div class="input">
                    <label>Activo</label>
                    <input type="checkbox" value="1" name="status" <?=$status?>>
                </div>
				
			</div>

		</div>
	</div>
	
	<input id="id" type="hidden" name="id" value="<?=$id;?>" />
	
	
	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="cart/modificar_moneda/<?=$id;?>" data-delete-url="cart/eliminar_moneda/<?=$id;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>