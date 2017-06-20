<h2>Configuraci&oacute;n<a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion">

	<?= form_open('admin/catalog/config/update', array('class' => 'form')); ?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				<label for="menu_show_categories">Mostrar Categorias en el Men&uacute;</label>
				<select name="menu_show_categories">
					<option <?= $config['menu_show_categories'] === '1' ? 'selected' : '' ?> value="1">SI</option>
					<option <?= $config['menu_show_categories'] === '0' ? 'selected' : '' ?> value="0">NO</option>
				</select>
			</div>

			<div class="input">
				<label for="menu_show_products">Mostrar Productos en el Men&uacute;</label>
				<select name="menu_show_products">
					<option <?= $config['menu_show_products'] === '1' ? 'selected' : '' ?> value="1">SI</option>
					<option <?= $config['menu_show_products'] === '0' ? 'selected' : '' ?> value="0">NO</option>
				</select>
			</div>

		</div>
	</div>

	<?= form_close() ?>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/config/save">Guardar</a>
