<style type="text/css">
    .bk-diamondslider {
        width: <?=$banner->bannerWidth?>px;
        height: <?=$banner->bannerHeight?>px;
    }
</style>

<div class='bk-diamondslider banner_<?=$banner->bannerId?> <?=$banner_class?>'>
	<ul>
		<? foreach ($images as $image): ?>

	  	<li data-thumb="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>_thumb.<?=$image->bannerImageExtension?>" data-thumb-title="<?=$image->bannerImageName?>" data-title="<?= $image->labels[0]->bannerCamposTexto ?>"">
	  		<? if($image->bannerImagelink != ''): ?>
	  		<a href='<?=$image->bannerImagelink?>'>
	  		<? endif ?>
	  			<img class='slide_img' src='<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>' />
	  		<? if($image->bannerImagelink != ''): ?>
	  		</a>
	  		<? endif ?>
	  	</li>
	  	<? endforeach ?>
	</ul>
</div>

<script>

$(document).ready(function()
{															
			
	$('.banner_<?=$banner->bannerId?>').slider_plugin({
		theme: 'classic',
		timer_position: 'top-right',
		ken_burns_for_slides: false
	});
					   			   		   			   	   			   		   		   
});	

</script> 