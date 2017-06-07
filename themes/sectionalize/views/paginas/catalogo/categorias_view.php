<ul id="categorias">
	<?php foreach($categorias as $row):?>
	<li id="cat_<?=url_title($row['productoCategoriaNombre'], 'underscore', TRUE);?>">
		<a href="<?=base_url() . $diminutivo . '/' . $pagina_url . '/' . $row['id'] . '/' . $row['productoCategoriaUrl'] ?>">
			<?php if($row['categoriaImagen'] != ''):?>
			<img src="<?=base_url()?>assets/public/images/catalog/cat_<?=$row['categoriaId']?>.<?=$row['categoriaImagen']?>" alt="<?=$row['productoCategoriaNombre'];?>" />
			<?php endif; ?>
			<span><?=$row['productoCategoriaNombre']?></span>
            <? if($row['productoCategoriaDescripcion'] != ''): ?>
			<div><?=$row['productoCategoriaDescripcion']?></div>
            <? endif ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
