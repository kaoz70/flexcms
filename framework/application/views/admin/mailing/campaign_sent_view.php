<h2><?=$window_title?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<p class="error">Esta campa&ntilde;a ya fue enviada</p>

			<div class="input">
				<label for="subject">Asunto del email</label>
				<span><?=$subject?></span>
			</div>

			<div class="input">
				<label for="from_name">From name</label>
				<span><?=$from_name?></span>
			</div>

			<div class="input">
				<label for="from_email">From email</label>
				<span><?=$from_email?></span>
			</div>

			<div class="input">
				<label for="list_id">Listado de destinatarios</label>
				<? foreach ($lists as $list): ?>
					<? if($list_id ===  $list['id']): ?>
					<span><?=$list['name']?></span>
					<? endif ?>
				<? endforeach ?>
			</div>

		</div>
	</div>

	<div class="field">
		<div class="header">Resumen</div>
		<div class="content">

			<div class="input">
				<label for="subject">Emails enviados</label>
				<span><?=$summary['emails_sent']?></span>
			</div>

			<div class="input">
				<label for="subject">Reportes de abuso</label>
				<span><?=$summary['abuse_reports']?></span>
			</div>

			<div class="input">
				<label for="subject">Emails abiertos</label>
				<span><?=$summary['opens']?></span>
			</div>

			<div class="input">
				<label for="subject">Emails abiertos &uacute;nicos</label>
				<span><?=$summary['unique_opens']?></span>
			</div>

			<div class="input">
				<label for="subject">Clicks</label>
				<span><?=$summary['clicks']?></span>
			</div>

			<div class="input">
				<label for="subject">Clicks &uacute;nicos</label>
				<span><?=$summary['unique_clicks']?></span>
			</div>

		</div>
	</div>

</div>

<a href="admin/mailchimp/campaign/preview/<?=$id?>" class="nivel3 ajax importante boton n1" >Ver email enviado</a>