<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="width: 780px">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/articles/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				
				<fieldset>
					<legend>Título</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<div class="input small">
						<label for="<?=$idioma['idiomaDiminutivo']?>_articuloTitulo"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_articuloTitulo" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->articuloTitulo?>"/>
						<? else: ?>
							<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_articuloTitulo" type="text" value=""/>
						<? endif ?>
						</div>
					<? endforeach ?>
				</fieldset>
				
			</div>
			<div class="input">
				
				<fieldset>
					<legend>Contenido</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<label for="<?=$idioma['idiomaDiminutivo']?>_articuloContenido"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_articuloContenido" rows="20" cols="85"><?=$traducciones[$idioma['idiomaDiminutivo']]->articuloContenido?></textarea>
						<? else: ?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_articuloContenido" rows="20" cols="85"></textarea>
						<? endif ?>
						
						<script type="text/javascript">
							initEditor('<?=$idioma['idiomaDiminutivo']?>_editor');
						</script>
						
					<? endforeach ?>
				</fieldset>

			</div>
			
			<div class="input check">
				<input type="checkbox" name="habilitado" id="habilitado" <?= $habilitado; ?> />
				<label for="habilitado">Publicado</label>
			</div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">
			<div class="input small">
				<label for="articuloClase">Clase</label>
			<input name="articuloClase"  id="articuloClase" type="text" value="<?= $articuloClase; ?>" />
			</div>
		</div>
	</div>
	
	<input type="hidden" name="paginaId" value="<?=$paginaId;?>" />
	<input id="articuloId" type="hidden" name="articuloId" value="<?=$articuloId;?>" />
	
	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="article/edit/" data-delete-url="article/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>