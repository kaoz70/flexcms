<div class="main_content noticia_detalle <?=$moduleClass?>">
	<div>
		<div class="detalle">
    		<h2 class="nombre"><?=$html->publicacionNombre?></h2>
			<div class="fecha"><?=$html->publicacionFecha?></div>
			<div class="texto"><?=$html->publicacionTexto?></div>
		</div>
		
		<? if($html->publicacionImagen != ''): ?>
		<div class="imagen">
			<img src="<?=base_url()?>assets/public/images/noticias/noticia_<?=$html->publicacionId;?>_big.<?=$html->publicacionImagen;?>" alt="<?=$html->publicacionNombre;?>" />
		</div>
		<? endif ?>

        <? if(count($imagenes) > 0): ?>
            <ul class="galeria">
                <? foreach($imagenes as $imagen):?>
                    <li>
                        <a rel="lightbox[image]" href="<?=base_url()?>assets/public/images/noticias/noticia_<?=$imagen->publicacionId?>_<?=$imagen->publicacionImagenId?>_big.<?=$imagen->publicacionImagenExtension?>">
                            <img src="<?=base_url()?>assets/public/images/noticias/noticia_<?=$imagen->publicacionId?>_<?=$imagen->publicacionImagenId?>_thumb.<?=$imagen->publicacionImagenExtension?>" width="115" alt="89" />
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
        <? endif ?>

        <? if($html->publicacionLink != ''): ?>
            <a href="<?=$html->publicacionLink?>">Link</a>
		<? endif ?>

	</div>
	
</div>