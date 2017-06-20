<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<?

$style = '';

if(count($campos) > 0) {
    foreach($campos as $row) {
        switch($row->inputTipoContenido) {
            case 'texto multilinea':
                $style = 'style="width: 780px"';
                break;
        }
    }
}

?>


<div class="contenido_col" <?=$style?>>

<?php

$attributes = array('class' => 'form');
echo form_open('admin/noticias/' . $link, $attributes);

?>
	
<div class="field">
	<div class="header">General</div>
	<div class="content">
		<div class="input">
			<label for="bannerImageName">Título:</label>
			<input class="required name" id="bannerImageName" name="bannerImageName" type="text" value="<?=$bannerImageName?>"/>
		</div>
		
		<div class="input">
			<label for="bannerImageLink">Link:</label>
			<input id="bannerImageLink" name="bannerImageLink" type="text" value="<?=$bannerImageLink?>"/>
		</div>
		
        <fieldset id="upload-image-banner">
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
		
		<input id="imagen-banner" type="hidden" name="bannerImagen" value="<?=$bannerImageExtension;?>" data-orig="<?=$imagenOrig?>" />

		<?php if(count($campos) > 0): ?>
			<?php foreach($campos as $row): ?>
				<div class="input">
				<fieldset>
					<legend><?=$row->bannerCampoNombre?></legend>
					
					<? foreach ($row->traducciones as $key => $value): ?>
						<div>
							<label for="<?=$key?>_campos_<?=$row->inputId?>"><?=$value->nombre?></label>
							<?php
							switch($row->inputTipoContenido): case 'texto': ?>
								<input name="<?=$key?>_campos[<?=$row->bannerCampoId?>]" id="<?=$key?>_campos_<?=$row->bannerCampoId?>" type="text" value="<?=$value->contenido->bannerCamposTexto?>" />
							<?php break; ?>
							<?php case 'texto multilinea':?>
								<textarea id="<?=$key?>_editor_<?=$row->bannerCampoId?>" rows="8" cols="75" class="editor" name="<?=$key?>_campos[<?=$row->bannerCampoId?>]" id="campos_<?=$row->bannerCampoId?>"><?=$value->contenido->bannerCamposTexto?></textarea>
								<script type="text/javascript">
									initEditor('<?=$key?>_editor_<?=$row->bannerCampoId?>');
								</script>
							<?php break;?>
							<?php endswitch;?>
						</div>
					<? endforeach ?>
				</fieldset>
			</div>
			<?php  endforeach; ?>
		<?php endif ?>
		
		<div class="input check">
			<input type="checkbox" name="bannerImageEnabled" id="bannerImageEnabled" <?= $bannerImageEnabled; ?> />
			<label for="bannerImageEnabled">Publicado</label>
		</div>
	</div>
</div>

    <input id="bannerId" type="hidden" name="bannerId" value="<?=$bannerId;?>" />
    <input class="coord" type="hidden" name="bannerImagenCoord" value="<?=$bannerImagenCoord;?>" />

<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel4" data-edit-url="sliders/image/edit/<?=$bannerId?>/<?=$imageId?>" data-delete-url="sliders/image/delete/<?=$bannerId?>/<?=$imageId?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-banner', 'imagen-banner', '<?=base_url();?>admin/imagen/banner/<?=$bannerId?>/<?=$imageId?>', <?=$width?>, <?=$height?>, true);
</script>