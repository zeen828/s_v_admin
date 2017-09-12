<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Report_event extends CI_Controller {
	private $data_view;
	private $vote_arr;
	function __construct() {
		parent::__construct ();
		// 資料庫
		// $this->load->database();
		// 權限
		$this->auth = new stdClass ();
		$this->load->library ( 'flexi_auth' );
		if (! $this->flexi_auth->is_logged_in ()) {
			redirect ( 'homes/login' );
		}
		// 引用
		$this->load->library ( 'session' );
		$this->load->library ( 'lib_log' );
		$this->load->library ( 'vidol/fun' );
		$this->load->helper ( 'formats' );
		$this->lang->load ( 'table_vidol', 'traditional-chinese' );
		// $this->config->load('vidol');
		// 初始化
		$this->data_view = format_helper_backend_view_data ( 'vote_config' );
		$this->data_view ['system'] ['action'] = 'Charts';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '數據報表',
				'link' => '/backend/report_event',
				'class' => 'fa-bar-chart'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	
	public function index() {
		//show_404();
		$this->vote(1);
	}
	
	/**
	 * 投票系統報表
	 * @param unknown $config_id
	 */
	public function vote($config_id) {
		try {
			if ($this->flexi_auth->is_privileged ( 'Charts View' ) && ! empty ( $config_id )) {
				// 寫log
				$this->fun->logs ( '觀看活動報表' );
				// 變數
				$data_post = array ();
				// 強制切換資料庫
				unset ( $this->db );
				$this->db = $this->load->database ( 'vidol_event_write', true );
				// grocery_CRUD 自產表單
				$this->load->library ( 'grocery_CRUD' ); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD ();
				// 語系
				$crud->set_language ( 'taiwan' );
				// 版型
				$crud->set_theme ( 'flexigrid' );
				// 表格
				$crud->set_table ( 'event_vote_report_tbl' );
				$crud->where ( 'config_id', $config_id );
				// 標題
				$crud->set_subject ( '活動成效報表' );
				// 移除新增
				$crud->unset_add ();
				// 移除編輯
				$crud->unset_edit ();
				// 移除刪除
				$crud->unset_delete ();
				// 清單顯示欄位
				$crud->columns ( 'date_at', 'vote', 'new_vote', 'registered', 'proportion', 'vidol_registered', 'vidol_proportion', 'single_vote', 'total_vote', 'total_registered' );
				// 資料庫欄位文字替換
				$crud->display_as ( 'id', $this->lang->line ( 'fields_pk' ) );
				$crud->display_as ( 'config_id', '設定檔id' );
				$crud->display_as ( 'vote', '當日投票數' );
				$crud->display_as ( 'new_vote', '第一次投票會員' );
				$crud->display_as ( 'single_vote', '累計不重複投票數' );
				$crud->display_as ( 'total_vote', '累計投票數' );
				$crud->display_as ( 'registered', '當日投票註冊數' );
				$crud->display_as ( 'total_registered', '累計投票註冊數' );
				$crud->display_as ( 'vidol_registered', 'vidol當日總註冊數' );
				$crud->display_as ( 'proportion', '投票註冊/投票數占比%' );
				$crud->display_as ( 'vidol_proportion', '投票註冊/當日總註冊數占比%' );
				$crud->display_as ( 'date_at', '時間(+8)' );
				$crud->display_as ( 'created_at', '建立時間(+8)' );
				// 產生表單
				$output = $crud->render ();
				// 資料整理
				$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view ['right_countent'] ['view_data'] = $output;
				$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
						'title' => '活動成效報表',
						'link' => '/backend/report_event/vote/' . $config_id,
						'class' => 'fa-bar-chart' 
				);
				// 套版
				$this->load->view ( 'AdminLTE/include/html5', $this->data_view );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 活動報表[玩很大進校園]
	 */
	public function mrplay_list() {
		try {
			if ($this->flexi_auth->is_privileged('Charts View')) {
				// 寫log
				$this->fun->logs('觀看投票報表');
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
				$crud->set_table('vote_mrplay_list_tbl');
				// 標題
				$crud->set_subject('玩很大進校園報表');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_vote_registered','v_registered','v_total_registered','v_proportion','v_created_at','v_updated_at');
				// 資料庫欄位文字替換
				$crud->display_as('v_pk', $this->lang->line('fields_pk'));
				$crud->display_as('v_date', '時間(TW)');
				$crud->display_as('v_new_vote', '新投票會員');
				$crud->display_as('v_vote', '投票數');
				$crud->display_as('v_single_vote', '不重複投票數');
				$crud->display_as('v_total_vote', '累計投票數');
				$crud->display_as('v_vote_registered', '投票註冊數');
				$crud->display_as('v_registered', 'vidol註冊數');
				$crud->display_as('v_total_registered', '累計投票註冊數');
				$crud->display_as('v_proportion', '註冊占比');
				$crud->display_as('v_created_at', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('v_updated_at', $this->lang->line('fields_time_update_utc'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '活動報表[玩很大進校園]',
						'link' => '/backend/report_event/mrplay_list',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 活動報表[玩粉感恩大放送]
	 */
	public function mrplay_gifts_list() {
		try {
			if ($this->flexi_auth->is_privileged('Charts View')) {
				// 寫log
				$this->fun->logs('觀看投票報表');
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
				$crud->set_table('vote_mrplay_gifts_list_tbl');
				// 標題
				$crud->set_subject('玩粉感恩大放送');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_vote_registered','v_registered','v_total_registered','v_proportion','v_created_at','v_updated_at');
				// 資料庫欄位文字替換
				$crud->display_as('v_pk', $this->lang->line('fields_pk'));
				$crud->display_as('v_date', '時間(TW)');
				$crud->display_as('v_new_vote', '新投票會員');
				$crud->display_as('v_vote', '投票數');
				$crud->display_as('v_single_vote', '不重複投票數');
				$crud->display_as('v_total_vote', '累計投票數');
				$crud->display_as('v_vote_registered', '投票註冊數');
				$crud->display_as('v_registered', 'vidol註冊數');
				$crud->display_as('v_total_registered', '累計投票註冊數');
				$crud->display_as('v_proportion', '註冊占比');
				$crud->display_as('v_created_at', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('v_updated_at', $this->lang->line('fields_time_update_utc'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '活動報表[玩粉感恩大放送]',
						'link' => '/backend/report_event/mrplay_gifts_list',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 活動報表[愛上哥們贈東京票]
	 */
	public function bromance_meetings_list() {
		try {
			if ($this->flexi_auth->is_privileged('Charts View')) {
				// 寫log
				$this->fun->logs('觀看投票報表');
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
				$crud->set_table('vote_bromance_meetings_list_tbl');
				// 標題
				$crud->set_subject('愛上哥們贈東京票');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_vote_registered','v_registered','v_total_registered','v_proportion','v_created_at','v_updated_at');
				// 資料庫欄位文字替換
				$crud->display_as('v_pk', $this->lang->line('fields_pk'));
				$crud->display_as('v_date', '時間(TW)');
				$crud->display_as('v_new_vote', '新投票會員');
				$crud->display_as('v_vote', '投票數');
				$crud->display_as('v_single_vote', '不重複投票數');
				$crud->display_as('v_total_vote', '累計投票數');
				$crud->display_as('v_vote_registered', '投票註冊數');
				$crud->display_as('v_registered', 'vidol註冊數');
				$crud->display_as('v_total_registered', '累計投票註冊數');
				$crud->display_as('v_proportion', '註冊占比');
				$crud->display_as('v_created_at', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('v_updated_at', $this->lang->line('fields_time_update_utc'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '活動報表[愛上哥們贈東京票]',
						'link' => '/backend/report_event/bromance_meetings_list',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 活動報表[OB嚴選送iphone8]
	 */
	public function ob_iphone8_list() {
		try {
			if ($this->flexi_auth->is_privileged('Charts View')) {
				// 寫log
				$this->fun->logs('觀看投票報表');
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
				$crud->set_table('vote_ob_iphone8_list_tbl');
				// 標題
				$crud->set_subject('OB嚴選送iphone8');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				//$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_vote_registered','v_registered','v_total_registered','v_proportion','v_created_at','v_updated_at');
				$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_total_registered','v_vote_registered','v_registered','v_proportion','v_created_at','v_updated_at');
				// 資料庫欄位文字替換
				$crud->display_as('v_pk', $this->lang->line('fields_pk'));
				$crud->display_as('v_date', '時間(TW)');
				$crud->display_as('v_new_vote', '新投票會員');
				$crud->display_as('v_vote', '投票數');
				$crud->display_as('v_single_vote', '不重複投票數');
				$crud->display_as('v_total_vote', '第一抽累計投票數');
				$crud->display_as('v_vote_registered', '投票註冊數');
				$crud->display_as('v_registered', 'vidol註冊數');
				$crud->display_as('v_total_registered', '第二抽累計投票數(6/20中午12:00)');
				$crud->display_as('v_proportion', '註冊占比');
				$crud->display_as('v_created_at', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('v_updated_at', $this->lang->line('fields_time_update_utc'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '活動報表[OB嚴選送iphone8]',
						'link' => '/backend/report_event/ob_iphone8_list',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 得獎名單[OB嚴選送iphone8(第一周)]
	 */
	public function iphone8_week1() {
		try {
			if ($this->flexi_auth->is_privileged('Charts View')) {
				// 寫log
				$this->fun->logs('觀看抽獎結果');
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
				$crud->set_table('lottery_iphone_1_list_tbl');
				// 標題
				$crud->set_subject($this->lang->line('tabels_user_accounts'));
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('id','mongo_id','member_id','created_at');
				// 資料庫欄位文字替換
				$crud->display_as('id', $this->lang->line('fields_pk'));
				$crud->display_as('mongo_id', 'mondo_id');
				$crud->display_as('member_id', 'member_id');
				$crud->display_as('created_at', '抽獎時間');
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '得獎名單[OB嚴選送iphone8(第一周)]',
						'link' => '/backend/report_event/iphone8',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 得獎名單[OB嚴選送iphone8(第二周)]
	 */
	public function iphone8_week2() {
		try {
			if ($this->flexi_auth->is_privileged('Charts View')) {
				// 寫log
				$this->fun->logs('觀看抽獎結果');
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
				$crud->set_table('lottery_iphone_2_list_tbl');
				// 標題
				$crud->set_subject($this->lang->line('tabels_user_accounts'));
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('id','mongo_id','member_id','created_at');
				// 資料庫欄位文字替換
				$crud->display_as('id', $this->lang->line('fields_pk'));
				$crud->display_as('mongo_id', 'mondo_id');
				$crud->display_as('member_id', 'member_id');
				$crud->display_as('created_at', '抽獎時間');
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view ['system'] ['action'] = 'Lottery';
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/lotters/iphone8';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '得獎名單[OB嚴選送iphone8(第二周)]',
						'link' => '/backend/report_event/iphone8',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
