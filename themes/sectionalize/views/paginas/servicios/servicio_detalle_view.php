<div class="main_content <?=$moduleClass?>">
    <div class="<?=$servicio->servicioClase?>">
        <h3><?=$servicio->servicioTitulo?></h3>
        <?=$servicio->servicioImagen?>
        <div class="texto"><?=$servicio->servicioTexto?></div>
		<? foreach($servicio->imagenes as $img): ?>
			<img src="<?= base_url('assets/public/images/servicios/gal_' . $servicio->servicioId . '_' . $img->id . '_thumb.' . $img->extension) ?>">
		<? endforeach ?>
    </div>
</div>