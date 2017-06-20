<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/catalogo/subirVideoGaleriaProducto/'.$productoId.'/'.$productoArchivoId, $attributes);
?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="productoArchivoNombre">Nombre:</label>
				<input class="required name" id="productoArchivoNombre" name="productoArchivoNombre" type="text" value="<?=$productoArchivoNombre ?>"/>
			</div>
	
			<fieldset>
				<legend>Descripción</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoDescargaDescripcion"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoDescargaDescripcion" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoDescargaDescripcion?>"/>
					<? else: ?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoDescargaDescripcion" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

            <fieldset>
                <legend>Video Youtube</legend>
                <input name="productoArchivoExtension" type="text" value="<?=$productoArchivoExtension?>"/>
            </fieldset>
	
			<div class="input check">
				<input type="checkbox" name="productoArchivoEnabled" id="productoArchivoEnabled" <?= $productoArchivoEnabled; ?> />
				<label for="productoArchivoEnabled">Publicado</label>
			</div>
		</div>
	</div>

	<input id="productoVideo" type="hidden" name="productoVideo" value="<?=$productoArchivoExtension; ?>" />
	<input id="productoArchivoId" type="hidden" name="productoArchivoId" value="<?=$productoArchivoId; ?>" />
	<input id="productoId" type="hidden" name="productoId" value="<?=$productoId; ?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/file/edit/" data-delete-url="catalog/file/delete/" class="guardar boton importante n1 product_files video no_sort <?=$nuevo?>" ><?=$txt_boton; ?></a>