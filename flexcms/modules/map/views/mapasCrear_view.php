<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/faq/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<div class="input">
				<fieldset>
					<legend>Nombre</legend>
						<div>
							<input class="required name" name="mapaNombre" type="text" value="<?=$mapaNombre?>"/>
						</div>
				</fieldset>
			</div>
			
            <fieldset id="upload-image-mapa">
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
				<input type="checkbox" name="mapaPublicado" id="mapaPublicado" <?= $mapaPublicado; ?> />
				<label for="mapaPublicado">Publicado</label>
			</div>
		</div>
	</div>
	
	<input id="imagen-mapa" type="hidden" name="mapaImagen" value="<?=$mapaImagen;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="mapaImagenCoord" value="<?=$mapaImagenCoord;?>" />
	
	<?= form_close(); ?> 
	
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="maps/map/edit/" data-delete-url="maps/map/delete/" class="guardar boton importante no_sort n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-mapa', 'imagen-mapa', '<?=base_url();?>admin/imagen/mapas/<?=$mapaId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>