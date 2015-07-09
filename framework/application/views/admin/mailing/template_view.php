<h2><?=$window_title?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 765px;">

	<?= form_open('admin/mailing/' . $link, array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				<label for="name">Nombre del template</label>
				<input type="text" name="name" id="name" value="<?=$name?>" class="required name" />
			</div>

			<div class="input">
				<label for="html">Contenido HTML</label>
				<textarea id="mailchimp_editor" class="editor required" name="html" rows="20" cols="85"><?=htmlentities($html)?></textarea>

				<script type="text/javascript">
					initEditor('mailchimp_editor');
				</script>
			</div>

		</div>
	</div>

	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" nivel="nivel2" modificar="mailing/modify_template/" eliminar="mailing/delete_template/" class="guardar boton no_sort importante n1 <?=$new?>" ><?=$button_text?></a>