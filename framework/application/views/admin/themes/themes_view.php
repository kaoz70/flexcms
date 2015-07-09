<h2>Temas<a class="cerrar" href="#" >cancelar</a></h2>

<ul id="themes" class="contenido_col listado_general">

	<?php foreach($themes as $theme): ?>

		<li class="listado theme" id="<?=$theme->folder?>">
			<a class="modificar nivel3" href="<?=base_url('admin/theme/modificar/' . $theme->folder)?>">

				<img src="<?=base_url('themes/' . $theme->folder . '/preview.jpg')?>">

				<div class="nombre">
					<span><?=$theme->name?></span>
				</div>

			</a>
			<a href="<?=base_url('admin/theme/eliminar/' . $theme->folder)?>" class="eliminar" >eliminar</a>
		</li>
		
	<?php  endforeach; ?>
	
</ul>