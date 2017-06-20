<h2><?=$title; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div id="publicidad" class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/publicidad/' . $link, $attributes);

?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
            <div class="input">
                <label for="name">Título:</label>
                <input class="required name" id="name" name="name" type="text" value="<?=$advert->name?>"/>
            </div>

            <div class="input small">
                <label for="date_start">Fecha inicio</label>
                <input id="date_start" class="fecha" name="date_start" type="datetime" value="<?=$advert->date_start?>"/>
            </div>

            <div class="input small">
                <label for="date_end">Fecha fin</label>
                <input id="date_end" class="fecha" name="date_end" type="datetime" value="<?=$advert->date_end?>"/>
            </div>

            <div class="input">
                <label for="paginaIds">Paginas:</label>
                <select id="paginaIds" name="paginaIds[]" multiple>
                    <?php foreach($paginas as $pagina): ?>
                        <option <?= in_array($pagina->id, $paginaIds) ? 'selected="selected"' : ''?> value="<?= $pagina->id ?>"><?= $pagina->getTranslation('es')->name ?></option>
                    <?php  endforeach; ?>
                </select>
            </div>

            <!--<fieldset id="upload-file">
                <legend><?/*=$txt_botImagen;*/?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <?/* if($archivoUrl != ''): */?>
                        <li class="default">
                            <?/*=$archivoUrl*/?>
                        </li>
                    <?/* endif; */?>
                </ul>
            </fieldset>-->

            <div class="input check">
                <input type="checkbox" name="enabled" id="enabled" <?= $advert->enabled; ?> />
                <label for="enabled">Publicado</label>
            </div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">

            <div class="input small">
                <label for="css_class">clase</label>
                <input id="css_class" type="text" name="css_class" value="<?=$advert->css_class; ?>" />
            </div>

		</div>
	</div>

	<input id="publicidadId" type="hidden" name="publicidadId" value="<?=$advert->id;?>" />
	<input id="publicidadTipoId" type="hidden" name="publicidadTipoId" value="3" />

    <input id="upload-publicidadArchivo1" type="hidden" name="publicidadArchivo1" value="<?=$advert->file1;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="advert/edit/" data-delete-url="advert/delete/" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    initDatePicker();
    upload.file('upload-file', 'upload-publicidadArchivo1', '<?=base_url();?>admin/archivo/publicidad', true);
</script>