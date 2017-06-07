<div class="content">
    <ul>
	<?php foreach($enlaces as $key => $value): ?>
		<li class="<?=$value->enlaceClase?>">
			<? if($value->enlaceImagen != ''): ?>
			<a href="<?=$value->enlaceLink?>">
				<img src="<?=base_url()?>assets/public/images/enlaces/enlace_<?=$value->enlaceId?><?=$imageSize?>.<?=$value->enlaceImagen?>">
			</a>
			<? endif?>
			<a href="<?=$value->enlaceLink?>"><?=$value->enlaceTexto?></a>
		</li>
	<?php endforeach;?>
    </ul>
	<a class="leer" href="<?=base_url($diminutivo.'/'.$paginaEnlacesUrl)?>"><?=$this->lang->line('ui_view_all')?></a>
	<?=$pagination?>
</div>

