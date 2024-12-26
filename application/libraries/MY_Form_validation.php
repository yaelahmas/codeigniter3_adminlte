<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Form_validation  extends CI_Form_validation
{
	protected $ci;

	public function __construct($rules = array())
	{
		parent::__construct($rules);
		$this->ci = &get_instance();
		$this->ci->load->model('User_model', 'user');
	}

	public function is_not_registered($identity)
	{
		$fieldType = filter_var($this->ci->input->post('identity'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$user = $this->ci->user->findBy($fieldType, $identity);
		if (!$user) {
			$this->ci->form_validation->set_message('is_not_registered', '{field} is not registered in the system.');
			return FALSE;
		}
		return TRUE;
	}

	public function email_check()
	{
		$post = $this->ci->input->post(null, TRUE);
		$user_email = $this->ci->db->query("SELECT * FROM users WHERE email = '$post[email]' AND user_id != '$post[user_id]'");
		if ($user_email->num_rows() > 0) {
			$this->ci->form_validation->set_message('email_check', '{field} already in the system');
			return FALSE;
		}
		return TRUE;
	}

	public function name_check()
	{
		$post = $this->ci->input->post(null, TRUE);
		$user_name = $this->ci->db->query("SELECT * FROM users WHERE name = '$post[name]' AND user_id != '$post[user_id]'");
		if ($user_name->num_rows() > 0) {
			$this->ci->form_validation->set_message('name_check', '{field} already in the system');
			return FALSE;
		}
		return TRUE;
	}

	public function module_check()
	{
		$post = $this->ci->input->post(null, TRUE);
		$module = $this->ci->db->query("SELECT * FROM module WHERE nama_module = '$post[nama_module]' AND module_id != '$post[module_id]'");
		if ($module->num_rows() > 0) {
			$this->ci->form_validation->set_message('module_check', '{field} already in the system');
			return FALSE;
		}
		return TRUE;
	}

	public function role_check()
	{
		$post = $this->ci->input->post(null, TRUE);
		$role = $this->ci->db->query("SELECT * FROM role WHERE role = '$post[role]' AND role_id != '$post[role_id]'");
		if ($role->num_rows() > 0) {
			$this->ci->form_validation->set_message('role_check', '{field} already in the system');
			return FALSE;
		}
		return TRUE;
	}

	public function valid_password($password = '')
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (empty($password)) {
			$this->ci->form_validation->set_message('valid_password', 'Please enter a {field}');
			return FALSE;
		}
		// if (preg_match_all($regex_lowercase, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_uppercase, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_number, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
		// 	return FALSE;
		// }
		// if (preg_match_all($regex_special, $password) < 1)
		// {
		// 	$this->ci->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
		// 	return FALSE;
		// }
		if (strlen($password) < 8) {
			$this->ci->form_validation->set_message('valid_password', '{field} field must be at least 8 characters in length');
			return FALSE;
		}

		if (strlen($password) > 32) {
			$this->ci->form_validation->set_message('valid_password', '{field} field cannot exceed 32 characters in length');
			return FALSE;
		}
		return TRUE;
	}
}
