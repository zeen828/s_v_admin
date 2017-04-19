<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fun
{

    private $CI;

    private $data_result;

    public function __construct ()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('vidol');
        $this->CI->load->library('flexi_auth');
    }

    public function logs ($message)
    {
        // 取得帳號id
        $identity = $this->CI->flexi_auth->get_user_identity();
        // 合併訊息
        $message = sprintf('[%s]%s', $identity, $message);
        // 寫log
        trigger_error($message, 1024);
    }
}
