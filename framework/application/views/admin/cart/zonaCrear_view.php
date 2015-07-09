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
					<legend>Descripcion</legend>
                    <input type="text" name="desc" value="<?=$desc?>" />
				</fieldset>

                <label>Activo</label>
                <input type="checkbox" value="1" name="status" <?=$checked?>>
				
			</div>

		</div>
	</div>
	
	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel4" data-edit-url="cart/modificar_zona/<?=$zonaId;?>" data-delete-url="cart/eliminar_zona/<?=$zonaId;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>