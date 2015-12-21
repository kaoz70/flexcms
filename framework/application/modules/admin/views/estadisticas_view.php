<h2><?=$titulo ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="<?=base_url('admin/configuracion')?>" style="width:600px; bottom: 0;">

	<div class="field">
		<div class="header">Cantidad total de visitas</div>
		<div class="content">
			<div><strong>Totales:</strong> <?=$visitas_total?></div>
			<div><strong>Hoy:</strong> <?=$visitas_day?></div>
			<div><strong>Mes:</strong> <?=$visitas_month?></div>
			<div><strong>Año:</strong> <?=$visitas_year?></div>
		</div>
	</div>

	<div class="field">
		<div class="header">10 Paginas más visitadas</div>
		<div class="content">
			<table>
				<tr><th>URL</th><th>Cantidad</th></tr>
				<? foreach ($paginas as $key => $value): ?>
				<tr><td><a class="external" target="_blank" href="<?=base_url($value->estadisticaUrl)?>"><?=$value->estadisticaUrl?></a></td><td><?=$value->count?></td></tr>
				<? endforeach ?>
			</table>
		</div>
	</div>

</div>

