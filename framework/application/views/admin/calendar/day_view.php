<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div id="banner" class="contenido_col">

<?= form_open('admin/calendar/' . $link, array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input small">
				<label for="date">Fecha:</label>
				<input id="date" class="fecha required name" name="date" type="text" value="<?=$date?>"/>
			</div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">

            <div class="input small">
                <label for="class">clase</label>
                <input id="class" type="text" name="class" value="<?=$class; ?>" />
            </div>

		</div>
	</div>

	<input type="hidden" name="id" value="<?=$id;?>" />

<?= form_close(); ?>
</div>

<a class="nivel3 ajax boton n2" href="<?=base_url('admin/calendar/activities/'.$id)?>">Administrar Actividades</a>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="calendar/edit/" data-delete-url="calendar/delete/" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	initDayPicker();
</script>