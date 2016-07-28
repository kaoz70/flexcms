<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 796px">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/contacto/' . $link, $attributes);

?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <div class="input">
                <label for="contactoDireccionNombre">Nombre</label>
                <input class="required name" name="name" type="text" value="<?= $address->name ?>"/>
            </div>

            <fieldset>
                <legend>Direccion</legend>

                <? foreach ($translations as $key => $trans): ?>
                    <label for="address_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <textarea rows="20" cols="85"
                              id="<?=$key?>_editor"
                              class="required"
                              name="address[<?=$key?>]"
                              type="text"><?= isset($trans->address) ? $trans->address : '' ?></textarea>
                <? endforeach ?>

            </fieldset>

            <fieldset id="upload-image-address">
                <legend>Subir im&aacute;gen</legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if(isset($image->extension)): ?>
                        <li class="image">
                            <?=$image?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>

        </div>
    </div>

   <!-- <input id="imagen-address" type="hidden" name="contactoDireccionImagen" value="<?/*=$imagenExtension;*/?>" data-orig="<?/*=$imagenOrig*/?>" />
    <input class="coord" type="hidden" name="contactoDireccionCoord" value="<?/*=$contactoDireccionCoord;*/?>" />-->

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="<?= $edit_url ?>" data-delete-url="<?= $delete_url ?>" class="guardar boton importante n1 contacto_direccion <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-address', 'imagen-address', '<?=base_url();?>admin/imagen/contactoDireccion/<?=$direccionId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>