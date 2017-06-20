<style>
    .main_content > h2.titulo {
        display: none;
    }
</style>

<h2 class="nombre"><?=$producto->categoria->productoCategoriaNombre?> / <strong><?=$producto->productoNombre?></strong></h2>
<a class="regresar" href="<?=$regresarCatalogo?>"><?=$this->lang->line('ui_back')?></a>

<div id="detalle" class="row">

    <div class="column small-12">
        <? foreach($producto->imagenes as $row):?>
            <? if(count($row->contenido) != 0): ?>
                <div class="gal_wrapper">
                    <div class="galeria">
                        <? if($row->labelHabilitado == 's'): ?>
                            <span class="label"><?=$row->label?></span>
                        <? endif;?>
                        <? foreach($row->contenido as $imagen):?>
                            <div>
                                <? if($imagen->productoImagenTexto): ?>
                                    <div class="info"><span>»</span> <?=$imagen->productoImagenTexto?></div>
                                <? endif;?>
                                <img src="<?= base_url() ?>assets/public/images/catalog/gal_<?=$producto->productoId;?>_<?=$imagen->productoImagenId?>_big.<?=$imagen->productoImagen?>" alt="<?=$imagen->productoImagenNombre?>" />
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>

            <? endif ?>
        <? endforeach; ?>
    </div>

    <div class="column <?=$producto->videos && $producto->videos[0]->videos ? 'large-6' : ''?>">

        <div class="addthis_sharing_toolbox"></div>

        <? foreach($producto->campos as $row):?>
            <? if($row->contenido != ''): ?>
                <div class="campo <?= $row->clase?>">
                    <? if($row->contenido != ''): ?>
                        <? if($row->labelHabilitado): ?>
                            <div class="label"><?=$row->label?></div>
                        <? endif;?>
                        <div class="info texto"><?=$row->contenido->productoCampoValor?></div>
                    <? endif ?>
                </div>
            <? endif ?>
        <? endforeach; ?>
    </div>

    <? if($producto->videos && $producto->videos[0]->videos): ?>
        <div class="column large-6">
            <? foreach ($producto->videos as $key => $row): ?>
                <? if (count($row->videos) != 0): ?>

                    <div class="videos">
                        <div class="flex-video">
                            <div id="videoPlayer-<?=$key?>">You need Flash player 8+ and JavaScript enabled to view this video.</div>
                        </div>
                        <? if(count($row->videos) > 1): ?>
                            <div class="gal_videos">
                                <? foreach ($row->videos as $video): ?>
                                    <a href="http://www.youtube.com/v/<?=$video->productoVideo?>?version=3&amp;hl=es_ES&amp;rel=0">
                                        <img width="120" height="90" data-videoId="<?=$video->productoVideo?>"
                                             src="http://img.youtube.com/vi/<?=$video->productoVideo?>/1.jpg">
                                    </a>
                                <? endforeach; ?>
                            </div>
                        <? endif ?>
                    </div>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            initVideoCarrousel('<?=$row->videos[0]->productoVideo?>', $('.gal_videos img'), "videoPlayer-<?=$key?>");
                        });
                    </script>

                <? endif ?>
            <? endforeach; ?>
        </div>
    <? endif ?>

    <? foreach($producto->precios as $row):?>
        <? if($row->contenido != ''): ?>
            <div class="campo <?= $row->clase?>">

                <? if($row->labelHabilitado): ?>
                    <div class="label"><?=$row->label?></div>
                <? endif;?>
                <strong>$<?=number_format($row->contenido, 2, '.', '')?></strong>

            </div>
        <? endif ?>
    <? endforeach; ?>

    <div class="row collapse">
        <div class="column medium-12 large-8">
            <div class="pedido">
                <?= form_open('ajax/cart/add', array('class' => 'pedido', 'id' => 'pedido', 'data-abide' => '')); ?>
                <? foreach ($producto->listado as $listado) : ?>
                    <? if($listado->mostrar_en_pedido): ?>
                        <input type="hidden" name="campo[<?=$listado->campo_id?>]" value="<?= array_key_exists(0, $listado->contenido) ? $listado->contenido[0]->productoCamposListadoPredefinidoTexto : ''?>">
                    <? endif ?>
                <? endforeach ?>
                <input type="hidden" name="cantidad" value="1">
                <input type="hidden" name="productoId" value="<?=$producto->productoId?>">
                <input type="hidden" name="idioma" value="<?=$diminutivo?>">
                <a class="add" href="#">AÑADIR A MIS PEDIDOS</a>
                </form>
            </div>
        </div>
    </div>

</div>