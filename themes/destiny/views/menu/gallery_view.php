<ul<?=$attrs?>>
<? foreach ($children as $childNode) : ?>

	<? if($childNode->temporal !== 1) : ?>

		<li class="<?= (in_array($childNode->id, $path) ? 'active' : '') ?><?= has_dropdown($childNode) ? ' has-dropdown' : '' ?>" data-id="<?= $childNode->id ?>">
			<a href="<?= base_url($CI->m_idioma . '/' . $page . '/' . $childNode->id . '/' . $childNode->descargaCategoriaUrl) ?>"><?= $childNode->descargaCategoriaNombre ?></a>
			<? if ($childNode->getChildrenCount()) {
				render_generic_menu($childNode, $path, $page, FALSE, $view, array('class' => 'category dropdown'));
			} ?>
		</li>
	<? endif ?>

<? endforeach ?>
</ul>