<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Error extends REST_Controller
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
        //if (! $this->flexi_auth->is_logged_in()) {
            //$this->response(NULL, 404);
        //}
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
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
    
    // 測試GET用
    public function get_get ()
    {
        $this->data_result['GET'] = $_GET;
        
        $this->response($this->data_result, 200);
    }
    
    // 測試POST用
    public function post_post ()
    {
        $this->data_result['POST'] = $_POST;
        
        $this->response($this->data_result, 200);
    }

    public function debug_get ()
    {
        //$this->rest->debug();
        //DELETE FROM `vidol`.`Board_tbl` WHERE  `b_type` =  'event'
    }
    
}
