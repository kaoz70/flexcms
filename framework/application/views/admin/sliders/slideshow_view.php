<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div id="banner" class="contenido_col" style="bottom: 72px">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/noticias/' . $link, $attributes);

?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input small">
				<label for="bannerName">Título</label>
				<input class="required name" id="bannerName" name="bannerName" type="text" value="<?=$bannerName?>"/>
			</div>
			<div class="input check">
				<input type="checkbox" name="bannerEnabled" id="bannerEnabled" <?= $bannerEnabled ? 'checked="checked"' : '' ?> />
				<label for="bannerEnabled">Publicado</label>
			</div>
		</div>
	</div>
	
	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">

            <div class="input">
				<label for="bannerType">Tipo:</label>
				<select id="bannerType" name="bannerType">
					<?php foreach($banner_config as $row): ?>
						<option <?= $row->data->name === $bannerType ? 'selected="selected"' : '' ?> value="<?= $row->data->name?>"><?= $row->data->name?></option>
					<?php endforeach; ?>
				</select>
			</div>
			
			<div class="input small">
				<label for="bannerWidth">Ancho</label>
				<input class="required validate-integer" type="text" name="bannerWidth" id="bannerWidth" value="<?= $bannerWidth; ?>" />
			</div>
			
			<div class="input small">
				<label for="bannerHeight">Alto</label>
				<input class="required validate-integer" type="text" name="bannerHeight" id="bannerHeight" value="<?= $bannerHeight; ?>" />
			</div>

            <div class="input small">
                <label for="bannerClass">clase</label>
                <input id="bannerClass" type="text" name="bannerClass" value="<?=$bannerClass; ?>" />
            </div>

		</div>
	</div>

	<div class="field">
		<div class="header">Configuraci&oacute;n</div>

		<?php foreach($banner_config as $banner): ?>
			<div data-type="<?=$banner->folder?>" class="content banner_config" style="<?= $bannerType !== $banner->folder ? 'display: none' : ''?>">

				<div class="input">
					Versi&oacute;n de <?=$banner->data->name?>: <?=$banner->data->version?>
				</div>

				<? if($banner->data->docs) :?>
				<div class="input">
					<a class="external" target="_blank" href="<?=$banner->data->docs?>">Documentaci&oacute;n - Ayuda</a>
				</div>
				<? endif ?>

				<?php foreach($banner->data->config as $key => $row): ?>

					<? if(is_array($row)): ?>

						<div class="input medium">
							<label for="<?=$key?>"><?=$key?></label>
							<select id="<?=$key?>" name="config[<?=$banner->data->name?>][<?=$key?>]">
								<?php foreach($row as $value): ?>
									<? if(is_bool($value)): ?>
										<option <?= (array_key_exists($key, $config) AND $config[$key] === $value) ? 'selected="selected"' : '' ?> value="<?=is_bool($value) ? var_export($value) : $value?>"><?=is_bool($value) ? var_export($value) : $value?></option>
									<? else: ?>
										<option <?= (array_key_exists($key, $config) AND $config[$key] === $value) ? 'selected="selected"' : '' ?> value="<?=$value?>"><?=$value?></option>
									<? endif ?>
								<?php endforeach; ?>
							</select>
						</div>

					<? elseif (is_object($row)): ?>

						<div class="input">
							<label for="<?=$key?>" title="<?=$key?>"><?=$key?></label>
							<? foreach((array)$row as $key2 => $property): ?>

								<div class="input medium">
									<label for="<?=$key2?>" title="<?=$key2?>"><?=$key2?></label>
									<input class="required" type="text" name="config[<?=$banner->data->name?>][<?=$key?>][<?=$key2?>]" id="<?=$key2?>" value="<?= array_key_exists($key2, (array)$row) ? $row->$key2 : $property; ?>" />
								</div>

							<? endforeach ?>
						</div>

					<? else: ?>

						<div class="input medium">
							<label for="<?=$key?>" title="<?=$key?>"><?=$key?></label>
							<input class="required" type="text" name="config[<?=$banner->data->name?>][<?=$key?>]" id="<?=$key?>" value="<?= array_key_exists($key, $config) ? (is_null($config[$key]) ? 'null' : $config[$key]) : (is_null($row) ? 'null' : $row); ?>" />
						</div>

					<? endif ?>

				<?php endforeach; ?>

			</div>
		<?php  endforeach; ?>

	</div>

	<input id="bannerId" type="hidden" name="bannerId" value="<?=$bannerId;?>" />

<?= form_close(); ?>
</div>

<a class="nivel3 ajax boton_imagesBanner boton n2" href="<?=base_url('admin/sliders/image/index/'.$bannerId)?>">Administrar Imágenes</a>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="sliders/slider/edit/" data-delete-url="sliders/slider/delete/" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>