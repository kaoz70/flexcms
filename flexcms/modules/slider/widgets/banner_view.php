<h3 id="tempMod_<?=$moduleId?>">Banner</h3>
<input type="hidden" name="parametro2" value="<?=$moduleData->moduloParam2?>" />
<input type="hidden" name="moduloVerPaginacion" value="on" />
<input type="hidden" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
<input type="hidden" name="parametro4" value="" />

<? foreach ($idiomas as $key => $idioma):?>
<input type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_html" value="" />
<? endforeach ?>

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
	<label for="parametro1">Banner</label>
	<select name="parametro1" class="banner_select">
		<? foreach ($banners as $key => $value):?>
			<? if($moduleData->moduloParam1 == $value['bannerId']): ?>
				<? $bannerType = $value['bannerType'] ?>
				<option selected="selected" data-type="<?=$value['bannerType']?>" value="<?=$value['bannerId'] ?>"><?=$value['bannerName'] ?></option>
			<? else: ?>
				<option data-type="<?=$value['bannerType']?>" value="<?=$value['bannerId'] ?>"><?=$value['bannerName'] ?></option>
			<? endif ?>
		<? endforeach ?>
	</select>
</p>

<p class="input small">
	<label for="moduloVista_<?=stripallslashes($key)?>">Vista:</label>
	<? foreach ($moduleViews as $key => $folder): ?>
		<select data-type="<?=stripallslashes($key)?>" style="display: <?=stripallslashes($key) === $bannerType ? 'inline' : 'none'?>" class="banner_view_select" id="moduloVista_<?=stripallslashes($key)?>" name="moduloVista[<?=stripallslashes($key)?>]">
			<? foreach ($folder as $view): ?>
				<option <?=$moduleData->moduloVista == $view ? 'selected="selected"' : ''?> value="<?=$view?>"><?=$view?></option>
			<? endforeach ?>
		</select>
	<? endforeach ?>
</p>

<input class="banner_view_hidden" type="hidden" name="moduloVista" value="<?=$moduleData->moduloVista?>" />

<p class="input check">
	<input id="habilitado_<?=$moduleId?>" type="checkbox" name="habilitado" <?=$moduleData->moduloHabilitado ? 'checked' : ''?> value="1" />
	<label for="habilitado_<?=$moduleId?>">Habilitado</label>
</p>

<p class="input small">
	<label for="moduloClase">Clase:</label>
	<input id="moduloClase" type="text" name="moduloClase" value="<?=$moduleData->moduloClase?>" />
</p>

<div class="save_module" style="display: none;">guardar</div>