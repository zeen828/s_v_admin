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

	public function user ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看會員管理');
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_old_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('User_tbl');
				// 標題
				$crud->set_subject($this->lang->line('tabels_User_tbl'));
				//if (! $this->flexi_auth->is_privileged('Users Add')) {
					// 移除新增
					$crud->unset_add();
				//}
				//if (! $this->flexi_auth->is_privileged('Users Edit')) {
					// 移除編輯
					$crud->unset_edit();
				//}
				//if (! $this->flexi_auth->is_privileged('Users Del')) {
					// 移除刪除
					$crud->unset_delete();
				//}
				// 清單顯示欄位
				$crud->columns('u_id', 'u_mongo_id', 'u_member_id', 'u_nick_name', 'u_email');
				// 資料庫欄位文字替換
				$crud->display_as('u_id', $this->lang->line('fields_u_id'));
				$crud->display_as('u_mongo_id', $this->lang->line('fields_mongo_id'));
				$crud->display_as('u_member_id', $this->lang->line('fields_member_id'));
				$crud->display_as('u_nick_name', $this->lang->line('fields_nick_name'));
				$crud->display_as('u_username', $this->lang->line('fields_u_username'));
				$crud->display_as('u_profile', $this->lang->line('fields_u_profile'));
				$crud->display_as('u_password', $this->lang->line('fields_u_password'));
				$crud->display_as('u_token', $this->lang->line('fields_u_token'));
				$crud->display_as('u_session_token', $this->lang->line('fields_u_session_token'));
				$crud->display_as('u_email', $this->lang->line('fields_u_email'));
				$crud->display_as('u_email_contact', $this->lang->line('fields_u_email_contact'));
				$crud->display_as('u_email_domain', $this->lang->line('fields_u_email_domain'));
				$crud->display_as('u_email_verify_token', $this->lang->line('fields_u_email_verify_token'));
				$crud->display_as('u_email_verified', $this->lang->line('fields_u_email_verified'));
				$crud->display_as('u_facebook_data', $this->lang->line('fields_u_facebook_data'));
				$crud->display_as('u_facebook_id', $this->lang->line('fields_u_facebook_id'));
				$crud->display_as('u_facebook_token', $this->lang->line('fields_u_facebook_token'));
				$crud->display_as('u_facebook_expiration', $this->lang->line('fields_u_facebook_expiration'));
				$crud->display_as('u_start', $this->lang->line('fields_status'));
				$crud->display_as('u_creat_utc', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('u_creat_unix', $this->lang->line('fields_time_creat_unix'));
				$crud->display_as('u_update_utc', $this->lang->line('fields_time_update_utc'));
				$crud->display_as('u_update_unix', $this->lang->line('fields_time_update_unix'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '會員管理',
						'link' => '/backend/users/user',
						'class' => 'fa-users'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
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

	/**
	 * 會員每小時註冊數
	 */
	public function registered_hour ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看註冊數(小時)');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/registered_hour';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '註冊數(小時)',
						'link' => '/backend/users/registered_hour',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	/**
	 * 會員每日註冊數
	 */
	public function registered_day ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看註冊數(每日)');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/registered_day';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '註冊數(每日)',
						'link' => '/backend/users/registered_day',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 會員每日註冊數
	 */
	public function registered_month ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看註冊數(每月)');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/registered_month';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '註冊數(每月)',
						'link' => '/backend/users/registered_month',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 年齡層
	 */
	public function age ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看年齡層');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/age';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '年齡層',
						'link' => '/backend/users/age',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 會員每日不重複登入數
	 */
	public function login_day ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看登入數(每日)');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/login_day';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '登入數(每日)',
						'link' => '/backend/users/login_day',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 會員每月不重複登入數
	 */
	public function login_month ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Users View')) {
				// 寫log
				$this->fun->logs('觀看登入數(每月)');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/login_month';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '登入數(每月)',
						'link' => '/backend/users/login_month',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	public function vidol()
	{
		try {
			if ($this->flexi_auth->is_privileged('Accounts View')) {
				// 寫log
				$this->fun->logs('vidol會員管理');
				// 變數
				$data_post = array();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/users/vidol';
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => 'vidol會員管理',
						'link' => '/backend/users/vidol',
						'class' => 'fa-users'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
