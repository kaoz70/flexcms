<style>		
.peKenBurns {
	width: 959px;
    height: 400px;
}
</style>

<script>

$(function($){
	$(".banner_<?=$banner->bannerId?>").peKenburnsSlider({externalFont:true})
})
// for google fonts, handle captions sizing only after fonts are loaded
$(window).load(function() {
	$(".banner_<?=$banner->bannerId?>").each(function() {
		$(this).data("peKenburnsSlider").fontsLoaded()
	})
})
	
</script>

<div class="peKenBurns peNoJs banner_<?=$banner->bannerId?> <?=$banner_class?>" data-autopause="image" data-thumb="enabled" data-mode="kb" data-controls="over" data-shadow="enabled" data-logo="disabled">
	
	<? foreach ($images as $key => $image): ?>
		<? if($key == 0): ?>
		<div class="peKb_active" data-delay="5" data-thumb="<?=base_url() ?>assets/public/images/banners/banner_<?=$image -> bannerId ?>_<?=$image -> bannerImagesId ?>_thumb.<?=$image -> bannerImageExtension ?>">
		<? else: ?>
		<div data-delay="5" data-thumb="<?=base_url() ?>assets/public/images/banners/banner_<?=$image -> bannerId ?>_<?=$image -> bannerImagesId ?>_thumb.<?=$image -> bannerImageExtension ?>">
		<? endif ?>
		<img src="<?=base_url() ?>assets/public/images/banners/banner_<?=$image -> bannerId ?>_<?=$image -> bannerImagesId ?>.<?=$image -> bannerImageExtension ?>" alt="<?=$image -> bannerImageName ?>"/>
        <? if(count($image->labels)  > 0): ?>
            <h1><?=$image->labels[0]->bannerCampoValor ?></h1>
        <? endif ?>
		</div>
	<? endforeach ?>
	 
</div>	