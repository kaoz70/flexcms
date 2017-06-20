<h2><?=$txt_titulo?><a class="cerrar" href="#" >cancelar</a></h2>
<? if($search): ?>
<div class="buscar">
    <input data-page-id="<?=$this->uri->segment(4)?>" type="text" name="searchString" value="Buscar..." />
    <div class="searchButton"></div>
</div>
<? endif ?>

<?= admin_cat_tree($root_node->getChildren(), $nivel, $item_methods, $urls, array(
        'id' => $list_id,
        'class' => $search ? 'contenido_col listado_general searchResults' : 'contenido_col listado_general',
        'style' => "bottom: {$bottomMargin}px",
    ))
?>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>

<? if($search): ?>
<script type="text/javascript">
    search.init('<?=$url_search?>', 'es');
</script>
<? endif ?>