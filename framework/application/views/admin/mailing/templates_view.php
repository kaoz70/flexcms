<h2>Templates<a class="cerrar" href="#" >cancelar</a></h2>

<ul id="themes" class="contenido_col listado_general">

	<?php foreach($user as $template): ?>

		<li class="listado theme">
			<a class="modificar <?=$select ? 'seleccionar' : '' ?> nivel3" href="<?=base_url('admin/mailing/modify_template/' . $template['id'])?>">

				<img src="<?=$template['preview_image']?>">

				<div class="nombre">
					<span><?=$template['name']?></span>
				</div>

			</a>
			<a href="<?=base_url('admin/mailing/delete_template/' . $template['id'])?>" class="eliminar" >eliminar</a>
		</li>
		
	<?php  endforeach; ?>
	
</ul>

<a href="<?=base_url('admin/mailing/create_template')?>" class="nivel3 ajax importante n1 boton">crear nuevo template</a>