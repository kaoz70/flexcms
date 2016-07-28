<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>FlexCMS</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">

    <?php \theme\Assets::css_group('admin', $assets_css); ?>

    <script type="text/javascript">
        var system = {
            baseUrl: '<?=base_url()?>'
        }
    </script>

    <script src="<?= base_url('framework/packages/isotope/dist/isotope.pkgd.min.js') ?>"></script>

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

</head>

<body>

    <div id="header">
        <img style="margin-bottom: 13px; width: 100%" src="<?=base_url('assets/admin/images/logo.png')?>" />
        <ul id="menu" class="contenido">
            <li>
                <a title="Estructura"
                   rel="Crear páginas, editar su estructura, añadir módulos"
                   class="nivel1 ajax tooltip"
                   data-controller="admin/structure"
                   href="<?=base_url('admin/structure');?>">
                    <i class="material-icons">view_quilt</i>Estructura</a>
            </li>
            <? foreach ($menu as $item): ?>
                <li>
                    <a title="<?=$item->name->es?>"
                       rel="<?=$item->tooltip->es?>"
                       class="nivel1 ajax tooltip"
                       data-controller="admin/<?=$item->controller?>"
                       href="<?=base_url('admin/' . $item->controller);?>">
                        <i class="material-icons"><?=$item->icon?></i><?=$item->name->es?></a>
                </li>
            <? endforeach ?>
            <li>
                <a title="Idiomas"
                   rel="Editar idiomas para sitios multi-idiomas"
                   class="nivel1 ajax tooltip"
                   data-controller="admin/language"
                   href="<?=base_url('admin/language');?>">
                    <i class="material-icons">language</i>Idiomas</a>
            </li>
            <li>
                <a title="Configuración"
                   rel="Tamaños de imagenes, configuracion general"
                   class="nivel1 ajax tooltip"
                   data-controller="admin/config"
                   href="<?=base_url('admin/config');?>">
                    <i class="material-icons">settings</i>Configuraci&oacute;n</a>
            </li>
        </ul>
        <div class="user">
            <div id="counter"></div>
            <a title="Cerrar sessi&oacute;n"
               rel="<?=$user->first_name?> <?=$user->last_name?>"
               class="external tooltip"
               id="logout"
               href="<?=base_url();?>admin/logout"><span></span>Salir</a>
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

    <div id="version">v2.0.0</div>

</body>
</html>