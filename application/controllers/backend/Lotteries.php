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
		$this->data_view = format_helper_backend_view_data ( 'lotters_content' );
		$this->data_view ['system'] ['action'] = 'Lottery';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '抽獎活動',
				'link' => '/backend/lotteries',
				'class' => 'fa-star'
		);
		// 效能檢查
		//$this->output->enable_profiler(TRUE);
	}

	/**
	 * 抽獎系統-開獎頁
	 * @param unknown $pk
	 */
	public function lottery_list($pk) {
		try {
			if ($this->flexi_auth->is_privileged('Events Config View')) {
				// 寫log
				$this->fun->logs('觀看[抽獎系統-開獎頁]');
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_old_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$this->load->library('vidol/grocery_callback');
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('lotters_winners_list_tbl');
				$crud->where('lw_lc_pk', $pk);
				$crud->where('lw_status', 1);
				// 標題
				$crud->set_subject('抽獎系統-開獎頁');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('lw_pk', 'lw_lc_pk', 'lw_lc_title', 'lw_mongo_id', 'lw_member_id', 'lw_status', 'lw_crated_at');
				// 資料庫欄位文字替換
				$crud->display_as('lw_pk', $this->lang->line('fields_pk'));
				$crud->display_as('lw_lc_pk', '抽獎系統主建');
				$crud->display_as('lw_lc_title', '抽獎系統標題');
				$crud->display_as('lw_mongo_id', $this->lang->line('fields_mongo_id'));
				$crud->display_as('lw_member_id', $this->lang->line('fields_member_id'));
				$crud->display_as('lw_status', $this->lang->line('fields_status'));
				$crud->display_as('lw_crated_at', $this->lang->line('fields_time_creat_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/lotters/winners_list';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '抽獎系統-開獎頁',
						'link' => '/backend/lotteries/lottery_list',
						'class' => 'fa-cog'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	/**
	 * 抽獎系統-開獎名單
	 * @param unknown $pk
	 */
	public function winners_list($pk) {
		try {
			if ($this->flexi_auth->is_privileged('Events Config View')) {
				// 寫log
				$this->fun->logs('觀看[抽獎系統-開獎名單]');
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_old_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$this->load->library('vidol/grocery_callback');
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('lotters_winners_list_tbl');
				$crud->where('lw_lc_pk', $pk);
				$crud->where('lw_status', 1);
				// 標題
				$crud->set_subject('抽獎系統-中獎名單');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('lw_pk', 'lw_lc_pk', 'lw_lc_title', 'lw_mongo_id', 'lw_member_id', 'lw_status', 'lw_crated_at');
				// 資料庫欄位文字替換
				$crud->display_as('lw_pk', $this->lang->line('fields_pk'));
				$crud->display_as('lw_lc_pk', '抽獎系統主建');
				$crud->display_as('lw_lc_title', '抽獎系統標題');
				$crud->display_as('lw_mongo_id', $this->lang->line('fields_mongo_id'));
				$crud->display_as('lw_member_id', $this->lang->line('fields_member_id'));
				$crud->display_as('lw_status', $this->lang->line('fields_status'));
				$crud->display_as('lw_crated_at', $this->lang->line('fields_time_creat_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '抽獎系統-開獎名單',
						'link' => '/backend/lotteries/winners_list',
						'class' => 'fa-cog'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	/**
	 * 抽獎系統
	 */
	public function system() {
		try {
			if ($this->flexi_auth->is_privileged('Events Config View')) {
				// 寫log
				$this->fun->logs('觀看[抽獎系統]');
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_old_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$this->load->library('vidol/grocery_callback');
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('lotters_config_tbl');
				// 標題
				$crud->set_subject('抽獎系統');
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('lc_pk', 'lc_title', 'lc_des', 'lc_method', 'lc_status', 'lc_start_at', 'lc_end_at');
				// 事件
				$crud->add_action('開獎頁', '/assets/grocery_crud/themes/flexigrid/css/images/export.png', '', '', array($this->grocery_callback,'callback_config_to_lotters_url'));
				$crud->add_action('開獎名單', '/assets/grocery_crud/themes/flexigrid/css/images/export.png', '/backend/lotteries/winners_list');
				// 資料庫欄位文字替換
				$crud->display_as('lc_pk', $this->lang->line('fields_pk'));
				$crud->display_as('lc_title', '標題');
				$crud->display_as('lc_des', '描述');
				$crud->display_as('lc_db_type', '資料庫類型');
				$crud->display_as('lc_db_table', '資料庫表');
				$crud->display_as('lc_db_where', '條件');
				$crud->display_as('lc_value1', '數值1');
				$crud->display_as('lc_value2', '數值2');
				$crud->display_as('lc_value3', '數值3');
				$crud->display_as('lc_value4', '數值4');
				$crud->display_as('lc_value5', '數值5');
				$crud->display_as('lc_method', '開講方法');
				$crud->display_as('lc_status', '狀態');
				$crud->display_as('lc_start_at', '開始時間');
				$crud->display_as('lc_end_at', '結束時間');
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '抽獎系統',
						'link' => '/backend/lotteries/system',
						'class' => 'fa-cog'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}

	/**
	 * 抽獎系統設定
	 */
	public function config() {
		try {
			if ($this->flexi_auth->is_privileged('Events Config View')) {
				// 寫log
				$this->fun->logs('觀看[抽獎系統設定]');
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
				$crud->set_table('lotters_config_tbl');
				// 標題
				$crud->set_subject('抽獎系統設定');
				if (! $this->flexi_auth->is_privileged('Events Config Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Events Config Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Events Config Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				// 清單顯示欄位
				$crud->columns('lc_pk', 'lc_title', 'lc_des', 'lc_method', 'lc_status', 'lc_start_at', 'lc_end_at');
				// 資料庫欄位文字替換
				$crud->display_as('lc_pk', $this->lang->line('fields_pk'));
				$crud->display_as('lc_title', '標題');
				$crud->display_as('lc_des', '描述');
				$crud->display_as('lc_db_type', '資料庫類型');
				$crud->display_as('lc_db_table', '資料庫表');
				$crud->display_as('lc_db_where', '條件');
				$crud->display_as('lc_value1', '數值1');
				$crud->display_as('lc_value2', '數值2');
				$crud->display_as('lc_value3', '數值3');
				$crud->display_as('lc_value4', '數值4');
				$crud->display_as('lc_value5', '數值5');
				$crud->display_as('lc_method', '開講方法');
				$crud->display_as('lc_status', '狀態');
				$crud->display_as('lc_start_at', '開始時間');
				$crud->display_as('lc_end_at', '結束時間');
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '抽獎系統設定',
						'link' => '/backend/lotteries/config',
						'class' => 'fa-cog'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
