<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="width: 780px">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/servicios/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				
				<fieldset>
					<legend>Título</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<div class="input small">
						<label for="<?=$idioma['idiomaDiminutivo']?>_servicioTitulo"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<input class="required name unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_servicios" data-columna="servicioUrl" data-columna-id="servicioId" data-id="<?=$servicioId;?>" name="<?=$idioma['idiomaDiminutivo']?>_servicioTitulo" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->servicioTitulo?>"/>
						<? else: ?>
							<input class="required name unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_servicios" data-columna="servicioUrl" data-columna-id="servicioId" data-id="<?=$servicioId;?>" name="<?=$idioma['idiomaDiminutivo']?>_servicioTitulo" type="text" value=""/>
						<? endif ?>
						</div>
					<? endforeach ?>
				</fieldset>
				
			</div>

            <fieldset id="upload-image-servicio">
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
						<label for="<?=$idioma['idiomaDiminutivo']?>_servicioTexto"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_servicioTexto" rows="20" cols="85"><?=$traducciones[$idioma['idiomaDiminutivo']]->servicioTexto?></textarea>
						<? else: ?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_servicioTexto" rows="20" cols="85"></textarea>
						<? endif ?>
						
						<script type="text/javascript">
							initEditor('<?=$idioma['idiomaDiminutivo']?>_editor');
						</script>
						
					<? endforeach ?>
				</fieldset>

			</div>
			
			<div class="input check">
				<input type="checkbox" name="servicioDestacado" id="servicioDestacado" <?= $servicioDestacado ? 'checked' : ''; ?> value="1" />
				<label for="servicioDestacado">Destacado</label>
			</div>

			<div class="input check">
				<input type="checkbox" name="servicioPublicado" id="servicioPublicado" <?= $servicioPublicado; ?> />
				<label for="servicioPublicado">Publicado</label>
			</div>
		</div>
	</div>

	<div class="field">
		<div class="header">SEO</div>
		<div class="content">

			<fieldset>
				<legend>Palabras Clave</legend>
				<small>Separados por coma ","</small>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
						<label for="<?=$idioma['idiomaDiminutivo']?>_servicioKeywords"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<input name="<?=$idioma['idiomaDiminutivo']?>_servicioKeywords" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->servicioKeywords?>"/>
						<? else: ?>
							<input name="<?=$idioma['idiomaDiminutivo']?>_servicioKeywords" type="text" value=""/>
						<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

			<fieldset>
				<legend>T&iacute;tulo</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
						<label for="<?=$idioma['idiomaDiminutivo']?>_servicioMetaTitulo"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<input name="<?=$idioma['idiomaDiminutivo']?>_servicioMetaTitulo" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->servicioMetaTitulo?>"/>
						<? else: ?>
							<input name="<?=$idioma['idiomaDiminutivo']?>_servicioMetaTitulo" type="text" value=""/>
						<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

			<fieldset>
				<legend>Descripci&oacute;n</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
						<label for="<?=$idioma['idiomaDiminutivo']?>_servicioDescripcion"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<textarea style="width: 100%; resize: vertical" name="<?=$idioma['idiomaDiminutivo']?>_servicioDescripcion" ><?= $traducciones[$idioma['idiomaDiminutivo']]->servicioDescripcion ?></textarea>
						<? else: ?>
							<textarea style="width: 100%; resize: vertical" name="<?=$idioma['idiomaDiminutivo']?>_servicioDescripcion" ></textarea>
						<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">
			<div class="input small">
				<label for="servicioClase">Clase</label>
			    <input name="servicioClase"  id="servicioClase" type="text" value="<?= $servicioClase; ?>" />
			</div>
		</div>
	</div>

	<input type="hidden" name="paginaId" value="<?=$paginaId;?>" />
	<input id="servicioId" type="hidden" name="servicioId" value="<?=$servicioId;?>" />
    <input id="imagen-servicio" type="hidden" name="servicioImagen" value="<?=$servicioImagen;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="servicioImagenCoord" value="<?=$servicioImagenCoord;?>" />
	
	<?= form_close(); ?>
	
</div>

<script type="text/javascript">
    upload.image('upload-image-servicio', 'imagen-servicio', '<?=base_url();?>admin/imagen/servicio/<?=$servicioId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>

<a href="<?=base_url('admin/services/image/index/' . $servicioId); ?>" class="boton importante n2 nivel2" >Galeri&iacute;a</a>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="services/service/edit/" data-delete-url="services/service/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>