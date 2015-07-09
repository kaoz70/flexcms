<div class="main_content <?=$moduleClass?>">
    <? foreach ($servicios as $servicio): ?>
        <div class="<?=$servicio->servicioClase?>">
            <h3><?=$servicio->servicioTitulo?></h3>
	        <img src="<?= base_url('assets/public/images/servicios/servicio_' . $servicio->servicioId . '_medium.' . $servicio->servicioImagen) ?>" />
            <div class="texto"><?=$servicio->servicioTexto?></div>
            <a href="<?=base_url($diminutivo.'/'.$paginaServiciosUrl.'/'.$servicio->servicioUrl)?>"><?=$this->lang->line('ui_view')?></a>
        </div>
    <? endforeach ?>
</div>