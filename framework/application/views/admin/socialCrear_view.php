<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/contacto/' . $link, $attributes);

?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="rsNombre">Nombre</label>
				<input class="required" id="rsNombre" name="rsNombre" type="text" value="<?=$info->rsNombre?>"/>
			</div>
			<div class="input">
				<label for="rsLink">Link</label>
				<input class="required" id="rsLink" name="rsLink" type="text" value="<?=$info->rsLink?>"/>
			</div>
			<div class="input">
				<label for="rsClase">Clase</label>
				<input id="rsClase" name="rsClase" type="text" value="<?=$info->rsClase?>"/>
			</div>
		</div>
	</div>

	<input id="rsId" type="hidden" name="rsId" value="<?=$info->rsId;?>" />
	
    

<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" class="guardar boton importante n1" ><?=$txt_boton;?></a>