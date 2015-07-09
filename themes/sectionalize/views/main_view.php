<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?=$diminutivo?>"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="<?=$diminutivo?>"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="<?=$diminutivo?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=$diminutivo?>"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?=$titulo?></title>
	<meta name="description" content="<?=$meta_description?>">
	<meta name="keywords" content="<?=$meta_keywords?>">
	<meta name="generator" content="FlexCMS">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

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
			lang: '<?=$diminutivo?>',
			fb_login: false
		}
	</script>

	<?php Assets::css_group('system', array_merge($assets_banner_css, array(
		'packages/bootstrap/dist/css/bootstrap.min.css',
		'packages/magnific-popup/dist/magnific-popup.css',
		'packages/bx-slider.js/dist/jquery.bxslider.css',
		'themes/' . $theme . '/css/system.scss',
		'themes/' . $theme . '/css/global.less',
		'themes/' . $theme . '/css/global.sass',
		'themes/' . $theme . '/css/global.scss',
		'themes/' . $theme . '/css/pages.less',
		'themes/' . $theme . '/css/pages.sass',
		'themes/' . $theme . '/css/pages.scss',
		'themes/' . $theme . '/packages/font-awesome/css/font-awesome.css',
		'themes/' . $theme . '/css/mobile_menu.scss',
		'themes/' . $theme . '/css/generated/background.css',
	)), $theme); ?>

	<script src="<?= base_url('packages/foundation/js/vendor/modernizr.js') ?>"></script>

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

<? if($config->facebook_login): ?>
<script src="<?= base_url('themes/' . $theme . '/scripts/facebook_login.js') ?>"></script>
<script>init_facebook_login('<?=$config->facebook_app_id?>')</script>
<? endif ?>

<? $this->benchmark->mark('body_html_start');?>

<nav class="navbar navbar-trans navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapsible">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"><?=$nombre_sitio?></a>
		</div>
		<div class="navbar-collapse collapse" id="navbar-collapsible">
			<? render_menu('mobile', $menu['tree'], $menu, array('class' => 'nav navbar-nav navbar-left')) ?>
			<form class="navbar-form">
				<div class="form-group" style="display:inline;">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="What are searching for?">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span> </span>
					</div>
				</div>
			</form>
		</div>
	</div>
</nav>

<?= render_content() ?>

<footer id="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-3 column">
				<h4>Information</h4>
				<ul class="nav">
					<li><a href="about-us.html">Products</a></li>
					<li><a href="about-us.html">Services</a></li>
					<li><a href="about-us.html">Benefits</a></li>
					<li><a href="elements.html">Developers</a></li>
				</ul>
			</div>
			<div class="col-xs-6 col-md-3 column">
				<h4>Follow Us</h4>
				<ul class="nav">
					<li><a href="#">Twitter</a></li>
					<li><a href="#">Facebook</a></li>
					<li><a href="#">Google+</a></li>
					<li><a href="#">Pinterest</a></li>
				</ul>
			</div>
			<div class="col-xs-6 col-md-3 column">
				<h4>Contact Us</h4>
				<ul class="nav">
					<li><a href="#">Email</a></li>
					<li><a href="#">Headquarters</a></li>
					<li><a href="#">Management</a></li>
					<li><a href="#">Support</a></li>
				</ul>
			</div>
			<div class="col-xs-6 col-md-3 column">
				<h4>Customer Service</h4>
				<ul class="nav">
					<li><a href="#">About Us</a></li>
					<li><a href="#">Delivery Information</a></li>
					<li><a href="#">Privacy Policy</a></li>
					<li><a href="#">Terms &amp; Conditions</a></li>
				</ul>
			</div>
		</div><!--/row-->
	</div>
</footer>

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

<?php Assets::js_group('system', array_merge(array(
	'packages/foundation/js/vendor/jquery.js',
	'packages/jquery.easing/js/jquery.easing.min.js',
), $assets_banner_js), $theme); ?>

<script src="<?= base_url('assets/slideshow_js') ?>"></script>

<?php Assets::js_group('footer', array(
	'packages/bootstrap/dist/js/bootstrap.min.js',
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
	'themes/' . $theme . '/scripts/share.js',
	'themes/' . $theme . '/scripts/custom.js',
), $theme); ?>

<!-- Page rendered in {elapsed_time} seconds -->
</body>
</html>
