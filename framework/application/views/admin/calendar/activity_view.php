<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div id="banner" class="contenido_col" style="width: 780px">

<?= form_open('admin/calendar/' . $link, array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input small">
				<label for="time">Hora:</label>
				<input id="time" class="fecha required name" name="time" type="text" value="<?=$time?>"/>
			</div>
			<div class="input small">
				<label for="place">Lugar:</label>
				<select id="place" name="place" >
					<? foreach($places as $p): ?>
						<option <?= $place === $p['mapaUbicacionId'] ? 'selected' : '' ?> value="<?=$p['mapaUbicacionId']?><"><?=$p['mapaUbicacionNombre']?></option>
					<? endforeach ?>
				</select>
			</div>
			<div class="input small">
				<label for="data">Contenido:</label>
				<textarea id="data_editor" class="editor" name="data" rows="20" cols="85"><?=$data?></textarea>
				<script type="text/javascript">
					initEditor('data_editor');
				</script>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="id" value="<?=$id;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="calendar/edit_activity/" data-delete-url="calendar/delete_activity/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	initTimePicker();
</script>