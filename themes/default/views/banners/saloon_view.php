<div class="banner_<?=$banner->bannerId?> <?=$banner_class?>">

    <ul style="visibility:hidden">

        <? foreach ($images as $image): ?>
        <li data-delayoffset="4000">
            <img src="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>"/>
            <div  class="creative_layer">
                <? foreach ($image->labels as $label): ?>
                    <div class="<?=$label->bannerCampoClase?>" style="top:220px;left:550px;">
                        <?=$label->bannerCamposTexto?>
                    </div>
                <? endforeach ?>
            </div>
        </li>
        <? endforeach ?>

    </ul>

</div>

<div class="shadow"></div>

<script type="text/javascript">


    $(document).ready(function() {

        $('.banner_<?=$banner->bannerId?>').saloon({
            width:<?=$width?>,
            height:<?=$height?>,
            speed:1600,
            delay:5000,
            direction:"vertical",
            thumbs:"bottom",
            grab:"on",
            thumbsYOffset:20,
            thumbsXOffset:4,
            googleFonts:'Open+Sans:600,800',
            googleFontJS:'http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js'
        });
    });
</script>
