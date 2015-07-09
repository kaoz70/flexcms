<div class="content video">
    <div class="flex-video">
        <div id="videoPlayer">You need Flash player 8+ and JavaScript enabled to view this video.</div>
    </div>
    <? if(count($videos) > 1): ?>
    <ul class="videos">
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
    $(document).ready(function () {
        initVideoCarrousel('<?=$videos[0]->descargaArchivo?>', $('.videos img'), "videoPlayer");
    });
</script>