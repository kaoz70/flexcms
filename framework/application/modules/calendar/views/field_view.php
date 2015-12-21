<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?=form_open('admin/calendar/' . $link, array('class' => 'form'));?>
    
    <div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<fieldset>
				<legend>Nombre</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div class="input small">
					<label for="<?=$idioma['idiomaDiminutivo']?>_name"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_name" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->name?>"/>
					<? else: ?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_name" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>
			
			<div class="input">
				<label for="input_id">Tipo</label>
				<select id="input_id" name="input_id">
		        	<? foreach ($inputs as $row) : ?>
		       	  	    <option value="<?=$row->inputId;?>" <?=$input_id == $row->inputId ? 'selected' : '';?> ><?=$row->inputTipoContenido;?></option>
		     		<? endforeach; ?>
		       	</select>
			</div>
			
			<div class="input small">
				<label for="class">Clase</label>
				<input id="class" type="text" name="class" maxlength="250" value="<?=$class;?>" />
			</div>
			<div class="input check">
				<input id="enabled" name="enabled" type="checkbox" <?= $enabled ? 'checked' : ''; ?> />
				<label for="enabled">Habilitado</label>
			</div>
		</div>
	</div>
	
  <?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="calendar/edit_field/" data-delete-url="calendar/delete_field/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

  