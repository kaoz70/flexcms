<h2><?=$txt_titulo?><a class="cerrar" href="#" >cancelar</a></h2>

<ul class="contenido_col listado_galeria" style="bottom: <?=$bottomMargin?>px" rel="<?=$url_rel?>">

	<fieldset id="upload-gallery">
		<div>
			<input class="fileselect" type="file" name="fileselect[]" />
			<div class="filedrag">o arrastre los archivos aquí</div>
		</div>
		<ul class="list galeria sorteable" id="<?=$list_id?>" style="overflow: hidden" data-sort="<?=$url_sort?>">

			<?php foreach($items as $item): ?>

				<?

				$width1 = 100;

				//Proportional resize
				$ratio = $width / $height;   // get ratio for scaling image
				if( $ratio > 1) {
					$final_width = $width1;
					$final_height = $width1/$ratio;
				}
				else {
					$final_width = $width1*$ratio;
					$final_height = $width1;
				}

				$data['item'] = $item;
				$data['extension'] = $item['productoArchivoExtension'];
				$data['final_width'] = $final_width;
				$data['final_height'] = $final_height;
				$this->load->view('admin/item/product_file_view', $data);

				?>

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