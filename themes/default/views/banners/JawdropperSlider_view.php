<div id="slider-holder">
	<div id="slider" class='jdslider banner_<?=$banner->bannerId?> <?=$banner_class?>'>
		<? foreach ($images as $image): ?>
		<? if($image->bannerImagelink != ''): ?>
		<a href="<?=$image->bannerImagelink?>">
		<? endif ?>
			<img src="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>"/>
			<span>
				<? foreach ($image->labels as $label): ?>
					<strong class="<?=$label->bannerCampoClase?>"><?=$label->bannerCamposTexto?></strong>
				<? endforeach ?>
			</span>
		<? if($image->bannerImagelink != ''): ?>
		</a>
		<? endif ?>
		<? endforeach ?>
	</div>
</div>
<script>
$(document).ready(function() {
	$('.banner_<?=$banner->bannerId?>').jdSlider({
		width : <?=$width?>,
		height : <?=$height?>,
		transitions : "blocksDiagonalIn, randomBlocks, randomSlicesVertical, randomSlicesHorizontal, sliceFade",
		showSelectors : true,
        showNavigation: true,
		randomTransitions : true,
		showCaption : true,
		delay: 8000
	});
});
</script> 