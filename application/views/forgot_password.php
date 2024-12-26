<div class="login-box">
	<div class="login-logo">
		<a href="<?= base_url('forgot-password'); ?>"><b>Admin</b>LTE</a>
	</div>

	<div class="login-box-body">
		<p class="login-box-msg">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
		<?= $this->session->flashdata('message'); ?>
		<?= form_open(base_url('forgot-password')); ?>
		<div class="form-group <?= form_error('identity') ? 'has-error' : ''; ?>">
			<label for="identity">Email address</label>
			<input type="text" class="form-control" placeholder="Email address" name="identity" id="identity" value="<?= set_value('identity'); ?>">
			<?= form_error('identity', '<span id="identity-error" class="help-block">', '</span>'); ?>
		</div>

		<button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 10px;">Send Password Reset Link</button>
		<?= form_close(); ?>

		<p><a href="<?= base_url('login'); ?>" class="text-center">Login</a></p>

	</div>
</div>