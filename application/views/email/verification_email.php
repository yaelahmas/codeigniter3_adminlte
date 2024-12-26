<html>

<head>
	<link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Overpass';
			font-size: 16px;
		}

		h1 {
			font-size: 50px;
		}

		.logo-container {
			text-align: center;
		}

		.logo {
			display: inline-block;
			margin: auto;
		}

		.wrapper {
			max-width: 650px;
			margin: auto;
			margin-top: 20px;
		}

		.button {
			text-decoration: none;
			display: inline-block;
			margin-bottom: 0;
			font-weight: normal;
			text-align: center;
			vertical-align: middle;
			background-image: none;
			border: 1px solid transparent;
			white-space: nowrap;
			padding: 7px 15px;
			line-height: 1.5384616;
			background-color: #0277bd;
			border-color: #0277bd;
			color: #FFFFFF;
		}

		.button span {
			font-family: 'Overpass';
			font-size: 16px;
			color: #FFFFFF;
		}

		p {
			font-size: 16px;
		}

		.alert {
			display: inline-block;
			margin-bottom: 0;
			font-weight: normal;
			text-align: left;
			vertical-align: middle;
			background-image: none;
			border: 1px solid transparent;
			padding: 7px 15px;
			line-height: 1.5384616;
			background-color: #ffb4b4;
			border-color: #ff9c9c;
			color: #c34949;
			font-size: 16px;
		}
	</style>
</head>

<body>
	<div class="wrapper">
		<div class="logo-container">
			<h1><b><?= FROM_NAME; ?></b></h1>
		</div>
		<p><b>Hello!</b></p>
		<p>Please click the button below to verify your email address.</p>
		<p>
			<a class="button" href="<?= $url_token; ?>" target="_blank">
				<span>Verify Email Address</span></a>
		</p>
		<p>If you did not create an account, no further action is required.</p>
		<p>
			Regards, <br />
			<strong><?= FROM_NAME; ?></strong> <br />
			<hr />
			If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <?= $url_token; ?>
		</p>
	</div>
</body>

</html>