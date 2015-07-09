<div id="crystal-gallery_<?=$banner->bannerId?>" style="width: <?=$width?>px; height: <?=$height?>px" class="crystal-gallery-container <?=$banner_class?>">
    <div title="Nature" class="crystal-category crystal-thumb-size-80x80">
        <? foreach ($images as $image): ?>
        <div class="crystal-photo">
            <div class="crystal-image" title="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>"></div>
            <div class="crystal-thumb" title="<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>_thumb.<?=$image->bannerImageExtension?>"></div>
            <div class="crystal-desc crystal-align-bottom-right crystal-desc-width-308">
                <? if($image->bannerImagelink != ''): ?>
                    <a href='<?=$image->bannerImagelink?>'>
                <? endif ?>
                <h2><?=$image->bannerImageName ?></h2>
                <? if(count($image->labels) > 0): ?>
                <div class="<?=$image->labels[0]->bannerCampoClase?>"><?=$image->labels[0]->bannerCamposTexto?></div>
                <? endif ?>
                <? if($image->bannerImagelink != ''): ?>
                    </a>
                <? endif ?>
            </div>
        </div>
        <? endforeach ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#crystal-gallery_<?=$banner->bannerId?>").crystalGallery({
            layout: "fixed",
            galleryLogo: null
        });
    });
</script>