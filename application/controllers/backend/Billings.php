<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billings extends CI_Controller
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
		$this->data_view = format_helper_backend_view_data('billings_content');
		$this->data_view['system']['action'] = 'Billings';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '計費管理',
				'link' => '/backend/billings',
				'class' => 'fa-btc'
		);
		// 效能檢查
		$this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		//show_404();
		$this->package();
	}

	public function package ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Billing View')) {
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
				$crud->set_table('Sold_packages_tbl');
				// 排序
				$crud->order_by('sp_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_Sold_packages_tbl'));
				if (! $this->flexi_auth->is_privileged('Billing Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Billing Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Billing Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				// 清單顯示欄位
				$crud->columns('sp_pk', 'sp_title', 'sp_type', 'sp_price', 'sp_show', 'sp_unit', 'sp_unit_value', 'sp_status');
				// 欄位控制
				//$crud->fields('');
				$crud->add_fields('sp_title', 'sp_des', 'sp_type', 'sp_type_des', 'sp_cost', 'sp_price', 'sp_show', 'sp_sort', 'sp_unit', 'sp_unit_value', 'sp_status', 'sp_time_start', 'sp_time_end', 'payments');
				$crud->edit_fields('sp_show', 'sp_sort', 'sp_status', 'sp_time_end', 'payments');
				// 表單必填欄位
				$crud->required_fields('cs_title', 'cs_type', 'cs_word', 'cs_count', 'cs_repeat', 'cs_user_repeat', 'cs_time_start', 'cs_time_end');
				//不使用ckedit
				$crud->unset_texteditor(array('sp_des', 'full_text'));
				// 數值對應
				$crud->field_type('sp_show', 'true_false', array('1'=>'顯示', '0'=>'隱藏'));
				$crud->field_type('sp_status', 'true_false', array('1'=>'開啟', '0'=>'關閉'));
				// relation n n
				$crud->set_relation_n_n('payments', 'Sold_package_payment_tbl', 'Payments_tbl', 'spp_package_no', 'spp_payment_no', '{p_proxy}-{p_title}', null, array('p_status'=>'1'));
				// 資料庫欄位文字替換
				$crud->display_as('sp_pk', $this->lang->line('fields_pk'));
				$crud->display_as('sp_title', $this->lang->line('fields_title'));
				$crud->display_as('sp_des', $this->lang->line('fields_description'));
				$crud->display_as('sp_type', $this->lang->line('fields_type'));
				$crud->display_as('sp_type_des', $this->lang->line('Sold_packages_tbl_fields_sp_type_des'));
				$crud->display_as('sp_image', $this->lang->line('Sold_packages_tbl_fields_sp_image'));
				$crud->display_as('sp_cost', $this->lang->line('fields_cost'));
				$crud->display_as('sp_price', $this->lang->line('fields_price'));
				$crud->display_as('sp_show', $this->lang->line('Sold_packages_tbl_fields_sp_show'));
				$crud->display_as('sp_sort', $this->lang->line('fields_sort'));
				$crud->display_as('sp_unit', $this->lang->line('Sold_packages_tbl_fields_sp_unit'));
				$crud->display_as('sp_unit_value', $this->lang->line('Sold_packages_tbl_fields_sp_unit_value'));
				$crud->display_as('sp_status', $this->lang->line('fields_status'));
				$crud->display_as('sp_time_start', $this->lang->line('fields_time_start_tw'));
				$crud->display_as('sp_time_end', $this->lang->line('fields_time_end_tw'));
				$crud->display_as('sp_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('sp_time_update', $this->lang->line('fields_time_update_tw'));
				$crud->display_as('sp_remark', $this->lang->line('fields_remark'));
				$crud->display_as('payments', '支援金流通路');
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '產品包管理',
						'link' => '/backend/billings/package',
						'class' => 'fa-object-group'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
