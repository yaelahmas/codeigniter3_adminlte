<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config = array(
	'register' => array(
		array(
			'field' => 'name',
			'label' => 'Nama',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email|is_unique[users.email]'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|min_length[8]'
		),
		array(
			'field' => 'passconf',
			'label' => 'Confirm Password',
			'rules' => 'trim|matches[password]'
		)
	),
	'login' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required'
		),
	),
	'forgot_password' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email'
		)
	),
	'resend_verification' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email'
		)
	),
);
