<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="bottom: 71px;">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/estados/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				
				<fieldset>
					<legend>Nombre</legend>
                    <input type="text" name="name" class="name required" value="<?=$nombre?>" />
				</fieldset>

                <div class="input">
                    <label>Estado cuando sde cancela el pedido</label>
                    <input type="checkbox" value="1" name="cancelled" <?=$cancelled?>>
                </div>

                <div class="input">
                    <label>Estado cuando se guarda el pedido</label>
                    <input type="checkbox" value="1" name="save_default" <?=$save_default?>>
                </div>

                <div class="input">
                    <label>Estado cuando se vuelve a guardar el pedido</label>
                    <input type="checkbox" value="1" name="resave_default" <?=$resave_default?>>
                </div>
				
			</div>

		</div>
	</div>

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel4" data-edit-url="cart/modificar_estado/<?=$id;?>" data-delete-url="cart/eliminar_estado/<?=$id;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>