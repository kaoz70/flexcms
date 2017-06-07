<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?=$lang->slug?>"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="<?=$lang->slug?>"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="<?=$lang->slug?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=$lang->slug?>"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?=$titulo?></title>
	<meta name="description" content="<?=$meta_description?>">
	<meta name="keywords" content="<?=$meta_keywords?>">
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
		<? if($lang->slug != $item['diminutivo']): ?>
			<link rel="alternate" hreflang="<?=$item['diminutivo'];?>" href="<?=$item['link'];?>" >
		<? endif ?>
	<?php endforeach; ?>

	<script>
		var system = {
			base_url: '<?=base_url()?>',
			<?= !empty($pagPedidos) ? "pag_pedidos: '" . $pagPedidos->paginaNombreURL . "'," . PHP_EOL : ""?>
			<?= !empty($pagAutenticacion) ? "pag_autenticacion: '" . $pagAutenticacion->paginaNombreURL . "'," . PHP_EOL : ""?>
			<?= $popup_banner ? "popup_banner: { html:'" . $popup_banner->html . "',id: " . $popup_banner->id . "}," . PHP_EOL : ""?>
			lang: '<?=$lang->slug?>',
			fb_login: false
		}
	</script>

	<?php \theme\Assets::css_group('system', array_merge($assets_banner_css, array(
		'framework/packages/foundation/css/foundation.css',
		'framework/packages/magnific-popup/dist/magnific-popup.css',
		'framework/packages/bx-slider.js/dist/jquery.bxslider.css',
		'themes/' . $theme . '/css/system.scss',
		'themes/' . $theme . '/css/global.scss',
		'themes/' . $theme . '/css/pages.scss',
		'themes/' . $theme . '/packages/font-awesome/css/font-awesome.css',
		'themes/' . $theme . '/css/mobile_menu.scss',
	)), $theme); ?>

	<script src="<?= base_url('framework/packages/foundation/js/vendor/modernizr.js') ?>"></script>

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

<body class="<?=$page->css_class?>">

<? if($config['facebook_login']): ?>
<script src="<?= base_url('themes/' . $theme . '/scripts/facebook_login.js') ?>"></script>
<script>init_facebook_login('<?=$config->facebook_app_id?>')</script>
<? endif ?>

<? $this->benchmark->mark('body_html_start');?>

<div id="containter">
	<div id="mobile-menu" class="hide-for-medium-up slide">
		<div id="navToggle" class="svg-wrapper">
			<svg x="0px" y="0px" width="100%" viewBox="0 0 105 105" class="gh-svg gh-svg-top" enable-background="new 0 0 105 105">
				<rect width="35" height="4" x="35" y="52" class="gh-svg-rect gh-svg-rect-top"></rect>
			</svg>
			<svg x="0px" y="0px" width="100%" viewBox="0 0 105 105" class="gh-svg gh-svg-bottom" enable-background="new 0 0 105 105">
				<rect width="35" height="4" x="35" y="52" class="gh-svg-rect gh-svg-rect-bottom"></rect>
			</svg>
		</div>
		<h4>Menu</h4>
	</div>

	<nav id="drawer" class="slide">
		<? render_menu('mobile', $menu['tree'], $menu) ?>
	</nav>

	<div id="content" class="slide">

		<header>
			<div class="row">

				<div class="column large-offset-4 medium-4 large-4 align-center">
					<a href="<?=base_url()?>"><img id="logo" src="<?=theme_url('images/logo.' . $this->theme_config->logo_extension)?>" alt="<?=$nombre_sitio?>" /></a>
				</div>

				<div class="column medium-4 large-4 align-center">
					<div class="social">
						<a target="_blank" class="facebook" href="http://www.facebook.com/lovedestinyhotel">Facebook</a>
						<a target="_blank" class="twitter" href="https://twitter.com/mydestinyhotel">Twitter</a>
						<a target="_blank" class="instagram" href="http://instagram.com/destinyhotel">Instagram</a>
						<a target="_blank" class="linkedin" href="http://ec.linkedin.com/pub/destiny-hotel/a0/874/3a8">LinkedIn</a>
					</div>
				</div>

			</div>

			<?php if(count($menu_idiomas) > 1) : ?>
				<div id="idiomas">
					<?php foreach($menu_idiomas as $item): ?>
						<a href="<?=$item['link'];?>">
							<span class="flag flag-<?=$item['diminutivo'];?>" title="<?=$item['label'];?>"><?=$item['label'];?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</header>

		<? if ($error_message): ?>
			<div class="row error">
				<?=$error_message?>
			</div>
		<? endif ?>

		<? render_menu('main', $menu['tree'], $menu, array('id' => 'main_menu')) ?>

		<div id="main_content">
			<?= render_content() ?>
		</div>

		<footer>
			<div class="row">
				<div class="column large-4">
					<div class="direccion"><i class="fa fa-location-arrow fa-2x pull-left"></i>Calles Oscar Efrén Reyes y Ambato<br> Baños, Tungurahua, Ecuador.</div>
					<div class="telefonos"><i class="fa fa-phone-square fa-2x"></i>(+593) 3 2741935 / (+593) 3 2741643</div>
					<div class="email"><i class="fa fa-send fa-2x"></i><?=safe_mailto('reserva@destinyhotel.ec', 'reserva@destinyhotel.ec')?></div>
				</div>
				<div class="column large-4 align-center">
					<img src="<?=theme_url('images/logo_footer.png')?>" alt="<?=$nombre_sitio?>" />
				</div>
				<div class="column large-4 social">

					<div class="fshare">
						<a class="FBConnectButton" id="button" onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[url]=http://destinyhotel.ec/&amp;?>', 'sharer', 'toolbar=0,status=0,width=550,height=400');" target="_parent" href="javascript: void(0)">
					<span class="fa-stack fa-2x">
						<i class="fa fa-circle fa-stack-2x"></i>
						<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
					</span>
						</a>
						<div class="fb_share_count">
							<div class="fb_share_count_inner">0</div>
						</div>
						<div id="fb-root"></div>
						<script src="http://connect.facebook.net/en_US/all.js"></script>
					</div>
					<div class="tweet">
						<a rel="{'data-url':'<?=base_url()?>','data-count':'vertical','data-via':'','data-text':'<?=$nombre_sitio?>'}" href="http://twitter.com/share" class="twitter-share-button"></a>
					</div>

				</div>
			</div>
		</footer>

		<div class="row">
			<div class="column large-6" id="submenu">
				<? foreach ($footermenu as $item): ?>
					<? print_r($item) ?>
					<a class="item_<?=$item['id']?> <?=$item['url'] == $pagina_url ? 'active' : '' ?> <?=$item['css_class']?>" href="<?=base_url($lang->slug.'/'.$item['paginaNombreURL'])?>"><?=$item['paginaNombreMenu']?></a>
				<? endforeach ?>
			</div>
			<div class="column large-6 align-right" id="author">
				<small>Dejabú © 2014 | <a title="Diseño páginas web Ecuador" href="http://www.dejabu.ec" target="_blank">Diseño web Dejabú</a></small>
			</div>
		</div>

	</div>
</div>

<? if(count($this->theme_config->backgrounds) > 1):?>
	<div id="background">
		<ul class="cb-slideshow">
			<? foreach ($this->theme_config->backgrounds as $image): ?>
				<li style="background-image: url('<?= theme_url('images/fondos/' . $image) ?>');"></li>
			<? endforeach ?>
		</ul>
	</div>
<? endif ?>

<? $this->benchmark->mark('body_html_end'); ?>

<?php \theme\Assets::js_group('system', array_merge(array(
	'packages/foundation/js/vendor/jquery.js',
	'packages/jquery.easing/js/jquery.easing.min.js',
), $assets_banner_js), $theme); ?>

<script src="<?= base_url('assets/slideshow_js') ?>"></script>

<?php \theme\Assets::js_group('footer', array(
	'packages/foundation/js/foundation/foundation.js',
	//'packages/foundation/js/foundation/foundation.abide.js',
	'packages/foundation/js/foundation/foundation.alert.js',
	'packages/foundation/js/foundation/foundation.equalizer.js',
	'packages/foundation/js/foundation/foundation.offcanvas.js',
	'packages/foundation/js/foundation/foundation.orbit.js',
	'packages/foundation/js/foundation/foundation.reveal.js',
	'packages/foundation/js/foundation/foundation.tooltip.js',
	'packages/foundation/js/foundation/foundation.topbar.js',
	'packages/magnific-popup/dist/jquery.magnific-popup.min.js',
	'packages/bx-slider.js/dist/jquery.bxslider.min.js',
	'packages/imagesloaded/imagesloaded.pkgd.min.js',
	'packages/jquery.cookie/jquery.cookie.js',
	'packages/swfobject/swfobject/swfobject.js',
	'packages/flexcms/jquery.nestedAccordion.js',
	'packages/flexcms/search.js',
	'packages/flexcms/cart.js',
	'packages/flexcms/modules.js',
	'packages/flexcms/mobile_menu.js',
	'packages/flexcms/system.js',
    'packages/formvalidation/dist/js/formValidation.min.js',
    'packages/formvalidation/dist/js/framework/foundation.min.js', //bootstrap.min.js
    'packages/formvalidation/dist/js/language/' . ($lang->slug === 'es' ? 'es_ES' : 'en_US') . '.js',
	'themes/' . $theme . '/scripts/share.js',
	'themes/' . $theme . '/scripts/custom.js',
), $theme); ?>

<!-- Page rendered in {elapsed_time} seconds -->
</body>
</html>
