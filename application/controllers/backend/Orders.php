<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
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
        $this->data_view = format_helper_backend_view_data('orders_content');
        $this->data_view['system']['action'] = 'Orders';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '訂單管理',
                'link' => '/backend/orders',
                'class' => 'fa-money'
        );
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        //show_404();
        $this->search();
    }
    
    public function search ()
    {
    	//$this->output->enable_profiler(TRUE);
    	try {
    		if ($this->flexi_auth->is_privileged('Orders View')) {
    			// 寫log
    			$this->fun->logs('觀看訂單查詢紀錄');
    			// 變數
    			$data_post = array();
    			// 資料整理
    			$this->data_view['right_countent']['view_path'] = 'AdminLTE/orders/search';
    			//$this->data_view['right_countent']['view_data'] = $output;
    			$this->data_view['right_countent']['tags']['tag_3'] = array(
    					'title' => '訂單查詢紀錄',
    					'link' => '/backend/orders/search',
    					'class' => 'fa-building-o'
    			);
    			// 套版
    			$this->load->view('AdminLTE/include/html5', $this->data_view);
    		}
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }

    public function paykit_order ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Orders View')) {
                // 寫log
                $this->fun->logs('觀看paykit訂單查詢紀錄');
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
                $crud->set_table('paykit_order_tbl');
                // 標題
                $crud->set_subject($this->lang->line('tabels_paykit_order_tbl'));
                // 移除新增
                $crud->unset_add();
                // 移除編輯
                $crud->unset_edit();
                // 移除刪除
                $crud->unset_delete();
                // 清單顯示欄位
                $crud->columns('id', 'mongo_id', 'member_id', 'packageNames', 'action', 'type', 'cost');
                // 資料庫欄位文字替換
                $crud->display_as('id', $this->lang->line('fields_id'));
                $crud->display_as('user_id', $this->lang->line('fields_user_id'));
                $crud->display_as('mongo_id', $this->lang->line('fields_mongo_id'));
                $crud->display_as('member_id', $this->lang->line('fields_member_id'));
                $crud->display_as('nick_name', $this->lang->line('fields_nick_name'));
                $crud->display_as('action', $this->lang->line('fields_action'));
                $crud->display_as('type', $this->lang->line('fields_type'));
                $crud->display_as('packageNames', $this->lang->line('fields_packageNames'));
                $crud->display_as('comment', $this->lang->line('fields_comment'));
                $crud->display_as('cost', $this->lang->line('fields_cost'));
                $crud->display_as('coupon', $this->lang->line('fields_coupon'));
                $crud->display_as('status', $this->lang->line('fields_status'));
                $crud->display_as('createdAt', $this->lang->line('fields_createdAt'));
                $crud->display_as('updatedAt', $this->lang->line('fields_updatedAt'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => 'paykit 訂單查詢紀錄',
                        'link' => '/backend/orders/paykit_order',
                        'class' => 'fa-building-o'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    /**
     * 訂單查詢
     * @param string $transtionID   訂單id
     */
    public function paykit_search ($transtionID = '')
    {
        try {
            if ($this->flexi_auth->is_privileged('Orders View')) {
            	// 寫log
            	$this->fun->logs('paykit訂單查詢');
                // 變數
                $data_post = array();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/orders/paykit_search';
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => 'paykit 訂單查詢',
                        'link' => '/backend/orders/paykit_search',
                        'class' => 'fa-search'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
