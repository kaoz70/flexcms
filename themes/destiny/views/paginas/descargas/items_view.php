<div class="detalle">
    <h2><?=$title?> <? if($cat_link !== ''): ?><small><a href="<?=$cat_link?>" target="_blank">Visitar Sitio Web</a></small><? endif ?></h2>

    <ul class="large-block-grid-3 medium-block-grid-3 small-block-grid-2">
        <? foreach($descargas as $descarga): ?>
            <li>
                <? if($esImagen = preg_match('/jpg|jpeg|png|gif/', mb_strtolower($descarga['descargaArchivo']))): //Es imagen ?>
                    <a class="imagen" href="<?= base_url() ?>assets/public/images/downloads/img_<?=$descarga['descargaId']?>.<?=$descarga['descargaArchivo']?>"
                       target="_blank" >
                        <img src="<?= base_url() ?>assets/public/images/downloads/img_<?=$descarga['descargaId']?>_thumb.<?=$descarga['descargaArchivo']?>"
                             alt="<?=$descarga['descargaNombre']?>" />
                    </a>
                <? elseif (!$esImagen && strpos($descarga['descargaArchivo'], '.') === false): //Es video ?>
                    <a class="video" href="http://www.youtube.com/v/<?=$descarga['descargaArchivo']?>?version=3&amp;hl=es_ES&amp;rel=0">
                        <img data-videoId="<?=$descarga['descargaArchivo']?>" src="http://img.youtube.com/vi/<?=$descarga['descargaArchivo']?>/0.jpg">
                    </a>
                <? elseif (!$esImagen && strpos($descarga['descargaArchivo'], '.')): //Es archivo ?>
                    <a class="documento"
                       href="<?= base_url() ?>assets/public/files/downloads/<?=$descarga['descargaArchivo']?>"
                       target="_blank" ><?=lang('ui_download')?></a>
                <? endif ?>
                <h2><?=$descarga['descargaNombre']?></h2>
            </li>
        <? endforeach ?>
    </ul>

</div>