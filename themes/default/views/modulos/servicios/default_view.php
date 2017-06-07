<div class="content">
    <? foreach ($servicios as $servicio): ?>
    <div class="<?=$servicio->servicioClase?>">
        <h3><?=$servicio->servicioTitulo?></h3>
        <? if ($servicio->servicioImagen != ''): ?>
        <img src="<?=base_url('assets/public/images/servicios/servicio_'.$servicio->servicioId.$imageSize.'.'.$servicio->servicioImagen)?>" />
        <? endif ?>
        <div class="texto"><?=character_limiter(strip_tags($servicio->servicioTexto),120)?></div>
        <a href="<?=base_url($diminutivo.'/'.$paginaServiciosUrl.'/'.$servicio->servicioUrl)?>"><?=$this->lang->line('ui_view')?></a>
    </div>
    <? endforeach ?>
    <?=$pagination?>
</div>