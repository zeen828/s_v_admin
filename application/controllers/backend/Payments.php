<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
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
		$this->data_view = format_helper_backend_view_data('payments_content');
		$this->data_view['system']['action'] = 'Payments';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '金流通路管理',
				'link' => '/backend/payments',
				'class' => 'fa-btc'
		);
		// 效能檢查
		//$this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		//show_404();
		$this->package();
	}
	
	public function payment ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Payments View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_billing_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('Payments_tbl');
				// 排序
				$crud->order_by('p_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_Payments_tbl'));
				if (! $this->flexi_auth->is_privileged('Payments Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Payments Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Payments Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				// 清單顯示欄位
				$crud->columns('p_pk', 'p_title', 'p_des', 'p_proxy', 'p_type');
				//欄位控制
				$crud->fields('p_title', 'p_des', 'p_proxy', 'p_type', 'p_rs', 'p_status');
				//$crud->add_fields('');
				//$crud->edit_fields('');
				// 表單必填欄位
				$crud->required_fields('p_title', 'p_des', 'p_proxy', 'p_type', 'p_rs', 'p_status');
				//不使用ckedit
				$crud->unset_texteditor(array('p_des', 'full_text'));
				// 數值對應
				$crud->field_type('p_rs', 'dropdown', array('0'=>'非約定', '1'=>'約定自動扣款'));
				$crud->field_type('p_status', 'dropdown', array('1'=>'開啟', '0'=>'關閉'));
				// 資料庫欄位文字替換
				$crud->display_as('p_pk', $this->lang->line('fields_pk'));
				$crud->display_as('p_title', $this->lang->line('fields_title'));
				$crud->display_as('p_des', $this->lang->line('fields_description'));
				$crud->display_as('p_proxy', $this->lang->line('Payments_tbl_fields_p_proxy'));
				$crud->display_as('p_type', $this->lang->line('fields_type'));
				$crud->display_as('p_rs', $this->lang->line('Payments_tbl_fields_p_rs'));
				$crud->display_as('p_status', $this->lang->line('fields_status'));
				$crud->display_as('p_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('p_time_update', $this->lang->line('fields_time_update_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '金流通路管理',
						'link' => '/backend/payments/payment',
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
