<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/catalogo/subirImagenGaleriaProducto/'.$productoArchivoId, $attributes);
?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="productoArchivoNombre">Nombre:</label>
				<input class="required name" id="productoArchivoNombre" name="productoArchivoNombre" type="text" value="<?=$productoArchivoNombre ?>"/>
			</div>
	
			<fieldset>
				<legend>Descripción</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoDescargaDescripcion"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoDescargaDescripcion" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion?>"/>
					<? else: ?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoDescargaDescripcion" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

            <fieldset id="upload-image-productoImagen">
                <legend><?=$txt_botImagen;?></legend>
                <ul class="list">
                    <? if($imagenUrl != ''): ?>
                        <li class="image">
                            <?=$imagenUrl?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>

			<input id="imagen-productoImagen" type="hidden" name="productoImagen" value="<?=$productoArchivoExtension; ?>" data-orig="<?=$imagenOrig?>" />
	
			<div class="input check">
				<input type="checkbox" name="productoArchivoEnabled" id="productoArchivoEnabled" <?= $productoArchivoEnabled; ?> />
				<label for="productoArchivoEnabled">Publicado</label>
			</div>
		</div>
	</div>

	<input id="upload-fileName" type="hidden" name="productoArchivoExtension" value="<?=$productoArchivoExtension; ?>" />
	<input id="productoArchivoId" type="hidden" name="productoArchivoId" value="<?=$productoArchivoId; ?>" />
    <input class="coord" type="hidden" name="productoArchivoCoord" value="<?=urlencode($productoArchivoCoord);?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalogo/modificarProductoImagen/<?=$productoArchivoId; ?>" data-delete-url="catalogo/eliminarProductoImagen/<?=$productoArchivoId; ?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>

<script type="text/javascript">
    upload.image('upload-image-productoImagen', 'imagen-productoImagen', '<?=base_url();?>admin/imagen/productoGaleria/<?=$productoId?>/<?=$productoCampoId?>/<?=$productoArchivoId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>