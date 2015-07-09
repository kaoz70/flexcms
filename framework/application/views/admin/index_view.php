<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<title>FlexCMS</title>

	<?php Assets::css_group('admin', $assets_css); ?>

    <script type="text/javascript">
        var system = {
            base_url: '<?=base_url()?>'
        }
    </script>

	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/mootools-core-1.4.5-full-compat-yc.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/mootools-more-1.4.0.1.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/clientcide.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/mootools-class-extras.js')?>"></script>
	
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/MooEditable.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/MooEditable.UploadImage.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/MooEditable.Forecolor.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/MooEditable.CleanPaste.js')?>"></script>
	
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/Uploader.js')?>"></script>
	
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/DatePicker/Locale.es-ES-DatePicker.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/DatePicker/Picker.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/DatePicker/Picker.Attach.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/DatePicker/Picker.Date.js')?>"></script>
	
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/colorpicker/DynamicColorPicker.js')?>"></script>

	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/Tree.js')?>"></script>
	
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/Scrollable.js')?>"></script>
	
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/tableManager.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/moduleManager.js')?>"></script>
	
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/ImageManipulation.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/PeriodicalExecuter.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/CountDown.js')?>"></script>

	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/upload.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/admin/scripts/general.js')?>"></script>

    <script type="text/javascript">
        window.addEvent('domready', function () {
            //initLoginCoundown(<?=$sess_expiration?>);
        });
    </script>

</head>

<body>
	
	<div id="header">
		<img style="margin-bottom: 13px; width: 100%" src="<?=base_url('assets/admin/images/logo.png')?>" />
        <ul id="menu" class="contenido">
            <? foreach ($secciones as $seccion): ?>
                <li><a title="<?=$seccion->adminSeccionNombre?>" rel="<?=$seccion->desc?>" class="nivel1 ajax tooltip" id="<?=$seccion->adminSeccionController?>" href="<?=base_url('admin/'.$seccion->adminSeccionController);?>"><?=$seccion->adminSeccionNombre?></a></li>
            <? endforeach ?>
        </ul>
        <div class="user">
            <div id="counter"></div>
            <a title="Cerrar sessi&oacute;n" rel="<?=$user->first_name?> <?=$user->last_name?>" class="external tooltip" id="logout" href="<?=base_url();?>login/terminate"><span></span>Salir</a>
        </div>
	</div>

	<div id="content">
		<div id="pages">
			<div class="titulo">P&aacute;ginas</div>
			<div class="contenido">
				<?= admin_structure_tree($root_node->getChildren(), $visible); ?>
			</div>
            <div class="show"></div>
		</div>
	</div>

	<div id="contenido">
		<table cellspacing="2">
			<tr id="columnas">
			</tr>
		</table>
	</div>

	<div id="version">v1.6.4</div>

</body>
</html>