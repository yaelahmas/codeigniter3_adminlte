<div class="register-box">
	<div class="register-logo">
		<a href="<?= base_url('register'); ?>"><b>Admin</b>LTE</a>
	</div>

	<div class="register-box-body">
		<p class="login-box-msg">Register a new membership</p>
		<?= $this->session->flashdata('message'); ?>
		<?= form_open(base_url('register')); ?>
		<div class="form-group <?= form_error('name') ? 'has-error' : ''; ?>">
			<label for="name">Full name</label>
			<input type="text" class="form-control" placeholder="Full name" name="name" id="name" value="<?= set_value('name'); ?>">
			<?= form_error('name', '<span id="name-error" class="help-block">', '</span>'); ?>
		</div>
		<div class="form-group <?= form_error('email') ? 'has-error' : ''; ?>">
			<label for="email">Email address</label>
			<input type="email" class="form-control" placeholder="Email address" name="email" id="email" value="<?= set_value('email'); ?>">
			<?= form_error('email', '<span id="email-error" class="help-block">', '</span>'); ?>
		</div>
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
		<div class="row">
			<div class="col-xs-8">
				<div class="checkbox">
					<label>
						<input type="checkbox"> I agree to the <a href="#">terms</a>
					</label>
				</div>
			</div>
			<div class="col-xs-4">
				<button type="submit" class="btn btn-primary btn-block">Register</button>
			</div>
		</div>
		<?= form_close(); ?>

		<p><a href="<?= base_url('login'); ?>" class="text-center">I already have a membership</a></p>
	</div>
</div>