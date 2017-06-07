<div class="content">

    <img class="mapa_fondo" src="<?= base_url('assets/public/images/mapas/mapa_' . $mapa->mapaId . '.' . $mapa->mapaImagen) ?>" alt="<?=$mapa->mapaNombre?>" />
    <ul class="mapa_ubicaciones" style="position: absolute; top: 0; width: 100%; height: 100%; max-width: <?= $imageWidth ?>px;">
        <? foreach ($mapa->ubicaciones as $key => $value): ?>
            <?
            //Convert fixed to responsive coords
            $x = ($value->mapaUbicacionX * 100) / $imageWidth;
            $y = ($value->mapaUbicacionY * 100) / $imageHeight;
            ?>
            <li id="ubicacion_<?=$key?>"
                data-id="<?=$key?>"
                class="<?=$value->mapaUbicacionClase?> <?= $key ? '' : 'selected'?>"
                style="position: absolute; top: <?=$y?>%; left: <?=$x?>%"><?=$value->mapaUbicacionNombre?></li>
        <? endforeach ?>
    </ul>
    <ul class="mapa_slides">
        <? foreach ($mapa->ubicaciones as $key => $value): ?>
            <? if($key === 0): ?>
                <li id="slide_<?=$key?>" data-id="<?=$key?>" >
            <? else: ?>
                <li id="slide_<?=$key?>" data-id="<?=$key?>" style="display: none" >
            <? endif ?>
                <h3>HPI <?=$value->mapaUbicacionNombre?></h3>
                <? if($value->mapaUbicacionImagen != ''): ?>
                    <img src="<?= base_url('assets/public/images/mapas/mapa_ubicacion_' . $value->mapaUbicacionId . '.' . $value->mapaUbicacionImagen) ?>"  />
                <? endif ?>
                <? if(isset($value->campos)): ?>
                    <div id="campos">
                        <? foreach ($value->campos as $campo): ?>
                            <div class="campo <?=$campo->mapaCampoClase?>">
                                <p><strong><?=$campo->mapaCampoLabel?>:</strong></p>
                                <p><?=$campo->mapaCampoTexto?></p>
                            </div>
                        <? endforeach ?>
                    </div>
                <? endif ?>

            </li>
        <? endforeach ?>
    </ul>
</div>