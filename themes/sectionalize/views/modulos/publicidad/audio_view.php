<div class="content">
    <audio id="audioplayer" preload controls loop style="width:424px;">
        <source src="<?=base_url()?>assets/public/files/publicidad/<?=$archivo?>">
    </audio>
    <script type="text/javascript">
        var audioTag = document.createElement('audio');
        if (!(!!(audioTag.canPlayType) && ("no" != audioTag.canPlayType("audio/mpeg")) && ("" != audioTag.canPlayType("audio/mpeg")))) {
            AudioPlayer.embed("audioplayer", {soundFile: "<?=base_url()?>assets/public/files/publicidad/<?=$archivo?>"});
        }
    </script>
</div>