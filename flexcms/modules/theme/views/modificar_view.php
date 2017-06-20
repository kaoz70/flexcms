<h2><?=$name?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 620px;">

	<table id="pageData">
		<tr>
			<td>

				<?php
				$attributes = array('class' => 'form');
				echo form_open('admin/themes/theme/edit', $attributes);
				?>

					<div class="field">
						<div class="header">General</div>
						<div class="content">

							<fieldset id="upload-image-logo">
								<legend>Logo</legend>
								<div>
									<input class="fileselect" type="file" name="fileselect[]" />
									<div class="filedrag">o arrastre el archivo aquí</div>
								</div>
								<ul class="list">
									<li class="image">
										<img src="<?=base_url('themes/' . $folder . '/images/logo.' . $config->logo_extension)?>" >
									</li>
								</ul>
							</fieldset>

							<div class="input small">
								<label for="primary_color">Color primario</label>
								<input id="primary_color" class="color-field" name="primary_color" type="text" value="<?=$config->primary_color?>"/>
							</div>

							<div class="input small">
								<label for="secondary_color">Color secundario</label>
								<input id="secondary_color" class="color-field" name="secondary_color" type="text" value="<?=$config->secondary_color?>"/>
							</div>

						</div>
					</div>

					<input type="hidden" name="theme" value="<?=$folder;?>" />
					<input type="hidden" class="name" value="<?=$name;?>" />
					<input id="logo_extension" type="hidden" name="logo_extension" value="<?=$config->logo_extension;?>" />

				<?= form_close(); ?>

				<div class="field">
					<div class="header">Informaci&oacute;n</div>
					<div class="content">

						<div class="input small">
							<label for="primary_color">Nombre</label>
							<span><?=$name?></span>
						</div>

						<div class="input small">
							<label for="primary_color">Autor</label>
							<span><?=$author?></span>
						</div>

						<div class="input small">
							<label for="primary_color">Versi&oacute;n</label>
							<span><?=$version?></span>
						</div>

						<div class="input small">
							<label for="primary_color">Descripci&oacute;n</label>
							<div><?=$description?></div>
						</div>

					</div>
				</div>

			</td>
			<td>

				<div class="field" id="backgrounds">
					<div class="header">Fondos</div>
					<div class="content">

						<fieldset id="upload-backgrounds">
							<div>
								<input class="fileselect" type="file" name="fileselect[]" />
								<div class="filedrag">o arrastre los archivos aquí</div>
							</div>
							<ul class="list galeria" id="theme_backgrounds" style="overflow: hidden" data-sort="<?=base_url('admin/themes/background/reorder/'.$folder)?>">

								<? foreach ($config->backgrounds as $background):?>

									<li class="image drag" id="<?=$background?>">
										<img src="<?= base_url('themes/' . $folder . '/images/fondos/' . $background) ?>" />
										<span class="nombre"><span><?=$background?></span></span>
										<a href="<?= base_url('admin/themes/background/delete/' . $folder . '/' . $background) ?>" class="eliminar" >eliminar</a>
									</li>

								<? endforeach ?>

							</ul>
						</fieldset>

					</div>
				</div>
			</td>
		</tr>

	</table>

</div>
<a href="<?= base_url('admin/theme/theme/update/') ?>" data-level="nivel2" class="guardar boton importante n1" >Modificar</a>

<script type="text/javascript">
    upload.image('upload-image-logo', 'logo_extension', '<?=base_url();?>admin/themes/logo/index/<?=$folder?>', 0, 0, false);
	upload.gallery('upload-backgrounds', 'theme/<?=$folder?>', 0, 0, '', '', '<?= base_url('admin/themes/background/delete/' . $folder . '/') ?>');
	initSortables($("theme_backgrounds"));
</script>