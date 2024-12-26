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

	<link type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
	<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
			<a href="<?= base_url('dashboard'); ?>" class="logo">
				<span class="logo-mini"><b>A</b>LT</span>
				<span class="logo-lg"><b>Admin</b>LTE</span>
			</a>
			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown messages-menu">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-envelope-o"></i>
								<span class="label label-success">4</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 4 messages</li>
								<li>
									<ul class="menu">
										<li>
											<a href="javascript:void(0);">
												<div class="pull-left">
													<img
														src="<?= base_url('public/images/user/') . $user['image']; ?>"
														class="img-circle"
														alt="User Image" />
												</div>
												<h4>
													Support Team
													<small><i class="fa fa-clock-o"></i> 5 mins</small>
												</h4>
												<p>Why not buy a new awesome theme?</p>
											</a>
										</li>
									</ul>
								</li>
								<li class="footer"><a href="javascript:void(0);">See All Messages</a></li>
							</ul>
						</li>

						<li class="dropdown notifications-menu">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<span class="label label-warning">10</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 10 notifications</li>
								<li>
									<ul class="menu">
										<li>
											<a href="javascript:void(0);">
												<i class="fa fa-users text-aqua"></i> 5 new members
												joined today
											</a>
										</li>
									</ul>
								</li>
								<li class="footer"><a href="javascript:void(0);">View all</a></li>
							</ul>
						</li>

						<li class="dropdown user user-menu">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
								<img
									src="<?= base_url('public/images/user/') . $user['image']; ?>"
									class="user-image user-image"
									alt="User Image" />
								<span class="hidden-xs user_name"><?= $user['name']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<img
										src="<?= base_url('public/images/user/') . $user['image']; ?>"
										class="img-circle user_image"
										alt="User Image" />

									<p>
										<span class="user_name"><?= $user['name']; ?></span>
										<small>Member since Nov. 2012</small>
									</p>
								</li>

								<li class="user-footer">
									<div class="pull-left">
										<a href="<?= base_url('profile'); ?>" class="btn btn-default btn-flat">Profile</a>
									</div>
									<div class="pull-right">
										<a href="<?= base_url('logout'); ?>" class="btn btn-default btn-flat">Log out</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- =============================================== -->

		<aside class="main-sidebar">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img
							src="<?= base_url('public/images/user/') . $user['image']; ?>"
							class="img-circle user_image"
							alt="User Image" />
					</div>
					<div class="pull-left info">
						<p class="user_name"><?= $user['name']; ?></p>
						<a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>

				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">MAIN NAVIGATION</li>
					<?= build_menu($menu); ?>
					<li><a href="<?= base_url('logout'); ?>"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
				</ul>
			</section>
		</aside>

		<!-- =============================================== -->

		<?= $contents; ?>

		<!-- =============================================== -->

		<footer class="main-footer">
			<div class="pull-right hidden-xs"><b>Version</b> 2.4.18</div>
			<strong>Copyright &copy; 2014-2019
				<a href="https://adminlte.io">AdminLTE</a>.</strong>
			All rights reserved. <?= $this->session->flashdata('message'); ?>
		</footer>

	</div>

	<script>
		$(document).ready(function() {
			var url = window.location;

			$('.sidebar-menu').tree();

			$('ul.sidebar-menu a').filter(function() {
				return this.href == url;
			}).parent().addClass('active');

			$('ul.treeview-menu a').filter(function() {
				return this.href == url;
			}).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
		});
	</script>
</body>

</html>