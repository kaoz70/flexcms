<script type="text/javascript">

$(document).ready(function() {
	$("#banner_<?=$banner->bannerId?>").zoomShowcase({
		imageWidth : 520,
		imageHeight : 347,
		bannerWidth : 900,
		animationSpeed : 750,
		easing : "easeOutQuint",
		sideOpacity : 0.5,
		autoPlay : true,
		autoPlayDelay : 4000,
		randomizeItems : false,
		linkTarget : "_parent.",
		sideZoom : 0.75,
		backZoom : 0.5
	});
});
	
</script>

<div class="zoom-gallery <?=$banner_class?>" id="banner_<?=$banner->bannerId?>">

	<img src="<?=base_url()?>assets/public/banners/ZoomShowCase/img/preloader.gif" class="preloader" width="55" height="18" alt="preloader" />

	<ul>
		
		<? foreach ($images as $image): ?>
		<li title="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>">
			<ul class="align-right" title="<?= base_url() . $image->bannerImagelink?>">
				<?php 
					$count = 1;
					$height = 55;
				?>
				<? foreach ($image->labels as $label): ?>
				<li class="20x<?=$count*$height?>" style="background-color: #FFFFFF; color: #000000"><?=$label->bannerCamposTexto?></li>
				<? $count++ ?>
				<? endforeach ?>
			</ul>
		</li>
		<? endforeach ?>
	</ul>

	<!-- If JavaScript is disabled, we'll just display the first image -->

	<noscript>
		<div id="noscript">
			<img src="<?=base_url()?>assets/public/banners/ZoomShowCase/img/showcase/1.jpg" width="565" height="377" alt="$ Banner Rotator" />
		</div>
	</noscript>

</div>
