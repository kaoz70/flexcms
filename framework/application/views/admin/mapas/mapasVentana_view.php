<h2><?php echo $txt_titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<ul class="contenido_col" rel="<?=base_url('admin/mapas')?>">


	<?php foreach($mapas as $mapa): ?>

		<li class="listado" id="<?=$mapa['mapaId'];?>">
			<a id="<?=$mapa['mapaId'];?>" class="nombre mapa seleccionar" href="<?=base_url();?>admin/mapas/modificar/<?=$mapa['mapaId'];?>"><span><?=$mapa['mapaNombre']?></span></a>
			<a href="<?=base_url();?>admin/mapas/eliminar/<?=$mapa['mapaId'];?>" class="eliminar" >eliminar</a>
		</li>
		
	<?php  endforeach; ?>

</ul>
<a id="crear" class="nivel4 ajax importante n1 boton" href="<?=base_url();?>admin/mapas/nuevo"><?=$txt_nuevo;?></a>