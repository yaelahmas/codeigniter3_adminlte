<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resend_verification extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserToken_model', 'user_token');
	}

	public function index()
	{
		if ($this->validate()) return false;
		$this->render('layouts/register', 'resend_verification', $this->data);
	}

	private function validate()
	{
		$this->form_validation->set_rules('identity', 'Email address', 'required|valid_email|is_not_registered');
		$this->form_validation->set_message('required', '{field} field is required.');
		$this->form_validation->set_message('valid_email', 'Please enter a valid {field}');
		if ($this->form_validation->run() == FALSE) {
			return false;
		} else {
			$user = $this->user->findBy('email', $this->input->post('identity'));
			if ($user['verified'] == 1) {
				$this->session->set_flashdata('message', '<div class="alert alert-warning">Email has been verified, Please login</div>');
				redirect('login');
			} else {
				$resend = $this->user_token->setResendVerification($this->input->post('identity'));
				if ($resend) {
					$this->session->set_flashdata('message', '<div class="alert alert-success">Resend verification email successfully! Please verify your email</div>');
					redirect('login');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Something went wrong, Please try again later</div>');
					redirect('resend-verification');
				}
			}
		}
	}
}
