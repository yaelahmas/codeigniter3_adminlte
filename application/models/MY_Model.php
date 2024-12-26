<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['string', 'email']);
	}

	public function getUserByEmail($email)
	{
		$user = $this->db->get_where('users', ['email' => $email])->row_array();
		if (!$user)
			return FALSE;

		$user = $this->getUserById($user['user_id']);
		return $user;
	}

	public function getUserById($user_id)
	{
		$user = $this->db->get_where('users', ['user_id' => $user_id])->row_array();
		if (!$user)
			return FALSE;

		$user['role'] = $this->db->get_where('role', ['role_id' => $user['role_id']])->row_array();
		$user['default_module'] = $this->db->get_where('module', ['module_id' => $user['role']['module_id']])->row_array();
		return $user;
	}

	public function getRoleAccess($role_id)
	{
		return $this->db->get_where('role_access', ['role_id' => $role_id])->row_array();
	}

	public function getMenu()
	{
		$user = $this->getUserById($this->session->userdata('user_id'));
		$where_role = $user['role']['role_id'] ? $user['role']['role_id'] : 'null';
		$sql = 'SELECT * FROM menu 
					LEFT JOIN role_menu USING (menu_id) 
					LEFT JOIN module USING (module_id)
				WHERE role_id IN ( ' . $where_role . ')
				ORDER BY menu.urut';

		$menus = $this->db->query($sql)->result_array();

		$menu_tree = array();
		foreach ($menus as $menu) {
			if ($menu['parent_id'] == 0) {
				$menu_tree[] = $menu;
			} else {
				$this->addChildMenu($menu_tree, $menu);
			}
		}
		return $menu_tree;
	}

	private function addChildMenu(&$menu_tree, $menu)
	{
		if (is_array($menu_tree) || is_object($menu_tree)) {
			foreach ($menu_tree as &$parent_menu) {
				if ($parent_menu['menu_id'] == $menu['parent_id']) {
					$parent_menu['child'][] = $menu;
					return;
				}
				$this->addChildMenu($parent_menu['child'], $menu);
			}
		}
	}

	public function updateLastLogin($user_id)
	{
		$this->db->update('users', ['last_login' => time()], ['user_id' => $user_id]);
		return $this->db->affected_rows() == 1;
	}
}
