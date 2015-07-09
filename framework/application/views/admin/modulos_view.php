<div id="modulos">

    <ul class="principales">
    <? foreach ($modulos[0] as $modulo): ?>
        <li id="mod_t_<?=$modulo->paginaModuloTipoId?>" data-id="<?=$modulo->paginaModuloTipoId?>"><?=$modulo->moduloTipoNombre?></li>
    <? endforeach ?>
    </ul>

    <div class="secundarios">
        <? foreach ($modulos as $key => $group): ?>
            <? if($key != 0):?>
                <ul id="group_<?=$key?>">
                    <? foreach ($group as $modulo): ?>
                        <li id="mod_t_<?=$modulo->paginaModuloTipoId?>" data-id="<?=$modulo->paginaModuloTipoId?>"><?=$modulo->moduloTipoNombre?></li>
                    <? endforeach ?>
                </ul>
            <? endif ?>
        <? endforeach ?>
    </div>

</div>
