<style>
    .banner_<?=$banner['bannerId']?> {
        padding:30px 0;
        width: 100%;
    }
    .swiper-slide {
        width:auto;
        height:150px;
        background-size:cover;
        background-repeat:no-repeat;
        background-position:center;
        border-radius:5px;
        border-bottom:1px solid #555;
    }
    .reflection  {
        width: 100%;
        height: 15px;
        border-radius: 3px 3px 0 0;
        position: absolute;
        left: 0;
        bottom: -17px;
        background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(0,0,0,0.3)), to(rgba(0,0,0,0))); /* Safari 4+, Chrome */
        background-image: -webkit-linear-gradient(top, rgba(0,0,0,0.3), rgba(0,0,0,0)); /* Chrome 10+, Safari 5.1+, iOS 5+ */
        background-image:    -moz-linear-gradient(top, rgba(0,0,0,0.3), rgba(0,0,0,0)); /* Firefox 3.6-15 */
        background-image:      -o-linear-gradient(top, rgba(0,0,0,0.3), rgba(0,0,0,0)); /* Opera 11.10-12.00 */
        background-image:         linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0)); /* Firefox 16+, IE10, Opera 12.50+ */
    }
</style>

<div class="swiper-container banner_<?=$banner['bannerId']?> <?=$banner_class?>">
    <div class="swiper-wrapper">
        <? foreach ($images as $image): ?>
        <div class="swiper-slide" style="background-image:url(<?=base_url()?>assets/public/images/banners/banner_<?=$image['bannerId']?>_<?=$image['bannerImagesId']?>.<?=$image['bannerImageExtension']?>)">
            <? if($image['bannerImageLink'] != ''): ?>
            <a href="<?=$image['bannerImageLink']?>">Click</a>
            <? endif ?>
            <div class="reflection"></div>
        </div>
        <? endforeach ?>
    </div>
</div>