<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Role_model extends CI_Model
{
	protected $table 		= 'role';
	protected $primaryKey 	= 'role_id';

	public function findAll()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		return $this->db->get()->result_array();
	}

	public function find($role_id = NULL)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if ($role_id)  $this->db->where('role.role_id', $role_id);
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

	public function setRoleAccess($role_id)
	{
		$result = $this->getRoleAccess($role_id);

		if (is_null($result)) {
			$module = $this->getRoleAccessModule();
			$data = array('role_id' => $role_id, 'access' => json_encode($module));
			$this->insertRoleAccess($data);
			$result = $this->getRoleAccess($role_id);
		}

		return $result;
	}

	public function getRoleAccessModule()
	{
		$this->db->select('url_module');
		$this->db->from('module');
		$this->db->where('login', 'yes');
		$query = $this->db->get();
		$data = [];
		foreach ($query->result_array() as $key => $value) {
			$row = [];
			$row['module'] = $value['url_module'];
			$row['access'] = 0;
			$row['create_records'] = 0;
			$row['read_records'] = 0;
			$row['update_records'] = 0;
			$row['delete_records'] = 0;
			$data[] = $row;
		}
		return $data;
	}

	public function insertRoleAccess($data)
	{
		$this->db->trans_start();
		$this->db->insert('role_access', $data);
		$this->db->trans_complete();
	}

	private function getRoleAccess($role_id)
	{
		$this->db->select('role_id, access');
		$this->db->from('role_access');
		$this->db->where('role_id', $role_id);
		$query = $this->db->get();
		$result = $query->row();
		return $result;
	}

	public function updateRoleAccess($id, $data)
	{
		$this->db->where($this->primaryKey, $id);
		return $this->db->update('role_access', $data);
	}

	public function deleteRoleAccess($role_id)
	{
		$this->db->where('role_id', $role_id);
		$this->db->delete('role_access');
		return $this->db->affected_rows();
	}
}
