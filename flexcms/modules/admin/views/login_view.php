<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8" />
    <title>FlexCMS | Login</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="<?= admin_asset_path('img/favicon.png')?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/material-design-icons/iconfont/material-icons.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/angular-material/angular-material.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('../../packages/animsition/dist/css/animsition.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/animate.min.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/admin.css')?>" type="text/css" />

    <script>
        var system = {
            base_url: "<?= base_url() ?>"
        }
    </script>

    <script src="<?= admin_asset_path('js/modernizr.custom.js') ?>"></script>
    <script src="<?= admin_asset_path('js/jquery.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/animsition/dist/js/animsition.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../node_modules/angular/angular.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-aria/angular-aria.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-material/angular-material.js') ?>"></script>
    <script src="<?= admin_asset_path('js/lib/angular/angular-animate.min.js') ?>"></script>
    <script src="<?= admin_asset_path('../../packages/angular-spinner/dist/angular-spinner.min.js') ?>"></script>
    <script src="<?= admin_asset_path('js/login.js') ?>"></script>

</head>
<body ng-controller="login" layout="column" >

<div class="flip-container animsition" layout="column" layout-align="center center" layout-fill="layout-fill">
    <div class="flipper">
        <div class="front">

            <div class="card" layout="row" layout-xs="column">

                <md-content class="dark" layout="row" layout-align="center center">
                    <img height="108"
                         src="<?= admin_asset_path('img/logo.svg')?>"
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
                            <md-icon ng-show="showIcon">{{icon}}</md-icon>
                            <span ng-show="showSpinner" us-spinner="spinnerOpts"></span>
                            {{message}}
                        </md-button>
                    </div>

                </md-content>

            </div>

        </div>
    </div>
</div>

</body>
</html>