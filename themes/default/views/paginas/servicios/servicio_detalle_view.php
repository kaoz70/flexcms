<div class="main_content <?=$moduleClass?>">
    <div class="<?=$servicio->servicioClase?>">
        <h3><?=$servicio->servicioTitulo?></h3>
	    <img src="<?= base_url('assets/public/images/servicios/servicio_' . $servicio->servicioId . '_big.' . $servicio->servicioImagen) ?>" />
        <div class="texto"><?=$servicio->servicioTexto?></div>
    </div>
</div>