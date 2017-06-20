<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8" />
    <title>FlexCMS | Login</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link rel="apple-touch-icon" sizes="180x180" href="{{ admin_asset_path('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ admin_asset_path('favicon/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ admin_asset_path('favicon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ admin_asset_path('favicon/manifest.json') }}">
    <link rel="mask-icon" href="{{ admin_asset_path('favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link rel="shortcut icon" href="{{ admin_asset_path('favicon/favicon.ico') }}">
    <meta name="msapplication-config" content="{{ admin_asset_path('favicon/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="{{ base_url('assets/admin/build/app.css') }}">

    <script>
        var system = {
            base_url: "{{ base_url() }}"
        }
    </script>

    <script src="{{ base_url('assets/admin/build/login.js') }}"></script>

</head>
<body ng-controller="login" layout="column" >

<div class="flip-container animsition" layout="column" layout-align="center center" layout-fill="layout-fill">
    <div class="flipper">
        <div class="front">

            <div class="card" layout="row" layout-xs="column">

                <md-content class="dark" layout="row" layout-align="center center">
                    <img height="108"
                         src="{{ admin_asset_path('img/logo.svg') }}"
                         alt="FlexCMS - Content Management System" />
                </md-content>

                <md-content class="light" flex>

                    <md-input-container class="md-icon-float md-block">
                        <label>Email</label>
                        <md-icon>mail_outline</md-icon>
                        <input ng-model="user.username">
                    </md-input-container>

                    <md-input-container class="md-icon-float md-block">
                        <label>Password</label>
                        <md-icon>lock_outline</md-icon>
                        <input type="password" ng-model="user.password">
                    </md-input-container>

                    <div layout="column" layout-align="stretch">
                        <md-button ng-click="login()" class="md-raised md-primary" ng-class="buttonClass" ng-disabled="buttonDissabled">
                            <md-icon ng-show="showIcon">@{{icon}}</md-icon>
                            <span ng-show="showSpinner" us-spinner="spinnerOpts"></span>
                            @{{message}}
                        </md-button>
                    </div>

                </md-content>

            </div>

        </div>
    </div>
</div>

</body>
</html>