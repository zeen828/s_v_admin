<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Vote_config extends CI_Controller {
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
		$this->data_view ['system'] ['action'] = 'Votes';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '投票系統',
				'link' => '/backend/vote_config',
				'class' => 'fa-line-chart' 
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function config() {
		try {
			if ($this->flexi_auth->is_privileged ( 'Votes Config View' )) {
				// 寫log
				$this->fun->logs ( '觀看投票系統設定' );
				// 變數
				$data_post = array ();
				// 強制切換資料庫
				unset ( $this->db );
				$this->db = $this->load->database ( 'vidol_event_write', true );
				// grocery_CRUD 自產表單
				$this->load->library ( 'grocery_CRUD' ); // CI整合表單http://www.grocerycrud.com/
				$this->load->library ( 'vidol/grocery_callback' );
				$crud = new grocery_CRUD ();
				// 語系
				$crud->set_language ( 'taiwan' );
				// 版型
				$crud->set_theme ( 'flexigrid' );
				// 表格
				$crud->set_table ( 'event_vote_config_tbl' );
				// 標題
				$crud->set_subject ( '抽獎系統設定檔' );
				if (! $this->flexi_auth->is_privileged ( 'Votes Config Add' )) {
					// 移除新增
					$crud->unset_add ();
				}
				if (! $this->flexi_auth->is_privileged ( 'Votes Config Edit' )) {
					// 移除編輯
					$crud->unset_edit ();
				}
				if (! $this->flexi_auth->is_privileged ( 'Votes Config Del' )) {
					// 移除刪除
					$crud->unset_delete ();
				}
				// 清單顯示欄位
				$crud->columns ( 'id', 'title', 'login_where', 'vote_where', 'vote_int', 'status', 'start_at', 'end_at' );
				// 欄位控制
				$crud->add_fields ( 'title', 'login_where', 'vote_where', 'vote_int', 'status', 'start_at', 'end_at' );
				$crud->edit_fields ( 'title', 'login_where', 'vote_where', 'vote_int', 'status', 'start_at', 'end_at' );
				// 表單必填欄位
				$crud->required_fields ( 'title', 'login_where', 'vote_where', 'status', 'start_at', 'end_at' );
				// 事件
				$crud->add_action ( '投票項目設定', '/assets/grocery_crud/themes/flexigrid/css/images/export.png', '', '', array (
						$this->grocery_callback,
						'callback_config_to_item_url' 
				) );
				// 資料庫欄位文字替換
				$crud->display_as ( 'id', $this->lang->line ( 'fields_pk' ) );
				$crud->display_as ( 'title', '標題' );
				$crud->display_as ( 'des', '描述' );
				$crud->display_as ( 'login_where', '登入規則' );
				$crud->display_as ( 'vote_where', '投票規則' );
				$crud->display_as ( 'vote_int', '每天可投票次數' );
				$crud->display_as ( 'status', '狀態' );
				$crud->display_as ( 'start_at', '開始時間(+8)' );
				$crud->display_as ( 'end_at', '結束時間(+8)' );
				$crud->display_as ( 'created_at', '建立時間(+8)' );
				$crud->display_as ( 'updated_at', '建立時間(+8)' );
				// 產生表單
				$output = $crud->render ();
				// 資料整理
				$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view ['right_countent'] ['view_data'] = $output;
				$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
						'title' => '投票系統設定',
						'link' => '/backend/vote_config/config',
						'class' => 'fa-cog' 
				);
				// 套版
				$this->load->view ( 'AdminLTE/include/html5', $this->data_view );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function config_item($config_id) {
		try {
			if ($this->flexi_auth->is_privileged ( 'Votes Config View' ) && is_integer ( $config_id )) {
				// 寫log
				$this->fun->logs ( '觀看投票項目設定' );
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
				$crud->set_table ( 'event_vote_item_tbl' );
				$crud->where ( 'id', $config_id );
				// 標題
				$crud->set_subject ( '抽獎系統設定檔' );
				if (! $this->flexi_auth->is_privileged ( 'Votes Config Add' )) {
					// 移除新增
					$crud->unset_add ();
				}
				if (! $this->flexi_auth->is_privileged ( 'Votes Config Edit' )) {
					// 移除編輯
					$crud->unset_edit ();
				}
				if (! $this->flexi_auth->is_privileged ( 'Votes Config Del' )) {
					// 移除刪除
					$crud->unset_delete ();
				}
				// 清單顯示欄位
				$crud->columns ( 'id', 'config_id', 'group_no', 'sort', 'title', 'ticket', 'ticket_add', 'proportion', 'status' );
				// 欄位控制
				$crud->add_fields ( 'config_id', 'group_no', 'sort', 'title', 'des', 'img_url', 'click_url', 'status' );
				$crud->edit_fields ( 'group_no', 'sort', 'title', 'des', 'img_url', 'click_url', 'status' );
				// 表單必填欄位
				$crud->required_fields ( 'cs_title', 'cs_type', 'cs_word', 'cs_count', 'cs_repeat', 'cs_user_repeat', 'cs_time_start', 'cs_time_end' );
				// 資料庫欄位文字替換
				$crud->display_as ( 'id', $this->lang->line ( 'fields_pk' ) );
				$crud->display_as ( 'config_id', '設定檔id' );
				$crud->display_as ( 'group_no', '群組' );
				$crud->display_as ( 'sort', '排序' );
				$crud->display_as ( 'title', '標題' );
				$crud->display_as ( 'des', '描述' );
				$crud->display_as ( 'img_url', '圖片' );
				$crud->display_as ( 'click_url', '網址' );
				$crud->display_as ( 'ticket', '總投票數' );
				$crud->display_as ( 'ticket_add', '灌票數' );
				$crud->display_as ( 'proportion', '得票機率' );
				$crud->display_as ( 'status', '狀態' );
				$crud->display_as ( 'created_at', '建立時間(+8)' );
				$crud->display_as ( 'updated_at', '建立時間(+8)' );
				// 產生表單
				$output = $crud->render ();
				// 資料整理
				$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view ['right_countent'] ['view_data'] = $output;
				$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
						'title' => '投票項目設定',
						'link' => '/backend/vote_config/config_item/' . $config_id,
						'class' => 'fa-cog' 
				);
				// 套版
				$this->load->view ( 'AdminLTE/include/html5', $this->data_view );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
