<? if(count($products) > 0): ?>
<ul id="productos">
	<?php foreach($products as $producto): ?>
	<li>
		<a href="<?=$link_base . $producto->categoriaId . '/' . $producto->productoId ?>"><img src="<?=base_url('assets/public/images/catalog/prod_' . $producto->productoId . '_medium.' . $producto->productoImagenExtension)?>" alt="<?=$producto->productoNombre?>" /></a>
		<div class="detalle">
			<h2 class="nombre"><?=character_limiter($producto->productoNombre,20);?></h2>
			<?php foreach($producto->camposValor as $key => $label): ?>
				<?if(isset($label['contenido']->productoCampoRelContenido) && $label['contenido']->productoCampoRelContenido != ''): ?>
					<? if($label['contenido']->productoCampoVerFiltro): ?>
					<div class="<?= $label['clase'] ?>">
						<? if($label['labelHabilitado']): ?>
						<strong><?= $label['label'] ?></strong>
						<? endif ?>
						<?= character_limiter($label['contenido']->productoCampoRelContenido,20); ?>
					</div>
					<?  endif ?>
				<?  endif ?>
			<?php endforeach; ?>
		</div>
		<a class="ver_producto" href="<?=$link_base . $producto->categoriaId . '/' . $producto->productoId ?>"><?=$this->lang->line('ui_view_product')?></a>
	</li>
	<?php endforeach; ?>
</ul>
<? else: ?>
<div class="error">No existen resultados para esta búsqueda, inténte con otros términos.</div>
<? endif ?>