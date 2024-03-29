<h3 id="tempMod_<?=$moduleId?>">Catálogo - Filtros</h3>

<fieldset>
	<legend>Nombre</legend>
	<? foreach ($idiomas as $key => $idioma):?>
	<div class="input small">
		<label for="<?=$idioma['idiomaDiminutivo']?>_nombre"><?=$idioma['idiomaNombre']?></label>
        <? if($moduleData->traducciones[$idioma['idiomaDiminutivo']]): ?>
            <input class="nombre_modulo" rel="<?=$idioma['idiomaDiminutivo']?>" type="text" name="<?=$idioma['idiomaDiminutivo']?>_nombre" value="<?=$moduleData->traducciones[$idioma['idiomaDiminutivo']]->moduloNombre?>" />
        <? else: ?>
            <input class="nombre_modulo" rel="<?=$idioma['idiomaDiminutivo']?>" type="text" name="<?=$idioma['idiomaDiminutivo']?>_nombre" value="" />
        <? endif ?>
	</div>
	<? endforeach ?>
</fieldset>

<p class="input check">
	<input id="moduloMostrarTitulo_<?=$moduleId?>" type="checkbox" name="moduloMostrarTitulo" <?=$moduleData->moduloMostrarTitulo ? 'checked' : ''?>  />
	<label for="moduloMostrarTitulo_<?=$moduleId?>">Mostrar nombre</label>
</p>


<input type="hidden" name="moduloVerPaginacion" value="on" />

<p class="input check">
	<?
		if($moduleData->moduloParam1)
			$moduleData->moduloParam1 = 'checked="checked"';
		else {
			$moduleData->moduloParam1 = '';
		}
	?>
	<input id="parametro1" type="checkbox" name="parametro1" <?=$moduleData->moduloParam1?> />
	<label for="parametro1">Para productos destacados:</label>
</p>

<? foreach ($idiomas as $key => $idioma):?>
<input type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_html" value="" />
<? endforeach ?>

<p class="input small">
    <label for="moduloVista">Vista:</label>
    <select id="moduloVista" name="moduloVista">
        <? foreach ($moduleViews as $view): ?>
            <option <?=$moduleData->moduloVista == $view ? 'selected="selected"' : ''?> value="<?=$view?>"><?=$view?></option>
        <? endforeach ?>
    </select>
</p>
<input type="hidden" name="parametro2" value="<?=$moduleData->moduloParam2?>" />
<input type="hidden" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
<input type="hidden" name="parametro4" value="" />
<p class="input check">
	<input id="habilitado_<?=$moduleId?>" type="checkbox" name="habilitado" <?=$moduleData->moduloHabilitado ? 'checked' : ''?> value="1" />
	<label for="habilitado_<?=$moduleId?>">Habilitado</label>
</p>
<p class="input small">
	<label for="moduloClase">Clase:</label>
	<input type="text" name="moduloClase" value="<?=$moduleData->moduloClase?>" />
</p>
<div class="save_module" style="display: none;">guardar</div>