<div class="mod_menu">

    <h3 id="tempMod_<?=$moduleId?>">Menu</h3>

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


    <input type="hidden" name="parametro1" value="" />
    <input type="hidden" name="parametro2" value="" />
    <input type="hidden" name="parametro3" value="" />
    <input type="hidden" name="parametro4" value="" />
    <? foreach ($idiomas as $key => $idioma):?>
    <input type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_html" value="" />
    <? endforeach ?>
    <input type="hidden" name="moduloVerPaginacion" value="on" />
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

    <div class="save_module" style="display: none;">guardar</div>

</div>