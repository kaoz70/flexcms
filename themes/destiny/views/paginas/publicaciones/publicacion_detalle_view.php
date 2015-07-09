<div class="main_content noticia_detalle">

	<div class="fecha small">
		<div class="dia"><?=strftime("%d", strtotime($html->publicacionFecha))?></div>
		<div class="mes"><?=strftime("%b", strtotime($html->publicacionFecha))?></div>
	</div>

	<div class="title">
		<h2 class="nombre"><?=$html->publicacionNombre?></h2>
	</div>

	<div class="<?=count($imagenes) ? 'gallery' : 'single'?>">

		<? if($html->publicacionImagen != ''): ?>
			<div class="image">
				<img src="<?=base_url()?>assets/public/images/noticias/noticia_<?=$html->publicacionId;?>_big.<?=$html->publicacionImagen;?>" alt="<?=$html->publicacionNombre;?>" />
			</div>
		<? endif ?>

		<? foreach($imagenes as $imagen):?>
			<div class="image">
				<img src="<?=base_url()?>assets/public/images/noticias/noticia_<?=$imagen->publicacionId?>_<?=$imagen->publicacionImagenId?>_big.<?=$imagen->publicacionImagenExtension?>" />
			</div>
		<? endforeach; ?>

	</div>

	<div class="addthis_sharing_toolbox"></div>

	<div class="texto"><?=$html->publicacionTexto?></div>

	<? if($html->publicacionLink != ''): ?>
		<a href="<?=$html->publicacionLink?>">Link</a>
	<? endif ?>

</div>

