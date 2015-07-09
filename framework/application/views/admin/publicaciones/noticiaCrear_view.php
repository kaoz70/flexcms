<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="width: 780px; bottom: 72px">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/noticias/' . $link, $attributes);

?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
				
				<fieldset>
					<legend>Título</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
				<div class="input small">
						<label for="<?=$idioma['idiomaDiminutivo']?>_publicacionNombre"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<input class="required name unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_publicaciones" data-columna="publicacionUrl" data-columna-id="publicacionId" data-id="<?=$publicacionId;?>" name="<?=$idioma['idiomaDiminutivo']?>_publicacionNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->publicacionNombre?>"/>
						<? else: ?>
							<input class="required name unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_publicaciones" data-columna="publicacionUrl" data-columna-id="publicacionId" data-id="<?=$publicacionId;?>" name="<?=$idioma['idiomaDiminutivo']?>_publicacionNombre" type="text" value=""/>
						<? endif ?>
					</div>
					<? endforeach ?>
				</fieldset>
				
			<div class="input small">
				<label for="publicacionFecha">Fecha:</label>
				<input id="publicacionFecha" class="fecha" name="publicacionFecha" type="text" value="<?=$publicacionFecha?>"/>
			</div>

            <fieldset>
                <legend>Enlace</legend>
                <? foreach ($idiomas as $key => $idioma): ?>
                    <div class="input small">
                        <label for="<?=$idioma['idiomaDiminutivo']?>_publicacionLink"><?=$idioma['idiomaNombre']?></label>
                        <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                            <input name="<?=$idioma['idiomaDiminutivo']?>_publicacionLink" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->publicacionLink?>"/>
                        <? else: ?>
                            <input name="<?=$idioma['idiomaDiminutivo']?>_publicacionNombre" type="text" value=""/>
                        <? endif ?>
                    </div>
                <? endforeach ?>
            </fieldset>
			
            <fieldset id="upload-image-noticia">
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
			
			<div class="input">
				
				<fieldset>
					<legend>Contenido</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<label for="<?=$idioma['idiomaDiminutivo']?>_publicacionTexto"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_publicacionTexto" rows="20" cols="85"><?=$traducciones[$idioma['idiomaDiminutivo']]->publicacionTexto?></textarea>
						<? else: ?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_publicacionTexto" rows="20" cols="85"></textarea>
						<? endif ?>
						
						<script type="text/javascript">
							initEditor('<?=$idioma['idiomaDiminutivo']?>_editor');
						</script>
						
					<? endforeach ?>
				</fieldset>
				
			</div>
			
			<div class="input check">
				<input type="checkbox" name="publicacionHabilitado" id="publicacionHabilitado" <?= $publicacionHabilitado; ?> />
				<label for="publicacionHabilitado">Publicado</label>
			</div>
		</div>
	</div>

	<div id="avanzado" class="field">
		<div class="header">Avanzado</div>
		<div class="content">
			<div class="input small">
				<label for="paginaClase">clase</label>
				<input id="paginaClase" type="text" name="publicacionClase" value="<?=$publicacionClase; ?>" />
			</div>
		</div>
	</div>
	
	<input type="hidden" name="paginaId" value="<?=$paginaId;?>" />
	<input id="publicacionId" type="hidden" name="publicacionId" value="<?=$publicacionId;?>" />
	<input id="imagen-noticia" type="hidden" name="publicacionImagen" value="<?=$publicacionImagen;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="publicacionImagenCoord" value="<?=$publicacionImagenCoord;?>" />

<?= form_close(); ?>
</div>
<a href="admin/noticias/galeria/<?=$publicacionId?>" class="nivel3 ajax boton n2" >Galer&iacute;a</a>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="noticias/modificar/" data-delete-url="noticias/eliminar/" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	initDatePicker();
	upload.image('upload-image-noticia', 'imagen-noticia', '<?=base_url();?>admin/imagen/publicacion/<?=$publicacionId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>