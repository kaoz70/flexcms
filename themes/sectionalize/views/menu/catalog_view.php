<ul<?=$attrs?>>

	<? foreach ($children as $childNode): ?>

		<? if($childNode->temporal !== 1) : ?>

			<li class="<?= (in_array($childNode->id, $path) ? 'active' : '') ?><?= has_dropdown($childNode) ? ' dropdown' : '' ?>" data-id="<?= $childNode->id ?>">
				<a href="<?= base_url($lang . '/' . $page . '/' . $childNode->id . '/' . $childNode->productoCategoriaUrl) ?>"> <?= $childNode->productoCategoriaNombre ?></a>

				<? if ($show_items): ?>
					<? $products = $CI->Catalog->getProductsByCategory($childNode->id, $CI->m_idioma); ?>
					<? if($products): ?>
						<ul class="products">
						<? foreach($products as $prod): ?>
							<li class="<?= ($prod->productoUrl === $CI->uri->segment(5) ? 'active' : '') ?>" data-id="<?= $prod->productoId ?>">
								<a href="<?= base_url($CI->m_idioma . '/' . $page . '/' . $childNode->id . '/' . $childNode->productoCategoriaUrl) . '/' . $prod->productoUrl ?>"> <?= $prod->productoNombre ?></a>
							</li>
						<? endforeach ?>
						</ul>
					<? endif ?>
				<? endif ?>

				<? if ($childNode->getChildrenCount()) {
					render_generic_menu($childNode, $path, $page, $show_items, $view, array('class' => 'category dropdown-menu'));
				} ?>

			</li>

		<? endif ?>

	<? endforeach ?>

</ul>