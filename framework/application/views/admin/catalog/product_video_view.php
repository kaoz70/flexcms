<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/catalogo/subirVideoGaleriaProducto/'.$productoId.'/'.$productoVideoId, $attributes);
?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<label for="productoVideoNombre">Nombre:</label>
				<input class="required name" id="productoVideoNombre" name="productoVideoNombre" type="text" value="<?=$productoVideoNombre ?>"/>
			</div>
	
			<fieldset>
				<legend>Descripción</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoVideoDescripcion"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoVideoDescripcion" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoVideoDescripcion?>"/>
					<? else: ?>
						<input name="<?=$idioma['idiomaDiminutivo']?>_productoVideoDescripcion" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

            <fieldset>
                <legend>Video Youtube</legend>
                <input name="productoVideo" type="text" value="<?=$productoVideo?>"/>
            </fieldset>
	
			<div class="input check">
				<input type="checkbox" name="productoVideoEnabled" id="productoVideoEnabled" <?= $productoVideoEnabled; ?> />
				<label for="productoVideoEnabled">Publicado</label>
			</div>
		</div>
	</div>

	<input id="productoVideoId" type="hidden" name="productoVideoId" value="<?=$productoVideoId; ?>" />
	<input id="productoId" type="hidden" name="productoId" value="<?=$productoId; ?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/edit_video/<?=$productoId?>/" data-delete-url="catalog/delete_video/<?=$productoId?>/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>