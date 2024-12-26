<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $data = array();
	public $template;
	public $isLoggedIn;
	public $userId;

	public $db;
	public $auth;
	public $session;
	public $router;
	public $model;
	public $input;
	public $form_validation;

	public $currentModule;
	protected $methodName;
	protected $moduleURL;
	protected $isAdmin = 0;
	protected $roleAccess = [];

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper(array('menu'));
		$this->load->library(array('Auth', 'Template'));
		$this->load->model('MY_Model', 'model');

		$controller  = $this->router->class;
		$exp  = explode('\\', $controller);

		$nama_module =  'welcome';
		foreach ($exp as $key => $val) {
			if (!$val || strtolower($val) == 'application' || strtolower($val) == 'controllers')
				unset($exp[$key]);
		}

		$nama_module = str_replace('_', '-', strtolower(join('/', $exp)));
		$url_module = base_url() . $nama_module;

		$this->session->set_userdata('web', ['url_module' => $url_module, 'nama_module' => $nama_module, 'method' => $this->router->method]);

		$web = $this->session->userdata('web');

		$nama_module = $web['nama_module'];
		$module = $this->db->get_where('module', ['url_module' => $nama_module])->row_array();

		if (!$module) {
			show_404();
		}

		$this->isLoggedIn = $this->session->userdata('logged_in');
		$this->userId = $this->session->userdata('user_id');
		$this->currentModule = $module;

		$this->data['isLoggedIn'] = $this->isLoggedIn;
		$this->data['user_id'] = $this->userId;
		$this->data['title'] = 'AdminLTE 2 CodeIgniter 3';
		$this->data['meta_desc'] = 'Description AdminLTE 2 CodeIgniter 3';
		$this->data['user'] = [];
		$this->data['stylesheets'] = [];
		$this->data['scripts'] = [];

		if ($this->currentModule['login'] == 'yes' && $nama_module != 'login') {
			$this->loginRequired();
		} else if ($this->currentModule['login'] == 'restrict') {
			$this->loginRestricted();
		}

		if ($this->isLoggedIn) {
			$this->data['user'] = $this->model->getUserById($this->userId);
			$this->data['menu'] = $this->model->getMenu();
			$this->roleAccess = $this->checkRoleAccess();
			if ($nama_module == 'login') {
				$this->redirectOnLoggedIn();
			}
		}
	}

	protected function checkRoleAccess()
	{
		if ($this->isLoggedIn) {
			$result = [];
			$roleAccess = $this->model->getRoleAccess($this->data['user']['role_id']);
			if (!empty($roleAccess)) {
				$access = json_decode($roleAccess['access']);
				foreach ($access as $module) {
					$result[$module->module] = (array) $module;
				}
			}

			return $result;
		}
	}

	protected function credentials($identity, $password = NULL)
	{
		$limit = 1;
		$status = "active";
		$fieldType = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'Email address' : 'Username';
		$user = $this->db->get_where("users", ["username" => $identity, "status" => $status], $limit)->row_array();
		if (!$user) {
			$user = $this->db->get_where("users", ["email" => $identity, "status" => $status], $limit)->row_array();
		}

		if (!password_verify($password, $user['password'])) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Wrong password!</div>');
			return FALSE;
		}

		if ($user['verified'] == 0) {
			$this->session->set_flashdata('message', '<div class="alert alert-warning">' . $fieldType . ' has been registered but not verified, Please verify your email first</div>');
			return FALSE;
		}

		if ($user && password_verify($password, $user['password'])) {
			$this->model->updateLastLogin($user['user_id']);
			return $user;
		}
	}

	protected function setUser($user)
	{
		$this->session->set_userdata([
			"user_id"	=> $user['user_id'],
			"logged_in" => true
		]);

		redirect('login');
	}

	protected function redirectOnLoggedIn()
	{
		if ($this->isLoggedIn) {
			header('Location: ' . base_url($this->data['user']['default_module']['url_module']));
		}
	}

	protected function loginRequired()
	{
		if (!$this->isLoggedIn) {
			header('Location: ' . base_url('login'));
			exit();
		}
	}

	protected function loginRestricted()
	{
		if ($this->isLoggedIn) {
			if ($this->methodName !== 'logout') {
				header('Location: ' . base_url());
			}
		}
	}

	protected function addStylesheet($file)
	{
		$this->data['stylesheets'][] = $file;
	}

	protected function addScript($file, $print = FALSE)
	{
		if ($print) {
			$this->data['scripts'][] = ['print' => TRUE, 'script' => $file];
		} else {
			$this->data['scripts'][] = $file;
		}
	}

	protected function render($layout, $view, $data = array())
	{
		$this->data = array_merge($this->data, $data);
		$this->template->load($layout, $view, $this->data);
	}

	protected function redirectAccessDenied()
	{
		if ($this->isLoggedIn) {
			$this->data['title'] = 'Access Denied';
			// return $this->template->load('layouts/page', 'errors/access-denied', $this->data);
			header('Location: ' . base_url($this->data['user']['default_module']['url_module']));
		}
	}

	// protected function isAdmin()
	// {
	// 	if ($this->isAdmin == ADMINISTRATOR) {
	// 		return TRUE;
	// 	} else {
	// 		return FALSE;
	// 	}
	// }

	protected function hasAccessCreate()
	{
		if ((array_key_exists($this->currentModule['url_module'], $this->roleAccess) && ($this->roleAccess[$this->currentModule['url_module']]['access'] == 1 || $this->roleAccess[$this->currentModule['url_module']]['create_records'] == 1))) {
			return TRUE;
		}
		return FALSE;
	}

	protected function hasAccessRead()
	{
		if ((array_key_exists($this->currentModule['url_module'], $this->roleAccess) && ($this->roleAccess[$this->currentModule['url_module']]['access'] == 1 || $this->roleAccess[$this->currentModule['url_module']]['read_records'] == 1))) {
			return TRUE;
		}
		return FALSE;
	}

	protected function hasAccessUpdate()
	{
		if ((array_key_exists($this->currentModule['url_module'], $this->roleAccess) && ($this->roleAccess[$this->currentModule['url_module']]['access'] == 1 || $this->roleAccess[$this->currentModule['url_module']]['update_records'] == 1))) {
			return TRUE;
		}
		return FALSE;
	}

	protected function hasAccessDelete()
	{
		if ((array_key_exists($this->currentModule['url_module'], $this->roleAccess) && ($this->roleAccess[$this->currentModule['url_module']]['access'] == 1 || $this->roleAccess[$this->currentModule['url_module']]['delete_records'] == 1))) {
			return TRUE;
		}
		return FALSE;
	}
}
