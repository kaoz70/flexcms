<!DOCTYPE html>

<html lang="en" ng-app="app" ng-controller="MainController">
<head>
    <!--
    [1. Meta Tags]
    -->
    <meta charset="utf-8" />
    <title ng-bind="'FlexCMS | ' + pageTitle || 'FlexCMS'">FlexCMS</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="<?= admin_asset_path('img/favicon.png') ?>" type="image/x-icon">

    <!--
    [2. Css References]
    -->
    <link rel="stylesheet" href="<?= admin_asset_path('css/animate.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/material-design-icons/iconfont/material-icons.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/codemirror/lib/codemirror.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/codemirror/addon/hint/show-hint.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/codemirror/addon/fold/foldgutter.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/codemirror/theme/rubyblue.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/app.css') ?>" type="text/css" id="link-app" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/less/admin.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/less/skins/dark.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/font-awesome.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/chosen/chosen.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../node_modules/angular-bootstrap-datetimepicker/src/css/datetimepicker.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/angular-ui-tree/dist/angular-ui-tree.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/angular-material/angular-material.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/angular-timezone-selector/dist/angular-timezone-selector.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/pe-icon-7-stroke.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/animate.css/animate.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/md-color-picker/dist/mdColorPicker.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/ui-cropper/compile/minified/ui-cropper.css') ?>" type="text/css" />

    <script type="text/ng-template" id="nodes_renderer.html">
        <div class="node">
            <div ui-tree-handle><i class="fa fa-bars" aria-hidden="true"></i></div>
            <a ng-href='#/page/{{node.id}}'>{{node.translation.name ? node.translation.name : '{missing translation}'}}</a>
        </div>
        <ol class="node-children" ui-tree-nodes="" ng-model="node.children">
            <li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_renderer.html'">
            </li>
        </ol>
    </script>

    <script type="text/ng-template" id="nodes_layout_renderer.html">
        <div class="node">
            <div ui-tree-handle><i class="fa fa-bars" aria-hidden="true"></i></div>
            <a ng-href='#/layout/{{node.id}}'>{{node.translation.name ? node.translation.name : '{missing translation}'}}</a>
        </div>
        <ol class="node-children" ui-tree-nodes="" ng-model="node.children">
            <li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_layout_renderer.html'">
            </li>
        </ol>
    </script>

    <script>
        var system = {
            base_url: "<?= base_url() ?>"
        }
    </script>

</head>
<body style="overflow:hidden;" ng-class="isSidebarOpen ? '' : 'minimized'">
<div class="animsition">
    <!--
    [3. Sidebar Menu]
    -->
    <div class="sidebar menu">
        <div class="sidebar-header">
            <div class="header-brand">
                <div class="brand-logo">
                    <img src="<?= admin_asset_path('img/logo.png') ?>" alt="FlexCMS" />
                </div>
                <div class="brand-slogan">
                    <div class="slogan-title">FlexCMS</div>
                </div>
            </div>

        </div>
        <div class="sidebar-menu">
            <ul class="menu">

                <li ng-class="{'active open': $routeSegment.startsWith('layout')}">

                    <a ng-href="#/layout">
                        <md-tooltip md-direction="right">Estructura</md-tooltip>
                        <md-icon>view_quilt</md-icon>
                        <span>Estructura</span>
                    </a>
                    <ul>
                        <li class="submenu-title">
                            <span>Estructura</span>
                        </li>
                        <li class="col-xs-12" ui-sref-active="active">

                            <div ui-tree>
                                <ol ui-tree-nodes="" ng-model="pages" id="tree-root">
                                    <li ng-repeat="node in pages" ui-tree-node ng-include="'nodes_layout_renderer.html'"></li>
                                </ol>
                            </div>

                        </li>

                    </ul>
                </li>

                <li ng-class="{'active open': $routeSegment.startsWith('page')}">
                    <a ng-href="#/page">
                        <md-tooltip md-direction="right">P&aacute;ginas</md-tooltip>
                        <md-icon>view_list</md-icon>
                        <span>P&aacute;ginas</span>
                    </a>
                    <ul>
                        <li class="submenu-title">
                            <span>P&aacute;ginas</span>
                        </li>
                        <li class="col-xs-12" ui-sref-active="active">

                            <div id="pages" ui-tree data-drag-enabled="false">
                                <ol ui-tree-nodes="" ng-model="pages" id="tree-root">
                                    <li ng-repeat="node in pages" ui-tree-node ng-include="'nodes_renderer.html'"></li>
                                </ol>
                            </div>

                        </li>

                    </ul>
                </li>

                <? foreach ($menu as $item): ?>
                    <li ng-class="{'active open': $routeSegment.startsWith('<?=$item->controller?>')}">
                        <a title="<?=$item->name->es?>"
                           rel="<?=$item->tooltip->es?>"
                           ng-href="#/<?=$item->controller ?>">
                            <md-tooltip md-direction="right"><?=$item->tooltip->es?></md-tooltip>
                            <md-icon><?=$item->icon?></md-icon>
                            <span><?=$item->name->es?></span>
                        </a>
                    </li>
                <? endforeach ?>

                <li ng-class="{'active': $routeSegment.startsWith('language')}">
                    <a title="Idiomas"
                       ng-click="closePanel()"
                       rel="Editar idiomas para sitios multi-idiomas"
                       ng-href="#/language" >
                        <md-tooltip md-direction="right">Idiomas</md-tooltip>
                        <md-icon>language</md-icon>
                        <span>Idiomas</span>
                    </a>
                </li>
                <li ng-class="{'active': $routeSegment.startsWith('config')}">
                    <a title="Configuración"
                       rel="Tamaños de imagenes, configuracion general"
                       ng-href="#/config">
                        <md-tooltip md-direction="right">Config</md-tooltip>
                        <md-icon>settings</md-icon>
                        <span>Configuraci&oacute;n</span>
                    </a>
                </li>

            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="footer-user">
                <a ui-sref="profile"><?= $user->first_name ?> <?= $user->last_name ?></a>
            </div>
            <div class="footer-links">
                <a ui-sref="pages.login" class="links-logout">
                    <i class="pe-7s-power"></i>
                    <span>Cerrar Sesi&oacute;n</span>
                </a>
            </div>
        </div>
    </div>
    <!--
    [4. Sidebar Form]
    -->
    <div class="sidebar form collapsed">
    </div>
    <!--
    [5. Main Page Content]
    -->
    <div class="main-content">
        <!--
        [5.1. Page Header]
        -->
        <div class="content-header">
            <!--
            [5.1.1. BreadCrumb]
            -->
            <div ncy-breadcrumb></div>

        </div>

        <!--
        [5.3. Page Body]
        -->
        <div ng-class="{'content-body': true, 'body-full': hasFullContainer}" ui-view></div>

        <div id="windows">
            <div app-view-segment="0"></div>
        </div>

    </div>
    <!--
    [6. JavaScript References]
    -->
    <script src="<?= admin_asset_path('js/jquery.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/modernizr.custom.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/animsition/dist/js/animsition.min.js') ?>"></script>

    <script src="<?= admin_asset_path('../../node_modules/jstz/dist/jstz.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/moment/min/moment.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/moment-timezone/builds/moment-timezone-with-data.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/chosen/chosen.jquery.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/lodash/dist/lodash.min.js') ?>"></script>

    <script src="<?= admin_asset_path('../../node_modules/angular/angular.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-aria/angular-aria.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-material/angular-material.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-scroll-glue/src/scrollglue.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/ng-file-upload/ng-file-upload.js') ?>"></script>

    <script src="<?= admin_asset_path('../../packages/codemirror/lib/codemirror.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/edit/closetag.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/edit/matchtags.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/display/autorefresh.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/fold/foldcode.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/fold/xml-fold.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/fold/foldgutter.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/hint/show-hint.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/hint/xml-hint.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/addon/hint/html-hint.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/mode/xml/xml.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/mode/javascript/javascript.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/mode/css/css.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/codemirror/mode/htmlmixed/htmlmixed.js') ?>"></script>

    <script src="<?= admin_asset_path('../../packages/angular-ui-codemirror/ui-codemirror.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-i18n/angular-locale_es-ec.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-route/angular-route.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-route-segment/build/angular-route-segment.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/tinymce/tinymce.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-ui-tinymce/dist/tinymce.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-timezone-selector/dist/angular-timezone-selector.min.js') ?>"></script>

    <script src="<?= admin_asset_path('../../node_modules/angular-resource/angular-resource.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-sanitize/angular-sanitize.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.templates.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-drag-and-drop-lists/angular-drag-and-drop-lists.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-ui-router/release/angular-ui-router.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-ui-tree/dist/angular-ui-tree.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/tinycolor/dist/tinycolor-min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/md-color-picker/dist/mdColorPicker.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/ui-cropper/compile/minified/ui-cropper.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/color-thief/dist/color-thief.min.js') ?>"></script>

    <script src="<?= admin_asset_path('js/lib/notifications/snap.svg-min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/notifications/notificationFx.js') ?>"></script>
    <script src="<?= admin_asset_path('js/pages/notifications.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-animate.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-touch.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/ui-bootstrap-tpls.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/ocLazyLoad.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-breadcrumb.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/main.js') ?>"></script>

    <?php
    //Load the all the modules JS
    \theme\Assets::js_group('admin', $assets_js);
    ?>

    <script src="<?= admin_asset_path('js/app/directives/easypiechart.directive.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/sparkline.directive.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/WindowDirective.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/ListItemDirective.js') ?>"></script>

    <script src="<?= admin_asset_path('js/app/controllers/sidebar.controller.js') ?>"></script>

</div>

</body>
</html>
