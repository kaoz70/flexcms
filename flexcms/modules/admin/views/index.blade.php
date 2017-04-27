<!DOCTYPE html>

<html lang="en" ng-app="app" ng-controller="MainController">
<head>

    <meta charset="utf-8" />
    <title ng-bind="'FlexCMS | ' + pageTitle || 'FlexCMS'">FlexCMS</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="" type="image/x-icon">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ admin_asset_path('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ admin_asset_path('favicon/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ admin_asset_path('favicon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ admin_asset_path('favicon/manifest.json') }}">
    <link rel="mask-icon" href="{{ admin_asset_path('favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link rel="shortcut icon" href="{{ admin_asset_path('favicon/favicon.ico') }}">
    <meta name="msapplication-config" content="{{ admin_asset_path('favicon/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="{{ base_url('assets/admin/build/app.css') }}">

    <script type="text/ng-template" id="nodes_renderer.html">
        <div class="node @{{node.content_type ? 'has-content' : 'no-content'}} @{{ node.content_type }}">
            <div ui-tree-handle><i class="fa fa-bars" aria-hidden="true"></i></div>
            <a ng-href='@{{node.content_type ? "#/page/" + node.id : ""}}'>@{{node.translation.menu_name ? node.translation.menu_name : '{missing translation}'}}</a>
        </div>
        <ol class="node-children" ui-tree-nodes="" ng-model="node.children">
            <li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_renderer.html'">
            </li>
        </ol>
    </script>

    <script type="text/ng-template" id="nodes_layout_renderer.html">
        <div class="node">
            <div ui-tree-handle><i class="fa fa-bars" aria-hidden="true"></i></div>
            <a ng-href='#/layout/edit/@{{node.id}}'>@{{node.translation.menu_name ? node.translation.menu_name : '{missing translation}'}}</a>
            <a class="delete" href="" ng-click="deleteNode(node.id)" layout="row" layout-align="center center"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
        </div>
        <ol class="node-children" ui-tree-nodes="" ng-model="node.children">
            <li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_layout_renderer.html'">
            </li>
        </ol>
    </script>

    <script>
        var system = {
            base_url: "{{ base_url() }}"
        }
    </script>

</head>

<body style="overflow:hidden;" ng-class="isSidebarOpen ? 'open' : ''" layout="row">

<div class='loading'>
    <img class="logo" src='{{ admin_asset_path("img/logo.svg") }}'>
    <div class='loader-circle'>
        <img class="rotating" src='{{ admin_asset_path("img/spinner.svg") }}'>
    </div>
</div>

<div class="animsition" layout="row">

    <div class="sidebar menu" layout="column" >
        <div class="sidebar-header" layout="row" layout-align="center center">
            <img src="{{ admin_asset_path('img/isotype.svg') }}" alt="FlexCMS" />
        </div>
        <div class="sidebar-menu" layout="column" layout-align="stretch">

            <ul class="menu">

                    <li ng-class="{'active open': $routeSegment.startsWith('layout')}">

                        <a ng-href="#/layout" layout="row" layout-align="center center">
                            <md-tooltip class="main-menu" md-direction="right">Estructura</md-tooltip>
                            <md-icon>view_quilt</md-icon>
                            <span>Estructura</span>
                        </a>
                        <div class="submenu-panel">
                            <ul>
                                <li class="submenu-title" layout="row">
                                    <div layout="row" flex layout-align="start center">
                                        <span>Estructura</span>
                                    </div>
                                    <md-button ng-href="#/layout/create" class="md-icon-button" aria-label="Crear">
                                        <md-icon>add</md-icon>
                                    </md-button>
                                </li>
                                <li class="submenu-menu" ui-sref-active="active">

                                    <div perfect-scrollbar id="layout" ui-tree="treeOptions">
                                        <ol class="tree" ui-tree-nodes="" ng-model="pages">
                                            <li ng-repeat="node in pages" ui-tree-node ng-include="'nodes_layout_renderer.html'"></li>
                                        </ol>
                                    </div>

                                </li>

                            </ul>
                        </div>
                    </li>

                    <li ng-class="{'active open': $routeSegment.startsWith('page')}">
                        <a ng-href="#/page" layout="row" layout-align="center center">
                            <md-tooltip class="main-menu" md-direction="right">P&aacute;ginas</md-tooltip>
                            <md-icon>view_list</md-icon>
                            <span>P&aacute;ginas</span>
                        </a>
                        <div class="submenu-panel">
                            <ul>
                                <li class="submenu-title">
                                    <span>P&aacute;ginas</span>
                                </li>
                                <li class="submenu-menu" ui-sref-active="active">

                                    <div perfect-scrollbar id="pages" ui-tree data-drag-enabled="false">
                                        <ol class="tree" ui-tree-nodes="" ng-model="pages">
                                            <li ng-repeat="node in pages" ui-tree-node ng-include="'nodes_renderer.html'"></li>
                                        </ol>
                                    </div>

                                </li>

                            </ul>
                        </div>

                    </li>

                    @foreach($menu as $item)
                        <li ng-class="{'active open': $routeSegment.startsWith('{{$item->controller}}')}">
                            <a title="{{$item->name->es}}"
                               layout="row"
                               layout-align="center center"
                               rel="{{$item->tooltip->es}}"
                               ng-href="#/{{$item->controller }}">
                                <md-tooltip class="main-menu" md-direction="right">{{$item->tooltip->es}}</md-tooltip>
                                <md-icon>{{$item->icon}}</md-icon>
                                <span>{{$item->name->es}}</span>
                            </a>
                        </li>
                    @endforeach

                    <li ng-class="{'active': $routeSegment.startsWith('language')}">
                        <a title="Idiomas"
                           layout="row" layout-align="center center"
                           ng-click="closePanel()"
                           rel="Editar idiomas para sitios multi-idiomas"
                           ng-href="#/language" >
                            <md-tooltip class="main-menu" md-direction="right">Idiomas</md-tooltip>
                            <md-icon>language</md-icon>
                            <span>Idiomas</span>
                        </a>
                    </li>

                    <li ng-class="{'active': $routeSegment.startsWith('config')}">
                        <a title="Configuración"
                           layout="row" layout-align="center center"
                           rel="Tamaños de imagenes, configuracion general"
                           ng-href="#/config">
                            <md-tooltip class="main-menu" md-direction="right">Config</md-tooltip>
                            <md-icon>settings</md-icon>
                            <span>Configuraci&oacute;n</span>
                        </a>
                    </li>

                </ul>

            <a class="logout" href="{{ base_url('admin/logout') }}">
                <md-tooltip md-direction="right">Cerrar sessi&oacute;n</md-tooltip>
                <i class="pe-7s-power"></i>
            </a>
        </div>
    </div>

    <div class="main-content" flex>
        <div id="windows" flex>
            <div app-view-segment="0"></div>
        </div>
    </div>

    <script src="{{ base_url('assets/admin/build/app.js') }}"></script>

    {{ scripts('modules', $assets_js) }}

</div>

</body>
</html>
