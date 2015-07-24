<h2><?=$window_title?> <a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?= form_open($link, array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="<?=$email?>" class="required name" />
			</div>

			<div class="input">
				<label for="merges[FNAME]">Nombre</label>
				<input type="text" name="merges[FNAME]" id="merges[FNAME]" value="<?=$merges['FNAME']?>" class="required" />
			</div>

			<div class="input">
				<label for="merges[LNAME]">Apellido</label>
				<input type="text" name="merges[LNAME]" id="merges[LNAME]" value="<?=$merges['LNAME']?>" class="required" />
			</div>

		</div>
	</div>

	<div class="field">
		<div class="header">Datos adicionales</div>
		<div class="content">

			<div class="input">
				<label for="email_type">Preferencia de email</label>
				<select name="email_type" id="email_type">
					<option <?=$email_type === 'html' ? 'selected' : ''?> value="html">HTML</option>
					<option <?=$email_type === 'text' ? 'selected' : ''?> value="text">texto</option>
				</select>
			</div>

			<? if($new): ?>
			<div class="input">
				<input checked type="checkbox" value="1" name="double_optin" id="double_optin">
				<label for="double_optin">Enviar correo de confirmaci&oacute;n</label>
				<p class="error"><strong>Desactivar solo si es necesario.</strong> Abusar de esto puede suspender su cuenta en Mailchimp.</p>
			</div>
			<? endif ?>

		</div>
	</div>


	
	<input id="list_id" type="hidden" name="list_id" value="<?=$id;?>" />

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" nivel="nivel2" data-unsubscribe-url="mailchimp/subscriber/unsubscribe/<?=$list_id?>/" modificar="mailchimp/subscriber/edit/<?=$list_id?>" eliminar="mailchimp/subscriber/delete/<?=$list_id?>" class="guardar boton importante mailchimp n1 no_sort <?=$new?>" ><?=$button_text?></a>