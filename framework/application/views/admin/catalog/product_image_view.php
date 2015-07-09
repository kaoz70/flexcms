<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/catalogo/subirImagenGaleriaProducto/'.$productoImagenId, $attributes);
?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="productoImagenNombre">Nombre:</label>
				<input class="required name" id="productoImagenNombre" name="productoImagenNombre" type="text" value="<?=$productoImagenNombre ?>"/>
			</div>
	
			<fieldset>
				<legend>Descripción</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoImagenDescripcion"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoImagenDescripcion" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoImagenDescripcion?>"/>
					<? else: ?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoImagenDescripcion" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

            <fieldset id="upload-image-productoImagen">
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
			
			<input id="imagen-productoImagen" type="hidden" name="productoImagen" value="<?=$productoImagen; ?>" data-orig="<?=$imagenOrig?>" />
	
			<div class="input check">
				<input type="checkbox" name="productoImagenEnabled" id="productoImagenEnabled" <?= $productoImagenEnabled; ?> />
				<label for="productoImagenEnabled">Publicado</label>
			</div>
		</div>
	</div>

	<input id="productoImagenId" type="hidden" name="productoImagenId" value="<?=$productoImagenId; ?>" />
    <input class="coord" type="hidden" name="productoImagenCoord" value="<?=$productoImagenCoord;?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalogo/modificarProductoImagen/<?=$productoImagenId; ?>" data-delete-url="catalogo/eliminarProductoImagen/<?=$productoImagenId; ?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>

<script type="text/javascript">
    upload.image('upload-image-productoImagen', 'imagen-productoImagen', '<?=base_url();?>admin/imagen/productoGaleria/<?=$productoId?>/<?=$productoImagenId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>