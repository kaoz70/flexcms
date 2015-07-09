<div class="mod_content">
    <h3 id="tempMod_<?=$moduleId?>">Contenido</h3>

    <? foreach ($idiomas as $key => $idioma):?>
        <? if($moduleData->traducciones[$idioma['idiomaDiminutivo']]): ?>
            <input class="nombre_modulo" rel="<?=$idioma['idiomaDiminutivo']?>" type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_nombre" value="<?=$moduleData->traducciones[$idioma['idiomaDiminutivo']]->moduloNombre?>" />
        <? else: ?>
            <input class="nombre_modulo" rel="<?=$idioma['idiomaDiminutivo']?>" type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_nombre" value="" />
        <? endif ?>
    <? endforeach ?>

    <input type="hidden" name="moduloMostrarTitulo" value="on" />

    <p class="input small">
        <label for="parametro1">Tipo:</label>
        <select id="parametro1" name="parametro1">
            <? foreach ($pageTypes as $key => $value):?>
            <? if($moduleData->moduloParam1 == $value['pagina_tipoId']): ?>
                <option selected="selected" value="<?=$value['pagina_tipoId'] ?>"><?=$value['pagina_tipoNombre'] ?></option>
                <? else: ?>
                <option value="<?=$value['pagina_tipoId'] ?>"><?=$value['pagina_tipoNombre'] ?></option>
                <? endif ?>
            <? endforeach ?>
        </select>
    </p>

    <? switch ((int)$moduleData->moduloParam1):
    case 7: ?>
        <p id="currentContent">
            <label for="parametro2">Página:</label>
            <select id="parametro2" name="parametro2">
                <? foreach ($paginas as $key => $value):?>
                <? if($moduleData->moduloParam2 == $value['paginaId']): ?>
                    <option selected="selected" value="<?=$value['paginaId'] ?>"><?=$value['paginaNombreMenu'] ?></option>
                    <? else: ?>
                    <option value="<?=$value['paginaId'] ?>"><?=$value['paginaNombreMenu'] ?></option>
                    <? endif ?>
                <? endforeach ?>
                <? if($moduleData->moduloParam2 == 0): ?>
                <option selected="selected" value="0">Otro</option>
                <? else: ?>
                <option value="0">Otro</option>
                <? endif ?>
            </select>

            <? if($moduleData->moduloParam2 == 0): ?>
            <label for="parametro3">URL</label>
            <input id="parametro3" type="text" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
            <? else: ?>
            <label for="parametro3" style="display: none">URL</label>
            <input id="parametro3" style="display: none" type="text" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
            <? endif ?>
        </p>
        <? break ?>

        <?case 5: //Publicacion ?>
        <p id="currentContent" class="input check">
            <? if((int)$moduleData->moduloParam2 == 1): ?>
            <input id="parametro2" name="parametro2" type="checkbox" checked="checked" />
            <? else: ?>
            <input id="parametro2" name="parametro2" type="checkbox" />
            <? endif ?>
			<label for="parametro2">Mostrar Listado:</label>
        </p>
        <? break ?>

        <? case 4: case 6: ?>
        <p id="currentContent" class="input check">
            <? if((int)$moduleData->moduloParam2 == 1): ?>
            <input id="parametro2" name="parametro2" type="checkbox" checked="checked" />
            <? else: ?>
            <input id="parametro2" name="parametro2" type="checkbox" />
            <? endif ?>
			<label for="parametro2">Mostrar Categorías en Menu:</label>
        </p>

        <? break ?>

        <?default: ?>
        <p id="currentContent">
            <input id="parametro2" name="parametro2" type="hidden" />
            <input id="parametro3" name="parametro3" type="hidden" />
        </p>
        <? break ?>

        <? endswitch ?>

    <div id="hiddenContent" style="display: none">
        <p id="h_pagina">
            <label for="parametro2_<?=$moduleData->moduloId?>">Página:</label>
            <select id="parametro2_<?=$moduleData->moduloId?>">
                <? foreach ($paginas as $key => $value):?>
                <? if($moduleData->moduloParam2 == $value['paginaId']): ?>
                    <option selected="selected" value="<?=$value['paginaId'] ?>"><?=$value['paginaNombreMenu'] ?></option>
                    <? else: ?>
                    <option value="<?=$value['paginaId'] ?>"><?=$value['paginaNombreMenu'] ?></option>
                    <? endif ?>
                <? endforeach ?>
                <? if($moduleData->moduloParam2 == 0): ?>
                <option selected="selected" value="0">Otro</option>
                <? else: ?>
                <option value="0">Otro</option>
                <? endif ?>
            </select>
            <? if($moduleData->moduloParam2 == 0): ?>
            <label for="parametro3_<?=$moduleData->moduloId?>">URL</label>
            <input id="parametro3_<?=$moduleData->moduloId?>" type="text" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
            <? else: ?>
            <label for="parametro3_<?=$moduleData->moduloId?>" style="display: none">URL</label>
            <input id="parametro3_<?=$moduleData->moduloId?>" style="display: none" type="text" name="parametro3" value="<?=$moduleData->moduloParam3?>" />
            <? endif ?>
        </p>
        <p id="h_listado" class="input check">
            <? if($moduleData->moduloParam3 == (int)1): ?>
            <input id="parametro2_<?=$moduleData->moduloId?>" type="checkbox" checked="checked" />
            <? else: ?>
            <input id="parametro2_<?=$moduleData->moduloId?>" type="checkbox" />
            <? endif ?>
			<label for="parametro2_<?=$moduleData->moduloId?>">Mostrar Listado:</label>
        </p>
        <p id="h_categorias" class="input check">
            <? if($moduleData->moduloParam3 == (int)1): ?>
            <input id="parametro2_<?=$moduleData->moduloId?>" type="checkbox" checked="checked" />
            <? else: ?>
            <input id="parametro2_<?=$moduleData->moduloId?>" type="checkbox" />
            <? endif ?>
			<label for="parametro2_<?=$moduleData->moduloId?>">Mostrar Categorías en Menu:</label>
        </p>
        <p id="h_default">
            <input id="parametro2" type="hidden" />
        </p>
    </div>
    <hr />
    <p class="input small">
        Solamente aplica a: Servicios, Publicaciones y Catálogo</br>
        <label for="parametro4">Mostrar:</label> <input type="text" name="parametro4" value="<?=$moduleData->moduloParam4?>" />
    </p>
    <p class="input check">
        <?
        if($moduleData->moduloVerPaginacion)
            $moduleData->moduloVerPaginacion = 'checked="checked"';
        else {
            $moduleData->moduloVerPaginacion = '';
        }
        ?>
        <input id="moduloVerPaginacion" type="checkbox" name="moduloVerPaginacion" <?=$moduleData->moduloVerPaginacion?> />
		<label for="moduloVerPaginacion">Mostrar Paginación</label>
    </p>
    <hr />
	<p class="input check">
		<input id="habilitado_<?=$moduleId?>" type="checkbox" name="habilitado" <?=$moduleData->moduloHabilitado ? 'checked' : ''?> value="1" />
		<label for="habilitado_<?=$moduleId?>">Habilitado</label>
	</p>
    <p class="input small">
        <label for="moduloClase">Clase:</label>
        <input id="moduloClase" type="text" name="moduloClase" value="<?=$moduleData->moduloClase?>" />
    </p>

    <input type="hidden" name="moduloVista" value="<?=$moduleData->moduloVista?>" />

    <? foreach ($idiomas as $key => $idioma):?>
    <input type="hidden" name="<?=$idioma['idiomaDiminutivo']?>_html" value="" />
    <? endforeach ?>

</div>

<div class="save_module" style="display: none;">guardar</div>