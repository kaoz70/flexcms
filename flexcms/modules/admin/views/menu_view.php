<h2><?php echo $titulo;?></h2>
<ul class="contenido_col sorteable" title="menu/reorganizarLinks" rel="menu">

<?php  if(count($result) > 0): ?>

	<?php foreach($result as $row): ?>

		<li class="listado drag" id="<?=$row['mnuId'];?>">
			<div class="mover">mover</div>
			<a class="nombre modificar nivel2" href="<?=base_url();?>admin/menu/modificarLink/<?=$row['mnuId'];?>"><?=$nombre[$row['mnuId']];?></a>
			<a href="<?=base_url();?>admin/menu/eliminarLink/<?=$row['mnuId'];?>" class="eliminar" >eliminar</a>
		</li>
		
	<?php  endforeach; ?>

<?php else: ?>
	<li class="none">no existe ningun link, cree uno con el boton de abajo</li>
<?php endif; ?>
</ul>
</div>
<a id="crear" class="nivel2 ajax" href="<?=base_url();?>admin/menu/nuevoLink"><?=$nuevo_menu_txt;?></a>