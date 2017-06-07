<div class="main_content <?=$moduleClass?>">
    <? foreach ($servicios as $servicio): ?>
        <div class="<?=$servicio->servicioClase?>">
            <h2><?=$servicio->servicioTitulo?></h2>
			<? if ($servicio->servicioImagen): ?>
				<img src="<?=base_url('assets/public/images/servicios/servicio_' . $servicio->servicioId . '_medium.' . $servicio->servicioImagen)?>" />
			<? endif ?>
            <div class="texto"><?=$servicio->servicioTexto?></div>

			<? foreach($servicio->imagenes as $img): ?>
				<img src="<?= base_url('assets/public/images/servicios/gal_' . $servicio->servicioId . '_' . $img->id . '_thumb.' . $img->extension) ?>">
			<? endforeach ?>

            <a href="<?=base_url($diminutivo.'/'.$paginaServiciosUrl.'/'.$servicio->servicioUrl)?>"><?=lang('ui_view')?></a>
        </div>
    <? endforeach ?>
	<?= $pagination ?>
</div>