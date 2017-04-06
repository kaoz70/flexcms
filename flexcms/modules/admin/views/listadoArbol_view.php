<h2><?=$titulo;?><a class="cerrar" href="#" >cancelar</a></h2>

<div class="contenido_col" style="width: <?= (200 + (27 * ($tree_size))) ?>px">
    <?= admin_tree($root_node->getChildren(), 'nivel2', $url_edit, $url_delete, $section, array(
        'id' => $id,
        'class' => 'tree',
        'data-sort' => $url_sort,
    )) ?>
</div>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>

<script language="JavaScript">
    initTree('<?=$id?>');
</script>