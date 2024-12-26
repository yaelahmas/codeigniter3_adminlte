<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_model extends CI_Model
{
	protected $table 		= 'module';
	protected $primaryKey 	= 'module_id';

	public function findAll($key = NULL, $value = NULL)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if ($key && $value)  $this->db->where($key, $value);
		return $this->db->get()->result_array();
	}

	public function find($module_id = NULL, $key = NULL, $value = NULL)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if ($module_id)  $this->db->where('module.module_id', $module_id);
		if ($key && $value)  $this->db->where($key, $value);
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

	public function update($id, $data)
	{
		$this->db->where($this->primaryKey, $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where($this->primaryKey, $id);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}
}
