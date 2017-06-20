<div class="content banner clearfix">

    <div class="slider-banner-container">
        <div class="banner_<?=$banner['bannerId']?> <?=$banner_class?>">
            <ul class="slides">

                <? foreach ($images as $image): ?>

                    <li data-transition="random" data-slotamount="7" data-masterspeed="500" data-saveperformance="on" data-title="<?= $image['bannerImageName'] ?>">

                        <img
                            data-bgposition="center top"
                            data-bgrepeat="no-repeat"
                            data-bgfit="cover"
                            src="<?=base_url()?>assets/public/images/banners/banner_<?= $image['bannerId'] ?>_<?= $image['bannerImagesId'] ?>.<?= $image['bannerImageExtension'] ?>"/>

                        <div class="tp-caption dark-translucent-bg"
                             data-x="center"
                             data-y="bottom"
                             data-speed="600"
                             data-start="0">
                        </div>

                        <div class="tp-caption sfb fadeout large_white"
                             data-x="left"
                             data-y="180"
                             data-speed="500"
                             data-start="1000"
                             data-easing="easeOutQuad"><?= $image['bannerImageName'] ?>
                        </div>

                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption sfb fadeout large_white tp-resizeme hidden-xs"
                             data-x="left"
                             data-y="300"
                             data-speed="500"
                             data-start="1300"
                             data-easing="easeOutQuad"><div class="separator-2 light"></div>
                        </div>

                        <? foreach ($image['labels'] as $key => $label): ?>
                            <div class="<?=$label->bannerCampoClase?>"
                                 data-x="left"
                                 data-y="<?= $label->bannerCampoLabel ?>"
                                 data-speed="500"
                                 data-start="<?= 1300 + ($key * 300)?>"
                                 data-easing="easeOutQuad"><?=$label->bannerCamposTexto?>
                            </div>
                        <? endforeach ?>

                        <? if($image['bannerImageLink'] != ''): ?>
                            <div class="tp-caption sfb fadeout small_white text-center"
                                 data-x="left"
                                 data-y="430"
                                 data-speed="500"
                                 data-start="1600"
                                 data-easing="easeOutQuad"
                                 data-endspeed="600"><a href="<?=$image['bannerImageLink']?>" class="btn btn-default btn-animated">Leer m&aacute;s <i class="fa fa-arrow-right"></i></a>
                            </div>
                        <? endif ?>

                    </li>

                <? endforeach ?>

            </ul>

        </div>

    </div>

</div>