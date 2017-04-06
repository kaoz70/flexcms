<h3 id="tempMod_<?=$moduleId?>">Productos al Azar</h3>

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

<p class="input small">
    <label for="parametro1">Categoría:</label>
    <select id="parametro1" name="parametro1">
        <option value="todas">Todas las Categorias</option>
        <? foreach ($categorias as $key => $value):?>
			<option <?= $moduleData->moduloParam1 == $value['id'] ? 'selected' : '' ?> value="<?=$value['productoCategoriaId'] ?>"><?=$value['productoCategoriaNombre'] ?></option>
        <? endforeach ?>
    </select>
</p>

<p class="input small">
    <label for="parametro2">Mostrar:</label> <input type="text" name="parametro2" value="<?=$moduleData->moduloParam2?>" />
</p>
<p class="input small">
    <label for="parametro3">Imagen:</label>
    <select id="parametro3" name="parametro3">
        <? foreach ($moduleImages as $image): ?>
            <? if((int) $moduleData->moduloParam3 == $image->imagenId): ?>
                <option selected="selected" value="<?=$image->imagenId?>"><?=$image->imagenNombre?> (<?=$image->imagenAncho?>x<?=$image->imagenAlto?>)</option>
            <? else: ?>
                <option value="<?=$image->imagenId?>"><?=$image->imagenNombre?> (<?=$image->imagenAncho?>x<?=$image->imagenAlto?>)</option>
            <? endif ?>
        <? endforeach ?>
    </select>
</p>
<input type="hidden" name="parametro4" value="" />
<p class="input small">
    <label for="moduloVista">Vista:</label>
    <select id="moduloVista" name="moduloVista">
        <? foreach ($moduleViews as $view): ?>
            <option <?=$moduleData->moduloVista == $view ? 'selected="selected"' : ''?> value="<?=$view?>"><?=$view?></option>
        <? endforeach ?>
    </select>
</p>
<input id="moduloVerPaginacion" type="hidden" name="moduloVerPaginacion" value="" />
<p class="input check">
	<input id="habilitado_<?=$moduleId?>" type="checkbox" name="habilitado" <?=$moduleData->moduloHabilitado ? 'checked' : ''?> value="1" />
	<label for="habilitado_<?=$moduleId?>">Habilitado</label>
</p>
<p class="input small">
    <label for="moduloClase">Clase:</label>
    <input id="moduloClase" type="text" name="moduloClase" value="<?=$moduleData->moduloClase?>" />
</p>

<? foreach ($idiomas as $key => $idioma):?>
<input type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_html" value="" />
<? endforeach ?>

<div class="save_module" style="display: none;">guardar</div>