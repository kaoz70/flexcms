<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/catalogo/subirImagenGaleriaProducto/<?=$productoId?>/<?=$productoImagenId?>', $attributes);
    ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">
            <div class="input">
                <label for="publicacionImagenNombre">Nombre:</label>
                <input class="required name" id="publicacionImagenNombre" name="publicacionImagenNombre" type="text" value="<?=$publicacionImagenNombre ?>"/>
            </div>

            <fieldset id="upload-image-publicacionImagen">
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

    <input id="imagen-publicacionImagenExtension" type="hidden" name="publicacionImagenExtension" value="<?=$imagenExtension; ?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="publicacionImagenCoord" value="<?=$imagenCoord;?>" />
    <input id="publicacionImagenId" type="hidden" name="publicacionImagenId" value="<?=$publicacionImagenId; ?>" />
    <input id="publicacionId" type="hidden" name="publicacionId" value="<?=$publicacionId; ?>" />

    <?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel4" data-edit-url="noticias/modificarImagen/<?=$publicacionImagenId?>" data-delete-url="noticias/eliminarImagen/<?=$publicacionImagenId?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-publicacionImagen', 'imagen-publicacionImagenExtension', '<?=base_url();?>admin/imagen/publicacionGaleria/<?=$publicacionId?>/<?=$publicacionImagenId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>