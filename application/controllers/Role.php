<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Role extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		require_once APPPATH . 'third_party/ssp.php';
		$this->load->model('Role_model', 'role');
		$this->load->model('Module_model', 'module');
		$this->addStylesheet(base_url('public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'));
		$this->addStylesheet(base_url('public/bower_components/select2/dist/css/select2.min.css'));
		$this->addStylesheet(base_url('public/bower_components/sweetalert2/sweetalert2.min.css'));
		$this->addStylesheet(base_url('public/dist/css/skins/_all-skins.min.css'));

		$this->addScript(base_url('public/bower_components/datatables.net/js/jquery.dataTables.min.js'));
		$this->addScript(base_url('public/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'));
		$this->addScript(base_url('public/bower_components/select2/dist/js/select2.full.min.js'));
		$this->addScript(base_url('public/bower_components/sweetalert2/sweetalert2.min.js'));
		$this->addScript(base_url('public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'));
		$this->addScript(base_url('public/bower_components/fastclick/lib/fastclick.js'));
		$this->addScript(base_url('public/dist/js/adminlte.min.js'));
	}

	public function index()
	{
		if (!$this->hasAccessRead()) return $this->render('layouts/page', 'errors/access-denied', $this->data);
		$this->render('layouts/page', 'pages/role', $this->data);
	}

	public function datatables()
	{
		if ($this->input->is_ajax_request() == true) {
			$table = "role";
			$primaryKey = "role_id";

			$sql_details = array(
				"host"  => $this->db->hostname,
				"user"  => $this->db->username,
				"pass"  => $this->db->password,
				"db"    => $this->db->database,
			);

			$columns = array(
				array(
					'db' => '`role`.`role_id`',
					'dt' => 0,
					'field' => 'role_id'
				),
				array(
					'db' => '`role`.`role`',
					'dt' => 1,
					'field' => 'role'
				),
				array(
					'db' => '`module`.`nama_module`',
					'dt' => 2,
					'field' => 'nama_module'
				),
				array(
					'db' => '`role`.`role_id`',
					'dt' => 3,
					'field' => 'role_id',
					'formatter' => function ($d, $row) {
						return '
                        <a href="javascript:void(0);" class="btn btn-sm btn-success btn-flat btn-access" data-id="' . $row['role_id'] . '">Access</a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-flat btn-edit" data-id="' . $row['role_id'] . '">Edit</a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-flat btn-delete" data-id="' . $row['role_id'] . '">Delete</a>
                    ';
					}
				),
			);

			$joinQuery = "FROM `role` JOIN `module` ON (`role`.`module_id` = `module`.`module_id`)";

			echo json_encode(
				SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery)
			);
		}
	}

	public function get()
	{
		if ($this->input->is_ajax_request() == true) {
			if ($this->input->get('role_id')) {
				$data = $this->role->find($this->input->get('role_id'));
			} else {
				$data = $this->role->findAll();
			}
			echo json_encode($data);
		}
	}

	public function get_role_access()
	{
		if ($this->input->is_ajax_request() == true) {
			$this->data['role'] = $this->role->find($this->input->get('role_id'));
			$roleAccess = $this->role->setRoleAccess($this->input->get('role_id'));
			$this->data['roleAccess'] = json_decode($roleAccess->access);
			$this->data['module'] = $this->role->getRoleAccessModule();
			$response = ['data' => $this->load->view('pages/role_access', $this->data, true)];
			echo json_encode($response);
		}
	}

	public function create()
	{
		if ($this->input->is_ajax_request() == true) {
			$this->form_validation->set_rules('role', 'Role', 'trim|required|is_unique[role.role]');
			$this->form_validation->set_rules('module_id', 'Default module', 'trim|required');
			$this->form_validation->set_message('required', '{field} field is required.');
			$this->form_validation->set_message('is_unique', '{field} already in system.');
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(['statusCode' => 400, 'message' => $this->form_validation->error_array()]);
			} else {
				$data = [
					'role' 		=> $this->input->post('role'),
					'module_id' => $this->input->post('module_id')
				];
				if ($this->role->insert($data)) {
					echo json_encode(['statusCode' => 200, 'message' => 'New Role has been created successfully']);
				} else {
					echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
				}
			}
		}
	}

	public function update()
	{
		if ($this->input->is_ajax_request() == true) {
			$this->form_validation->set_rules('role', 'Role', 'trim|required|role_check');
			$this->form_validation->set_rules('module_id', 'Default module', 'trim|required');
			$this->form_validation->set_message('required', '{field} field is required.');
			$this->form_validation->set_message('is_unique', '{field} already in system.');
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(['statusCode' => 400, 'message' => $this->form_validation->error_array()]);
			} else {
				$data = [
					'role' 		=> $this->input->post('role'),
					'module_id' => $this->input->post('module_id')
				];
				if ($this->role->update($this->input->post('role_id'), $data)) {
					echo json_encode(['statusCode' => 200, 'message' => 'Role has been updated successfully']);
				} else {
					echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
				}
			}
		}
	}

	public function delete()
	{
		if ($this->input->is_ajax_request() == true) {
			if ($this->role->delete($this->input->get('role_id'))) {
				$this->role->deleteRoleAccess($this->input->get('role_id'));
				echo json_encode(['statusCode' => 200, 'message' => 'Role has been deleted successfully']);
			} else {
				echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
			}
		}
	}

	public function set_role_access()
	{
		$role_id = $this->input->post('role_id');
		$accessParams = $this->input->post('access');

		$modules = $this->role->getRoleAccessModule();
		$accessModule = [];
		foreach ($modules as $module) {
			$singleModule = ['module' => $module['module']];
			foreach ($module as $key => $val) {
				if (isset($accessParams[$module['module']][$key])) {
					$singleModule[$key] = $accessParams[$module['module']][$key] == 'on' ? 1 : $accessParams[$module['module']][$key];
				} else {
					$singleModule[$key] = 0;
				}
			}
			$accessModule[] = $singleModule;
		}

		if ($this->role->updateRoleAccess($role_id, ['access' => json_encode($accessModule)])) {
			echo json_encode(['statusCode' => 200, 'message' => 'Role access has been updated successfully']);
		} else {
			echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
		}
	}
}
