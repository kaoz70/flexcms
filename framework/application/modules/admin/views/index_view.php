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
    <link rel="stylesheet" href="<?= admin_asset_path('css/bootstrap.min.css') ?>" type="text/css" id="link-bootstrap" />
    <link rel="stylesheet" href="#null" type="text/css" id="link-bootstrap-rtl" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/animate.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/app.css') ?>" type="text/css" id="link-app" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/less/admin.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/less/skins/dark.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/font-awesome.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/pe-icon-7-stroke.css') ?>" type="text/css" />

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
                            <span>P&aacute;ginas</span>
                        </li>
                        <li class="col-xs-12" ui-sref-active="active">
                            <?= admin_structure_tree($root_node->getChildren(), $visible); ?>
                        </li>

                    </ul>
                </li>

                <li ng-class="{'active open': $state.includes('page')}">
                    <a ng-click="openPanel()">
                        <i class="material-icons">view_list</i>
                        <span>P&aacute;ginas</span>
                    </a>
                    <ul>
                        <li class="submenu-title">
                            <span>P&aacute;ginas</span>
                        </li>
                        <li class="col-xs-12" ui-sref-active="active">
                            <?= admin_structure_tree($root_node->getChildren(), $visible); ?>
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

                <li ng-class="{'active open': $routeSegment.startsWith('/language')}">
                    <a title="Idiomas"
                       ng-click="closePanel()"
                       rel="Editar idiomas para sitios multi-idiomas"
                       class="ajax"
                       ui-sref="language.index" >
                        <i class="material-icons">language</i><span>Idiomas</span></a>
                </li>
                <li ng-class="{'active open': $routeSegment.startsWith('config')}">
                    <a title="Configuración"
                       rel="Tamaños de imagenes, configuracion general"
                       class="ajax"
                       ui-sref="config">
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
    <script src="<?= admin_asset_path('js/bootstrap.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/modernizr.custom.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/slimscroll/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/animsition/animsition.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular/angular.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-route/angular-route.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-route-segment/build/angular-route-segment.js') ?>"></script>
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

</body>
</html>
