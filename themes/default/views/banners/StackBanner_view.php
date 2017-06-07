<script type="text/javascript">

    function stackGalleryReady(){
        //function called when component is ready to receive public method calls
        //console.log('stackGalleryReady');
    }

    function detailActivated(){
        //function called when prettyphoto (in this case) is being triggered (in which case slideshow if is on, automatically stops, then later when prettyphoto is closed, slideshow is resumed)
        //console.log('detailActivated');
    }

    function detailClosed(){
        //function called when prettyphoto (in this case) is closed
        //console.log('detailClosed');
    }

    function beforeSlideChange(slideNum){
        //function called before slide change (plus ORIGINAL! slide number returned)
        //(ORIGINAL slide number is slide number in unmodified stack from the bottom as slides are listed in html '.componentPlaylist' element, 1st slide from the bottom = 0 slide number, second slide from the bottom = 1 slide number, etc...)
        //console.log('beforeSlideChange, slideNum = ', slideNum);
    }

    function afterSlideChange(slideNum){
        //function called after slide change (plus ORIGINAL! slide number returned)
        //console.log('afterSlideChange, slideNum = ', slideNum);
    }

    jQuery(document).ready(function($) {

        //init component
        gallery = $('#banner_<?=$banner->bannerId?>').stackGallery({
            /* slideshowLayout: horizontalLeft, horizontalRight, verticalAbove, verticalRound */
            slideshowLayout: 'verticalAbove',
            /* slideshowDirection: forward, backward */
            slideshowDirection:'forward',
            /* controlsAlignment: rightCenter, topCenter */
            controlsAlignment:'rightCenter',
            /* fullSize: slides 100% size of the componentWrapper, true/false. */
            fullSize:true,
            /* slideshowDelay: slideshow delay, in miliseconds */
            slideshowDelay: 3000,
            /* slideshowOn: true/false */
            slideshowOn:true,
            /* useRotation: true, false */
            useRotation:true,
            /* swipeOn: enter slide number(s) for which you want swipe applied separated by comma (counting starts from 0) */
            swipeOn:'0,1,2,3,4'
        });

        //init prettyphoto
        $("#componentWrapper a[data-rel^='prettyPhoto']").prettyPhoto({theme:'pp_default',
            callback: function(){gallery.checkSlideshow2();}/* Called when prettyPhoto is closed */});

    });

	
</script>

<div class="content" style="position: relative; height: <?=$height?>px;">

    <div id="banner_<?=$banner->bannerId?>" class="componentWrapper <?=$banner_class?>">

        <!--
        Note: slides are stacked in order from bottom, so first slide in 'componentPlaylist' is a the bottom!
        (Their z-position is manipulated with z-indexes in jquery)
         -->

        <div class="componentPlaylist">

            <? foreach ($images as $image): ?>

                <div class="slide" >
                    <div class="scaler">
                        <img class='stack_img' src='<?=base_url()?>assets/public/images/banners/banner_<?=$image->bannerId?>_<?=$image->bannerImagesId?>.<?=$image->bannerImageExtension?>' width='500' height='250' alt=''/>
                        <div class='caption_list'>
                            <? foreach ($image->labels as $key => $label): ?>
                                <div data-type='caption' class='caption_one <?=$label->bannerCampoClase?>'><?=$label->bannerCamposTexto?></div>
                            <? endforeach ?>
                        </div>
                        <div class="slide_detail">
                            <a class="pp_link" href="<?=$image->bannerImagelink?>" target='_blank' ><img src="<?= base_url() ?>assets/public/banners/StackBanner/images/icons/link.png" width="30" height="30" alt="" /></a>
                        </div>
                    </div>
                </div>

            <? endforeach ?>

        </div>

        <!-- controls -->
        <div class="componentControls">
            <!-- next -->
            <div class="controls_next">
                <img src="<?= base_url() ?>assets/public/banners/StackBanner/images/icons/next.png" alt="" width="30" height="30"/>
            </div>
            <!-- toggle -->
            <div class="controls_toggle">
                <img src="<?= base_url() ?>assets/public/banners/StackBanner/images/icons/pause.png" alt="" width="30" height="30"/>
            </div>
            <!-- previous -->
            <div class="controls_previous">
                <img src="<?= base_url() ?>assets/public/banners/StackBanner/images/icons/prev.png" alt="" width="30" height="30"/>
            </div>
        </div>

    </div>
</div>


