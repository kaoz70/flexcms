<?

//Remove the ?123456 (cache param)
$ext = preg_replace('/\?+\d{0,}/', '', $item['productoArchivoExtension']);
	
//Get the extension form images or files directory
$extension = pathinfo('./assets/public/images/catalog/' . $item['productoArchivoId'] . '_admin.' . $ext, PATHINFO_EXTENSION);
if(!$extension) {
	$extension = pathinfo('./assets/public/files/catalog/' . $ext, PATHINFO_EXTENSION);
}

if($extension && strlen($extension) < 9):

	if(!$extension) {
		$extension = $item['productoArchivoExtension'];
	}
	
	?>
	
	<? switch(mb_strtolower($extension)):

		//Images
		case 'jpg':
		case 'gif':
		case 'png':
		case 'jpeg': ?>

			<li class="image drag" id="<?=$item['productoArchivoId']?>">
				<a class="modificar details nivel2" href="<?=base_url('admin/catalog/file/edit/'.$item['productoArchivoId'])?>">
					<img src="<?=base_url()?>assets/public/images/catalog/gal_<?=$item['productoArchivoId']?>_admin.<?=$item['productoArchivoExtension'] . '?' . time()?>" />
					<div class="nombre"><span><?=$item['productoArchivoNombre']?></span></div>
				</a>
				<a href="<?=base_url('admin/catalog/file/delete/'.$item['productoArchivoId'])?>" class="eliminar" ></a>
			</li>

			<? break ?>
				
		<?//Audio
		case 'mp3':
		case 'ogg':
		case 'mwa':
		case 'wav': ?>
				
			<li class="audio drag" id="<?=$item['productoArchivoId']?>">
				<a class="modificar details nivel2" href="<?=base_url('admin/catalog/file/edit/'.$item['productoArchivoId'])?>" >
					<div class="file" style="width: <?=$final_width?>px; height: <?=$final_height?>px;"></div>
					<div class="nombre"><span><?=$item['productoArchivoNombre']?></span></div>
				</a>
				<a href="<?=base_url('admin/catalog/file/delete/'.$item['productoArchivoId'])?>" class="eliminar" ></a>
			</li>
				
			<? break ?>
						
		<?//Video
		case 'ogv':
		case 'avi':
		case 'wmv':
		case 'mov': ?>
						
			<li class="video drag" id="<?=$item['productoArchivoId']?>">
				<a class="modificar details nivel2" href="<?=base_url('admin/catalog/file/edit/'.$item['productoArchivoId'])?>">
					<div class="file" style="width: <?=$final_width?>px; height: <?=$final_height?>px;"></div>
					<div class="nombre"><span><?=$item['productoArchivoNombre']?></span></div>
				</a>
				<a href="<?=base_url('admin/catalog/file/delete/'.$item['productoArchivoId'])?>" class="eliminar" ></a>
			</li>

			<? break ?>
			
		<?//Other
		default: ?>
	
			<li class="default drag" id="<?= $item['productoArchivoId'] ?>">
				<a class="modificar details nivel2" href="<?= base_url('admin/catalog/file/edit/'.$item['productoArchivoId']) ?>">
					<div class="file" style="width: <?= $final_width ?>px; height: <?= $final_height ?>px;">
						<span class="extension"><?= mb_strtoupper($extension) ?></span>
					</div>
					<div class="nombre"><span><?= $item['productoArchivoNombre'] ?></span></div>
				</a>
				<a href="<?= base_url('admin/catalog/file/delete/'.$item['productoArchivoId']) ?>" class="eliminar" ></a>
			</li>
	
	<? endswitch ?>
	
<? else://Probably youtube video ?>
	
	<li class="video drag" id="<?= $item['productoArchivoId'] ?>">
		<a class="modificar details nivel2" href="<?= base_url('admin/catalog/file/edit/'.$item['productoArchivoId']) ?>">
			<img height="<?= $final_height ?>" src="http://img.youtube.com/vi/<?= $item['productoArchivoExtension'] ?>/1.jpg" />
			<div class="nombre"><span><?= $item['productoArchivoNombre'] ?></span></div>
		</a>
		<a href="<?= base_url('admin/catalog/file/delete/'.$item['productoArchivoId']) ?>" class="eliminar" ></a>
	</li>

<? endif ?>