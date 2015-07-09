<h2><?=$titulo;?><a class="cerrar" href="#" >cancelar</a></h2>

<div class="contenido_col" style="width: <?= (200 + (27 * ($tree_size))) ?>px">
	<?= admin_tree($root_node->getChildren(), 'nivel2', $edit_url, $delete_url, $name, array(
		'id' => $id,
		'class' => 'tree',
		'data-sort' => $url_reorganizar,
		'rel' => $url_rel,
	)) ?>
</div>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>

<script language="JavaScript">
	initTree('<?=$id?>');
</script>