<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('verification_email')) {
	function verification_email($data)
	{
		$CI = set_email();
		$CI->email->to($data['email']);
		$CI->email->from(FROM_ADDRESS, FROM_NAME);
		$CI->email->subject('Verify Email Address');
		$CI->email->message($CI->load->view($data['message'], $data, TRUE));

		if ($CI->email->send()) {
			return TRUE;
		} else {
			// echo $CI->email->print_debugger();
			// die;
			return FALSE;
		}
	}
}

if (!function_exists('reset_password')) {
	function reset_password($data)
	{
		$data["data"] = $data;

		$CI = set_email();

		$CI->email->from(FROM_ADDRESS, FROM_NAME);
		$CI->email->subject("Reset Password Notification");
		$CI->email->message($CI->load->view($data['message'], $data, TRUE));
		$CI->email->to($data["email"]);
		$status = $CI->email->send();

		return $status;
	}
}

if (!function_exists('set_email')) {
	function set_email()
	{
		$CI = &get_instance();
		$CI->load->library('email');

		$config['protocol'] = PROTOCOL;
		$config['mailpath'] = MAILPATH;
		$config['smtp_crypto'] = SMTP_CRYPTO;
		$config['smtp_host'] = SMTP_HOST;
		$config['smtp_port'] = SMTP_PORT;
		$config['smtp_user'] = SMTP_USER;
		$config['smtp_pass'] = SMTP_PASS;
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";

		$CI->email->initialize($config);

		return $CI;
	}
}
