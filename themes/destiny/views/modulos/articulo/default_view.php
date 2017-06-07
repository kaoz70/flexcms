<div class="content">
    <div class="<?=$articulo->articuloClase?>">
        <h2><?=$articulo->articuloTitulo?></h2>
        <div class="texto"><?=character_limiter(strip_tags($articulo->articuloContenido),120)?></div>
        <a class="mas" href="<?=$link?>"><? lang('ui_read_more') ?></a>
    </div>
</div>