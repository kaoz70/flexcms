<ul<?=$attrs?>>

	<? foreach ($children as $childNode): ?>

		<? if($childNode->temporal !== 1 AND $childNode->paginaEnabled) : ?>

			<li class="<?= (in_array($childNode->id, $menu['path']) ? 'active' : '')?><?= has_dropdown($childNode, $menu) ? ' has-dropdown' : '' ?>" data-id="<?= $childNode->id ?>">

				<a href="<?= base_url($lang . '/' . $childNode->paginaNombreURL) ?>"> <?=$childNode->paginaNombreMenu ?></a>

				<? //Show the catalog menu
				if (array_key_exists('page', $menu['catalog']) && $menu['catalog']['page']->paginaNombreURL === $childNode->paginaNombreURL) {
					render_generic_menu($menu['catalog']['tree'], $menu['catalog']['path'], $menu['catalog']['page']->paginaNombreURL, TRUE, 'catalog_view', ['class' => 'category dropdown']);
				} ?>

				<? //Show the gallery menu
				if (array_key_exists('page', $menu['gallery']) && $menu['gallery']['page']->paginaNombreURL === $childNode->paginaNombreURL) {
					render_generic_menu($menu['gallery']['tree'], $menu['gallery']['path'], $menu['gallery']['page']->paginaNombreURL, FALSE, 'gallery_view',['class' => 'category dropdown']);
				} ?>

				<? //If the page has children call this function again
				if ($childNode->getChildrenCount()) {
					$attrs_array['class'] = array_key_exists('class', $attrs_array) ? $attrs_array['class'] . ' dropdown' : 'dropdown';
					render_menu_uncached($childNode, $menu, $attrs_array, $view);
				} ?>

			</li>

		<? endif ?>

	<? endforeach ?>

</ul>