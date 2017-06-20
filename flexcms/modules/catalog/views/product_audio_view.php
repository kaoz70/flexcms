<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/catalogo/subirAudioProducto/'.$productoId.'/'.$productoAudioId, $attributes);
?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="productoAudioNombre">Nombre:</label>
				<input class="required name" id="productoAudioNombre" name="productoAudioNombre" type="text" value="<?=$productoAudioNombre ?>"/>
			</div>
	
            <fieldset id="upload-audio">
                <legend><?=$txt_subir?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if($audio != ''): ?>
                        <li class="image">
                            <?=$audio?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>
			
			<input id="upload-fileName" type="hidden" name="productoAudioExtension" value="<?=$productoAudioExtension; ?>" />
	
			<div class="input check">
				<input type="checkbox" name="productoAudioEnabled" id="productoAudioEnabled" <?= $productoAudioEnabled; ?> />
				<label for="productoAudioEnabled">Publicado</label>
			</div>
		</div>
	</div>

	<input id="productoAudioId" type="hidden" name="productoAudioId" value="<?=$productoAudioId; ?>" />
	<input id="productoId" type="hidden" name="productoId" value="<?=$productoId; ?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/audio/edit/" data-delete-url="catalog/audio/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>

<script type="text/javascript">
    upload.file('upload-audio', 'upload-fileName', '<?=base_url();?>admin/archivo/productoAudio/<?=$productoId; ?>/<?=$productoAudioId?>', false);
</script>