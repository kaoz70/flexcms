<div class="images <?=$cat_clase?>">
    <ul class="thumbs imagenes large-block-grid-5 small-block-grid-2">
        <?php foreach($imagenes as $image): ?>
        <li>
            <a href="<?= base_url() ?>assets/public/images/downloads/img_<?=$image->descargaId?>_lightbox.<?=$image->descargaArchivo?>" target="_blank" >
                <img src="<?= base_url() ?>assets/public/images/downloads/img_<?=$image->descargaId?>_thumb.<?=$image->descargaArchivo?>" alt="<?=$image->descargaNombre?>" />
                <h2><?=$image->descargaNombre?></h2>
            </a>
        </li>
        <?php endforeach;?>
    </ul>
</div>