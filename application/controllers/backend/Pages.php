<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{

	private $data_view;

	function __construct ()
	{
		parent::__construct();
		// 資料庫
		$this->load->database();
		// 權限
		$this->auth = new stdClass();
		$this->load->library('flexi_auth');
		if (! $this->flexi_auth->is_logged_in()) {
			redirect('homes/login');
		}
		// 引用
		$this->load->library('session');
		$this->load->library('lib_log');
		$this->load->library('vidol/fun');
		$this->load->helper('formats');
		$this->lang->load('table_vidol', 'traditional-chinese');
		// 初始化
		$this->data_view = format_helper_backend_view_data('orders_content');
		$this->data_view['system']['action'] = 'Pages';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '活動頁面管理',
				'link' => '/backend/pages',
				'class' => 'fa-money'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		//show_404();
		echo "Hi";
	}
	public function load ()
	{
		//show_404();
		echo "Hi";
	}
}
