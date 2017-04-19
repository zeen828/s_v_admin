<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Tools extends REST_Controller
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

    public function qrcode_post ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Tools View')) {
                // 變數
                $data_input = array();
                // input
                $data_input['qrcode_size'] = $this->post('qrcode_size');
                $data_input['qrcode_data'] = $this->post('qrcode_data');
                if (! empty($data_input['qrcode_data'])) {
                	//
                	$this->config->load('vidol');
                    // 寫log
                    $this->fun->logs(sprintf('建立QRcode{%s}', $data_input['qrcode_data']));
                    // 引用
                    $this->load->helper('file');
                    $this->load->library('ciqrcode');
                    //
                    $this->data_result['status'] = true;
                    $this->data_result['input'] = $data_input;
                    //
                    $file = sprintf("qrcode_%s.png", time());
                    $path = $this->config->item('qrcode_path');
                    $url = $this->config->item('qrcode_url');
                    $http = $this->config->item('qrcode_http_url');
                    // qrcode
                    $params['data'] = $data_input['qrcode_data'];
                    $params['level'] = 'H';
                    $params['size'] = $data_input['qrcode_size'];
                    // 字
                    $params['white'] = array(245, 243, 35);
                    // 底色
                    $params['black'] = array(71, 47, 255);
                    $params['savename'] = sprintf('%s%s', $path, $file);
                    $this->ciqrcode->generate($params);
                    if (true) {
                        // QRcode壓logo圖
                        $img = array();
                        $img['QR']['url'] = sprintf('%s%s', $http, $file);
                        $img['LG']['url'] = sprintf($this->config->item('qrcode_logo_str'), $data_input['qrcode_size']);
                        $img['QR']['img'] = imagecreatefromstring(file_get_contents($img['QR']['url'] ));
                        $img['LG']['img'] = imagecreatefromstring(file_get_contents($img['LG']['url']));
                        $img['QR']['width'] = imagesx($img['QR']['img']);
                        $img['QR']['height'] = imagesy($img['QR']['img']);
                        $img['LG']['width'] = imagesx($img['LG']['img']);
                        $img['LG']['height'] = imagesy($img['LG']['img']);
                        // 縮放组合图片
                        //$img['QRLG']['width'] = $img['QR']['width'] / 5;
                        //$img['scale'] = $img['LG']['width'] / $img['QRLG']['width'];
                        //$img['QRLG']['height'] = $img['LG']['height'] / $img['scale'];
                        //$img['from_width'] = ($img['QR']['width'] - $img['QRLG']['width']) / 2;
                        //imagecopyresampled($img['QR']['img'], $img['LG']['img'], $img['from_width'], $img['from_width'], 0, 0, $img['QRLG']['width'], $img['QRLG']['height'], $img['LG']['width'], $img['LG']['height']);
                        // 等比
                        $img['from_width'] = ($img['QR']['width'] - $img['LG']['width']) / 2;
                        imagecopyresampled($img['QR']['img'], $img['LG']['img'], $img['from_width'], $img['from_width'], 0, 0, $img['LG']['width'], $img['LG']['height'], $img['LG']['width'], $img['LG']['height']);
                        imagepng($img['QR']['img'], $params['savename']);
                    }
                    $this->data_result['data'] = sprintf('%s%s', $url, $file);
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
    
    public function board_post ()
    {
    	try {
    		if ($this->flexi_auth->is_privileged('Boards Edit')) {
    			// 變數
    			$data_input = array();
    			// input
    			$data_input['video_type'] = $this->post('video_type');
    			$data_input['video_no'] = $this->post('video_no');
    			$data_input['update_video_type'] = $this->post('update_video_type');
    			$data_input['update_video_no'] = $this->post('update_video_no');
    			if (! empty($data_input['video_type']) && ! empty($data_input['video_no']) && ! empty($data_input['update_video_type']) && ! empty($data_input['update_video_no'])) {
    				// 寫log
    				$this->fun->logs(sprintf('留言轉移{%s-%s}to{%s-%s}', $data_input['video_type'], $data_input['video_no'], $data_input['update_video_type'], $data_input['update_video_no']));
    				// 引用
    				$this->load->model('vidol_websocket/Boards_model');
    				//
    				$this->data_result['status'] = true;
    				$this->data_result['input'] = $data_input;
    				$this->Boards_model->update_live_board($data_input['video_type'], $data_input['video_no'], $data_input['update_video_type'], $data_input['update_video_no']);
    				$this->Boards_model->update_live_barrage($data_input['video_type'], $data_input['video_no'], $data_input['update_video_type'], $data_input['update_video_no']);
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
