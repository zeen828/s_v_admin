<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tools extends CI_Controller
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
        $this->config->load('vidol');
        // 初始化
        $this->data_view = format_helper_backend_view_data('tools_content');
        $this->data_view['system']['action'] = 'Tools';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '工具幫手',
                'link' => '/backend/tools',
                'class' => 'fa-wheelchair'
        );
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        // show_404();
        $this->qrcode();
    }

    public function qrcode ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Tools View')) {
            	// 寫log
            	$this->fun->logs('觀看QRcode產生');
                // 變數
                $data_post = array();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/tools/qrcode';
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => 'QRcode產生',
                        'link' => '/backend/tools/qrcode',
                        'class' => 'fa-qrcode'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function slide ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Tools View')) {
                // 寫log
                $this->fun->logs('觀看節目表');
                // 變數
                $data_post = array();
                // grocery_CRUD 自產表單
                $this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
                $crud = new grocery_CRUD();
                // 語系
                $crud->set_language('taiwan');
                // 版型
                $crud->set_theme('flexigrid');
                // 表格
                $crud->set_table('Program_tbl');
                // relation
                $crud->set_relation('p_type', 'Options_tpl', 'o_title', array('o_use' => 'Program_tbl.p_type', 'o_status' => 1), 'o_sort ASC');
                $crud->set_relation('p_week', 'Options_tpl', 'o_title', array('o_use' => 'Program_tbl.p_week', 'o_status' => 1), 'o_sort ASC');
                // 標題
                $crud->set_subject($this->lang->line('tabels_Program_tbl'));
                if (! $this->flexi_auth->is_privileged('Tools Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Tools Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Tools Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 清單顯示欄位
                $crud->columns('p_no', 'p_type', 'p_week', 'p_title', 'p_time_start', 'p_status');
                // 資料庫欄位文字替換
                $crud->display_as('p_no', $this->lang->line('fields_pk'));
                $crud->display_as('p_type', $this->lang->line('fields_p_type'));
                $crud->display_as('p_week', $this->lang->line('fields_p_week'));
                $crud->display_as('p_title', $this->lang->line('fields_p_title'));
                $crud->display_as('p_time_start', $this->lang->line('fields_p_time_start'));
                $crud->display_as('p_time_end', $this->lang->line('fields_p_time_end'));
                $crud->display_as('p_item_type', $this->lang->line('fields_p_item_type'));
                $crud->display_as('p_video_id', $this->lang->line('fields_p_video_id'));
                $crud->display_as('p_tag', $this->lang->line('fields_p_tag'));
                $crud->display_as('p_status', $this->lang->line('fields_p_status'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/tools/slide';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '節目表',
                        'link' => '/backend/tools/slide',
                        'class' => 'fa-television'
                );
                //SERVER IP
                $this->data_view['right_countent']['server_ip'] = $this->config->item('server_ip');
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function ios ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Tools View')) {
                // 寫log
                $this->fun->logs('觀看ios設定檔');
                // 變數
                $data_post = array();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/tools/ios';
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => 'ios設定檔',
                        'link' => '/backend/tools/ios',
                        'class' => 'fa-apple'
                );
                //SERVER IP
                $this->data_view['right_countent']['server_ip'] = $this->config->item('server_ip');
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function  board()
    {
    	try {
    		if ($this->flexi_auth->is_privileged('Tools View')) {
    			// 寫log
    			$this->fun->logs('觀看留言轉移');
    			// 變數
    			$data_post = array();
    			// 資料整理
    			$this->data_view['right_countent']['view_path'] = 'AdminLTE/tools/board';
    			$this->data_view['right_countent']['tags']['tag_3'] = array(
    					'title' => '留言轉移',
    					'link' => '/backend/tools/board',
    					'class' => 'fa-refresh'
    			);
    			// 套版
    			$this->load->view('AdminLTE/include/html5', $this->data_view);
    		}
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function  cron_url()
    {
    	try {
    		if ($this->flexi_auth->is_privileged('Tools View')) {
    			// 寫log
    			$this->fun->logs('觀看排程網址');
    			// 變數
    			$data_post = array();
    			// 資料整理
    			$this->data_view['right_countent']['view_path'] = 'AdminLTE/tools/cron_url';
    			$this->data_view['right_countent']['tags']['tag_3'] = array(
    					'title' => '觀看排程網址',
    					'link' => '/backend/tools/cron_url',
    					'class' => 'fa-refresh'
    			);
    			// 套版
    			$this->load->view('AdminLTE/include/html5', $this->data_view);
    		}
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function  tmp()
    {
    	try {
    		if ($this->flexi_auth->is_privileged('Tools View')) {
    			// 變數
    			$data_post = array();
    			// 強制切換資料庫
    			unset($this->db);
    			$this->db = $this->load->database('vidol_user_write', true);
    			// grocery_CRUD 自產表單
    			$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
    			$crud = new grocery_CRUD();
    			// 語系
    			$crud->set_language('taiwan');
    			// 版型
    			$crud->set_theme('flexigrid');
    			// 表格
    			$crud->set_table('User_pay_tbl');
    			// 排序
    			$crud->order_by('up_pk', 'asc');
   				// 移除新增
   				$crud->unset_add();
   				// 移除編輯
   				$crud->unset_edit();
   				// 移除刪除
   				$crud->unset_delete();
    			// 資料整理
    			$this->data_view['right_countent']['view_path'] = 'AdminLTE/tools/tmp';
    			$this->data_view['right_countent']['view_data'] = $output;
    			$this->data_view['right_countent']['tags']['tag_3'] = array(
    					'title' => '設備綁定管理(t)',
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
}
