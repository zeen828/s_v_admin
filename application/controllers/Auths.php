<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auths extends CI_Controller
{

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
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        $this->login();
    }

    public function login ()
    {
        // 變數
        $data_post = array();
        // input
        $data_post['login_user'] = $this->input->post('login_user');
        $data_post['login_password'] = $this->input->post('login_password');
        $data_post['login_captcha'] = $this->input->post('login_captcha');
        // print_r($data_post);
        // checking captcha
        $status_captcha = checking_my_captcha($data_post['login_captcha']);
        // var_dump($status_captcha);
        if ($status_captcha) {
            // flexi auth login
            $status_login = $this->flexi_auth->login($data_post['login_user'], $data_post['login_password'], false);
            // var_dump($status_login);
            if ($status_login) {
                $identity = $this->flexi_auth->get_user_identity();
                trigger_error(sprintf("[%s]登入後台.", $identity), E_USER_NOTICE);
                redirect('/backend/');
            } else {
                trigger_error(sprintf("[%s]登入後台錯誤!!", $data_post['login_user']), E_USER_WARNING);
                redirect('/homes/error/2');
            }
        } else {
            trigger_error(sprintf("[%s]登入後台檢查碼錯誤!!", $data_post['login_user']), E_USER_WARNING);
            redirect('/homes/error/1');
        }
    }

    public function logout ()
    {
        $identity = $this->flexi_auth->get_user_identity();
        // By setting the logout functions argument as 'TRUE', all browser
        // sessions are logged out.
        $this->flexi_auth->logout(TRUE);
        // Set a message to the CI flashdata so that it is available after the
        // page redirect.
        $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
        trigger_error(sprintf("[%s]登出後台.", $identity), E_USER_NOTICE);
        redirect('/');
    }
}
