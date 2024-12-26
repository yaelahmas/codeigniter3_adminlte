<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserToken_model', 'user_token');
	}

	public function index()
	{
		if ($this->validate()) return false;
		$this->render('layouts/register', 'register', $this->data);
	}

	private function validate()
	{
		$this->form_validation->set_rules('name', 'Full name', 'required');
		$this->form_validation->set_rules('email', 'Email address', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|valid_password');
		$this->form_validation->set_rules('passconf', 'Confirm password', 'matches[password]');

		$this->form_validation->set_message('required', 'Please enter a {field}');
		$this->form_validation->set_message('valid_email', 'Please enter a valid {field}');
		$this->form_validation->set_message('is_unique', '{field} already registered in the system');
		$this->form_validation->set_message('matches', 'Password\'s field does not match');
		if ($this->form_validation->run() == FALSE) {
			return false;
		} else {
			$data = [
				'name' 		=> $this->input->post('name'),
				'username' 	=> substr($this->input->post('email'), 0, strpos($this->input->post('email'), '@')),
				'email' 	=> $this->input->post('email'),
				'password' 	=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'role_id' 	=> 2,
			];

			$register = $this->user_token->setRegister($data);

			if ($register) {
				$this->session->set_flashdata('message', '<div class="alert alert-success">Register new account successfully! Please verify your email first</div>');
				redirect('login');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Something went wrong, Please try again later</div>');
				redirect('register');
			}
		}
	}
}
