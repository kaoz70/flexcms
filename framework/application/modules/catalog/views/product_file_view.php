<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/catalogo/subirDescargaProducto/'.$productoId.'/'.$productoArchivoId, $attributes);
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

            <fieldset id="upload-file">
                <legend><?=$txt_botImagen;?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if($archivoUrl != ''): ?>
                        <li class="default">
                            <?=$archivoUrl?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>

            <div class="input check">
                <input type="checkbox" name="productoArchivoEnabled" id="productoArchivoEnabled" <?= $productoArchivoEnabled; ?> />
				<label for="productoArchivoEnabled">Publicado</label>
            </div>
        </div>
    </div>

    <input id="upload-fileName" type="hidden" name="productoArchivoExtension" value="<?=$productoArchivoExtension; ?>" />
    <input id="productoId" type="hidden" name="productoId" value="<?=$productoId; ?>" />
    <input id="productoArchivoId" type="hidden" name="productoArchivoId" value="<?=$productoArchivoId; ?>" />

    <?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/edit_file/<?=$productoId?>/" data-delete-url="catalog/delete_file/<?=$productoId?>/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>

<script type="text/javascript">
    upload.file('upload-file', 'upload-fileName', '<?=base_url();?>admin/archivo/producto/<?=$productoId; ?>/<?=$productoArchivoId?>', true);
</script>