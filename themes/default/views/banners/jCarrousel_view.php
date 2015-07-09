<div class="content">
    <ul id="mycarousel_banner_<?=$banner->bannerId?>" class="jcarousel-skin-banner <?=$banner_class?>">
        <?php foreach($images as $key => $image): ?>
        <li>
            <a rel="lightbox[banner_<?=$banner->bannerId?>]" title="<?=$image->bannerImageName ?>" href="<?=base_url() ?>assets/public/images/banners/banner_<?=$image->bannerId ?>_<?=$image->bannerImagesId ?>.<?=$image->bannerImageExtension ?>">
                <img alt="<?=$image->bannerImageName ?>" src="<?=base_url() ?>assets/public/images/banners/banner_<?=$image->bannerId ?>_<?=$image->bannerImagesId ?>_thumb.<?=$image->bannerImageExtension ?>">
            </a>
        </li>
        <?php endforeach;?>
    </ul>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $('#mycarousel_banner_<?=$banner->bannerId?>').jcarousel({
            auto: 8,
            wrap: 'last',
            scroll: 5,
            initCallback: mycarousel_initCallback
        });

    });

</script>