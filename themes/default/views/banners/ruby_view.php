<div id='bk-rubyslider_<?=$banner->bannerId?>' class="bk-rubyslider <?=$banner_class?>">
    <ul>
        <? foreach ($images as $image): ?>
            <? if(count($image->labels) > 0): ?>
            <li data-item-desc='<?=$image->labels[0]->bannerCamposTexto?>' data-thumb='<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>_thumb.<?=$image->bannerImageExtension?>'>
            <? else: ?>
            <li data-thumb='<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>_thumb.<?=$image->bannerImageExtension?>'>
            <? endif ?>
                <img class='slide_img bk_ken_burns_img' src='<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>'/>
            </li>
        <? endforeach ?>
    </ul>
</div>

<script>
$(document).ready(function(){
    $('div#bk-rubyslider_<?=$banner->bannerId?>').slider_plugin({
        theme: 'classic',
        show_primary_buttons: false,
        timer_position: 'bottom-left'
    });

    $("a[rel^='prettyPhoto']").prettyPhoto({ social_tools: false });
});

</script> 