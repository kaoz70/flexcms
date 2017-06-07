<div class="main_content <?=$moduleClass?>">
	<div>
		<div class="noticias">
			<?php foreach($html as $noticia): ?>
			<div class="container <?=$noticia->publicacionClase?>">
				<div class="nombre"><?=$noticia->publicacionNombre?></div>
				<div class="fecha"><?=$noticia->publicacionFecha?></div>
				<img src="<?= base_url() . 'assets/public/images/noticias/noticia_' . $noticia->publicacionId . '_medium.' . $noticia->publicacionImagen?>" alt="<?=$noticia->publicacionNombre?>" />
				<div class="texto"><?=character_limiter(strip_tags($noticia->publicacionTexto),40)?></div>
				<a href="<?= base_url() . $diminutivo . '/' . $pagina_url . '/' . $noticia->publicacionUrl ?>"><?=$this->lang->line('ui_view')?></a>
			</div>
			<?php endforeach;?>
            <?=$pagination?>
		</div>
	</div>
</div>