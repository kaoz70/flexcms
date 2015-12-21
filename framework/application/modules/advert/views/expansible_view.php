<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div id="publicidad" class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/publicidad/' . $link, $attributes);

?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="publicidadNombre">Título:</label>
				<input class="required name" id="publicidadNombre" name="publicidadNombre" type="text" value="<?=$publicidadNombre?>"/>
			</div>

            <div class="input small">
                <label for="publicidadFechaInicio">Fecha inicio</label>
                <input id="publicidadFechaInicio" class="fecha" name="publicidadFechaInicio" type="text" value="<?=$publicidadFechaInicio?>"/>
            </div>

            <div class="input small">
                <label for="publicidadFechaFin">Fecha fin</label>
                <input id="publicidadFechaFin" class="fecha" name="publicidadFechaFin" type="text" value="<?=$publicidadFechaFin?>"/>
            </div>

            <div class="input">
                <label for="moduloId">Ubicaci&oacute;n:</label>
                <select class="selectbox" id="moduloId" name="moduloId">
                    <?php foreach($ubicaciones as $pagina): ?>
                        <optgroup data-pagina="<?=$pagina->id?>" label="P&aacute;gina: <?=$pagina->nombre?>">
                            <?php foreach($pagina->modulos as $modulo): ?>
                                <option <?=$modulo->id == $moduloId ? 'selected="selected"' : ''?> value="<?= $modulo->id ?>">M&oacute;dulo: <?= $modulo->nombre ?></option>
                            <?php  endforeach; ?>
                        </optgroup>
                    <?php  endforeach; ?>
                </select>
            </div>

            <fieldset id="upload-file1">
                <legend><?=$txt_botImagen1;?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if($archivoUrl1 != ''): ?>
                        <li class="default">
                            <?=$archivoUrl1?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>

            <fieldset id="upload-file2">
                <legend><?=$txt_botImagen2;?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if($archivoUrl2 != ''): ?>
                        <li class="default">
                            <?=$archivoUrl2?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>

			<div class="input check">
				<input type="checkbox" name="publicidadEnabled" id="publicidadEnabled" <?= $publicidadEnabled; ?> />
				<label for="publicidadEnabled">Publicado</label>
			</div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">

            <div class="input small">
                <label for="publicidadClase">clase</label>
                <input id="publicidadClase" type="text" name="publicidadClase" value="<?=$publicidadClase; ?>" />
            </div>

		</div>
	</div>

	<input id="publicidadId" type="hidden" name="publicidadId" value="<?=$publicidadId;?>" />
    <input id="publicidadTipoId" type="hidden" name="publicidadTipoId" value="2" />

    <input id="upload-publicidadArchivo1" type="hidden" name="publicidadArchivo1" value="<?=$publicidadArchivo1;?>" />
    <input id="upload-publicidadArchivo2" type="hidden" name="publicidadArchivo2" value="<?=$publicidadArchivo2;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="advert/edit/" data-delete-url="advert/delete/" class="guardar boton importante n1 grouped_selectbox no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    initDatePicker();
    upload.file('upload-file1', 'upload-publicidadArchivo1', '<?=base_url();?>admin/archivo/publicidad', true);
    upload.file('upload-file2', 'upload-publicidadArchivo2', '<?=base_url();?>admin/archivo/publicidad', true);
</script>