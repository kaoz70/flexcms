<!DOCTYPE html>

<html lang="en" ng-app="app" ng-controller="MainController">
<head>
    <!--
    [1. Meta Tags]
    -->
    <meta charset="utf-8" />
    <title ng-bind="'FLexCMS | ' + pageTitle || 'FLexCMS'">FLexCMS</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="<?= admin_asset_path('img/favicon.png') ?>" type="image/x-icon">
    <!--
    [2. Css References]
    -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="<?= admin_asset_path('css/animate.min.css') ?>" type="text/css" />
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

                <li ng-class="{'active open': $state.includes('dashboard')}">
                    <a ng-click="openPanel()">
                        <i class="material-icons">view_quilt</i>
                        <span>Estructura</span>
                    </a>
                    <ul>
                        <li class="submenu-title">
                            <span>Estructura</span>
                        </li>
                        <li class="col-xs-12" ui-sref-active="active">

                            <div ui-tree>
                                <ol ui-tree-nodes="" ng-model="pages" id="tree-root">
                                    <li ng-repeat="node in pages" ui-tree-node ng-include="'nodes_renderer.html'"></li>
                                </ol>
                            </div>

                        </li>

                    </ul>
                </li>

                <li ng-class="{'active open': $routeSegment.startsWith('page')}">
                    <a ng-click="openPanel()">
                        <i class="material-icons">view_list</i>
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
                    <li ng-class="{'active open': $state.includes('<?=$item->controller?>')}">
                        <a title="<?=$item->name->es?>"
                           rel="<?=$item->tooltip->es?>"
                           class="ajax"
                           ui-sref="<?=$item->controller ?>">
                            <i class="material-icons"><?=$item->icon?></i>
                            <span><?=$item->name->es?></span></a>
                    </li>
                <? endforeach ?>

                <li ng-class="{'active': $routeSegment.startsWith('language')}">
                    <a title="Idiomas"
                       ng-click="closePanel()"
                       rel="Editar idiomas para sitios multi-idiomas"
                       class="ajax"
                       ng-href="#/language" >
                        <i class="material-icons">language</i><span>Idiomas</span></a>
                </li>
                <li ng-class="{'active open': $routeSegment.startsWith('config')}">
                    <a title="Configuración"
                       rel="Tamaños de imagenes, configuracion general"
                       class="ajax"
                       ng-href="#/config">
                        <i class="material-icons">settings</i>
                        <span>Config</span></a>
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
            <div app-view-segment="1"></div>
            <div app-view-segment="2"></div>
            <div app-view-segment="3"></div>
            <div app-view-segment="4"></div>
            <div app-view-segment="5"></div>
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

    <script src="<?= admin_asset_path('../../node_modules/angular-sanitize/angular-sanitize.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.templates.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular-drag-and-drop-lists/angular-drag-and-drop-lists.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-ui-tree/dist/angular-ui-tree.min.js') ?>"></script>

    <script src="<?= admin_asset_path('js/lib/notifications/snap.svg-min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/notifications/notificationFx.js') ?>"></script>
    <script src="<?= admin_asset_path('js/pages/notifications.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-animate.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-touch.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/ui-bootstrap-tpls.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-ui-router.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/ocLazyLoad.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-breadcrumb.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/main.js') ?>"></script>


    <script src="<?= admin_asset_path('js/app/app.module.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/app.config.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/app.routes.js') ?>"></script>

    <?php
    \theme\Assets::js_group('admin', $assets_js);
    ?>

    <script src="<?= admin_asset_path('js/app/directives/easypiechart.directive.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/sparkline.directive.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/panel.directive.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/WindowDirective.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/directives/ListItemDirective.js') ?>"></script>

    <script src="<?= admin_asset_path('js/app/factories/WindowFactory.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/controllers/main.controller.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/services/Template.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/services/Notification.js') ?>"></script>
    <script src="<?= admin_asset_path('js/app/controllers/sidebar.controller.js') ?>"></script>

</div>

<div id="modal-danger" class="modal modal-message modal-danger fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <i class="pe-7s-shield"></i>
            </div>
            <div class="modal-title">Error</div>

            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
            </div>
        </div>
        <!-- / .modal-content -->
    </div>
    <!-- / .modal-dialog -->
</div>

<div id="modal-warning" class="modal modal-message modal-warning fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <i class="pe-7s-attention"></i>
            </div>
            <div class="modal-title">Error</div>

            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal" data-ok >Eliminar</button>
            </div>
        </div>
        <!-- / .modal-content -->
    </div>
    <!-- / .modal-dialog -->
</div>

</body>
</html>
