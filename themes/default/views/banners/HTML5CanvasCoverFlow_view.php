<script type="text/javascript">

$(document).ready(function() {
        var cc = new CanvasCoverflow("banner_<?=$banner->bannerId?>", "<?=base_url('banners/HTML5CanvasCoverFlow/'.$diminutivo.'/config/'.$banner->bannerId)?>");
});
	
</script>

<canvas id="banner_<?=$banner->bannerId?>" class="<?=$banner_class?>"></canvas>