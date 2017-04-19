<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analytics extends CI_Controller
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
        $this->data_view = format_helper_backend_view_data('analytics_content');
        $this->data_view['system']['action'] = 'Analytics';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '數據報表',
                'link' => '/backend/analytics',
                'class' => 'fa-jsfiddle'
        );
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        // show_404();
        $this->qrcode();
    }

    public function brightcove ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Analytics View')) {
                // 變數
                $data_post = array();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/analytics/brightcove';
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => 'Brightcove',
                        'link' => '/backend/analytics/brightcove',
                        'class' => 'fa-jsfiddle'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            }
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
