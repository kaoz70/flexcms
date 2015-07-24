<h2><?=$window_title?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="<?=$new ? '' : 'bottom: 71px;'?>">

	<?= form_open('admin/mailing/' . $link, array('class' => 'form')); ?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<div class="input">
				<label for="title">Nombre de la campa&ntilde;a</label>
				<input type="text" name="title" id="title" value="<?=$title?>" class="required name" />
			</div>

			<div class="input">
				<label for="subject">Asunto del email</label>
				<input type="text" name="subject" id="subject" value="<?=$subject?>" class="required" />
			</div>

			<div class="input">
				<label for="from_name">From name</label>
				<input type="text" name="from_name" id="from_name" value="<?=$from_name?>" class="required" />
			</div>

			<div class="input">
				<label for="from_email">From email</label>
				<input type="text" name="from_email" id="from_email" value="<?=$from_email?>" class="required" />
			</div>

			<div class="input">
				<label for="list_id">Listado de destinatarios</label>
				<select name="list_id">
					<? foreach ($lists as $list): ?>
						<option <?=$list_id ===  $list['id'] ? 'selected' : '' ?> value="<?=$list['id']?>"><?=$list['name']?></option>
					<? endforeach ?>
				</select>
			</div>

			<div class="input">
				<label for="template_id">Template</label>
				<select name="template_id">
					<? foreach ($templates as $template): ?>
						<option <?=$template_id ===  $template['id'] ? 'selected' : '' ?> value="<?=$template['id']?>"><?=$template['name']?></option>
					<? endforeach ?>
				</select>
			</div>

		</div>
	</div>

	<div class="field">
		<div class="header">Avanzado</div>
		<div class="content">

			<div class="input">
				<label for="analytics">Google Analytics Key</label>
				<input type="text" name="analytics" id="analytics" value="<?=$analytics?>" />
			</div>

		</div>
	</div>
	
	<input id="campaign_id" type="hidden" name="campaign_id" value="<?=$id;?>" />

	<?= form_close(); ?>
	
</div>

<? if(!$new): ?>
	<a href="<?=base_url('admin/mailchimp/campaign/preview/' . $id)?>" class="nivel3 ajax boton n2" >Previsualizar</a>
<? endif ?>
<a href="<?= $link; ?>" nivel="nivel2" modificar="mailchimp/campaign/edit/" eliminar="mailchimp/campaign/delete/" data-send-url="mailchimp/campaign/check/" class="mailchimp-campaign guardar boton no_sort importante n1 <?=$new?>" ><?=$button_text?></a>