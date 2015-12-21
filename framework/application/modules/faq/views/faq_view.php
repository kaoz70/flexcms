<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 780px">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/faq/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input">
				<fieldset>
					<legend>Pregunta</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<div class="input small">
							<label for="<?=$idioma['idiomaDiminutivo']?>_faqPregunta"><?=$idioma['idiomaNombre']?></label>
							<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
								<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_faqPregunta" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->faqPregunta?>"/>
							<? else: ?>
								<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_faqPregunta" type="text" value=""/>
							<? endif ?>
						</div>
					<? endforeach ?>
				</fieldset>
			</div>
			<div class="input">
				<fieldset>
					<legend>Respuesta</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<label for="<?=$idioma['idiomaDiminutivo']?>_faqRespuesta"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_faqRespuesta" rows="20" cols="70"><?=$traducciones[$idioma['idiomaDiminutivo']]->faqRespuesta?></textarea>
						<? else: ?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" name="<?=$idioma['idiomaDiminutivo']?>_faqRespuesta" rows="20" cols="70"></textarea>
						<? endif ?>
						
						<script type="text/javascript">
							initEditor('<?=$idioma['idiomaDiminutivo']?>_editor');
						</script>
						
					<? endforeach ?>
				</fieldset>
			</div>
			<div class="input check">
				<input type="checkbox" name="faqHabilitado" id="faqHabilitado" <?= $faqHabilitado; ?> />
				<label for="faqHabilitado">Publicado</label>
			</div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">
			<div class="input small">
				<label for="faqClase">Clase</label>
				<input name="faqClase"  id="faqClase" type="text" value="<?= $faqClase; ?>" />
			</div>
		</div>
	</div>

    <input type="hidden" name="paginaId" value="<?=$paginaId?>">
	
	<?= form_close(); ?> 
	
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="faq/edit/<?=$faqId?>" data-delete-url="faq/delete/<?=$faqId?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	initEditor('<?=$idioma['idiomaDiminutivo']?>_editor');
</script>