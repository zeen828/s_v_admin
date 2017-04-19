<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homes extends CI_Controller
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
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
        $this->load->helper('formats');
        $this->load->helper('captcha');
        // 初始化
        $this->data_view = format_helper_backend_view_data('homes_content');
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        $this->login();
    }

    public function login ($error_no = 0)
    {
        // 變數
        $this->data_view['system']['error_no'] = $error_no;
        $this->data_view['system']['form_action'] = '/auths/login';
        // captcha
        $cap = create_my_captcha(250, 43);
        $this->data_view['system']['captcha_img'] = $cap['image'];
        // 輸出view
        $this->load->view('login/login', $this->data_view);
    }

    public function error ($error_no = 0)
    {
        $this->login($error_no);
    }
}
