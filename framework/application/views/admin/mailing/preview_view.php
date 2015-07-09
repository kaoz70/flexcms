<h2>Previsualizar <a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 1100px; bottom: 0">

	<table id="preview">
		<tr>
			<td width="70%">

				<div class="field">
					<div class="header">Escritorio</div>
					<div class="content">

						<div class="input">
							<label>To:</label>
							<span><?=$to_name?> &lt; email del destinatario &gt;</span>
						</div>

						<div class="input">
							<label>From:</label>
							<span><?=$from_name?></span>
						</div>

						<div class="input">
							<label>Reply-to:</label>
							<span><?=$from_email?></span>
						</div>

						<div class="input">
							<label>Subject:</label>
							<span><?=$subject?></span>
						</div>

						<iframe src="<?=base_url('admin/mailing/get_campaign_content/' . $id)?>"></iframe>

					</div>
				</div>

			</td>
			<td width="30%">

				<div class="field">
					<div class="header">M&oacute;vil</div>
					<div class="content">

						<div class="mobile">
							<iframe src="<?=base_url('admin/mailing/get_campaign_content/' . $id)?>"></iframe>
						</div>

					</div>
				</div>

			</td>
		</tr>
	</table>

</div>