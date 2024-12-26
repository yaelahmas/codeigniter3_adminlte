<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Module extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		require_once APPPATH . 'third_party/ssp.php';
		$this->load->model('Module_model', 'module');
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
		$this->render('layouts/page', 'pages/module', $this->data);
	}

	public function datatables()
	{
		if ($this->input->is_ajax_request() == true) {
			$table = "module";
			$primaryKey = "module_id";

			$sql_details = array(
				"host"  => $this->db->hostname,
				"user"  => $this->db->username,
				"pass"  => $this->db->password,
				"db"    => $this->db->database,
			);

			$columns = array(
				array(
					'db' => 'module_id',
					'dt' => 0,
					'field' => 'module_id'
				),
				array(
					'db' => 'nama_module',
					'dt' => 1,
					'field' => 'nama_module'
				),
				array(
					'db' => 'url_module',
					'dt' => 2,
					'field' => 'url_module'
				),
				array(
					'db' => 'status_module',
					'dt' => 3,
					'field' => 'status_module',
					'formatter' => function ($d, $row) {
						return ucwords($d);
					}
				),
				array(
					'db' => 'login',
					'dt' => 4,
					'field' => 'login',
					'formatter' => function ($d, $row) {
						return ucwords($d);
					}
				),
				array(
					'db' => 'module_id',
					'dt' => 5,
					'field' => 'module_id',
					'formatter' => function ($d, $row) {
						return '
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-flat btn-edit" data-id="' . $row['module_id'] . '">Edit</a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-flat btn-delete" data-id="' . $row['module_id'] . '">Delete</a>
                    ';
					}
				),
			);

			echo json_encode(
				SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
			);
		}
	}

	public function get()
	{
		if ($this->input->is_ajax_request() == true) {
			if ($this->input->get('module_id')) {
				$data = $this->module->find($this->input->get('module_id'));
			} else {
				$data = $this->module->findAll('login', 'yes');
			}
			echo json_encode($data);
		}
	}

	public function create()
	{
		if ($this->input->is_ajax_request() == true) {
			$this->form_validation->set_rules('nama_module', 'Module', 'trim|required|is_unique[module.nama_module]');
			$this->form_validation->set_rules('status_module', 'Status', 'trim|required');
			$this->form_validation->set_rules('login', 'Login', 'trim|required');
			$this->form_validation->set_message('required', '{field} field is required.');
			$this->form_validation->set_message('is_unique', '{field} already in system.');
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(['statusCode' => 400, 'message' => $this->form_validation->error_array()]);
			} else {
				$data = [
					'nama_module' 	=> $this->input->post('nama_module'),
					'url_module' 	=> $this->input->post('url_module'),
					'status_module' => $this->input->post('status_module'),
					'login' 		=> $this->input->post('login')
				];
				if ($this->module->insert($data)) {
					echo json_encode(['statusCode' => 200, 'message' => 'New Module has been created successfully']);
				} else {
					echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
				}
			}
		}
	}

	public function update()
	{
		if ($this->input->is_ajax_request() == true) {
			$this->form_validation->set_rules('nama_module', 'Module', 'trim|required|module_check');
			$this->form_validation->set_rules('status_module', 'Status', 'trim|required');
			$this->form_validation->set_rules('login', 'Login', 'trim|required');
			$this->form_validation->set_message('required', '{field} field is required.');
			$this->form_validation->set_message('is_unique', '{field} already in system.');
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(['statusCode' => 400, 'message' => $this->form_validation->error_array()]);
			} else {
				$data = [
					'nama_module' 	=> $this->input->post('nama_module'),
					'url_module' 	=> $this->input->post('url_module'),
					'status_module' => $this->input->post('status_module'),
					'login' 		=> $this->input->post('login')
				];
				if ($this->module->update($this->input->post('module_id'), $data)) {
					echo json_encode(['statusCode' => 200, 'message' => 'Module has been updated successfully']);
				} else {
					echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
				}
			}
		}
	}

	public function delete()
	{
		if ($this->input->is_ajax_request() == true) {
			if ($this->module->delete($this->input->get('module_id'))) {
				echo json_encode(['statusCode' => 200, 'message' => 'Module has been deleted successfully']);
			} else {
				echo json_encode(['statusCode' => 500, 'message' => 'Something went wrong!']);
			}
		}
	}
}
