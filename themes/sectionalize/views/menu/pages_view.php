<ul<?=$attrs?>>

	<? foreach ($children as $childNode): ?>

		<? if($childNode->temporal !== 1 AND $childNode->paginaEnabled) : ?>

			<li class="<?= (in_array($childNode->id, $menu['path']) ? 'active' : '')?><?= has_dropdown($childNode, $menu) ? ' dropdown' : '' ?>" data-id="<?= $childNode->id ?>">

				<a
					class="<?= has_dropdown($childNode, $menu) ? ' dropdown-toggle' : '' ?>"
					href="<?= base_url($lang . '/' . $childNode->paginaNombreURL) ?>"
					<?= has_dropdown($childNode, $menu) ? 'data-toggle="dropdown"' : '' ?> >
					<?=$childNode->paginaNombreMenu ?>
					<? if (has_dropdown($childNode, $menu)) : ?>
						<span class="caret"></span>
					<? endif ?>
				</a>

				<? //Show the catalog menu
				if (array_key_exists('page', $menu['catalog']) && $menu['catalog']['page']->paginaNombreURL === $childNode->paginaNombreURL) {
					render_generic_menu($menu['catalog']['tree'], $menu['catalog']['path'], $menu['catalog']['page']->paginaNombreURL, TRUE, 'catalog_view', ['class' => 'category dropdown-menu']);
				} ?>

				<? //Show the gallery menu
				if (array_key_exists('page', $menu['gallery']) && $menu['gallery']['page']->paginaNombreURL === $childNode->paginaNombreURL) {
					render_generic_menu($menu['gallery']['tree'], $menu['gallery']['path'], $menu['gallery']['page']->paginaNombreURL, FALSE, 'gallery_view',['class' => 'category dropdown-menu']);
				} ?>

				<? //If the page has children call this function again
				if ($childNode->getChildrenCount()) {
					$attrs_array['class'] = array_key_exists('class', $attrs_array) ? $attrs_array['class'] . ' dropdown' : 'dropdown-menu';
					render_menu_uncached($childNode, $menu, array('class' => 'dropdown-menu'), $view);
				} ?>

			</li>

		<? endif ?>

	<? endforeach ?>

</ul>