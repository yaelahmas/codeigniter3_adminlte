<div class="login-box">
	<div class="login-logo">
		<a href="<?= base_url('resend-verification'); ?>"><b>Admin</b>LTE</a>
	</div>

	<div class="login-box-body">
		<p class="login-box-msg">If you did not receive the previous verification email, we will be happy to send you another verification email</p>
		<?= $this->session->flashdata('message'); ?>
		<?= form_open(base_url('resend-verification')); ?>
		<div class="form-group <?= form_error('identity') ? 'has-error' : ''; ?>">
			<label for="identity">Email address</label>
			<input type="text" class="form-control" placeholder="Email address" name="identity" id="identity" value="<?= set_value('identity'); ?>">
			<?= form_error('identity', '<span id="identity-error" class="help-block">', '</span>'); ?>
		</div>

		<button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 10px;">Resend Verification Email</button>
		<?= form_close(); ?>

		<p><a href="<?= base_url('login'); ?>" class="text-center">Login</a></p>

	</div>
</div>