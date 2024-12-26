<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $title; ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?= base_url('public'); ?>/bower_components/bootstrap/dist/css/bootstrap.min.css?r=<?= time(); ?>">
	<link rel="stylesheet" href="<?= base_url('public'); ?>/bower_components/font-awesome/css/font-awesome.min.css?r=<?= time(); ?>">
	<link rel="stylesheet" href="<?= base_url('public'); ?>/bower_components/Ionicons/css/ionicons.min.css?r=<?= time(); ?>">
	<link rel="stylesheet" href="<?= base_url('public'); ?>/dist/css/AdminLTE.min.css?r=<?= time(); ?>">
	<?php if (@$stylesheets) {
		foreach ($stylesheets as $file) {
			echo '<link rel="stylesheet" href="' . $file . '?r=' . time() . '"/>' . "\n";
		}
	} ?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<!-- =============================================== -->
	<script src="<?= base_url('public'); ?>/bower_components/jquery/dist/jquery.min.js?r=<?= time(); ?>"></script>
	<script src="<?= base_url('public'); ?>/bower_components/bootstrap/dist/js/bootstrap.min.js?r=<?= time(); ?>"></script>
	<?php if (@$scripts) {
		foreach ($scripts as $file) {
			echo '<script src="' . $file . '?r=' . time() . '"/></script>' . "\n";
		}
	} ?>
</head>

<body class="hold-transition register-page">
	<?= $contents; ?>
</body>

</html>