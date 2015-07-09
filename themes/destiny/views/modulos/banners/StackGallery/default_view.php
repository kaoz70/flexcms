<div class="content" style="position: relative; height: <?=$height?>px;">

    <div id="banner_<?=$banner['bannerId']?>" class="componentWrapper <?=$banner_class?>">

        <!--
        Note: slides are stacked in order from bottom, so first slide in 'componentPlaylist' is a the bottom!
        (Their z-position is manipulated with z-indexes in jquery)
         -->

        <div class="componentPlaylist">

            <? foreach ($images as $image): ?>

                <div class="slide" >
                    <div class="scaler">
                        <img class='stack_img' src='<?=base_url()?>assets/public/images/banners/banner_<?=$image['bannerId']?>_<?=$image['bannerImagesId']?>.<?=$image['bannerImageExtension']?>' width='500' height='250' alt=''/>
                        <div class='caption_list'>
                            <? foreach ($image['labels'] as $key => $label): ?>
                                <div data-type='caption' class='caption_one <?=$label['bannerCampoClase']?>'><?=$label['bannerCamposTexto']?></div>
                            <? endforeach ?>
                        </div>
                        <div class="slide_detail">
                            <a class="pp_link" href="<?=$image['bannerImageLink']?>" target='_blank' ><img src="<?= base_url() ?>assets/public/banners/StackGallery/images/icons/link.png" width="30" height="30" alt="" /></a>
                        </div>
                    </div>
                </div>

            <? endforeach ?>

        </div>

        <!-- controls -->
        <div class="componentControls">
            <!-- next -->
            <div class="controls_next">
                <img src="<?= base_url() ?>assets/public/banners/StackGallery/images/icons/next.png" alt="" width="30" height="30"/>
            </div>
            <!-- toggle -->
            <div class="controls_toggle">
                <img src="<?= base_url() ?>assets/public/banners/StackGallery/images/icons/pause.png" alt="" width="30" height="30"/>
            </div>
            <!-- previous -->
            <div class="controls_previous">
                <img src="<?= base_url() ?>assets/public/banners/StackGallery/images/icons/prev.png" alt="" width="30" height="30"/>
            </div>
        </div>

    </div>
</div>


