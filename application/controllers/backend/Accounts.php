<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounts extends CI_Controller
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
        $this->data_view = format_helper_backend_view_data('accounts_content');
        $this->data_view['system']['action'] = 'Accounts';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '系統管理',
                'link' => '/backend/accounts',
                'class' => 'fa-cog'
        );
        // 效能檢查
        $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        //show_404();
        $this->account();
    }

    /**
     * 帳號管理
     */
    public function account ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Accounts View')) {
                // 寫log
                $this->fun->logs('觀看帳號管理');
                // 變數
                $data_post = array();
                // grocery_CRUD 自產表單
                $this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
                $this->load->library('vidol/grocery_callback');
                $crud = new grocery_CRUD();
                // 語系
                $crud->set_language('taiwan');
                // 版型
                $crud->set_theme('flexigrid');
                // 表格
                $crud->set_table('user_accounts');
                // 標題
                $crud->set_subject($this->lang->line('tabels_user_accounts'));
                if (! $this->flexi_auth->is_privileged('Accounts Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Accounts Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Accounts Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 清單顯示欄位
                $crud->columns('uacc_id', 'uacc_email', 'uacc_username', 'uacc_group_fk', 'uacc_active', 'uacc_ip_address');
                // 新增欄位
                $crud->add_fields('uacc_group_fk', 'uacc_email', 'uacc_username', 'uacc_password', 'uacc_active');
                // 編輯欄位
                $crud->edit_fields('uacc_group_fk', 'uacc_email', 'uacc_username', 'uacc_password', 'uacc_active');
                // 表單必填欄位
               	$crud->required_fields('uacc_group_fk', 'uacc_email', 'uacc_username', 'uacc_password', 'uacc_active');
                // 欄位特殊屬性
                $crud->field_type('uacc_password', 'password');
                // 關聯
                $crud->set_relation('uacc_group_fk', 'user_groups', '{ugrp_name}', array(), 'ugrp_id');
                // 資料庫欄位文字替換
                $crud->display_as('uacc_id', $this->lang->line('fields_uacc_id'));
                $crud->display_as('uacc_group_fk', $this->lang->line('fields_uacc_group_fk'));
                $crud->display_as('uacc_email', $this->lang->line('fields_uacc_email'));
                $crud->display_as('uacc_username', $this->lang->line('fields_uacc_username'));
                $crud->display_as('uacc_password', $this->lang->line('fields_uacc_password'));
                $crud->display_as('uacc_ip_address', $this->lang->line('fields_uacc_ip_address'));
                $crud->display_as('uacc_salt', $this->lang->line('fields_uacc_salt'));
                $crud->display_as('uacc_activation_token', $this->lang->line('fields_uacc_activation_token'));
                $crud->display_as('uacc_forgotten_password_token', $this->lang->line('fields_uacc_forgotten_password_token'));
                $crud->display_as('uacc_forgotten_password_expire', $this->lang->line('fields_uacc_forgotten_password_expire'));
                $crud->display_as('uacc_update_email_token', $this->lang->line('fields_uacc_update_email_token'));
                $crud->display_as('uacc_update_email', $this->lang->line('fields_uacc_update_email'));
                $crud->display_as('uacc_active', $this->lang->line('fields_uacc_active'));
                $crud->display_as('uacc_suspend', $this->lang->line('fields_uacc_suspend'));
                $crud->display_as('uacc_fail_login_attempts', $this->lang->line('fields_uacc_fail_login_attempts'));
                $crud->display_as('uacc_fail_login_ip_address', $this->lang->line('fields_uacc_fail_login_ip_address'));
                $crud->display_as('uacc_date_fail_login_ban', $this->lang->line('fields_uacc_date_fail_login_ban'));
                $crud->display_as('uacc_date_last_login', $this->lang->line('fields_uacc_date_last_login'));
                $crud->display_as('uacc_date_added', $this->lang->line('fields_uacc_date_added'));
                $crud->display_as('uacc_personal_figure', $this->lang->line('fields_uacc_personal_figure'));
                // 欄位顯示改寫
                $crud->callback_field('uacc_password', array(
                        $this->grocery_callback,
                        'form_account_password'
                ));
                // 新增改寫
                $crud->callback_insert(array(
                        $this->grocery_callback,
                        'add_accounts'
                ));
                // 編輯改寫
                $crud->callback_update(array(
                        $this->grocery_callback,
                        'edit_accounts'
                ));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '帳號管理',
                        'link' => '/backend/accounts/account',
                        'class' => 'fa-user'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
