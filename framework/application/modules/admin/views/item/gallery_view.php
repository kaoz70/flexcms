<?

//Remove the ?123456 (cache param)
$ext = preg_replace('/\?+\d{0,}/', '', $item['descargaArchivo']);
	
//Get the extension form images or files directory
$extension = pathinfo('./assets/public/images/downloads/' . $item['descargaId'] . '_admin.' . $ext, PATHINFO_EXTENSION);
if(!$extension) {
	$extension = pathinfo('./assets/public/files/downloads/' . $ext, PATHINFO_EXTENSION);
}

if($extension && strlen($extension) < 6):

	if(!$extension) {
		$extension = $item['descargaArchivo'];
	}
	
	?>
	
	<? switch(mb_strtolower($extension)):

		//Images
		case 'jpg':
		case 'gif':
		case 'png':
		case 'jpeg': ?>

			<li class="image drag" id="<?=$item['descargaId']?>">
				<a class="modificar details nivel2" href="<?=base_url('admin/gallery/file/edit/'.$item['descargaId'])?>">
					<img src="<?=base_url()?>assets/public/images/downloads/img_<?=$item['descargaId']?>_admin.<?=$item['descargaArchivo'] . '?' . time()?>" />
					<div class="nombre"><span><?=$item['descargaNombre']?></span></div>
				</a>
				<a href="<?=base_url('admin/gallery/file/delete/'.$item['descargaId'])?>" class="eliminar" ></a>
			</li>

			<? break ?>
				
		<?//Audio
		case 'mp3':
		case 'ogg':
		case 'mwa':
		case 'wav': ?>
				
			<li class="audio drag" id="<?=$item['descargaId']?>">
				<a class="modificar details nivel2" href="<?=base_url('admin/gallery/file/edit/'.$item['descargaId'])?>" >
					<div class="file" style="width: <?=$final_width?>px; height: <?=$final_height?>px;"></div>
					<div class="nombre"><span><?=$item['descargaNombre']?></span></div>
				</a>
				<a href="<?=base_url('admin/gallery/file/delete/'.$item['descargaId'])?>" class="eliminar" ></a>
			</li>
				
			<? break ?>
						
		<?//Video
		case 'ogv':
		case 'avi':
		case 'wmv':
		case 'mov': ?>
						
			<li class="video drag" id="<?=$item['descargaId']?>">
				<a class="modificar details nivel2" href="<?=base_url('admin/gallery/file/edit/'.$item['descargaId'])?>">
					<div class="file" style="width: <?=$final_width?>px; height: <?=$final_height?>px;"></div>
					<div class="nombre"><span><?=$item['descargaNombre']?></span></div>
				</a>
				<a href="<?=base_url('admin/gallery/file/delete/'.$item['descargaId'])?>" class="eliminar" ></a>
			</li>

			<? break ?>
			
		<? //Other
		default: ?>
	
			<li class="default drag" id="<?= $item['descargaId'] ?>">
				<a class="modificar details nivel2" href="<?= base_url('admin/gallery/file/edit/'.$item['descargaId']) ?>">
					<div class="file" style="width: <?= $final_width ?>px; height: <?= $final_height ?>px;">
						<span class="extension"><?= mb_strtoupper($extension) ?></span>
					</div>
					<div class="nombre"><span><?= $item['descargaNombre'] ?></span></div>
				</a>
				<a href="<?= base_url('admin/gallery/file/delete/'.$item['descargaId']) ?>" class="eliminar" ></a>
			</li>
	
	<? endswitch ?>
	
<? else://Probably youtube video ?>
	
	<li class="video drag" id="<?= $item['descargaId'] ?>">
		<a class="modificar details nivel2" href="<?= base_url('admin/gallery/file/edit/'.$item['descargaId']) ?>">
			<img height="<?= $final_height ?>" src="http://img.youtube.com/vi/<?= $item['descargaArchivo'] ?>/1.jpg" />
			<div class="nombre"><span><?= $item['descargaNombre'] ?></span></div>
		</a>
		<a href="<?= base_url('admin/gallery/file/delete/'.$item['descargaId']) ?>" class="eliminar" ></a>
	</li>

<? endif ?>