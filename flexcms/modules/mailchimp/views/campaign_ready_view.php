<h2><?=$window_title?> <a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="bottom: <?=$bottomMargin?>px">

	<div class="field">

		<div class="header <?=$is_ready ? 'ready' : 'not-ready'?>">Estado</div>
		<div class="content <?=$is_ready ? 'tick-large' : 'warn-large'?>">
			<?=$is_ready ? 'listo' : 'errores'?>
		</div>
	</div>

	<? foreach ($items as $item): ?>
	<div class="field">

		<div class="header"><?=$item['heading']?></div>
		<div class="content <?=$item['type']?>">
			<div class="input">
				<span><?=mailchimp_parse($item['details'], $list_id)?></span>
			</div>
		</div>
	</div>
	<? endforeach ?>

</div>

<?foreach($menu as $item): ?>
	<?=$item?>
<? endforeach ?>