<div class="mod_title">
	<h3 id="tempMod_<?=$moduleId?>">Titulo de la página</h3>
	<? foreach ($idiomas as $key => $idioma):?>
        <? if($moduleData->traducciones[$idioma['idiomaDiminutivo']]): ?>
            <input class="nombre_modulo" rel="<?=$idioma['idiomaDiminutivo']?>" type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_nombre" value="<?=$moduleData->traducciones[$idioma['idiomaDiminutivo']]->moduloNombre?>" />
        <? else: ?>
            <input class="nombre_modulo" rel="<?=$idioma['idiomaDiminutivo']?>" type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_nombre" value="" />
        <? endif ?>
	<? endforeach ?>
	<input type="hidden" name="moduloMostrarTitulo" value="on" />
	<input type="hidden" name="moduloVerPaginacion" value="on" />
	<input type="hidden" name="parametro1" value="<?=$moduleData->moduloParam1?>" />
	<input type="hidden" name="parametro2" value="<?=$moduleData->moduloParam2?>" />
	<input type="hidden" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
    <input type="hidden" name="parametro4" value="" />
	<p class="input small">
        <label for="moduloVista">Vista:</label>
        <select id="moduloVista" name="moduloVista">
            <? foreach ($moduleViews as $view): ?>
                <option <?=$moduleData->moduloVista == $view ? 'selected="selected"' : ''?> value="<?=$view?>"><?=$view?></option>
            <? endforeach ?>
        </select>
    </p>
	<p class="input check">
		<input id="habilitado_<?=$moduleId?>" type="checkbox" name="habilitado" <?=$moduleData->moduloHabilitado ? 'checked' : ''?> value="1" />
		<label for="habilitado_<?=$moduleId?>">Habilitado</label>
	</p>
	<p class="input small">
		<label for="moduloClase">Clase:</label>
		<input id="moduloClase" type="text" name="moduloClase" value="<?=$moduleData->moduloClase?>" />
	</p>
	
</div>

<div class="save_module" style="display: none;">guardar</div>