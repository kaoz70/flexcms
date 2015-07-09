<ul id="slideshow" class="<?=$banner_class?>">
	<? foreach ($images as $image): ?>
	<li>
		<h3><?=$image->bannerImageName?></h3>
		<span><?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?></span>
		<p><?=$image->labels[0]->bannerCamposTexto ?></p>
		<? if($image->bannerImagelink != ''): ?>
	  	<a href='<?=$image->bannerImagelink?>'>
	  	<? endif ?>
			<img src="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>_thumb.<?=$image->bannerImageExtension?>" alt="<?=$image->bannerImageName?>" />
		<? if($image->bannerImagelink != ''): ?>
	  	</a>
	  	<? endif ?>
	</li>
	<? endforeach ?>
</ul>

<div id="ss_wrapper" class="<?=$banner_class?>">
	<div id="ss_fullsize">
		<div id="ss_imgprev" class="ss_imgnav" title="Previous Image"></div>
		<div id="ss_imglink"></div>
		<div id="ss_imgnext" class="ss_imgnav" title="Next Image"></div>
		<div id="ss_image"></div>
		<div id="ss_information">
			<h3></h3>
			<p></p>
		</div>
	</div>
	<div id="ss_thumbnails">
		<div id="ss_slideleft" title="Slide Left"></div>
		<div id="ss_slidearea">
			<div id="ss_slider"></div>
		</div>
		<div id="ss_slideright" title="Slide Right"></div>
	</div>
</div>

<script type="text/javascript">
$('#slideshow').css('display', 'none');
$('#ss_wrapper').css('display','block');
var slideshow=new TINY.slideshow("slideshow");
window.onload=function(){
	slideshow.auto=true;
	slideshow.speed=5;
	slideshow.link="ss_linkhover";
	slideshow.info="ss_information";
	slideshow.thumbs="ss_slider";
	slideshow.left="ss_slideleft";
	slideshow.right="ss_slideright";
	slideshow.scrollSpeed=4;
	slideshow.spacing=5;
	slideshow.active="#000";
	slideshow.init("slideshow","ss_image","ss_imgprev","ss_imgnext","ss_imglink");
}
</script>