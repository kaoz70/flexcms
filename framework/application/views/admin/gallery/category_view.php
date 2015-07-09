<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col">

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
				<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_descargaCategoriaNombre"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_descargas" data-columna="descargaUrl" data-columna-id="descargaId" data-id="<?=$id;?>" name="<?=$idioma['idiomaDiminutivo']?>_descargaCategoriaNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->descargaCategoriaNombre?>"/>
					<? else: ?>
						<input class="required name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_descargas" data-columna="descargaUrl" data-columna-id="descargaId" data-id="<?=$id;?>" name="<?=$idioma['idiomaDiminutivo']?>_descargaCategoriaNombre" type="text" value=""/>
					<? endif ?>
				</div>
				<? endforeach ?>
			</fieldset>

            <div class="input">
                <label for="descargaCategoriaEnlace">Enlace:</label>
                <input id="descargaCategoriaEnlace" name="descargaCategoriaEnlace" type="text" value="<?=$descargaCategoriaEnlace?>"/>
            </div>

            <fieldset id="upload-image-gallery-category">
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
				<input type="checkbox" name="descargaCategoriaPublicado" id="descargaCategoriaPublicado" <?= $descargaCategoriaPublicado ? 'checked' : '' ?> />
				<label for="descargaCategoriaPublicado">Publicado</label>
			</div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">
			<div class="input">
				<label for="descargaCategoriaClase">clase</label>
				<input id="descargaCategoriaClase" type="text" name="descargaCategoriaClase" value="<?=$descargaCategoriaClase; ?>" />
			</div>
		</div>
	</div>

	<input id="descargaCategoriaId" type="hidden" name="descargaCategoriaId" value="<?=$id;?>" />
    <input id="imagen-gallery-category" type="hidden" name="descargaCategoriaImagen" value="<?=$imagenExtension;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="descargaCategoriaImagenCoord" value="<?=$descargaCategoriaImagenCoord;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="gallery/edit_category/" data-delete-url="gallery/delete_category/" data-id="<?=$id?>" class="guardar boton importante n1 tree categoria <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-gallery-category', 'imagen-gallery-category', '<?=base_url();?>admin/imagen/galeriaCategoria/<?=$id?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>