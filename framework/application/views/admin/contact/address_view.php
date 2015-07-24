<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 796px">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/contacto/' . $link, $attributes);

?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">

            <p>
            <label for="contactoDireccionNombre">Nombre</label>
            <? if($contactoDireccionNombre != ''):?>
                <input class="required name" name="contactoDireccionNombre" type="text" value="<?=$contactoDireccionNombre?>"/>
            <? else: ?>
                <input class="required name" name="contactoDireccionNombre" type="text" value=""/>
            <? endif ?>
            </p>

			<fieldset>
				<legend>Direccion</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_contactoDireccion"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<textarea rows="20" cols="85" id="<?=$idioma['idiomaDiminutivo']?>_editor" class="required" name="<?=$idioma['idiomaDiminutivo']?>_contactoDireccion" type="text"><?=$traducciones[$idioma['idiomaDiminutivo']]->contactoDireccion?></textarea>
					<? else: ?>
						<textarea rows="20" cols="85" id="<?=$idioma['idiomaDiminutivo']?>_editor" class="required" name="<?=$idioma['idiomaDiminutivo']?>_contactoDireccion" type="text"></textarea>
					<? endif ?>
					</div>
                    <script type="text/javascript">initEditor("<?=$idioma['idiomaDiminutivo']?>_editor");</script>
				<? endforeach ?>
			</fieldset>

            <fieldset id="upload-image-address">
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

		</div>
	</div>
	
	<input id="contactoId" type="hidden" name="direccionId" value="<?=$direccionId;?>" />
    <input id="imagen-address" type="hidden" name="contactoDireccionImagen" value="<?=$imagenExtension;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="contactoDireccionCoord" value="<?=$contactoDireccionCoord;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="contact/address/edit/" data-delete-url="contact/address/delete/" class="guardar boton importante n1 contacto_direccion <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-address', 'imagen-address', '<?=base_url();?>admin/imagen/contactoDireccion/<?=$direccionId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>