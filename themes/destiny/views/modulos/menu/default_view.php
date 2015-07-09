<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name">
		</li>
		<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>

	<section class="top-bar-section">
		<?= render_menu('module', $menu['tree'], $menu) ?>
	</section>
</nav>