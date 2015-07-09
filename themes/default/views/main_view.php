<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?=$diminutivo?>"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="<?=$diminutivo?>"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="<?=$diminutivo?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=$diminutivo?>"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?=$titulo?></title>
	<meta name="description" content="<?=$description?>">
	<meta name="keywords" content="<?=$keywords?>">
	<meta name="generator" content="FlexCMS">
	<meta name="viewport" content="width=device-width,initial-scale=1" />

	<? //Facebook OpenGraph meta tags ?>
	<meta property="og:title" content="<?=$og_title?>" />
	<meta property="og:site_name" content="<?=$nombre_sitio?>" />
	<meta property="og:url" content="<?=current_url()?>" />
	<meta property="og:image" content="<?=$og_image?>" />
	<meta property="og:description" content="<?=$og_description?>"/>

	<link type="text/plain" rel="author" href="<?=base_url()?>humans.txt">

	<?php foreach($menu_idiomas as $item): ?>
		<? if($diminutivo != $item['diminutivo']): ?>
			<link rel="alternate" hreflang="<?=$item['diminutivo'];?>" href="<?=$item['link'];?>" >
		<? endif ?>
	<?php endforeach; ?>

	<script>
		var system = {
			base_url: '<?=base_url()?>',
			<?= !empty($pagPedidos) ? "pag_pedidos: '" . $pagPedidos->paginaNombreURL . "'," . PHP_EOL : ""?>
			<?= !empty($pagAutenticacion) ? "pag_autenticacion: '" . $pagAutenticacion->paginaNombreURL . "'," . PHP_EOL : ""?>
			<?= $popup_banner ? "popup_banner: { html:'" . $popup_banner->html . "',id: " . $popup_banner->id . "}," . PHP_EOL : ""?>
			lang: '<?=$diminutivo?>'
		}
	</script>

	<?php Assets::css_group('banner', $assets_banner_css); ?>

	<?php //Assets::cdn($assets_banner_cdn); ?>

	<?php Assets::css_group('system', array(
		'packages/foundation/css/normalize.css',
		'packages/foundation/css/foundation.css',
		'packages/magnific-popup/dist/magnific-popup.css',
		'packages/bxslider/bx_styles/bx_styles.css',
		'themes/' . $theme . '/assets/css/system.scss',
		'themes/' . $theme . '/assets/css/global.less',
		'themes/' . $theme . '/assets/css/global.sass',
		'themes/' . $theme . '/assets/css/global.scss',
		'themes/' . $theme . '/assets/css/pages.less',
		'themes/' . $theme . '/assets/css/pages.sass',
		'themes/' . $theme . '/assets/css/pages.scss',
		'themes/' . $theme . '/assets/css/generated/background.css',
	), $theme); ?>

	<?php Assets::js_group('header', array(
		'packages/foundation/js/vendor/modernizr.js',
		'packages/foundation/js/vendor/jquery.js',
		'packages/jquery.easing/js/jquery.easing.min.js',
	)); ?>

	<?php Assets::js_group('banner', $assets_banner_js); ?>

	<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-XXXXX-Y']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

	</script>

</head>

<body <?=$clase;?>>

<div id="header">
	<div class="row">
		<div class="columns large-4">
			<a href="<?=base_url()?>"><img id="logo" src="<?=theme_url('assets/images/logo.' . $this->theme_config->logo_extension)?>" alt="<?=$nombre_sitio?>" /></a>
		</div>

		<div class="columns large-4 large-offset-4">
			<div class="row collapse" id="search">
				<div class="small-8 columns">
					<input type="text" name="searchString" value="<?=$this->lang->line('ui_search')?>..." />
					<div class="searchResult" style="display: none"></div>
				</div>
				<div class="small-4 columns">
					<input class="small button postfix" type="submit" name="search" value="<?=$this->lang->line('ui_search')?>" />
				</div>
			</div>

			<? if(count($pagPedidos) > 0):?>
				<div id="compras">
					<div><a href="<?=base_url()?><?=$diminutivo?>/<?=$pagPedidos->paginaNombreURL ?>"><?=$this->lang->line('ui_view_cart')?></a></div>
					<? $this->load->view('paginas/cart/mini_cart_view', $mini_cart_items) ?>
				</div>
			<? endif ?>

			<? if(count($pagAutenticacion) > 0):?>
				<div id="login">
					<? if(!$loggedIn): ?>
						<div><a href="<?=base_url()?><?=$diminutivo?>/<?=$pagAutenticacion->paginaNombreURL?>"><?=$this->lang->line('ui_auth_login')?></a></div>
						<a href="<?=base_url($diminutivo.'/'.$pagAutenticacion->paginaNombreURL.'/password')?>"><?=$this->lang->line('ui_auth_forgot_password')?></a> | <a href="<?=base_url($diminutivo.'/'.$pagAutenticacion->paginaNombreURL.'/register')?>"><?=$this->lang->line('ui_auth_create_account')?></a>
					<? else: ?>
						<div><strong class="nombre"><?=$usuario->first_name ?> <?=$usuario->last_name?></strong> <a href="<?=base_url($diminutivo.'/'.$pagAutenticacion->paginaNombreURL.'/logout')?>">[<?=$this->lang->line('ui_auth_logout')?>]</a></div>
					<? endif ?>
				</div>
			<? endif ?>

			<?php if(count($menu_idiomas) > 1) : ?>
				<div id="idiomas">
					<ul>
						<?php foreach($menu_idiomas as $item): ?>
							<li id="<?=$item['diminutivo'];?>" <?=$item['activo'];?>>
								<a href="<?=$item['link'];?>">
									<span class="flag flag-<?=$item['diminutivo'];?>"></span>
									<span class="label"><?=$item['label'];?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

		</div>
	</div>
	<div class="row">
		<div class="contain-to-grid">
			<nav class="top-bar" data-topbar role="navigation">
				<ul class="title-area hide-for-large-up">
					<li class="name">
						<h1><a href="#"><?=$nombre_sitio?></a></h1>
					</li>
					<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>
				<section class="top-bar-section">
					<ul>
						<?=$menu?>
					</ul>
				</section>
			</nav>
		</div>
	</div>
</div>

<? if ($error_message): ?>
	<div class="row error">
		<?=$error_message?>
	</div>
<? endif ?>

<div id="main_content">
	<?= render_content() ?>
</div>

<div id="footer">
	<div class="row">
		<div class="columns large-12">
			<ul class="inline-list" id="menu_footer">
				<? foreach ($footermenu as $item): ?>
					<li class="item_<?=$item['paginaId']?> <?= $item['paginaNombreURL'] == $pagina_url ? 'active' : '' ?> <?=$item['paginaClase']?> <?= $item['paginaEsPopup'] ? 'popup' : '' ?>">
						<a href="<?=base_url($diminutivo.'/'.$item['paginaNombreURL'])?>"><?=$item['paginaNombreMenu']?></a>
					</li>
				<? endforeach ?>
			</ul>
			<div id="author">Dejabú © 2013 | <a title="Diseño páginas web Ecuador" href="http://www.dejabu.ec" target="_blank">Diseño web Dejabú</a></div>
		</div>
	</div>
</div>

<? if(count($this->theme_config->backgrounds) > 1):?>
	<div id="background">
		<ul class="cb-slideshow">
			<? foreach ($this->theme_config->backgrounds as $image): ?>
				<li style="background-image: url('<?= theme_url('assets/images/fondos/' . $image) ?>');"></li>
			<? endforeach ?>
		</ul>
	</div>
<? endif ?>

<?php Assets::js_group('footer', array(
	'packages/foundation/js/foundation/foundation.js',
	'packages/foundation/js/foundation/foundation.abide.js',
	'packages/foundation/js/foundation/foundation.alert.js',
	'packages/foundation/js/foundation/foundation.equalizer.js',
	'packages/foundation/js/foundation/foundation.offcanvas.js',
	'packages/foundation/js/foundation/foundation.orbit.js',
	'packages/foundation/js/foundation/foundation.reveal.js',
	'packages/foundation/js/foundation/foundation.tooltip.js',
	'packages/foundation/js/foundation/foundation.topbar.js',
	'packages/magnific-popup/dist/jquery.magnific-popup.min.js',
	'packages/bxslider/source/jquery.bxSlider.js',
	'packages/jquery-ui/jquery-ui.min.js',
	'packages/jquery.cookie/jquery.cookie.js',
	'packages/swfobject/swfobject/swfobject.js',
	'packages/flexcms/system.js',
	'themes/' . $theme . '/assets/scripts/custom.js',
), $theme); ?>

<!-- Page rendered in {elapsed_time} seconds -->
</body>
</html>