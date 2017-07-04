<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Coupons extends CI_Controller
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
		$this->data_view = format_helper_backend_view_data('coupons_content');
		$this->data_view['system']['action'] = 'Coupons';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '序號管理',
				'link' => '/backend/coupons',
				'class' => 'fa-btc'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		//show_404();
		$this->package();
	}
	
	public function coupon_set ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Coupons View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_billing_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$this->load->library('vidol/grocery_callback');
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('Coupon_set_tbl');
				// 排序
				$crud->order_by('cs_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_Coupon_set_tbl'));
				if (! $this->flexi_auth->is_privileged('Coupons Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Coupons Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Coupons Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				// 清單顯示欄位
				$crud->columns('cs_pk', 'cs_title', 'cs_des', 'cs_type', 'cs_package_no', 'cs_discount', 'cs_cash', 'cs_word', 'cs_count', 'cs_repeat', 'cs_user_repeat');
				// 欄位控制
				//$crud->fields('');
				$crud->add_fields('cs_title', 'cs_des', 'cs_type', 'cs_package_no', 'cs_discount', 'cs_cash', 'cs_word', 'cs_count', 'cs_repeat', 'cs_user_repeat', 'cs_assign', 'cs_status', 'cs_time_start', 'cs_time_end');
				$crud->edit_fields('cs_des', 'cs_count', 'cs_status', 'cs_time_end');
				// 表單必填欄位
				$crud->required_fields('cs_title', 'cs_type', 'cs_word', 'cs_count', 'cs_repeat', 'cs_user_repeat', 'cs_time_start', 'cs_time_end');
				// 數值對應
				$crud->field_type('cs_status', 'dropdown', array('1'=>'開啟', '0'=>'關閉'));
				// relation
				$crud->set_relation('cs_package_no', 'Sold_packages_tbl', 'sp_title', array('sp_status' => '1'), 'sp_sort ASC');
				// 資料庫欄位文字替換
				$crud->display_as('cs_pk', $this->lang->line('fields_pk'));
				$crud->display_as('cs_title', $this->lang->line('fields_title'));
				$crud->display_as('cs_des', $this->lang->line('fields_description'));
				$crud->display_as('cs_type', $this->lang->line('fields_type'));
				$crud->display_as('cs_package_no', $this->lang->line('Coupon_set_tbl_fields_cs_package_no'));
				$crud->display_as('cs_discount', $this->lang->line('Coupon_set_tbl_fields_cs_discount'));
				$crud->display_as('cs_cash', $this->lang->line('Coupon_set_tbl_fields_cs_cash'));
				$crud->display_as('cs_word', $this->lang->line('Coupon_set_tbl_fields_cs_word'));
				$crud->display_as('cs_count', $this->lang->line('Coupon_set_tbl_fields_cs_count'));
				$crud->display_as('cs_repeat', $this->lang->line('Coupon_set_tbl_fields_cs_repeat'));
				$crud->display_as('cs_user_repeat', $this->lang->line('Coupon_set_tbl_fields_cs_user_repeat'));
				$crud->display_as('cs_assign', $this->lang->line('Coupon_set_tbl_fields_cs_assign'));
				$crud->display_as('cs_produce', $this->lang->line('Coupon_set_tbl_fields_cs_produce'));
				$crud->display_as('cs_expired', $this->lang->line('Coupon_set_tbl_fields_cs_expired'));
				$crud->display_as('cs_status', $this->lang->line('fields_status'));
				$crud->display_as('cs_time_start', $this->lang->line('fields_time_start_tw'));
				$crud->display_as('cs_time_end', $this->lang->line('fields_time_end_tw'));
				$crud->display_as('cs_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('cs_time_update', $this->lang->line('fields_time_update_tw'));
				// 新增改寫
				$crud->callback_insert(array(
						$this->grocery_callback,
						'add_coupon_set'
				));
				// 編輯改寫
				$crud->callback_update(array(
						$this->grocery_callback,
						'edit_coupon_set'
				));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/coupons/coupon_set';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '序號設定',
						'link' => '/backend/coupons/coupon_set',
						'class' => 'fa-object-group'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	public function coupon ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Coupons View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_billing_read', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('Coupon_tbl');
				// 排序
				$crud->order_by('c_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_Coupon_tbl'));
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('c_pk', 'c_set_title', 'c_set_type', 'c_set_package_no', 'c_set_cs_discount', 'c_set_cash', 'c_sn', 'c_repeat', 'c_assign', 'c_status');
				// 資料庫欄位文字替換
				$crud->display_as('c_pk', $this->lang->line('fields_pk'));
				$crud->display_as('c_set_no', $this->lang->line('Coupon_tbl_fields_c_set_no'));
				$crud->display_as('c_set_title', $this->lang->line('fields_title'));
				$crud->display_as('c_set_type', $this->lang->line('fields_type'));
				$crud->display_as('c_set_package_no', $this->lang->line('Coupon_set_tbl_fields_cs_package_no'));
				$crud->display_as('c_set_cs_discount', $this->lang->line('Coupon_set_tbl_fields_cs_discount'));
				$crud->display_as('c_set_cash', $this->lang->line('Coupon_set_tbl_fields_cs_cash'));
				$crud->display_as('c_sn', $this->lang->line('Coupon_tbl_fields_c_sn'));
				$crud->display_as('c_repeat', $this->lang->line('Coupon_tbl_fields_c_repeat'));
				$crud->display_as('c_assign', $this->lang->line('Coupon_tbl_fields_c_assign'));
				$crud->display_as('c_status', $this->lang->line('fields_status'));
				$crud->display_as('c_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('c_time_update', $this->lang->line('fields_time_update_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '序號設定',
						'link' => '/backend/coupons/coupon',
						'class' => 'fa-object-ungroup'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
