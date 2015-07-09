<h2 class="nombre"><?=$producto->productoCategoriaNombre?> / <strong><?=$producto->productoNombre?></strong></h2>
<div id="detalle">



    <div class="col_left">
        <a rel="lightbox" href="<?=base_url()?>assets/public/images/catalog/prod_<?=$producto->productoId;?>_huge.<?=$producto->productoImagenExtension;?>">
            <img src="<?=base_url()?>assets/public/images/catalog/prod_<?=$producto->productoId;?>_medium.<?=$producto->productoImagenExtension;?>" alt="<?=$producto->productoNombre;?>" />
        </a>
        <div class="maximize"><?=$this->lang->line('ui_click_to_zoom')?></div>
    </div>

    <div class="col_middle">
        <? foreach($producto->imagenes as $row):?>
        <? if(count($row->imagenes) != 0): ?>
            <div class="galeria">
                <? if($row->labelHabilitado == 's'): ?>
                <span class="label"><?=$row->label?></span>
                <? endif;?>
                <? foreach($row->imagenes as $imagen):?>
                <a rel="lightbox[producto]" title="<?=$imagen->productoImagenTexto?>" href="<?= base_url() ?>assets/public/images/catalog/gal_<?=$producto->productoId;?>_<?=$imagen->productoImagenId?>.<?=$imagen->productoImagen?>">
                    <img src="<?= base_url() ?>assets/public/images/catalog/gal_<?=$producto->productoId;?>_<?=$imagen->productoImagenId?>_thumb.<?=$imagen->productoImagen?>" alt="<?=$imagen->productoImagenNombre?>" />
                </a>
                <? endforeach; ?>
            </div>
            <? endif ?>
        <? endforeach; ?>

        <? foreach ($producto->videos as $key => $row): ?>
            <? if (count($row->videos) != 0): ?>

                <div class="videos">
                    <h4 class="label"><?=$row->label?></h4>
                    <div class="flex-video">
                        <div id="videoPlayer-<?=$key?>">You need Flash player 8+ and JavaScript enabled to view this video.</div>
                    </div>
                    <? foreach ($row->videos as $video): ?>
                        <a href="http://www.youtube.com/v/<?=$video->productoVideo?>?version=3&amp;hl=es_ES&amp;rel=0">
                            <img width="120" height="90" data-videoId="<?=$video->productoVideo?>"
                                 src="http://img.youtube.com/vi/<?=$video->productoVideo?>/1.jpg">
                        </a>
                    <? endforeach; ?>
                </div>

                <script type="text/javascript">
                    $(document).ready(function () {
                        initVideoCarrousel('<?=$row->videos[0]->productoVideo?>', $('.galeria-vid img'), "videoPlayer-<?=$key?>");
                    });
                </script>

            <? endif ?>
        <? endforeach; ?>

        <? foreach($producto->audios as $audios):?>
            <div class="galeria">
                <? if($audios->labelHabilitado): ?>
                    <strong><?= $audios->label ?></strong>
                <? endif ?>
                <? foreach($audios->audios as $audio):?>
                    <audio controls>
                        <source src="<?=base_url('assets/public/audio/catalog/audio_' . $audio->productoId . '_' . $audio->productoAudioId . '.' . $audio->productoAudioExtension)?>" type="audio/mpeg">
                        <embed height="50" width="100" src="<?=base_url('assets/public/audio/catalog/audio_' . $audio->productoId . '_' . $audio->productoAudioId . '.' . $audio->productoAudioExtension)?>">
                    </audio>
                <? endforeach; ?>
            </div>
        <? endforeach; ?>

        <? if(count($producto->archivos) > 0): ?>
        <div class="archivos">
            <? foreach($producto->archivos as $row):?>
            <? if(count($row->archivos) > 0): ?>
                <? if($row->labelHabilitado): ?>
                    <h3 class="label"><?=$row->label?></h3>
                    <? endif;?>
                <ul>
                    <? foreach($row->archivos as $archivo):?>
                    <? if($archivo->productoDescargaArchivo != ''): ?>
                        <li class="<?= $row->clase ?>">
                            <a target="_blank" href="<?=base_url('assets/public/files/catalog/prod_'.$producto->productoId.'/'.$archivo->productoDescargaArchivo)?>"><?=$archivo->productoDescargaArchivo?></a>
                        </li>
                        <? endif ?>
                    <? endforeach ?>
                </ul>
                <? endif ?>
            <? endforeach; ?>
        </div>
        <? endif ?>

        <? foreach($producto->campos as $row):?>
        <? if($row->contenido != ''): ?>
            <div class="campo <?= $row->clase?>">
                <? if($row->contenido != ''): ?>
                    <? if($row->labelHabilitado): ?>
                    <div class="label"><?=$row->label?></div>
                    <? endif;?>
                    <div class="info"><?=$row->contenido?></div>
                <? endif ?>
            </div>
            <? endif ?>
        <? endforeach; ?>

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

        <? if(count($producto->listado) > 0): ?>
            <div class="archivos">
                <? foreach($producto->listado as $row):?>
                    <? if(count($row->listado) > 0): ?>
                        <? if($row->labelHabilitado): ?>
                            <h3 class="label"><?=$row->label?></h3>
                        <? endif;?>
                        <ul class="listado">
                            <? foreach($row->listado as $listado):?>
                                <? if($listado->productoCamposListadoPredefinidoTexto != ''): ?>
                                    <li class="<?= $listado->productoCamposListadoPredefinidoClase ?>"><?=$listado->productoCamposListadoPredefinidoTexto?></li>
                                <? endif ?>
                            <? endforeach ?>
                        </ul>
                    <? endif ?>
                <? endforeach; ?>
            </div>
        <? endif ?>

    </div>
    <? if(!empty($pagPedidos)): ?>
    <div class="col_right">
        <div class="campo condiciones">
            <div class="label"><?=$this->lang->line('ui_cart_conditions_title')?></div>
            <div class="info">
                <?=$this->lang->line('ui_cart_conditions_text')?>
            </div>
        </div>
        <div class="campo pedido">
            <div class="label"><?=$this->lang->line('ui_cart_add')?>:</div>
            <?= form_open('ajax/cart/add', array('class' => 'pedido', 'id' => 'pedido', 'data-abide' => '')); ?>
                <div class="input">
                    <label for="cantidad"><?=$this->lang->line('ui_cart_units')?></label>
                    <input class="required validate-integer" id="cantidad" type="text" name="cantidad" value="1">
                </div>
                <input type="hidden" name="productoId" value="<?=$producto->productoId?>">
                <input type="hidden" name="idioma" value="<?=$diminutivo?>">
                <a class="add" href="#"><?=$this->lang->line('ui_cart_add')?></a>
            </form>
        </div>
    </div>
    <? endif ?>

</div>

<ul id="relacionados">
    <? foreach($producto->productosRelacionados as $rel):?>
        <li>
            <div class="producto">
                <h3 class="nombre"><?=character_limiter($rel->productoNombre,20);?></h3>
                <a href="<?=$link_base . $rel->productoUrl ?>"><img src="<?=base_url('assets/public/images/catalog/prod_' . $rel->productoId . '_small.' . $rel->productoImagenExtension)?>" alt="<?=$rel->productoNombre?>" /></a>
                <div class="detalle">

                    <?php foreach($rel->campos as $campo): ?>
                        <div class="campo">
                            <? if($campo->mostrar_nombre): ?>
                                <strong><?= $campo->nombre ?></strong>
                            <? endif ?>
                            <?= character_limiter(strip_tags($campo->contenido->productoCampoRelContenido),1000); ?>
                        </div>
                    <?php endforeach; ?>

                    <? foreach($rel->imagenes as $imagenes):?>
                        <div class="galeria imagenes">
                            <? if($imagenes->mostrar_nombre): ?>
                                <strong><?= $imagenes->nombre ?></strong>
                            <? endif ?>
                            <? foreach ($imagenes->contenido as $imagen): ?>
                                <? if($imagen->productoImagen != ''):?>
                                    <div>
                                        <img src="<?= base_url() ?>assets/public/images/catalog/gal_<?=$rel->productoId;?>_<?=$imagen->productoImagenId?>_thumb.<?=$imagen->productoImagen?>" alt="<?=$imagen->productoImagenNombre?>" />
                                    </div>
                                <? endif ?>
                            <? endforeach ?>
                        </div>
                        <? break ?>
                    <? endforeach; ?>

                    <? foreach($rel->videos as $videos):?>
                        <div class="galeria">
                            <? if($videos->mostrar_nombre): ?>
                                <strong><?= $videos->nombre ?></strong>
                            <? endif ?>
                            <? foreach($videos->contenido as $video):?>
                                <iframe width="304" height="171" src="http://www.youtube.com/embed/<?=$video->productoVideo?>?rel=0" frameborder="0" allowfullscreen></iframe>
                            <? endforeach; ?>
                        </div>
                    <? endforeach; ?>

                    <? foreach($rel->audios as $audios):?>
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

                    <? foreach($rel->archivos as $archivos):?>
                        <ul>
                            <? foreach($archivos->contenido as $archivo):?>
                                <li>
                                    <a target="_blank" href="<?=base_url('docs/catalog/prod_'.$rel->productoId.'/'.$archivo->productoDescargaArchivo)?>"><?=$archivo->productoDescargaArchivo?></a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <? endforeach; ?>

                    <? foreach($rel->tablas as $tabla):?>
                        <? if($tabla->mostrar_nombre): ?>
                            <strong><?= $tabla->nombre ?></strong>
                        <? endif ?>
                        <?=$tabla->contenido->productoCampoRelContenido?>
                    <? endforeach; ?>

                    <? foreach($rel->listado_predefinido as $listado):?>
                        <? if($listado->mostrar_nombre): ?>
                            <strong><?= $listado->nombre ?></strong>
                        <? endif ?>
                        <ul>
                            <? foreach ($listado->contenido as $item): ?>
                                <li class="<?=$item->productoCamposListadoPredefinidoClase?>"><?=$item->productoCamposListadoPredefinidoTexto?></li>
                            <? endforeach ?>
                        </ul>
                    <? endforeach; ?>

                    <? foreach ($rel->precios as $precio): ?>
                        <? if($precio->contenido->productoCampoRelContenido != ''): ?>
                            <div class="precio">
                                <? if ($precio->mostrar_nombre): ?>
                                    <strong><?= $precio->nombre ?></strong>
                                <? endif ?>
                                <strong>$<?= number_format((double)$precio->contenido->productoCampoRelContenido, 2, '.', '') ?></strong>
                            </div>
                        <? endif ?>
                    <? endforeach; ?>

                </div>
            </div>

        </li>
    <? endforeach; ?>
</ul>
