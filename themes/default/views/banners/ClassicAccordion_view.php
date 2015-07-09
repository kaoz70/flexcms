<ul class="accordion banner_<?=$banner->bannerId?> <?=$banner_class?>">
	<? foreach ($images as $image): ?>
	<li>
		<? if($image->bannerImagelink != ''): ?>
		<a href="<?=$image->bannerImagelink?>">
		<? endif ?>
		<img src="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>"/>
		<? if($image->bannerImagelink != ''): ?>
		</a>
		<? endif ?>
		<div class="caption">
			<? foreach ($image->labels as $label): ?>
				<div class="<?=$label->bannerCampoClase?>"><?=$label->bannerCamposTexto?></div>
			<? endforeach ?>
		</div>
	</li>
	<? endforeach ?>
</ul>

<script>
$(document).ready(function(){
	$('.banner_<?=$banner->bannerId?>').classicAccordion({
		width:<?=$width?>, 
		height:<?=$height?>, 
		slideshow:true, 
		shadow:true, 
		alignType:'centerCenter', 
		closedPanelSize:40, 
		captionFadeDuration:50,
		captionWidth: 300,
		captionHeight: 100,
		captionTop: 400,
		captionLeft: 500
	});
});

</script> 