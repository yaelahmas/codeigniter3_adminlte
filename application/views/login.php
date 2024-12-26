<div class="login-box">
	<div class="login-logo">
		<a href="<?= base_url('login'); ?>"><b>Admin</b>LTE</a>
	</div>

	<div class="login-box-body">
		<p class="login-box-msg">Sign in to start your session</p>
		<?= $this->session->flashdata('message'); ?>
		<?= form_open(base_url('login')); ?>
		<!-- <div class="form-group <?= form_error('identity') ? 'has-error' : ''; ?>">
			<label for="identity">Username or email</label>
			<input type="text" class="form-control" placeholder="Username or email" name="identity" id="identity" value="<?= set_value('identity'); ?>">
			<?= form_error('identity', '<span id="identity-error" class="help-block">', '</span>'); ?>
		</div> -->
		<div class="form-group  <?= form_error('identity') ? 'has-error' : ''; ?>">
			<label for="identity">Username or email</label>
			<?= form_input($identity); ?>
			<?= form_error('identity', '<span class="help-block">', '</span>'); ?>
		</div>

		<div class="form-group  <?= form_error('password') ? 'has-error' : ''; ?>">
			<label for="password">Password</label>
			<a href="<?= base_url('forgot-password'); ?>" class="pull-right">Forgot password?</a>
			<?= form_input($password); ?>
			<?= form_error('password', '<span class="help-block">', '</span>'); ?>
		</div>

		<!-- <div class="form-group  <?= form_error('password') ? 'has-error' : ''; ?>">
			<label for="password">Password</label>
			<a href="<?= base_url('forgot-password'); ?>" class="pull-right">Forgot password?</a>
			<input type="password" class="form-control" placeholder="Password" name="password" id="password">
			<?= form_error('password', '<span id="password-error" class="help-block">', '</span>'); ?>
		</div> -->

		<div class="row">
			<div class="col-xs-8">
				<div class="checkbox">
					<label>
						<input type="checkbox"> Remember Me
					</label>
				</div>
			</div>
			<div class="col-xs-4">
				<button type="submit" class="btn btn-primary btn-block">Log In</button>
			</div>
		</div>
		<?= form_close(); ?>

		<p>
			<a href="<?= base_url('resend-verification'); ?>" class="text-center">Resend verification email</a> <br />
			<a href="<?= base_url('register'); ?>" class="text-center">Register a new membership</a>
		</p>

	</div>
</div>