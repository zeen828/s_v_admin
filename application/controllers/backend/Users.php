<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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
		$this->data_view = format_helper_backend_view_data('users_content');
		$this->data_view['system']['action'] = 'Users';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '會員管理',
				'link' => '/backend/users',
				'class' => 'fa-users'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		//show_404();
		$this->search();
	}

	/**
	 * 會員查詢
	 */
	public function search ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看會員查詢');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/search';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '會員查詢',
						'link' => '/backend/users/search',
						'class' => 'fa-search'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	/**
	 * 會員登入紀錄
	 * @param string $id	會員id 
	 */
	public function login_log ($id = '')
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看會員登入紀錄');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/login_log';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '會員登入紀錄',
						'link' => '/backend/users/login_log',
						'class' => 'fa-align-center'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 會員訂單查詢
	 * @param string $id	會員id
	 */
	public function order ($id = '')
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看會員訂單紀錄');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/order';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '會員訂單紀錄',
						'link' => '/backend/users/order',
						'class' => 'fa-credit-card'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
