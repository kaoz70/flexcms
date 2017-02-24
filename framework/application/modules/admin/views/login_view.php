<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Yima | </title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="<?= admin_asset_path('img/favicon.png')?>" type="image/x-icon">

    <!--Css Files-->
    <link rel="stylesheet" href="<?= admin_asset_path('css/bootstrap.min.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/animate.min.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?= admin_asset_path('css/app.css')?>" type="text/css" />
    <link href="<?= admin_asset_path('css/font-awesome.min.css')?>" rel="stylesheet" />
    <link href="<?= admin_asset_path('css/pe-icon-7-stroke.css')?>" rel="stylesheet" />

</head>
<body>

<div class="row membership">
    <div class="col-lg-8 col-md-6 hidden-sm hidden-xs membership-brand">
        <div class="brand-wrapper">
            <div class="brand-container">
                <a href="">
                    <img class="brand-logo" src="<?= admin_asset_path('img/logo.png')?>" alt="Yima - Admin Web App" />
                </a>
                <div class="brand-title">
                    Welcome to Yima
                </div>
                <div class="brand-subtitle">
                    Login or Register for a Yima account for free.
                </div>
                <div class="brand-description">
                    Logging in is usually used to enter a specific page, which trespassers cannot see. Once the user is logged in,
                    the login token may be used to track what actions the user has taken while connected to the site.
                    Logging out may be performed explicitly by the user taking some actions, such as entering the appropriate command,
                    or clicking a website link labelled as such.
                </div>
                <div class="brand-action">
                    <input type="button" class="btn btn-primary" value="Create a Support Ticket">
                </div>
                <ul class="brand-links">
                    <li>
                        <a href="">Terms & Conditions</a>
                    </li>
                    <li>
                        <a href="">License Agreement</a>
                    </li>
                    <li>
                        <a href="">Contact</a>
                    </li>
                    <li>
                        <a href="">Support</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 membership-container">
        <a class="hidden" id="toregister"></a>
        <a class="hidden" id="tologin"></a>
        <a href="" class="hidden-lg hidden-md">
            <img class="brand-logo" src="<?= admin_asset_path('img/logo.png')?>" alt="Yima - Admin Web App" />
        </a>
        <div class="login animated">

            <form action="<?= base_url('admin/validate') ?>" method="post">

                <? if($error): ?>
                    <div class="alert alert-danger" role="alert"><?= $error ?></div>
                <? endif; ?>

                <div class="membership-title">Already have an account?</div>
                <div class="membership-input">
                    <input type="text" class="form-control" placeholder="Email" name="username" />
                </div>
                <div class="membership-input">
                    <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" />
                </div>

                <div class="membership-forgot pull-right">
                    <a href="">Forgot Password?</a>
                </div>

                <div class="membership-submit">
                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Sign In">
                </div>

            </form>

        </div>
    </div>
</div>
<!--Js Files-->
<script src="<?= admin_asset_path('js/jquery.min.js')?>"></script>
<script src="<?= admin_asset_path('js/bootstrap.min.js')?>"></script>
<script src="<?= admin_asset_path('js/modernizr.custom.js')?>"></script>

</body>
</html>