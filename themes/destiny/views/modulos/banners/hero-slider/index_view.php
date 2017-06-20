<div class="row cd-hero banner_<?=$banner['bannerId']?> <?=$banner_class?>">
    <ul class="cd-hero-slider">

        <? foreach ($images as $key => $image): ?>

            <li class="<?= !$key ? 'selected' : '' ?>">

                <div class="row dragend-page">
                    <div class="column cd-half-width medium-7 large-9 image model">
                        <img
                            alt="<?= $image['bannerImageName'] ?>"
                            data-interchange="
                                [<?=base_url()?>assets/public/images/banners/banner_<?=$image['bannerId']?>_<?=$image['bannerImagesId']?>.<?=$image['bannerImageExtension']?>, (default)],
                                [<?=base_url()?>assets/public/images/banners/banner_<?=$image['bannerId']?>_<?=$image['bannerImagesId']?>_small.<?=$image['bannerImageExtension']?>, (banner_small)],
                                [<?=base_url()?>assets/public/images/banners/banner_<?=$image['bannerId']?>_<?=$image['bannerImagesId']?>_medium.<?=$image['bannerImageExtension']?>, (banner_medium)],
                                [<?=base_url()?>assets/public/images/banners/banner_<?=$image['bannerId']?>_<?=$image['bannerImagesId']?>.<?=$image['bannerImageExtension']?>, (banner_large))]"
                            />
                    </div>
                    <div class="column cd-full-width medium-5 large-3 detail target center-for-small">
                        <div>
                            <h2 class="cd-delay-1"><?= $image['bannerImageName'] ?></h2>
                            <div class="cd-delay-2 texto wow fadeInUp">
                                <? foreach ($image['labels'] as $label): ?>
                                    <div class="<?=$label->bannerCampoClase?>"><?=$label->bannerCamposTexto?></div>
                                <? endforeach ?>
                            </div>
                            <? if($image['bannerImageLink'] != ''): ?>
                                <a class="cd-delay-3 button full-width wow fadeInUp" data-wow-delay="200ms" href="<?=$image['bannerImageLink']?>"><?= lang('ui_read_more') ?></a>
                            <? endif ?>
                        </div>
                    </div>
                </div>

            </li>

        <? endforeach ?>
    </ul>

    <div class="row cd-slider-nav">

        <div class="column">
            <nav>
                <span class="cd-marker item-1"></span>
                <ul>
                    <? foreach ($images as $key => $image): ?>
                        <li class="<?= !$key ? 'selected' : '' ?>"><a href="#0">Slide <?=$key?></a></li>
                    <? endforeach ?>
                </ul>
            </nav>
        </div>

    </div>
</div>