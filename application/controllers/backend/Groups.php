<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Groups extends CI_Controller
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
        if (! $this->flexi_auth->is_logged_in() || ! $this->flexi_auth->is_admin()) {
            redirect('homes/login');
        }
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
        $this->load->library('vidol/fun');
        $this->load->helper('formats');
        $this->lang->load('table_flexi_auth', 'traditional-chinese');
        // 初始化
        $this->data_view = format_helper_backend_view_data('groups_content');
        $this->data_view['system']['action'] = 'Groups';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '系統管理',
                'link' => '/backend/groups',
                'class' => 'fa-cog'
        );
        // 效能檢查
        //$this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        //show_404();
        $this->group();
    }

    /**
     * 群組管理
     */
    public function group ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Groups View')) {
                // 寫log
                $this->fun->logs('觀看群組管理');
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
                $crud->set_table('user_groups');
                // 標題
                $crud->set_subject($this->lang->line('tabels_user_groups'));
                if (! $this->flexi_auth->is_privileged('Groups Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Groups Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Groups Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 清單顯示欄位
                $crud->columns('ugrp_id', 'ugrp_name', 'ugrp_desc', 'ugrp_admin');
                // 表單必填欄位
                $crud->required_fields('ugrp_name', 'ugrp_admin');
                // action
                if ($this->flexi_auth->is_privileged('Privileges Edit')) {
                    $crud->add_action('權限', '/assets/grocery_crud/themes/flexigrid/css/images/magnifier.png', '/backend/privileges/group_set');
                }
                // 資料庫欄位文字替換
                $crud->display_as('ugrp_id', $this->lang->line('fields_ugrp_id'));
                $crud->display_as('ugrp_name', $this->lang->line('fields_ugrp_name'));
                $crud->display_as('ugrp_desc', $this->lang->line('fields_ugrp_desc'));
                $crud->display_as('ugrp_admin', $this->lang->line('fields_ugrp_admin'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '群組管理',
                        'link' => '/backend/groups/group',
                        'class' => 'fa-group'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
