<h2><?=$title; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 797px">

    <?=form_open('admin/catalogo/' . $link, array('class' => 'form'));?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <fieldset>
                <legend>Nombre</legend>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="name_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <input class="required name"
                           name="name[<?=$key?>]"
                           type="text"
                           value="<?= isset($trans->name) ? $trans->name : '' ?>"
                    />
                <? endforeach ?>
            </fieldset>

            <fieldset>
                <legend>Descripci&oacute;n</legend>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="description_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <textarea id="description_<?=$key?>"
                              class="editor"
                              rows="20"
                              cols="85"
                              name="description[<?=$key?>]"><?= isset($trans->description) ? $trans->description : '' ?></textarea>
                <? endforeach ?>
            </fieldset>

            <input type="hidden" name="page_id" value="<?= $page_id ?>">

            <!--<fieldset id="upload-image-category">
                <legend><?/*=$txt_botImagen;*/?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <?/* if($imagen != ''): */?>
                        <li class="image">
                            <?/*=$imagen*/?>
                        </li>
                    <?/* endif; */?>
                </ul>
            </fieldset>-->

        </div>
    </div>

    <?= form_close(); ?>

</div>

<a href="<?= $link; ?>"
   data-level="nivel3"
   data-edit-url="><?=$url_edit?>"
   data-delete-url="<?=$url_delete?>"
   data-id="<?=$category->id?>"
   data-reorder="<?=$url_reorder?>"
   class="guardar boton importante n1 tree categoria <?=$nuevo?>" ><?=$txt_boton;?></a>

<!--<script type="text/javascript">
    upload.image('upload-image-category', 'imagen-categoria', '<?/*=base_url();*/?>admin/imagen/catalogoCategoria/<?/*=$categoriaId*/?>', <?/*=$cropDimensions->imagenAncho*/?>, <?/*=$cropDimensions->imagenAlto*/?>, <?/*=$cropDimensions->imagenCrop ? 'true' : 'false'*/?>);
</script>-->