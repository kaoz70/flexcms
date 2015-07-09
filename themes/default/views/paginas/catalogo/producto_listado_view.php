
<ul id="productos" class="large-block-grid-2">
    <?php foreach($products as $producto): ?>
    <li>
        <h3 class="nombre"><?=character_limiter($producto->productoNombre,20);?></h3>
        <a href="<?=$link_base . $producto->productoUrl ?>"><img src="<?=base_url('assets/public/images/catalog/prod_' . $producto->productoId . '_medium.' . $producto->productoImagenExtension)?>" alt="<?=$producto->productoNombre?>" /></a>
        <div class="detalle">

            <?php foreach($producto->campos as $campo): ?>
                <div class="campo">
                    <? if($campo->mostrar_nombre): ?>
                        <strong><?= $campo->nombre ?></strong>
                    <? endif ?>
                    <?= character_limiter(strip_tags($campo->contenido->productoCampoRelContenido),1000); ?>
                </div>
            <?php endforeach; ?>

            <? foreach($producto->imagenes as $imagenes):?>
                <div class="galeria imagenes">
                    <? if($imagenes->mostrar_nombre): ?>
                        <strong><?= $imagenes->nombre ?></strong>
                    <? endif ?>
                    <? foreach ($imagenes->contenido as $imagen): ?>
                        <? if($imagen->productoImagen != ''):?>
                            <div>
                                <img src="<?= base_url() ?>assets/public/images/catalog/gal_<?=$producto->productoId;?>_<?=$imagen->productoImagenId?>_thumb.<?=$imagen->productoImagen?>" alt="<?=$imagen->productoImagenNombre?>" />
                            </div>
                        <? endif ?>
                    <? endforeach ?>
                </div>
                <? break ?>
            <? endforeach; ?>

            <? foreach($producto->videos as $videos):?>
                <div class="galeria">
                    <? if($videos->mostrar_nombre): ?>
                        <strong><?= $videos->nombre ?></strong>
                    <? endif ?>
                    <? foreach($videos->contenido as $video):?>
                        <iframe width="304" height="171" src="http://www.youtube.com/embed/<?=$video->productoVideo?>?rel=0" frameborder="0" allowfullscreen></iframe>
                    <? endforeach; ?>
                </div>
            <? endforeach; ?>

            <? foreach($producto->audios as $audios):?>
                <div class="galeria">
                    <? if($audios->mostrar_nombre): ?>
                        <strong><?= $audios->nombre ?></strong>
                    <? endif ?>
                    <? foreach($audios->contenido as $audio):?>
                        <audio controls>
                            <source src="<?=base_url('assets/public/audio/catalog/audio_' . $audio->productoId . '_' . $audio->productoAudioId . '.' . $audio->productoAudioExtension)?>" type="audio/mpeg">
                            <embed height="50" width="100" src="<?=base_url('assets/public/audio/catalog/audio_' . $audio->productoId . '_' . $audio->productoAudioId . '.' . $audio->productoAudioExtension)?>">
                        </audio>
                    <? endforeach; ?>
                </div>
            <? endforeach; ?>

            <? foreach($producto->archivos as $archivos):?>
                <ul>
                    <? foreach($archivos->contenido as $archivo):?>
                        <li>
                            <a target="_blank" href="<?=base_url('assets/public/files/catalog/prod_'.$producto->productoId.'/'.$archivo->productoDescargaArchivo)?>"><?=$archivo->productoDescargaArchivo?></a>
                        </li>
                    <? endforeach; ?>
                </ul>
            <? endforeach; ?>

            <? foreach($producto->tablas as $tabla):?>
                <? if($tabla->mostrar_nombre): ?>
                    <strong><?= $tabla->nombre ?></strong>
                <? endif ?>
                <?=$tabla->contenido->productoCampoRelContenido?>
            <? endforeach; ?>

            <? foreach($producto->listado_predefinido as $listado):?>
                <? if($listado->mostrar_nombre): ?>
                    <strong><?= $listado->nombre ?></strong>
                <? endif ?>
                <ul>
                    <? foreach ($listado->contenido as $item): ?>
                        <li class="<?=$item->productoCamposListadoPredefinidoClase?>"><?=$item->productoCamposListadoPredefinidoTexto?></li>
                    <? endforeach ?>
                </ul>
            <? endforeach; ?>

            <? foreach($producto->precios as $precio):?>
                <div class="precio">
                    <? if($precio->mostrar_nombre): ?>
                        <strong><?= $precio->nombre ?></strong>
                    <? endif ?>
                    <strong>$<?=number_format((double)$precio->contenido->productoCampoRelContenido, 2, '.', '')?></strong>
                </div>
            <? endforeach; ?>

        </div>

        <div class="pedido">
            <?= form_open('ajax/cart/add', array('class' => 'pedido', 'id' => 'pedido', 'data-abide' => '')); ?>
                <? foreach ($producto->listado_predefinido as $listado) : ?>
                    <? if($listado->mostrar_en_pedido): ?>
                        <label for="campo_<?=$listado->campo_id?>"><?= $listado->nombre ?></label>
                        <select id="campo_<?=$listado->campo_id?>" name="campo[<?=$listado->campo_id?>]">
                            <? foreach ($listado->contenido as $item): ?>
                                <option value="<?=$item->productoCamposListadoPredefinidoTexto?>"><?=$item->productoCamposListadoPredefinidoTexto?></option>
                            <? endforeach ?>
                        </select>
                    <? endif ?>
                <? endforeach ?>
                <input type="text" name="cantidad" value="1">
                <input type="hidden" name="productoId" value="<?=$producto->productoId?>">
                <input type="hidden" name="idioma" value="<?=$diminutivo?>">
                <a class="add" href="#">AÑADIR A MIS PEDIDOS</a>
            </form>
        </div>

        <a class="ver_producto" href="<?=$link_base . $producto->productoUrl ?>"><?=$this->lang->line('ui_view_product')?></a>
    </li>
    <?php endforeach; ?>
</ul>
<?=$pagination?>