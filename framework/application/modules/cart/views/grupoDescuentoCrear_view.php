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
					<legend>Nombre</legend>
                    <input type="text" name="name" class="name" value="<?=$nombre?>" />
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

<a href="<?= base_url('admin/cart/discountItems/edit/' . $id); ?>" class="nivel6 ajax boton n2" >Items del grupo</a>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="cart/discountGroup/edit/<?=$id;?>" data-delete-url="cart/discountGroup/delete/<?=$id;?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>