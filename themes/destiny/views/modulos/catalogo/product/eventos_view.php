<div class="content">
    <div id="eventos">
        <? foreach ($productos as $producto): ?>
            <div class="evento">
                <a href="<?=base_url()?><?=$diminutivo?>/<?=$paginaCatalogoUrl?>/<?=$producto->categoria->id?>/<?=$producto->categoria->productoCategoriaUrl?>/<?=$producto->productoUrl?>">
                    <? if(array_key_exists(0, $producto->imagenes) && array_key_exists(0, $producto->imagenes[0]->contenido) && $producto->imagenes[0]->contenido[0]->productoImagen != ''): ?>
                        <img
                            src="<?= base_url() ?>assets/public/images/catalog/gal_<?=$producto->productoId;?>_<?=$producto->imagenes[0]->contenido[0]->productoImagenId?>_event.<?=$producto->imagenes[0]->contenido[0]->productoImagen?>"
                            alt="<?=$producto->productoNombre?>" />
                    <? endif ?>
                    <h3><span>»</span> <?=$producto->productoNombre?></h3>
                </a>
            </div>
        <? endforeach ?>
    </div>
    <?=$pagination?>
</div>