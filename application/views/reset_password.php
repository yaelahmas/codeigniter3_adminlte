<div class="login-box">
	<div class="login-logo">
		<a href="<?= current_url(); ?>"><b>Admin</b>LTE</a>
	</div>

	<div class="register-box-body">
		<!-- <p class="login-box-msg">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p> -->
		<?= $this->session->flashdata('message'); ?>
		<?= form_open(current_url()); ?>
		<div class="form-group <?= form_error('password') ? 'has-error' : ''; ?>">
			<label for="password">Password</label>
			<input type="password" class="form-control" placeholder="Password" name="password" id="password">
			<?= form_error('password', '<span id="password-error" class="help-block">', '</span>'); ?>
		</div>
		<div class="form-group <?= form_error('passconf') ? 'has-error' : ''; ?>">
			<label for="passconf">Confirm Password</label>
			<input type="password" class="form-control" placeholder="Confirm password" name="passconf" id="passconf">
			<?= form_error('passconf', '<span id="passconf-error" class="help-block">', '</span>'); ?>
		</div>

		<button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 10px;">Reset Password</button>
		<?= form_close(); ?>

		<p><a href="<?= base_url('login'); ?>" class="text-center">Login</a></p>
	</div>
</div>