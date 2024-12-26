<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->addStylesheet(base_url('public/dist/css/skins/_all-skins.min.css'));
		$this->addScript(base_url('public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'));
		$this->addScript(base_url('public/bower_components/fastclick/lib/fastclick.js'));
		$this->addScript(base_url('public/dist/js/adminlte.min.js'));
	}

	public function index()
	{
		$this->render('layouts/page', 'pages/dashboard', $this->data);
	}
}
