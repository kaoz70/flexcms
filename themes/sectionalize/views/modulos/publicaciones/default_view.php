<div class="content">
	<? foreach ($noticias as $noticia): ?>
	<div class="item">
		<a href="<?=base_url()?><?=$diminutivo?>/<?=$paginaNoticiaUrl?>/<?=$noticia->publicacionUrl?>">
			<span class="fecha">
				<span class="dia"><?=strftime("%d", strtotime($noticia->publicacionFecha))?></span>
				<span class="mes"><?=strftime("%b", strtotime($noticia->publicacionFecha))?></span>
			</span>
		</a>
		<h3><a href="<?=base_url()?><?=$diminutivo?>/<?=$paginaNoticiaUrl?>/<?=$noticia->publicacionUrl?>"><?=character_limiter($noticia->publicacionNombre, 20)?></a></h3>
        <div class="texto"><?=character_limiter(strip_tags($noticia->publicacionTexto),120)?></div>
		<a class="mas" href="<?=base_url()?><?=$diminutivo?>/<?=$paginaNoticiaUrl?>/<?=$noticia->publicacionUrl?>">→</a>
	</div>
	<? endforeach ?>
	<?=$pagination?>
</div>