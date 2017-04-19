<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Analytics extends REST_Controller
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
        $this->load->model('brightcove_model');
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

    public function report_get ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Analytics View')) {
                // 變數
                $data_input = array();
                // input
                $data_input['start'] = $this->get('start');
                $data_input['end'] = $this->get('end');
                if (! empty($data_input['start']) && ! empty($data_input['end'])) {
                    // 取得筆數
                    $data_get = $this->brightcove_model->get_report($data_input['start'], $data_input['end']);
                    if (count($data_get) > 0) {
                        $this->data_result['status'] = true;
                        $this->data_result['input'] = $data_input;
                        $this->data_result['data'] = $data_get;
                        $this->response($this->data_result, 200);
                    } else {
                        $this->response($this->data_result, 204);
                    }
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
    
    public function report_type_get ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Analytics View')) {
                // 變數
                $data_input = array();
                // input
                $data_input['type'] = $this->get('type');
                $data_input['start'] = $this->get('start');
                $data_input['end'] = $this->get('end');
                if (! empty($data_input['type']) && ! empty($data_input['start']) && ! empty($data_input['end'])) {
                    // 取得筆數
                    $data_get = $this->brightcove_model->get_report_type($data_input['type'], $data_input['start'], $data_input['end']);
                    if (count($data_get) > 0) {
                        $this->data_result['status'] = true;
                        $this->data_result['input'] = $data_input;
                        $this->data_result['data'] = $data_get;
                        $this->response($this->data_result, 200);
                    } else {
                        $this->response($this->data_result, 204);
                    }
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
    
    public function test_get ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Analytics View')) {
                // 變數
                $data_input = array();
                // input
                $data_input['start'] = $this->get('start');
                $data_input['end'] = $this->get('end');
                if (! empty($data_input['start']) && ! empty($data_input['end'])) {
                    // 取得筆數
                    $data_get = $this->brightcove_model->get_report($data_input['start'], $data_input['end']);
                    if (count($data_get) > 0) {
                        $this->data_result['status'] = true;
                        $this->data_result['input'] = $data_input;
                        $this->data_result['data'] = $data_get;
                        $this->response($this->data_result, 200);
                    } else {
                        $this->response($this->data_result, 204);
                    }
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
