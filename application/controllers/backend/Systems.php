<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Systems extends CI_Controller
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
        $this->data_view = format_helper_backend_view_data('systems_content');
        $this->data_view['system']['action'] = 'Systems';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '系統管理',
                'link' => '/backend/systems',
                'class' => 'fa-cog'
        );
        // 效能檢查
        //$this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        //show_404();
        $this->log();
    }

    /**
     * LOG管理
     */
    public function log ()
    {
        try {
            if ($this->flexi_auth->is_admin()) {
            	// 寫log
            	//$this->fun->logs('觀看記錄');
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
                $crud->set_table('Logs_tbl');
                // 標題
                $crud->set_subject($this->lang->line('tabels_Logs_tbl'));
                // 移除新增
                $crud->unset_add();
                // 移除編輯
                $crud->unset_edit();
                // 移除刪除
                $crud->unset_delete();
                // 清單顯示欄位
                $crud->columns('id', 'errtype', 'errstr', 'ip_address', 'time');
                // 資料庫欄位文字替換
                $crud->display_as('id', $this->lang->line('fields_pk'));
                $crud->display_as('errno', $this->lang->line('fields_l_errno'));
                $crud->display_as('errtype', $this->lang->line('fields_l_errtype'));
                $crud->display_as('errstr', $this->lang->line('fields_l_errstr'));
                $crud->display_as('errfile', $this->lang->line('fields_l_errfile'));
                $crud->display_as('errline', $this->lang->line('fields_l_errline'));
                $crud->display_as('user_agent', $this->lang->line('fields_l_user_agent'));
                $crud->display_as('ip_address', $this->lang->line('fields_ip'));
                $crud->display_as('time', $this->lang->line('fields_l_time'));
                // 排序
                $crud->order_by('id', 'desc');
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '記錄',
                        'link' => '/backend/systems/log',
                        'class' => 'fa-file-text-o'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
