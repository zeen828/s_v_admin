<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Lotteries extends CI_Controller {
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
		$this->data_view = format_helper_backend_view_data ( 'sdac_content' );
		$this->data_view ['system'] ['action'] = 'Lottery';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '抽獎活動',
				'link' => '/backend/lotteries',
				'class' => 'fa-star'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function iphone8_week1() {
		try {
			if ($this->flexi_auth->is_privileged('Lottery View')) {
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
						'title' => 'OB嚴選送iphone8',
						'link' => '/backend/lotteries/iphone8',
						'class' => 'fa-space-shuttle'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function iphone8_week2() {
		try {
			if ($this->flexi_auth->is_privileged('Lottery View')) {
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
				$crud->set_table('lottery_iphone_list_tbl');
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
						'title' => 'OB嚴選送iphone8',
						'link' => '/backend/lotteries/iphone8',
						'class' => 'fa-space-shuttle'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
