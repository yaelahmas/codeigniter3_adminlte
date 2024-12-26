<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
	protected $table 		= 'users';
	protected $primaryKey 	= 'user_id';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['string', 'email']);
	}

	public function findAll()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		return $this->db->get()->result_array();
	}

	public function find($user_id = NULL, $email = NULL)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if ($user_id)  $this->db->where('users.user_id', $user_id);
		if ($email) $this->db->where('users.email', $email);
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

	public function update($user_id, $data)
	{
		$this->db->where($this->primaryKey, $user_id);
		return $this->db->update($this->table, $data);
	}

	public function delete($user_id)
	{
		$this->db->where($this->primaryKey, $user_id);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}
}
