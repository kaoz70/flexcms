<div class="main_content">
	<div id="text">
		
		<ul class="enlaces">
			<?php foreach($enlaces as $key => $value): ?>
			<li class="<?=$value->enlaceClase?>">
				<? if($value->enlaceImagen != ''): ?>
				<a href="<?=$value->enlaceLink?>">
				<img src="<?=base_url()?>assets/public/images/enlaces/enlace_<?=$value->enlaceId?>_small.<?=$value->enlaceImagen?>" />
				</a>
				<? endif?>
				<a href="<?=$value->enlaceLink?>"><?=$value->enlaceTexto?></a>
			</li>
			<?php endforeach;?>
		</ul>
		
	</div>
</div>