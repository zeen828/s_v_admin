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
