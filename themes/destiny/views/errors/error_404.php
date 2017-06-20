<!DOCTYPE html>

<head>

    <title><?=$this->lang->line('error_404_header')?></title>

    <style type="text/css">

        html, body{
            background-repeat: repeat;
            padding: 0px;
            margin: 0px;
            width: 100%;
            height: 100%;
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1,
        h2 {
            margin: 0;
            width: 100%;
            padding: 0px;
        }

        h1 {
            font-size: 1.5em;
            text-transform: uppercase;
        }

        h1 span,
        a {
            color: #CB322C;
            text-decoration: none;
        }

        h2 {
            color: #7E7E7E;
            font-size: 1em;
            font-weight: normal;
            margin-bottom: 20px;
        }

        body {
            background: #000 url(<?=theme_url('images/404.jpg')?>) 50% 0 no-repeat;
        }

        .content {
            text-align: center;
            position: absolute;
            top: 430px;
            left: 50%;
            margin: 300px 0 20px -282px;
            width: 564px;
        }

    </style>

</head>

<body>

<div class="content">
    <h1><span>404</span> - <?=$this->lang->line('error_404_header')?></h1>
    <h2>Esto es debido a un error humano</h2>
    <a href="<?=base_url()?>"><?=$this->lang->line('error_404_back')?></a>
</div>

</body>

</html>