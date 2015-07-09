<h2>Modificar Listado <a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="bottom: 105px">

	<?= form_open('admin/mailing/modify_list', array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				<label for="name">Nombre del listado</label>
				<input type="text" name="name" id="name" value="<?=$name?>" class="required name" />
			</div>

		</div>
	</div>

	<div class="field">
		<div class="header">Valores por defecto</div>
		<div class="content">

			<div class="input">
				<label for="default_from_name">From name por defecto</label>
				<input type="text" name="default_from_name" id="default_from_name" value="<?=$default_from_name?>" class="required" />
			</div>

			<div class="input">
				<label for="default_from_email">From email por defecto</label>
				<input type="text" name="default_from_email" id="default_from_email" value="<?=$default_from_email?>" class="required" />
			</div>

			<div class="input">
				<label for="default_subject">Asunto por defecto</label>
				<input type="text" name="default_subject" id="default_subject" value="<?=$default_subject?>" />
			</div>

		</div>
	</div>

	<div class="field">
		<div class="header">Estad&iacute;sticas</div>
		<div class="content">

			<div class="input">
				<label for="default_from_name">Miembros:</label>
				<span><?=$stats['member_count']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Desuscritos:</label>
				<span><?=$stats['unsubscribe_count']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Promedio de suscripci&oacute;n:</label>
				<span><?=$stats['avg_sub_rate']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Promedio de desuscripci&oacute;n:</label>
				<span><?=$stats['avg_unsub_rate']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Tasa de apertura de email:</label>
				<span><?=$stats['open_rate']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Tasa de clics:</label>
				<span><?=$stats['click_rate']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Miembros desde env&iacute;o:</label>
				<span><?=$stats['member_count_since_send']?></span>
			</div>

			<div class="input">
				<label for="default_from_name">Desuscritos desde env&iacute;o:</label>
				<span><?=$stats['unsubscribe_count_since_send']?></span>
			</div>

		</div>
	</div>
	
	<input id="list_id" type="hidden" name="list_id" value="<?=$id;?>" />

	<?= form_close(); ?>
	
</div>

<a href="<?= base_url('admin/mailing/list_abuse/' . $id) ?>" nivel="nivel3" class="ajax nivel4 boton n3" >Reportes de abuso</a>
<a id="mailing_list_button" href="<?= base_url('admin/mailing/subscribers/' . $id) ?>" nivel="nivel3" class="ajax nivel4 boton importante n2" >Subscriptores</a>
<a href="<?= base_url('admin/mailing/modify_list') ?>" nivel="nivel2" class="guardar boton importante n1" >Modificar Listado</a>