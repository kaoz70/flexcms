<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>FlexCMS Login</title>
	<?php \theme\Assets::css_group('admin', $assets_css); ?>
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/mootools-core-1.4.5-full-compat-yc.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/mootools-more-1.4.0.1.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/admin/scripts/login.js')?>"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
	      rel="stylesheet">
</head>
<body class="login">

	<?
    $data['error'] = $error;
    $data['form_action'] = base_url('admin/validate');
    $this->load->view('admin/login_form_view', $data);
    ?>
	
</body>
</html>