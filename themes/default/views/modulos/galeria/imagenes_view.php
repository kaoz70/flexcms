<div class="content">
	<ul class="galeria imagenes">
	<?php foreach($imagenes as $key => $value): ?>
	
		<li>
			<? if($value->descargaArchivo != ''): ?>
			<a href="<?=base_url()?>assets/public/images/downloads/img_<?=$value->descargaId?>_lightbox.<?=$value->descargaArchivo?>" title="<?=$value->descargaNombre?>">
				<img src="<?=base_url()?>assets/public/images/downloads/img_<?=$value->descargaId?><?=$imageSize?>.<?=$value->descargaArchivo?>" alt="<?=$value->descargaNombre?>">
			</a>
			<? endif?>
		</li>
	
	<?php endforeach;?>
	</ul>
	<?=$pagination?>
</div>