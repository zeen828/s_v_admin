<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privileges extends CI_Controller
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
        $this->data_view = format_helper_backend_view_data('privileges_content');
        $this->data_view['system']['action'] = 'Privileges';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '系統管理',
                'link' => '/backend/privileges',
                'class' => 'fa-cog'
        );
        // 效能檢查
        //$this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        //show_404();
        $this->privilege();
    }

    /**
     * 權限管理
     */
    public function privilege ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Privileges View')) {
                // 寫log
                $this->fun->logs('觀看權限管理');
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
                $crud->set_table('user_privileges');
                // 標題
                $crud->set_subject($this->lang->line('tabels_user_privileges'));
                if (! $this->flexi_auth->is_privileged('Privileges Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Privileges Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Privileges Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 表單必填欄位
                $crud->required_fields('upriv_name', 'upriv_desc');
                // 資料庫欄位文字替換
                $crud->display_as('upriv_id', $this->lang->line('fields_upriv_id'));
                $crud->display_as('upriv_name', $this->lang->line('fields_upriv_name'));
                $crud->display_as('upriv_desc', $this->lang->line('fields_upriv_desc'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '權限管理',
                        'link' => '/backend/privileges/privilege',
                        'class' => 'fa-eye'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /**
     * 帳戶權限管理
     */
    public function account ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Privileges View')) {
                // 寫log
                $this->fun->logs('觀看帳號權限');
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
                $crud->set_table('user_privilege_users');
                // 標題
                $crud->set_subject($this->lang->line('tabels_user_privilege_users'));
                if (! $this->flexi_auth->is_privileged('Privileges Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Privileges Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Privileges Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 關聯
                $crud->set_relation('upriv_users_uacc_fk', 'user_accounts', '{uacc_username}', array(), 'uacc_id');
                $crud->set_relation('upriv_users_upriv_fk', 'user_privileges', '{upriv_name}[{upriv_desc}]', array(), 'upriv_id');
                // 資料庫欄位文字替換
                $crud->display_as('upriv_users_id', $this->lang->line('fields_upriv_users_id'));
                $crud->display_as('upriv_users_uacc_fk', $this->lang->line('fields_upriv_users_uacc_fk'));
                $crud->display_as('upriv_users_upriv_fk', $this->lang->line('fields_upriv_users_upriv_fk'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '帳號權限',
                        'link' => '/backend/privileges/account',
                        'class' => 'fa-eye'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /**
     * 群組權限管理
     */
    public function group ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Privileges View')) {
                // 寫log
                $this->fun->logs('觀看群組權限');
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
                $crud->set_table('user_privilege_groups');
                // 標題
                $crud->set_subject($this->lang->line('tabels_user_privilege_groups'));
                if (! $this->flexi_auth->is_privileged('Privileges Add')) {
                    // 移除新增
                    $crud->unset_add();
                }
                if (! $this->flexi_auth->is_privileged('Privileges Edit')) {
                    // 移除編輯
                    $crud->unset_edit();
                }
                if (! $this->flexi_auth->is_privileged('Privileges Del')) {
                    // 移除刪除
                    $crud->unset_delete();
                }
                // 關聯
                $crud->set_relation('upriv_groups_ugrp_fk', 'user_groups', '{ugrp_name}', array(), 'ugrp_id');
                $crud->set_relation('upriv_groups_upriv_fk', 'user_privileges', '{upriv_name}[{upriv_desc}]', array(), 'upriv_id');
                // 資料庫欄位文字替換
                $crud->display_as('upriv_groups_id', $this->lang->line('fields_upriv_groups_id'));
                $crud->display_as('upriv_groups_ugrp_fk', $this->lang->line('fields_upriv_groups_ugrp_fk'));
                $crud->display_as('upriv_groups_upriv_fk', $this->lang->line('fields_upriv_groups_upriv_fk'));
                // 產生表單
                $output = $crud->render();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
                $this->data_view['right_countent']['view_data'] = $output;
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '群組權限',
                        'link' => '/backend/privileges/group',
                        'class' => 'fa-eye'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /**
     * 群組權限設定
     * @param unknown $group_id 群組ID
     */
    public function group_set ($group_id)
    {
        try {
            if ($this->flexi_auth->is_privileged('Privileges Edit')) {
            	// 寫log
            	$this->fun->logs('觀看群組權限設定');
                // 變數
                $data_post = array();
                $data_group_privileges = array();
                //引用
                $this->load->model('flexi_auth_plus_model');
                if(! empty($group_id)){
                    // 取得群組權限pk
                    $data_group_privileges = $this->flexi_auth_plus_model->get_groups_privileges($group_id);
                    // get all pp
                    $query = $this->db->get("user_privileges");
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $row) {
                            if ($this->flexi_auth->is_privileged($row->upriv_name)) {
                                $checkbox = (in_array($row->upriv_id, $data_group_privileges)) ? true : false;
                                $privilege = explode(" ", $row->upriv_name);
                                $data_post[$privilege[0]]['title'] = $privilege[0];
                                $data_post[$privilege[0]]['data'][$privilege[1]] = array(
                                        'pk' => $row->upriv_id,
                                        'action' => $privilege[1],
                                        'checkbox' => $checkbox,
                                        'title' => $row->upriv_name,
                                        'description' => $row->upriv_desc
                                );
                            }
                        }
                    }
                    // 資料整理
                    $this->data_view['right_countent']['view_path'] = 'AdminLTE/privileges/group_set';
                    $this->data_view['right_countent']['view_data'] = $data_post;
                    $this->data_view['right_countent']['tags']['tag_3'] = array(
                            'title' => '群組權限設定',
                            'link' => '/backend/privileges/group_set',
                            'class' => 'fa-eye'
                    );
                }
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
