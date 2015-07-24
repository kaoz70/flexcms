<h2><?=$txt_titulo?><a class="cerrar" href="#" >cancelar</a></h2>
<? if($search): ?>
    <div class="buscar">
        <input data-page-id="<?=$this->uri->segment(4)?>" type="text" name="searchString" value="Buscar..." />
        <div class="searchButton"></div>
    </div>
    <ul id="<?=$list_id?>" class="contenido_col listado_general searchResults" style="bottom: <?=$bottomMargin?>px" rel="<?=$url_rel?>">
<? else: ?>
    <ul id="<?=$list_id?>" class="contenido_col listado_general" style="bottom: <?=$bottomMargin?>px" rel="<?=$url_rel?>">
<? endif ?>

    <? foreach($grupos as $grupo): ?>
        <li class="pagina field">
            <h3 class="header"><?=$txt_grupoNombre?>: <?=$grupo[$idx_nombre]?></h3>
            <ul id="list_<?=$grupo[$idx_grupo_id]?>" class="<?= $drag ? 'sorteable' : '' ?> content" data-sort="<?=$url_sort . '/' . $grupo[$idx_grupo_id]?>">
                <? foreach($items as $item): ?>
                    <? if($item[array_key_exists($idx_grupo_id, $item) && !isset($idx_grupo_id_alt) ? $idx_grupo_id : $idx_grupo_id_alt] == $grupo[$idx_grupo_id]): ?>
                        <?if($drag):?>
                            <li class="listado drag" id="<?=$item[$idx_item_id];?>">
                                <div class="mover"></div>
                        <? else: ?>
                            <li class="listado" id="<?=$item[$idx_item_id];?>">
                        <?endif?>
                            <a class="nombre modificar <?=$nivel?>" href="<?=$url_modificar . '/' . $item[$idx_item_id]?>"><span><?=$item[$idx_item_nombre]?></span></a>
                            <a href="<?=$url_eliminar . '/' . $item[$idx_item_id];?>" class="eliminar" ></a>
                        </li>
                    <? endif; ?>
                <? endforeach; ?>
            </ul>
            <?if($drag):?>
                <script type="text/javascript">
                    initSortables($('list_<?=$grupo[$idx_grupo_id]?>'));
                </script>
            <?endif?>
        </li>
    <?  endforeach; ?>
	
</ul>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>

<? if($search): ?>
<script type="text/javascript">
    search.init('<?=$url_search?>', 'es');
</script>
<? endif ?>