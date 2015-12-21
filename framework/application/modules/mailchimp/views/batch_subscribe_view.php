<h2><?=$window_title?> <a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?= form_open($link, array('class' => 'form')); ?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div>
				<p>Se limpiar&aacute;n autom&aacute;ticamente instancias duplicadas de direcciones de correo electr&oacute;nico de la lista.</p>
			</div>

			<fieldset id="upload-file">
				<legend>Archivo CSV</legend>
				<div>
					<input class="fileselect" type="file" name="fileselect[]" />
					<div class="filedrag">o arrastre el archivo aquí</div>
				</div>
				<ul class="list"></ul>
			</fieldset>

			<div class="input check">
				<input type="checkbox" value="1" name="auto_update" id="auto_update">
				<label for="auto_update">Auto actualizar el listado existente (mas lento)</label>

				<p>Si encontramos un correo que ya est&aacute; en su lista, se puede actualizar la informaci&oacute;n
					del abonado por usted. Esto es especialmente &uacute;til si usted tiene una lista existente del que est&aacute; tratando
					de mantener sincronizado con una lista externa. La elecci&oacute;n de esta opci&oacute;n har&aacute;
					que la importacion sea mucho m&aacute;s lenta, por lo que s&oacute;lo act&iacute;velo si usted tiene
					nueva informaci&oacute;n sobre los perfiles que necesitan ser actualizados.</p>
			</div>

			<div class="input">
				<input type="checkbox" value="1" name="double_optin" id="double_optin">
				<label for="double_optin">Enviar correo de confirmaci&oacute;n</label>
				<p class="error">
					<strong>Descargo de responsabilidad</strong>
					Active para que la importaci&oacute;n <strong>SI</strong> env&iacute;e correos electr&oacute;nicos de confirmaci&oacute;n.
					Aseg&uacute;rese de que todos en su lista en realidad se registraron para ella y le otorgaron permiso
					para enviarles correo electr&oacute;nico.
				</p>
			</div>

		</div>

	</div>

	<input id="upload-mailchimp_import" type="hidden" name="mailchimp_import" value="" />

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" class="importar boton importante mailchimp_batch n1" ><?=$button_text?></a>

<script type="text/javascript">
	upload.file('upload-file', 'upload-mailchimp_import', '<?=base_url();?>admin/archivo/mailchimp_batch_import/<?=$list_id; ?>/', true);
</script>