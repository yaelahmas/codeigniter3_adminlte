<?php

defined('BASEPATH') or exit('No direct script access allowed');

class UserToken_model extends CI_Model
{
	protected $table 		= 'user_token';
	protected $primaryKey 	= 'selector';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['string', 'email']);
		$this->load->model('User_model', 'user');
	}

	public function findAll()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		return $this->db->get()->result_array();
	}

	public function find($action = NULL, $user_id = NULL)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if ($action)  $this->db->where('user_token.action', $action);
		if ($user_id) $this->db->where('user_token.user_id', $user_id);
		return $this->db->get()->row_array();
	}

	public function findBy($key = NULL, $value = NULL)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if ($key && $value)  $this->db->where($key, $value);
		else show_404();
		return $this->db->get()->row_array();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($selector, $data)
	{
		$this->db->where($this->primaryKey, $selector);
		return $this->db->update($this->table, $data);
	}

	public function delete($selector)
	{
		$this->db->where($this->primaryKey, $selector);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}

	public function findByCode($code)
	{
		$token = $this->auth->retrieveSelectorValidatorCouple($code);
		$user_token = $this->db->get_where($this->table, ['selector' => $token->selector], 1)->row();
		if ($user_token) {
			if ($this->auth->verifyPassword($token->validator, $user_token->code)) {
				return $user_token;
			}
		}
		return FALSE;
	}

	public function setRegister($data)
	{
		$this->db->trans_start();
		$this->db->insert('users', $data);
		$user_id = $this->db->insert_id();

		$this->db->insert('user_has_role', [
			'user_id' => $user_id,
			'role_id' => $data['role_id'],
		]);

		$token = $this->auth->generateSelectorValidatorCouple(20, 80);

		$this->db->insert($this->table, [
			'user_id' 		=> $user_id,
			'action' 		=> 'verification_email',
			'selector' 		=> $token->selector,
			'code' 			=> $token->validator_hashed,
			'expired_at' 	=> time()
		]);

		$data_email = [
			'email' 	=> $data['email'],
			'message' 	=> 'email/verification_email',
			'url_token' => base_url('verify?token=' . $token->user_code)
		];

		if (!verification_email($data_email)) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function setResendVerification($email)
	{
		$this->db->trans_start();
		$token = $this->auth->generateSelectorValidatorCouple(20, 80);
		$user = $this->user->findBy('email', $email);
		$user_token = $this->find('verification_email', $user['user_id']);

		if ($user_token) {
			$this->update($user_token['selector'], [
				'action' 		=> 'verification_email',
				'selector' 		=> $token->selector,
				'code' 			=> $token->validator_hashed,
				'expired_at' 	=> time()
			]);
		} else {
			$this->db->insert($this->table, [
				'user_id' 		=> $user['user_id'],
				'action' 		=> 'verification_email',
				'selector' 		=> $token->selector,
				'code' 			=> $token->validator_hashed,
				'expired_at' 	=> time()
			]);
		}

		$data_email = [
			'email' 	=> $user['email'],
			'message' 	=> 'email/verification_email',
			'url_token' => base_url('verify?token=' . $token->user_code)
		];

		if (!verification_email($data_email)) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function setPasswordReset($email)
	{
		$this->db->trans_start();
		$token = $this->auth->generateSelectorValidatorCouple(20, 80);
		$user = $this->user->findBy('email', $email);
		$user_token = $this->find('reset_password', $user['user_id']);

		if ($user_token) {
			$this->update($user_token['selector'], [
				'action' 		=> 'reset_password',
				'selector' 		=> $token->selector,
				'code' 			=> $token->validator_hashed,
				'expired_at' 	=> time()
			]);
		} else {
			$this->db->insert($this->table, [
				'user_id' 		=> $user['user_id'],
				'action' 		=> 'reset_password',
				'selector' 		=> $token->selector,
				'code' 			=> $token->validator_hashed,
				'expired_at' 	=> time()
			]);
		}

		$data_email = [
			'email' 	=> $user['email'],
			'message' 	=> 'email/reset_password',
			'url_token' => base_url('reset-password/' . $token->user_code)
		];

		if (!reset_password($data_email)) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function setVerified($selector, $user_id)
	{
		$this->db->trans_start();
		$this->db->delete($this->table, ['selector' => $selector]);
		$this->db->update('users', ['verified' => 1], ['user_id' => $user_id]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
}
