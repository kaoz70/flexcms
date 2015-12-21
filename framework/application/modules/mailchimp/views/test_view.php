<h2>Prueba de env&iacute;o<a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 400px;">

	<p class="error">Pruebas enviadas: <?=$tests_sent?> / <?=$tests_remain + $tests_sent?></p>

	<?= form_open('admin/mailing/' . $link, array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				<label for="emails">Enviar prueba a:</label>
				<input type="text" name="emails" id="emails" value="" class="required" />
				<p>Para m&uacute;ltiples emails, sep&aacute;relos por coma (,)</p>
			</div>

			<div class="input">
				<label for="email_type">Tipo de correo</label>
				<select name="email_type" id="email_type">
					<option value="html">HTML</option>
					<option value="text">texto</option>
				</select>
			</div>

		</div>
	</div>

	<?= form_close(); ?>
	
</div>

<? if ($tests_sent < $tests_remain + $tests_sent): ?>
<a href="<?= $link; ?>" class="guardar boton no_sort importante n1" >Enviar</a>
<? endif ?>