<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealers extends CI_Controller
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
		$this->data_view = format_helper_backend_view_data('dealers_content');
		$this->data_view['system']['action'] = 'Dealers';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '經銷商管理',
				'link' => '/backend/dealers',
				'class' => 'fa-desktop'
		);
		// 效能檢查
		//$this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		//show_404();
		$this->package();
	}
	
	public function dealer ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Dealers View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_dealer_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$this->load->library('vidol/grocery_callback');
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('Dealers_tbl');
				// 排序
				$crud->order_by('d_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_Dealers_tbl'));
				if (! $this->flexi_auth->is_privileged('Dealers Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Dealers Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Dealers Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				// 清單顯示欄位
				$crud->columns('d_pk', 'd_title', 'd_des', 'd_version', 'd_update', 'd_status', 'd_time_creat', 'd_time_update');
				//欄位控制
				//$crud->fields();
				$crud->add_fields('d_title', 'd_des', 'd_logo_url', 'd_coupon', 'd_version', 'd_update', 'd_update_url', 'd_status', 'd_time_creat');
				$crud->edit_fields('d_title', 'd_des', 'd_logo_url', 'd_coupon', 'd_version', 'd_update', 'd_update_url', 'd_status', 'd_time_update');
				// 表單必填欄位
				$crud->required_fields('d_title', 'd_des', 'd_logo_url');
				//不使用ckedit
				$crud->unset_texteditor(array('d_des', 'full_text'));
				// 數值對應
				$crud->field_type('d_update', 'true_false', array('1'=>'強制', '0'=>'不強制'));
				$crud->field_type('d_status', 'true_false', array('1'=>'開啟', '0'=>'關閉'));
				$crud->field_type('d_time_creat', 'hidden', date('Y-m-d h:i:s'));
				$crud->field_type('d_time_update', 'hidden', date('Y-m-d h:i:s'));
				// 資料庫欄位文字替換
				$crud->display_as('d_pk', $this->lang->line('fields_pk'));
				$crud->display_as('d_title', $this->lang->line('fields_title'));
				$crud->display_as('d_des', $this->lang->line('fields_description'));
				$crud->display_as('d_logo_url', $this->lang->line('Dealers_tbl_fields_d_logo_url'));
				$crud->display_as('d_coupon', $this->lang->line('Dealers_tbl_fields_d_coupon'));
				$crud->display_as('d_version', $this->lang->line('Dealers_tbl_fields_d_version'));
				$crud->display_as('d_update', $this->lang->line('Dealers_tbl_fields_d_update'));
				$crud->display_as('d_update_url', $this->lang->line('Dealers_tbl_fields_d_update_url'));
				$crud->display_as('d_status', $this->lang->line('fields_status'));
				$crud->display_as('d_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('d_time_update', $this->lang->line('fields_time_update_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '經銷商管理',
						'link' => '/backend/dealers/dealer',
						'class' => 'fa-diamond'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	public function user_binding ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Dealers View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_dealer_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('User_binding_tbl');
				// 排序
				$crud->order_by('ub_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_User_binding_tbl'));
				if (! $this->flexi_auth->is_privileged('Dealers Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Dealers Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Dealers Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				
// 				if (! $this->flexi_auth->is_admin()) {
// 					// 移除新增
// 					$crud->unset_add();
// 					// 移除編輯
// 					$crud->unset_edit();
// 					// 移除刪除
// 					$crud->unset_delete();
// 				}
				// 清單顯示欄位
				$crud->columns('ub_pk', 'ub_user_no', 'ub_mongo_id', 'ub_member_id', 'ub_dealer', 'ub_device_id', 'ub_device_mac', 'ub_order_sn', 'ub_status', 'ub_time_creat', 'ub_time_update');
				//欄位控制
				//$crud->fields('');
				$crud->add_fields('ub_dealer', 'ub_device_id', 'ub_device_mac', 'ub_device_key', 'ub_status', 'ub_time_creat');
				$crud->edit_fields('ub_dealer', 'ub_device_id', 'ub_device_mac', 'ub_device_key', 'ub_status', 'ub_time_update');
				// 表單必填欄位
				$crud->required_fields('ub_dealer', 'ub_device_id', 'ub_device_mac', 'ub_device_key');
				//不使用ckedit
				//$crud->unset_texteditor(array('d_des', 'full_text'));
				// 數值對應
				$crud->field_type('ub_status', 'true_false', array('1'=>'綁定', '0'=>'未綁定'));
				$crud->field_type('ub_time_creat', 'hidden', date('Y-m-d h:i:s'));
				$crud->field_type('ub_time_update', 'hidden', date('Y-m-d h:i:s'));
				// 資料庫欄位文字替換
				$crud->display_as('ub_pk', $this->lang->line('fields_pk'));
				$crud->display_as('ub_user_no', $this->lang->line('fields_user_no'));
				$crud->display_as('ub_mongo_id', $this->lang->line('fields_mongo_id'));
				$crud->display_as('ub_member_id', $this->lang->line('fields_member_id'));
				$crud->display_as('ub_dealer', $this->lang->line('User_binding_tbl_fields_ub_dealer'));
				$crud->display_as('ub_device_id', $this->lang->line('User_binding_tbl_fields_ub_device_id'));
				$crud->display_as('ub_device_mac', $this->lang->line('User_binding_tbl_fields_ub_device_mac'));
				$crud->display_as('ub_device_key', $this->lang->line('User_binding_tbl_fields_ub_device_key'));
				$crud->display_as('ub_order_sn', $this->lang->line('fields_order_sn'));
				$crud->display_as('ub_status', $this->lang->line('fields_status'));
				$crud->display_as('ub_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('ub_time_update', $this->lang->line('fields_time_update_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/dealers/user_binding';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '設備綁定管理',
						'link' => '/backend/dealers/user_binding',
						'class' => 'fa-diamond'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	public function ovo_ad ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Dealers View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_dealer_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('OVO_ad_tbl');
				// 排序
				$crud->order_by('oa_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_OVO_ad_tbl'));
				if (! $this->flexi_auth->is_privileged('Dealers Add')) {
					// 移除新增
					$crud->unset_add();
				}
				if (! $this->flexi_auth->is_privileged('Dealers Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				if (! $this->flexi_auth->is_privileged('Dealers Del')) {
					// 移除刪除
					$crud->unset_delete();
				}
				// 清單顯示欄位
				$crud->columns('oa_pk', 'oa_title', 'oa_des', 'oa_img_url', 'oa_app_uri', 'oa_sort', 'oa_status', 'oa_time_creat', 'oa_time_update');
				//欄位控制
				//$crud->fields('');
				$crud->add_fields('oa_title', 'oa_des', 'oa_img_url', 'oa_app_uri', 'oa_sort', 'oa_status', 'oa_time_creat');
				$crud->edit_fields('oa_title', 'oa_des', 'oa_img_url', 'oa_app_uri', 'oa_sort', 'oa_status', 'oa_time_update');
				// 表單必填欄位
				$crud->required_fields('oa_title', 'oa_des', 'oa_img_url', 'oa_app_uri');
				//不使用ckedit
				$crud->unset_texteditor(array('oa_des', 'full_text'));
				// 數值對應
				$crud->field_type('oa_status', 'true_false', array('1'=>'開啟', '0'=>'關閉'));
				$crud->field_type('oa_time_creat', 'hidden', date('Y-m-d h:i:s'));
				$crud->field_type('oa_time_update', 'hidden', date('Y-m-d h:i:s'));
				// 資料庫欄位文字替換
				$crud->display_as('oa_pk', $this->lang->line('fields_pk'));
				$crud->display_as('oa_title', $this->lang->line('fields_title'));
				$crud->display_as('oa_des', $this->lang->line('fields_description'));
				$crud->display_as('oa_img_url', $this->lang->line('OVO_ad_tbl_fields_oa_img_url'));
				$crud->display_as('oa_app_uri', $this->lang->line('OVO_ad_tbl_fields_oa_app_uri'));
				$crud->display_as('oa_sort', $this->lang->line('fields_sort'));
				$crud->display_as('oa_status', $this->lang->line('fields_status'));
				$crud->display_as('oa_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('oa_time_update', $this->lang->line('fields_time_update_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => 'OVO動態牆管理',
						'link' => '/backend/dealers/user_binding',
						'class' => 'fa-desktop'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	public function version ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Dealers View')) {
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_dealer_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('Tvbox_tbl');
				// 排序
				$crud->order_by('t_pk', 'asc');
				// 標題
				$crud->set_subject($this->lang->line('tabels_Tvbox_tbl'));
				// 移除新增
				$crud->unset_add();
				if (! $this->flexi_auth->is_privileged('Dealers Edit')) {
					// 移除編輯
					$crud->unset_edit();
				}
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('t_des', 't_version', 't_update', 't_update_url', 't_time_creat', 't_time_update');
				//欄位控制
				//$crud->fields('');
				$crud->add_fields('t_des', 't_version', 't_update', 't_update_url', 't_time_creat');
				$crud->edit_fields('t_des', 't_version', 't_update', 't_update_url', 't_time_update');
				// 表單必填欄位
				$crud->required_fields('t_version', 't_update', 't_update_url');
				//不使用ckedit
				$crud->unset_texteditor(array('t_des', 'full_text'));
				// 數值對應
				$crud->field_type('d_update', 'true_false', array('1'=>'強制', '0'=>'不強制'));
				$crud->field_type('t_time_creat', 'hidden', date('Y-m-d h:i:s'));
				$crud->field_type('t_time_update', 'hidden', date('Y-m-d h:i:s'));
				// 資料庫欄位文字替換
				$crud->display_as('t_pk', $this->lang->line('fields_pk'));
				$crud->display_as('t_des', $this->lang->line('fields_description'));
				$crud->display_as('t_version', $this->lang->line('Tvbox_tbl_fields_t_version'));
				$crud->display_as('t_update', $this->lang->line('Tvbox_tbl_fields_t_update'));
				$crud->display_as('t_update_url', $this->lang->line('Tvbox_tbl_fields_t_update_url'));
				$crud->display_as('t_time_creat', $this->lang->line('fields_time_creat_tw'));
				$crud->display_as('t_time_update', $this->lang->line('fields_time_update_tw'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '電視盒公版版本管理',
						'link' => '/backend/dealers/version',
						'class' => 'fa-desktop'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
