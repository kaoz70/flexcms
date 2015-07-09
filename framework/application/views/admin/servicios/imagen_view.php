<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/servicios/subirImagen/'.$servicio_id, $attributes);
?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="nombre">Nombre:</label>
				<input class="required name" id="nombre" name="nombre" type="text" value="<?=$nombre ?>"/>
			</div>
	
            <fieldset id="upload-image-servicioImagen">
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
			
			<input id="imagen-servicioImagen" type="hidden" name="extension" value="<?=$productoImagen; ?>" data-orig="<?=$imagenOrig?>" />

		</div>
	</div>

	<input id="id" type="hidden" name="id" value="<?=$id; ?>" />
    <input class="coord" type="hidden" name="coords" value="<?=$coords;?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" nivel="nivel3" modificar="servicios/modificar_imagen/<?=$id; ?>" eliminar="servicios/eliminar_imagen/<?=$id; ?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>

<script type="text/javascript">
    upload.image('upload-image-servicioImagen', 'imagen-servicioImagen', '<?=base_url();?>admin/imagen/servicioGaleria/<?=$servicio_id?>/<?=$id?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>