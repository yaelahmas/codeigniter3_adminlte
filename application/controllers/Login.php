<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$fieldType = filter_var($this->input->post('identity'), FILTER_VALIDATE_EMAIL) ? 'Email' : 'Username';
		if ($fieldType == 'Email') {
			$this->form_validation->set_rules('identity', 'Email address', 'required|valid_email|is_not_registered');
			$this->form_validation->set_message('valid_email', 'Please enter a valid {field}');
		} else {
			$this->form_validation->set_rules('identity', 'Username', 'required|is_not_registered');
		}
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_message('required', 'Please enter a {field}');

		if ($this->form_validation->run() == FALSE) {
			$this->data['identity'] = [
				'type' => 'text',
				'class' => 'form-control',
				'id' => 'identity',
				'placeholder' => 'Username or email',
				'name' => 'identity',
				'value' => $this->form_validation->set_value('identity'),
			];

			$this->data['password'] = [
				'type' => 'password',
				'class' => 'form-control',
				'id' => 'password',
				'placeholder' => 'Password',
				'name' => 'password',
			];

			$this->render('layouts/register', 'login', $this->data);
		} else {
			$identity = $this->input->post('identity');
			$password = $this->input->post('password');
			$user = $this->credentials($identity, $password);
			if ($user) {
				return $this->setUser($user);
			} else {
				redirect('login');
			}
		}
	}

	// private function validate()
	// {
	// 	$fieldType = filter_var($this->input->post('identity'), FILTER_VALIDATE_EMAIL) ? 'Email' : 'Username';
	// 	if ($fieldType == 'Email') {
	// 		$this->form_validation->set_rules('identity', 'Email address', 'required|valid_email|is_not_registered');
	// 		$this->form_validation->set_message('valid_email', 'Please enter a valid {field}');
	// 	} else {
	// 		$this->form_validation->set_rules('identity', 'Username', 'required|is_not_registered');
	// 	}
	// 	$this->form_validation->set_rules('password', 'Password', 'required');
	// 	$this->form_validation->set_message('required', 'Please enter a {field}');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		return false;
	// 	}
	// 	return true;
	// }

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
