<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/noticias/' . $link, $attributes);

?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<fieldset>
				<legend>Nombre</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
				<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_enlaceTexto"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_enlaceTexto" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->enlaceTexto?>"/>
					<? else: ?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_enlaceTexto" type="text" value=""/>
					<? endif ?>
				</div>
				<? endforeach ?>
			</fieldset>
			
			<div class="input">
				<label for="enlaceLink">Link:</label>
				<input id="enlaceLink" name="enlaceLink" type="text" value="<?=$enlaceLink?>"/>
			</div>
			
            <fieldset id="upload-image-enlace">
                <legend><?=$txt_botImagen;?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if($imagen != ''): ?>
                        <li class="image">
                            <?=$imagen?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>
			
			<div class="input check">
				<input id="enlacePublicado" type="checkbox" name="enlacePublicado" id="noticiaHabilitado" <?= $enlacePublicado; ?> />
				<label for="enlacePublicado">Publicado</label>
			</div>
		</div>
	</div>

	<div id="avanzado" class="field">
		<div class="header">Avanzado</div>
		<div class="content">
			<div class="input small">
				<label for="enlaceClase">clase</label>
				<input id="enlaceClase" type="text" name="enlaceClase" value="<?=$enlaceClase; ?>" />
			</div>
		</div>
	</div>
	
	<input class="pagina_seleccion" type="hidden" name="paginaId" value="<?=$paginaId;?>" />
	<input id="enlaceId" type="hidden" name="enlaceId" value="<?=$enlaceId;?>" />
	<input id="imagen-enlace" type="hidden" name="enlaceImagen" value="<?=$enlaceImagen;?>" data-orig="<?=$imagenOrig?>" />
	<input class="coord" type="hidden" name="enlaceImagenCoord" value="<?=$enlaceImagenCoord;?>" />

<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="link/edit/" data-delete-url="link/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	upload.image('upload-image-enlace', 'imagen-enlace', '<?=base_url();?>admin/imagen/enlace/<?=$enlaceId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>