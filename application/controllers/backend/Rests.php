<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rests extends CI_Controller
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
        $this->data_view = format_helper_backend_view_data('rests_content');
        $this->data_view['system']['action'] = 'Rests';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => 'RESTful管理',
                'link' => '/backend/rests',
                'class' => 'fa-terminal'
        );
        // 效能檢查
        $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        //show_404();
        $this->key();
    }

    public function key ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Rests View')) {
                // 寫log
                $this->fun->logs('觀看金鑰管理');
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
                $crud->set_table('Rest_keys_tbl');
                // 標題
                $crud->set_subject($this->lang->line('tabels_Rest_keys_tbl'));
                if (! $this->flexi_auth->is_privileged('Rests Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Rests Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Rests Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 清單顯示欄位
                $crud->columns('id', 'key', 'is_private_key', 'ip_addresses');
                // 資料庫欄位文字替換
                $crud->display_as('id', $this->lang->line('fields_pk'));
                $crud->display_as('user_id', $this->lang->line('fields_r_user_id'));
                $crud->display_as('key', $this->lang->line('fields_r_key'));
                $crud->display_as('level', $this->lang->line('fields_r_level'));
                $crud->display_as('ignore_limits', $this->lang->line('fields_r_ignore_limits'));
                $crud->display_as('is_private_key', $this->lang->line('fields_r_is_private_key'));
                $crud->display_as('ip_addresses', $this->lang->line('fields_r_ip_addresses'));
                $crud->display_as('date_created', $this->lang->line('fields_r_date_created'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '金鑰管理',
                        'link' => '/backend/tools/qrcode',
                        'class' => 'fa-key'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
