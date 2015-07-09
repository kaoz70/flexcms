<div class="content" id="servicios">

    <? foreach ($productos as $producto): ?>
        <div class="servicio">

            <h3>
                <? if($producto->productoImagenExtension != ''): ?>
                    <img src="<?=base_url()?>assets/public/images/catalog/prod_<?=$producto->productoId?><?=$imageSize?>.<?=$producto->productoImagenExtension?>" alt="<?=$producto->productoNombre?>" />
                <? endif ?>
                <?=$producto->productoNombre?>
            </h3>

            <?php foreach($producto->campos as $campo): ?>
                <div class="campo">
                    <? if($campo->mostrar_nombre): ?>
                        <strong><?= $campo->nombre ?></strong>
                    <? endif ?>
                    <p><?= character_limiter(strip_tags($campo->contenido->productoCampoRelContenido),100); ?></p>
                </div>
            <?php endforeach; ?>

            <a class="small button" href="<?=base_url()?><?=$diminutivo?>/<?=$paginaCatalogoUrl?>/<?=$producto->categoriaUrl?>/<?=$producto->productoUrl?>"><?=$this->lang->line('ui_read_more')?></a>
        </div>
    <? endforeach ?>
    <?=$pagination?>
</div>