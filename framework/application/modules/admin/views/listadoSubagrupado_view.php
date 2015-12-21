<h2><?=$txt_titulo?><a class="cerrar" href="#" >cancelar</a></h2>
<? if($search): ?>
<div class="buscar">
    <input data-page-id="<?=$this->uri->segment(4)?>" type="text" name="searchString" value="Buscar..." />
    <div class="searchButton"></div>
</div>
<ul id="<?=$list_id?>" class="contenido_col listado_general searchResults" style="bottom: <?=$bottomMargin?>px" rel="<?=$url_rel?>">
<? else: ?>
<ul id="<?=$list_id?>" class="contenido_col listado_general" style="bottom: <?=$bottomMargin?>px" rel="<?=$url_rel?>">
<? endif ?>
   <?=$html?>
</ul>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>

<? if($search): ?>
<script type="text/javascript">
    search.init('<?=$url_search?>', 'es');
</script>
<? endif ?>