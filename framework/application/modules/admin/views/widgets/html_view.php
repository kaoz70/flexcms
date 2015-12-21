<h3 id="tempMod_<?=$moduleId?>">HTML</h3>
<input type="hidden" name="moduloVerPaginacion" value="on" />
<input type="hidden" name="parametro2" value="" />
<input type="hidden" name="parametro3" value="" />
<input type="hidden" name="parametro4" value="" />
<input type="hidden" name="moduloVista" value="<?=$moduleData->moduloVista?>" />
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


<input type="hidden" name="parametro1" value="<?=$moduleData->moduloParam1?>" />

<fieldset>
    <legend>Código</legend>
    <? foreach ($idiomas as $key => $idioma):?>
    <div class="input">
        <label for="<?=$idioma['idiomaDiminutivo']?>_html"><?=$idioma['idiomaNombre']?></label>
        <textarea id="<?=$idioma['idiomaDiminutivo']?>_html" class="modulo_html" name="<?=$idioma['idiomaDiminutivo']?>_html"><?=$moduleData->traducciones[$idioma['idiomaDiminutivo']]->moduloHtml?></textarea>
    </div>
    <? endforeach ?>
</fieldset>
<p class="input check">
	<input id="habilitado_<?=$moduleId?>" type="checkbox" name="habilitado" <?=$moduleData->moduloHabilitado ? 'checked' : ''?> value="1" />
	<label for="habilitado_<?=$moduleId?>">Habilitado</label>
</p>
<p class="input small">
	<label for="moduloClase">Clase:</label>
	<input id="moduloClase" type="text" name="moduloClase" value="<?=$moduleData->moduloClase?>" />
</p>
<div class="save_module" style="display: none;">guardar</div>