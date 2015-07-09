<script type="text/javascript">
    $(document).ready(function () {

        var SliderEffects = [
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "4500,50,1,1,Blur,TopLeft",
            "1250,35,8,6,FlyOut,Random",
            "1250,35,8,6,FlyOut,DiagonalLeft",
            "1100,25,20,1,FlyOut,TopLeft",
            "1250,50,8,6,FadeOut,Random",
            "1250,50,8,6,FadeOut,DiagonalLeft",
            "1250,50,8,6,FadeOut,DiagonalLeft-Reverse",
            "1250,35,8,6,FadeOut,DiagonalLeft-ToCenter-Reverse",
            "1100,25,20,1,FadeOut,TopLeft",
            "1100,25,20,1,FadeOut,Chess",
            "1250,35,1,1,FadeOut,TopLeft",
            "1100,25,1,15,FadeOut,TopLeft-Reverse",
            "1100,25,1,15,FadeOut,ToCenter-Reverse",
            "1100,25,1,15,RoundOut,TopLeft",
            "1100,25,20,1,RoundOut,TopLeft",
            "1100,25,20,1,RoundOut,TopLeft-Reverse",
            "1250,35,8,6,RoundOut,Chess",
            "1250,35,8,6,RoundOut,DiagonalLeft",
            "1250,35,8,6,RoundOut,DiagonalLeft-Reverse",
            "1250,35,8,6,RoundOut,DiagonalLeft-ToCenter",
            "1250,35,8,6,Swap,TopLeft-Reverse",
            "1250,35,8,6,Swap,DiagonalLeft",
            "1250,35,8,6,Swap,DiagonalLeft-Reverse",
            "1250,35,8,6,Swap,DiagonalLeft-ToCenter",
            "1250,35,8,6,Swap,DiagonalLeft-ToCenter-Reverse",
            "1100,25,1,15,Swap,TopLeft",
            "1100,25,20,1,Swap,Chess",
            "1100,25,20,1,Swap,ToCenter",
            "1100,25,20,1,Swap,ToCenter-Reverse",
            "1100,25,1,1,Swap,TopLeft",
            "1250,50,8,6,Radial,Chess",
            "1250,50,8,6,Radial,DiagonalLeft",
            "1250,50,8,6,Radial,SpiralTo",
            "1300,65,20,1,Radial,TopLeft",
            "1300,65,20,1,Radial,TopLeft-Reverse",
            "1250,50,8,6,MiddleOut,Chess",
            "1250,50,8,6,MiddleOut,ToCenter-Reverse",
            "1250,50,8,6,MiddleOut,DiagonalLeft",
            "1250,50,8,6,MiddleOut,DiagonalLeft-Reverse",
            "1250,50,8,6,MiddleOut,SpiralTo",
            "1250,50,8,6,MiddleOut,SpiralTo-Reverse",
            "1250,35,8,6,MiddleOut,DiagonalLeft-ToCenter-Reverse",
            "1100,6,20,1,MiddleOut,TopLeft",
            "1100,6,20,1,MiddleOut,ToCenter-Reverse",
            "1100,6,20,1,MiddleOut,ToCenter",
            "1100,6,1,15,MiddleOut,TopLeft",
            "1100,6,1,15,MiddleOut,Chess",
            "1250,50,20,1,FlyInLeft,TopLeft",
            "1100,40,1,15,FlyInLeft,TopLeft",
            "1100,40,1,15,FlyInLeft,ToCenter-Reverse",
            "1000,40,8,6,OutBack,ToCenter",
            "1000,40,8,6,OutBack,ToCenter-Reverse",
            "1000,40,8,6,OutBack,Random",
            "1000,40,8,6,OutBack,DiagonalLeft",
            "1000,40,8,6,OutBack,SpiralTo",
            "1000,40,8,6,OutBack,SpiralTo-Reverse",
            "1000,40,20,1,OutBack,TopLeft",
            "1000,40,20,1,OutBack,TopLeft-Reverse",
            "1000,40,20,1,OutBack,Chess",
            "1000,40,20,1,OutBack,ToCenter",
            "1000,40,20,1,OutBack,ToCenter-Reverse",
            "1000,40,1,15,OutBack,TopLeft",
            "1450,40,8,6,Popup,TopLeft",
            "1450,40,8,6,Popup,DiagonalLeft",
            "1450,40,8,6,Popup,DiagonalLeft-Reverse",
            "1450,40,8,6,Popup,SpiralTo",
            "1450,40,8,6,Popup,SpiralTo-Reverse",
            "1200,35,20,1,Popup,TopLeft",
            "1200,35,20,1,Popup,TopLeft-Reverse",
            "1200,35,20,1,Popup,ToCenter-Reverse",
            "1200,35,1,15,Popup,TopLeft",
            "1200,35,1,15,Popup,TopLeft-Reverse",
            "1200,35,1,15,Popup,Chess",
            "1200,35,1,15,Popup,ToCenter",
            "1200,35,1,15,Popup,ToCenter-Reverse",
            "1200,35,1,15,Popup,Random",
            "1250,35,8,6,Popup,Snake",
            "1250,35,8,6,Popup,DiagonalLeft-ToCenter-Reverse",
            "1300,45,8,6,Mirror,Chess",
            "1300,45,8,6,Mirror,Random",
            "1300,45,8,6,Mirror,DiagonalLeft",
            "1300,45,8,6,Mirror,DiagonalLeft-Reverse",
            "1300,45,8,6,Mirror,SpiralTo-Reverse",
            "1400,35,20,1,Mirror,TopLeft",
            "1400,35,20,1,Mirror,ToCenter",
            "1400,35,20,1,Mirror,ToCenter-Reverse",
            "1250,35,8,6,Mirror,Snake",
            "1250,35,8,6,Mirror,DiagonalLeft-ToCenter-Reverse",
            "1250,35,8,6,DuoSide,Random",
            "1250,35,8,6,DuoSide,DiagonalLeft",
            "1250,35,8,6,DuoSide,DiagonalLeft-Reverse",
            "1400,25,1,15,DuoSide,TopLeft",
            "1400,25,1,15,DuoSide,TopLeft-Reverse",
            "1400,25,1,15,DuoSide,ToCenter-Reverse",
            "1400,25,20,1,DuoSide,TopLeft-Reverse",
            "1400,25,20,1,DuoSide,ToCenter-Reverse",
            "1400,25,20,1,DuoSide,Random",
            "600,35,8,6,Crossroad,Chess",
            "600,35,8,6,Crossroad,Random",
            "600,35,8,6,Crossroad,DiagonalLeft-Reverse",
            "800,45,20,1,Crossroad,TopLeft",
            "800,45,1,15,Crossroad,TopLeft",
            "700,40,8,6,Arc,DiagonalLeft",
            "1100,50,20,1,Arc,TopLeft",
            "1100,50,20,1,Arc,TopLeft-Reverse",
            "1100,50,20,1,Arc,Chess",
            "1100,50,1,15,Arc,TopLeft",
            "1350,45,8,6,Elastic,TopLeft",
            "1350,45,8,6,Elastic,Chess",
            "1350,45,8,6,Elastic,Random",
            "1350,45,8,6,Elastic,DiagonalLeft",
            "1350,45,8,6,Elastic,DiagonalLeft-Reverse",
            "1350,45,8,6,Elastic,SpiralTo",
            "1350,45,8,6,Elastic,SpiralTo-Reverse",
            "1400,40,20,1,Elastic,TopLeft",
            "1400,40,20,1,Elastic,ToCenter",
            "1400,40,20,1,Elastic,ToCenter-Reverse",
            "1400,40,1,15,Elastic,TopLeft",
            "1400,40,1,15,Elastic,ToCenter",
            "1400,40,1,15,Elastic,ToCenter-Reverse",
            "1250,35,8,6,Elastic,Snake",
            "1250,35,8,6,Elastic,DiagonalLeft-ToCenter-Reverse",
            "850,30,8,6,Fade,TopLeft",
            "850,30,8,6,Fade,Chess",
            "850,30,8,6,Fade,Random",
            "850,30,8,6,Fade,DiagonalLeft",
            "850,30,8,6,Fade,SpiralTo",
            "850,30,8,6,Fade,SpiralTo-Reverse",
            "1200,30,20,1,Fade,TopLeft",
            "1200,30,20,1,Fade,TopLeft-Reverse",
            "1200,30,20,1,Fade,Chess",
            "1200,30,20,1,Fade,ToCenter",
            "1200,30,20,1,Fade,ToCenter-Reverse",
            "1200,30,1,10,Fade,TopLeft",
            "1200,30,1,10,Fade,TopLeft-Reverse",
            "1200,30,1,10,Fade,Chess",
            "1200,30,1,10,Fade,ToCenter",
            "1200,30,1,10,Fade,ToCenter-Reverse",
            "1500,30,1,1,Fade,TopLeft"
        ];

        $('#banner_<?=$banner->bannerId?>').IAslider({
            SliderWidth:920,
            SliderHeight:390,
            AutoSwitch:true,
            AutoSwitchDelay:8000,
            RandomEffect:true,
            PreviewEnable: false,
            Effects:SliderEffects,
            AssetsLocation:'<?=base_url("assets/public/banners/IASlider/js")?>/'
        });
    });
</script>

<div id="banner_<?=$banner->bannerId?>" class="sliderCont <?=$banner_class?>">
    <div class="slider-top">
        <div class="st-left"></div>
        <div class="st-main"></div>
        <div class="st-right"></div>
    </div>
    <div class="slider-box">
        <div class="slider">

            <? foreach ($images as $image): ?>
                <img title="<?=$image->bannerImageName?>" src='<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>'/>
                <? if(count($image->labels) > 0): ?>
                <div class="description">

                    <? if($image->bannerImagelink != ''): ?>
                    <a href="#" class="descLink">
                    <? endif ?>

                    <? foreach($image->labels as $label) :?>
                        <? if($label->bannerCampoLabelHabilitado): ?>
                            <h1 class="descHeading"><?=$label->bannerCampoLabel?></h1>
                        <? endif ?>
                        <p class="descText"><?=$label->bannerCampoValor?></p>
                    <? endforeach ?>

                    <? if($image->bannerImagelink != ''): ?>
                    </a>
                    <? endif ?>

                </div>
                <? endif ?>
            <? endforeach ?>

        </div>
    </div>
    <div class="slider-bottom"></div>
    <div class="shadow"></div>
</div>