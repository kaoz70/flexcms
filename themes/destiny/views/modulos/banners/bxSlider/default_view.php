<div class="content">
    <div class="banner_<?=$banner['bannerId']?> <?=$banner_class?>">
        <? foreach ($images as $image): ?>
            <div>
                <? if($image['bannerImageLink'] != ''): ?>
                <a href="<?=$image['bannerImageLink']?>">
                    <? endif ?>
                    <img src="<?=base_url()?>assets/public/images/banners/banner_<?= $image['bannerId'] ?>_<?= $image['bannerImagesId'] ?>.<?= $image['bannerImageExtension'] ?>"/>
                    <div class="texto">
                        <? foreach ($image['labels'] as $label): ?>
                            <div class="<?=$label->bannerCampoClase?>"><?=$label->bannerCamposTexto?></div>
                        <? endforeach ?>
                    </div>
                    <? if($image['bannerImageLink'] != ''): ?>
                </a>
            <? endif ?>
            </div>
        <? endforeach ?>
    </div>
</div>