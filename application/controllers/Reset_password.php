<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reset_password extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserToken_model', 'user_token');
		$this->load->model('User_model', 'user');
	}

	public function index($code = null)
	{
		if (!$code) {
			show_404();
		}

		$validate = $this->validateToken($code);
		if ($validate) {
			if ($this->validateInput($validate)) return false;
		}

		$this->render('layouts/register', 'reset_password', $this->data);
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

	private function validateInput($data)
	{
		$this->form_validation->set_rules('password', 'Password', 'required|valid_password');
		$this->form_validation->set_rules('passconf', 'Confirm password', 'matches[password]');

		$this->form_validation->set_message('required', 'Please enter a {field}');
		$this->form_validation->set_message('matches', 'Password\'s field does not match');
		if ($this->form_validation->run() == FALSE) {
			return false;
		} else {
			$reset_password = $this->user->update($data->user_id, ['password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)]);
			if ($reset_password) {
				$this->db->delete('user_token', ['selector' => $data->selector]);
				$this->session->set_flashdata('message', '<div class="alert alert-success">Password updated successfully, Please login</div>');
				redirect('login');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Something went wrong, Please try again later</div>');
				redirect('reset-password/' . $data);
			}
		}
	}
}
