<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/descargas/' . $link, $attributes);

?>

<div class="field">
	<div class="header">General</div>
	<div class="content">
		
		<fieldset>
			<legend>Nombre</legend>
			<? foreach ($idiomas as $key => $idioma): ?>
			<div>
				<label for="<?=$idioma['idiomaDiminutivo']?>_descargaNombre"><?=$idioma['idiomaNombre']?></label>
				<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
					<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_descargaNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->descargaNombre?>"/>
				<? else: ?>
					<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_descargaNombre" type="text" value=""/>
				<? endif ?>
			</div>
			<? endforeach ?>
		</fieldset>

		<div class="input">
			<label for="descargaCategoriaId">Categoría</label>
			<select class="selectbox" id="descargaCategoriaId" name="descargaCategoriaId">
				<?= admin_select_tree($categorias, $descargaCategoriaId, 'descargaCategoriaNombre') ?>
			</select>
		</div>
		
		<div class="input" id="video">
			<label for="fileName">Video ID:</label>
			<input id="fileName" name="fileName" type="text" value="<?=$descargaArchivo?>"/>
		</div>
		
		<div class="input">
			<input type="checkbox" name="descargaEnabled" id="descargaEnabled" <?= $descargaEnabled; ?> />
			<label for="descargaEnabled">Publicado</label>
		</div>

        <input id="descargaId" type="hidden" name="descargaId" value="<?=$descargaId;?>" />

	</div>
</div>

<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="gallery/edit/" data-delete-url="gallery/delete/" class="guardar boton importante n1 selectbox video no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	initDatePicker();
</script>