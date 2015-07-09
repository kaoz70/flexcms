<div class="main_content <?=$moduleClass?>">
	<div>
		<div class="noticias">
			<?php foreach($html as $noticia): ?>
			<div class="container item <?=$noticia->publicacionClase?>">
				<a href="<?= base_url() . $diminutivo . '/' . $pagina_url . '/' . $noticia->publicacionUrl ?>">
					<span class="fecha">
						<span class="dia"><?=strftime("%d", strtotime($noticia->publicacionFecha))?></span>
						<span class="mes"><?=strftime("%b", strtotime($noticia->publicacionFecha))?></span>
					</span>
				</a>
				<h2><a href="<?= base_url() . $diminutivo . '/' . $pagina_url . '/' . $noticia->publicacionUrl ?>"><?=$noticia->publicacionNombre?></a></h2>
				<div class="texto"><?=character_limiter(strip_tags($noticia->publicacionTexto),350)?></div>
				<a class="mas" href="<?= base_url() . $diminutivo . '/' . $pagina_url . '/' . $noticia->publicacionUrl ?>"><span><?=lang('ui_view')?></span> →</a>
			</div>
			<?php endforeach;?>
            <?=$pagination?>
		</div>
	</div>
</div>