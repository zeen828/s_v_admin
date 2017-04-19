<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Privileges extends REST_Controller
{

    private $data_result;

    public function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 權限
        $this->auth = new stdClass();
        $this->load->library('flexi_auth');
        if (! $this->flexi_auth->is_logged_in()) {
            $this->response(NULL, 404);
        }
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
        $this->load->library('vidol/fun');
        $this->load->helper('formats');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index_get ()
    {
        $this->response(NULL, 404);
    }

    public function group_set_post ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Privileges Edit')) {
                // 變數
                $data_input = array();
                $data_group_privileges = array();
                // 引用
                $this->load->model('flexi_auth_plus_model');
                // input
                $data_input['group_id'] = $this->post('group_id');
                $data_input['privileges'] = $this->post('privileges');
                if (! empty($data_input['group_id'])) {
                    $this->data_result['status'] = true;
                    $this->data_result['input'] = $data_input;
                    // 取得群組id
                    $group_id = $this->flexi_auth->get_user_group_id();
                    // 寫log
                    $this->fun->logs(sprintf('設定群組{%s}權限', $data_input['group_id']));
                    // 取得設定者擁有的權限pk
                    $data_group_privileges = $this->flexi_auth_plus_model->get_groups_privileges($group_id);
                    $data_group_privileges_tmp = $data_group_privileges;
                    // 先刪除設定者可設定的全部權限
                    if (count($data_group_privileges_tmp) > 0) {
                        foreach ($data_group_privileges_tmp as $key => $val) {
                            // 刪除
                            $this->flexi_auth->delete_user_group_privilege(array(
                                    'upriv_groups_ugrp_fk' => $data_input['group_id'],
                                    'upriv_groups_upriv_fk' => $val
                            ));
                        }
                    }
                    // 再添加設定者可增加的權限,設定者沒有的權限不可以增加
                    if (count($data_input['privileges']) > 0) {
                        foreach ($data_input['privileges'] as $key => $val) {
                            // 只能設定設定者擁有的權限
                            if (in_array($val, $data_group_privileges)) {
                                // 檢查是否已存在
                                if (! $this->flexi_auth_plus_model->check_groups_privileges($data_input['group_id'], $val)) {
                                    // 再增加設定權限
                                    $this->data_result['data'][$key] = $this->flexi_auth->insert_user_group_privilege($data_input['group_id'], $val);
                                }
                            }
                        }
                    }
                    $this->data_result['POST'] = $_POST;
                    $this->response($this->data_result, 200);
                } else {
                    $this->response(NULL, 400);
                }
            } else {
                $this->response(NULL, 401);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
