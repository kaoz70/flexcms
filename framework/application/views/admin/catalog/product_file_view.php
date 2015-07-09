<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/catalogo/subirDescargaProducto/'.$productoId.'/'.$productoDescargaId, $attributes);
    ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">
            <div class="input">
                <label for="productoDescargaNombre">Nombre:</label>
                <input class="required name" id="productoDescargaNombre" name="productoDescargaNombre" type="text" value="<?=$productoDescargaNombre ?>"/>
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
                <input type="checkbox" name="productoDescargaEnabled" id="productoDescargaEnabled" <?= $productoDescargaEnabled; ?> />
				<label for="productoDescargaEnabled">Publicado</label>
            </div>
        </div>
    </div>

    <input id="upload-fileName" type="hidden" name="productoDescargaArchivo" value="<?=$productoDescargaArchivo; ?>" />
    <input id="productoId" type="hidden" name="productoId" value="<?=$productoId; ?>" />
    <input id="productoDescargaId" type="hidden" name="productoDescargaId" value="<?=$productoDescargaId; ?>" />

    <?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/edit_file/<?=$productoId?>/" data-delete-url="catalog/delete_file/<?=$productoId?>/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>

<script type="text/javascript">
    upload.file('upload-file', 'upload-fileName', '<?=base_url();?>admin/archivo/producto/<?=$productoId; ?>/<?=$productoDescargaId?>', true);
</script>