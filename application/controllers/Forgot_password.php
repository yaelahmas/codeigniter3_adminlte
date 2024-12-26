<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forgot_password extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserToken_model', 'user_token');
	}

	public function index()
	{
		if ($this->validate()) return false;
		$this->render('layouts/register', 'forgot_password', $this->data);
	}

	private function validate()
	{
		$this->form_validation->set_rules('identity', 'Email address', 'required|valid_email|is_not_registered');
		$this->form_validation->set_message('required', '{field} field is required.');
		$this->form_validation->set_message('valid_email', 'Please enter a valid {field}');
		if ($this->form_validation->run() == FALSE) {
			return false;
		} else {
			$resend = $this->user_token->setPasswordReset($this->input->post('identity'));
			if ($resend) {
				$this->session->set_flashdata('message', '<div class="alert alert-success">Password reset link sent successfully, Please check your email</div>');
				redirect('login');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Something went wrong, Please try again later</div>');
				redirect('resend-verification');
			}
		}
	}
}
