<div class="content">
	<? foreach ($faq as $faq): ?>
	<div class="faq">
		<a class="mas" href="<?=base_url()?><?=$diminutivo?>/<?=$paginaFaqUrl?>#respuesta_<?=$faq->faqId?>/<?=$paginacionPaginaActual?>"><?=$faq->faqPregunta?></a>
	</div>
	<? endforeach ?>
	<a href="<?=base_url($diminutivo.'/'.$paginaFaqUrl)?>"><?= lang('ui_view_all') ?></a>
	<?=$pagination?>
</div>