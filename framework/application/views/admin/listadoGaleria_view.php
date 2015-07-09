<h2><?=$txt_titulo?><a class="cerrar" href="#" >cancelar</a></h2>

<ul class="contenido_col sorteable listado_galeria" style="bottom: <?=$bottomMargin?>px" rel="<?=$url_rel?>">

	<fieldset id="upload-gallery">
		<div>
			<input class="fileselect" type="file" name="fileselect[]" />
			<div class="filedrag">o arrastre los archivos aquí</div>
		</div>
		<ul class="list galeria" id="<?=$list_id?>" style="overflow: hidden" data-sort="<?=$url_sort?>">

			<?php foreach($items as $item): ?>

				<li class="image drag" id="<?=$item[$idx_id];?>">
					<a class="modificar details <?=$nivel?>" href="<?=$url_modificar . '/' . $item[$idx_id];?>">
						<img src="<?= $url_path . $item[$idx_id] . '_admin.' . $item[$idx_extension] . '?' . time() ?>" />
						<span class="nombre"><span><?=$item[$idx_nombre]?></span></span>
					</a>
					<a href="<?=$url_eliminar . '/' . $item[$idx_id];?>" class="eliminar" >eliminar</a>
				</li>

			<?php  endforeach; ?>

		</ul>
	</fieldset>

</ul>

<script type="text/javascript">
    initSortables($('<?=$list_id?>'));
    upload.gallery('upload-gallery', '<?=$method?>', <?=$width?>, <?=$height?>, '<?=$nivel?>', '<?=$url_modificar?>', '<?=$url_eliminar?>');
</script>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>