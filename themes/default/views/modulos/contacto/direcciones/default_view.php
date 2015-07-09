<div class="content">
    <ul>
        <? foreach($direcciones as $direccion): ?>
            <li>
                <? if($direccion->contactoDireccionImagen != ''): ?>
                    <img src="<?=base_url('assets/public/images/contacto/dir_' . $direccion->contactoDireccionId . $imageSize . '.' . $direccion->contactoDireccionImagen)?>" alt="<?=$direccion->contactoDireccionNombre?>" />
                <? endif ?>
                <h4><?=$direccion->contactoDireccion?></h4>
            </li>
        <? endforeach ?>
    </ul>
</div>