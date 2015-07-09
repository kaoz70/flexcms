<div class="videos">

    <div class="flex-video">
        <div id="videoPlayer">You need Flash player 8+ and JavaScript enabled to view this video.</div>
    </div>

    <? if(count($videos) > 1): ?>
    <ul class="thumbs">
        <?php foreach($videos as $key => $value): ?>
            <? if($value->descargaArchivo != ''): ?>
                <li>
                    <a href="http://www.youtube.com/v/<?=$value->descargaArchivo?>?version=3&amp;hl=es_ES&amp;rel=0">
                        <img width="120" height="90" data-videoId="<?=$value->descargaArchivo?>" src="http://img.youtube.com/vi/<?=$value->descargaArchivo?>/1.jpg">
                    </a>
                </li>
            <? endif?>
        <?php endforeach;?>
    </ul>
    <? endif ?>

</div>

<script type="text/javascript">

    $(function(){
        var params = { allowScriptAccess: "always" };
        swfobject.embedSWF("http://www.youtube.com/v/<?=$videos[0]->descargaArchivo?>?version=3&amp;hl=es_ES&amp;rel=0&amp;enablejsapi=1",
            "videoPlayer", "576", "324", "8", null, null, params, null);
    });

    $(document).ready(function() {
        "use strict";
        $('.videos ul.thumbs li img').click(videoClickListener);
    });

    function videoClickListener(event){
        event.preventDefault();

        var id = $(event.currentTarget).attr('data-videoId');

        if (ytplayer) {
            console.log(id);
            ytplayer.loadVideoById(id);
        }

    }

    function onYouTubePlayerReady() {
        ytplayer = document.getElementById("videoPlayer");
    }

</script>