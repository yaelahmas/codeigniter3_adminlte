<?php
defined('BASEPATH') or exit('No direct script access allowed');


class User extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		require_once APPPATH . 'third_party/ssp.php';
		$this->load->model('User_model', 'user');
		$this->addStylesheet(base_url('public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'));
		$this->addStylesheet(base_url('public/bower_components/sweetalert2/sweetalert2.min.css'));
		$this->addStylesheet(base_url('public/dist/css/skins/_all-skins.min.css'));

		$this->addScript(base_url('public/bower_components/datatables.net/js/jquery.dataTables.min.js'));
		$this->addScript(base_url('public/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'));
		$this->addScript(base_url('public/bower_components/sweetalert2/sweetalert2.min.js'));
		$this->addScript(base_url('public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'));
		$this->addScript(base_url('public/bower_components/fastclick/lib/fastclick.js'));
		$this->addScript(base_url('public/dist/js/adminlte.min.js'));
	}

	public function index()
	{
		if (!$this->hasAccessRead()) return $this->render('layouts/page', 'errors/access-denied', $this->data);
		$this->render('layouts/page', 'pages/user', $this->data);
	}

	public function datatables()
	{
		$table = "users";
		$primaryKey = "user_id";

		$sql_details = array(
			"host"  => $this->db->hostname,
			"user"  => $this->db->username,
			"pass"  => $this->db->password,
			"db"    => $this->db->database,
		);

		$columns = array(
			array(
				'db' => '`users`.`user_id`',
				'dt' => 0,
				'field' => 'user_id'
			),
			array(
				'db' => '`users`.`user_id`',
				'dt' => 1,
				'field' => 'user_id',
				'formatter' => function ($d, $row) {
					$user = $this->db->get_where('users', ['user_id' => $row['user_id']])->row_array();
					return '<img src="' . base_url('public/images/user/' . $user['image']) . '" class="img-thumbnail" style="max-width:70px;">';
				}
			),
			array(
				'db' => '`users`.`name`',
				'dt' => 2,
				'field' => 'name'
			),
			array(
				'db' => '`users`.`email`',
				'dt' => 3,
				'field' => 'email'
			),
			array(
				'db' => '`role`.`role`',
				'dt' => 4,
				'field' => 'role',
				'formatter' => function ($d, $row) {
					if ($d == null) {
						$role = '<span class="text-red">Role untuk user belum di set.</span>';
					} else {
						$role = $d;
					}
					return $role;
				}
			),
			array(
				'db' => '`users`.`user_id`',
				'dt' => 5,
				'field' => 'user_id',
				'formatter' => function ($d, $row) {
					return '
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-edit" data-id="' . $row['user_id'] . '">Edit</a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-delete" data-id="' . $row['user_id'] . '">Delete</a>
                    ';
				}
			),
		);

		$joinQuery = "FROM `users` LEFT JOIN `role` ON (`users`.`role_id` = `role`.`role_id`)";

		echo json_encode(
			SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery)
		);
	}

	public function get()
	{
		if ($this->input->is_ajax_request() == true) {
			$data = $this->user->find($this->input->get('user_id'));
			$data['image'] = base_url('public/images/user/' . $data['image']);
			echo json_encode($data);
		}
	}

	public function create()
	{
		if ($this->input->is_ajax_request() == true) {
			if (!$this->hasAccessCreate()) {
				echo json_encode(['statusCode' => 403, 'message' => 'Access Denied']);
				exit;
			}

			$this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[users.name]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[users.email]');
			$this->form_validation->set_rules('role_id', 'Role', 'trim|required');
			$this->form_validation->set_message('required', 'Please enter a {field}');
			$this->form_validation->set_message('is_unique', '{field} already in the system');
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(['statusCode' => 400, 'message' => $this->form_validation->error_array()]);
			} else {
				if (!empty($_FILES['image']['name'])) {
					$config['upload_path'] = "./public/images/user/";
					$config['allowed_types'] = 'jpeg|jpg|png';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('image')) {
						$data = [
							'name' 			=> $this->input->post('name'),
							'email' 		=>  strtolower($this->security->xss_clean($this->input->post('email'))),
							'password' 		=>  password_hash('password', PASSWORD_DEFAULT),
							'image' 		=> $this->upload->data('file_name'),
							'role_id' 		=> $this->input->post('role_id'),
							'is_verified'	=> 1,
						];
					} else {
						echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
					}
				} else {
					$data = [
						'name' 			=> $this->input->post('name'),
						'email' 		=>  strtolower($this->security->xss_clean($this->input->post('email'))),
						'password' 		=>  password_hash('password', PASSWORD_DEFAULT),
						'role_id' 		=> $this->input->post('role_id'),
						'is_verified'	=> 1,
					];
				}

				if ($this->user->insert($data)) {
					echo json_encode(['statusCode' => 200, 'message' => 'New User has been created successfully']);
				} else {
					echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
				}
			}
		}
	}

	public function update()
	{
		if ($this->input->is_ajax_request() == true) {
			if (!$this->hasAccessUpdate()) {
				echo json_encode(['statusCode' => 403, 'message' => 'Access Denied']);
				exit;
			}
			$this->form_validation->set_rules('name', 'Name', 'trim|required|name_check');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|email_check');
			$this->form_validation->set_rules('role_id', 'Role', 'trim|required');
			$this->form_validation->set_message('required', 'Please enter a {field}');
			$this->form_validation->set_message('is_unique', '{field} already in the system');
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(['statusCode' => 400, 'message' => $this->form_validation->error_array()]);
			} else {
				if (!empty($_FILES['image']['name'])) {
					$user = $this->user->get($this->input->post('user_id'));
					if ($user['image'] != 'default.png') {
						unlink('./public/images/user/' . $user['image']);
					}

					$config['upload_path'] = "./public/images/user/";
					$config['allowed_types'] = 'jpeg|jpg|png';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('image')) {
						$data = [
							'name' 		=> $this->input->post('name'),
							'email' 	=>  strtolower($this->security->xss_clean($this->input->post('email'))),
							'image' 	=> $this->upload->data('file_name'),
							'role_id' 	=> $this->input->post('role_id'),
						];
					} else {
						echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
					}
				} else {
					$data = [
						'name'		=> $this->input->post('name'),
						'email'		=>  strtolower($this->security->xss_clean($this->input->post('email'))),
						'role_id'	=> $this->input->post('role_id'),
					];
				}

				if ($this->user->update($this->input->post('user_id'), $data)) {
					echo json_encode(['statusCode' => 200, 'message' => 'User has been updated successfully']);
				} else {
					echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
				}
			}
		}
	}

	public function delete()
	{
		if ($this->input->is_ajax_request() == true) {
			if (!$this->hasAccessDelete()) {
				echo json_encode(['statusCode' => 403, 'message' => 'Access Denied']);
				exit;
			}
			$user = $this->user->get($this->input->get('user_id'));
			if ($user['image'] != 'default.png') {
				unlink('./public/images/user/' . $user['image']);
			}

			if ($this->user->delete($this->input->get('user_id'))) {
				echo json_encode(['statusCode' => 200, 'message' => 'User has been deleted successfully']);
			} else {
				echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
			}
		}
	}
}
