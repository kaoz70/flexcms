<div class="content">
	<? foreach ($noticias as $noticia): ?>
	<div class="noticia">
		<h3><?=$noticia->publicacionNombre?></h3>
		<? if($noticia->publicacionImagen != ''): ?>
		<a href="<?=base_url()?><?=$diminutivo?>/<?=$paginaNoticiaUrl?>/<?=$noticia->publicacionUrl?>">
			<img src="<?=base_url()?>assets/public/images/noticias/noticia_<?=$noticia->publicacionId?><?=$imageSize?>.<?=$noticia->publicacionImagen?>" alt="<?=$noticia->publicacionNombre?>" />
		</a>
		<? endif ?>
		<div class="fecha"><?=$noticia->publicacionFecha?></div>
        <div class="texto"><?=character_limiter(strip_tags($noticia->publicacionTexto),120)?></div>
		<a class="mas" href="<?=base_url()?><?=$diminutivo?>/<?=$paginaNoticiaUrl?>/<?=$noticia->publicacionUrl?>"><?=$this->lang->line('ui_read_more')?></a>
	</div>
	<? endforeach ?>
	<?=$pagination?>
</div>