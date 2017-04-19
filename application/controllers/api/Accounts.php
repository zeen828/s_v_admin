<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Mongodbs extends REST_Controller
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
        $this->load->library('email');
        $this->load->model('mongo_model');
        $this->load->helper('formats');
        $this->lang->load('vidol', 'traditional-chinese');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index_get ()
    {
        $this->response(NULL, 404);
    }

    //還沒做好,修改帳戶密碼
    public function change_password_post ()
    {
        try {
            // 變數
            $data_input = array();
            $result = array(
                    'status' => '',
                    'debug' => ''
            );
            //
            $data_input['new_password'] = $this->post('new_password');
            $data_input['confirm_password'] = $this->post('confirm_password');
            //還沒寫好
            print_r($data_input);
            
            $this->response($result, 200);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
