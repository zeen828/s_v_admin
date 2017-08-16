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
		$this->data_view ['system'] ['action'] = 'Report_event';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '活動報表',
				'link' => '/backend/report_event',
				'class' => 'fa-line-chart' 
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function vote($config_id) {
		try {
			if ($this->flexi_auth->is_privileged ( 'Votes Config View' ) && ! empty ( $config_id )) {
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
				$crud->set_subject ( '抽獎系統設定檔' );
				// 移除新增
				$crud->unset_add ();
				// 移除編輯
				$crud->unset_edit ();
				// 移除刪除
				$crud->unset_delete ();
				// 清單顯示欄位
				$crud->columns ( 'date_at', 'vote', 'new_vote', 'registered', 'vidol_registered', 'proportion', 'vidol_proportion', 'total_vote', 'total_registered' );
				// 資料庫欄位文字替換
				$crud->display_as ( 'id', $this->lang->line ( 'fields_pk' ) );
				$crud->display_as ( 'config_id', '設定檔id' );
				$crud->display_as ( 'vote', '當日投票數' );
				$crud->display_as ( 'new_vote', '新投票會員' );
				$crud->display_as ( 'single_vote', '不重複投票數' );
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
				$this->data_view ['system'] ['action'] = 'Users';
				$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view ['right_countent'] ['view_data'] = $output;
				$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
						'title' => '活動報表',
						'link' => '/backend/report_event/vote/' . $config_id,
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
