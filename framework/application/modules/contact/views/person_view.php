<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/contacto/' . $link, $attributes);

?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<fieldset>
				<legend>Nombre</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_contactoNombre"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_contactoNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->contactoNombre?>"/>
					<? else: ?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_contactoNombre" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>
			
			<div class="input">
				<label>Email:</label>
				<input class="required validate-email" id="contactoEmail" name="contactoEmail" type="text" value="<?=$contactoEmail?>"/>
			</div>
		</div>
	</div>
	
	<input id="contactoId" type="hidden" name="contactoId" value="<?=$contactoId;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="contact/person/edit/" data-delete-url="contact/person/delete/" class="guardar boton importante n1 contacto_persona no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>