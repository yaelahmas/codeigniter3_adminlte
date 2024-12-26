<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verify extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserToken_model', 'user_token');
		$this->load->model('User_model', 'user');
	}

	public function index()
	{
		$code = $this->input->get('token');
		if (!$code) {
			show_404();
		}

		$validate = $this->validateToken($code);

		if ($validate) {
			if ($this->user_token->setVerified($validate->selector, $validate->user_id)) {
				$this->session->set_flashdata('message', '<div class="alert alert-success">Email has been verified successfully, Please login</div>');
				redirect('login');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Something went wrong, Please try again later</div>');
			}
		}

		redirect('resend-verification');
	}

	private function validateToken($code)
	{
		$user_token = $this->user_token->findByCode($code);
		if (!is_object($user_token)) {
			show_404();
		} else {
			if (EXPIRATION > 0) {
				$expiration = EXPIRATION;
				if (time() - $user_token->expired_at > $expiration) {
					$this->user_token->delete($user_token->selector);
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token has expired, Please resend password reset link</div>');
					redirect('forgot-password');
				}
			}
			return $user_token;
		}
	}
}
