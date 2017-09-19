<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller
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

    /**
     * 查詢註冊數量
     * 權限 有登入
     * start 開始時間
     * end 結束時間
     * chart 查詢類型
     * time_zome 時區
     */
    public function registered_count_get ()
    {
        try {
            // 變數
            $data_input = array();
            // 引用
            $this->load->model('registered_model');
            // input
            $data_input['start'] = ($this->get('start')) ? $this->get('start') : date('YmdH0000', strtotime(date('Y-m-d H:i:s') . " -24 hour"));
            $data_input['end'] = ($this->get('end')) ? $this->get('end') : date('YmdH0000', strtotime(date('Y-m-d H:i:s') . " -1 hour"));
            $data_input['chart'] = ($this->get('chart') == 'day') ? 'day' : 'hour';
            $data_input['time_zome'] = ($this->get('time_zome') == 'tw') ? 'tw' : 'utc';
            $this->data_result['input'] = $data_input;
            $date_str = ($data_input['chart'] == 'hour') ? "Y-m-d H:i:s" : "Y-m-d";
            // 查詢資料
            $function_string = sprintf("get_%s_count_by_%s", $data_input['chart'], $data_input['time_zome']);
            $tmp_data = $this->registered_model->$function_string($data_input['start'], $data_input['end']);
            if (count($tmp_data) > 0) {
                $this->data_result['ststus'] = true;
                foreach ($tmp_data as $key => $val) {
                    $this->data_result['data'][] = array(
                            'date_utc' => date($date_str, $val->time - (8 * 60 * 60)),
                            'date_tw' => date($date_str, $val->time),
                            'count_re' => $val->count_re,
                            'count_fb' => $val->count_fb,
                    		'count_mobile' => $val->count_mobile,
                            'count' => $val->count
                    );
                }
            }
            $this->response($this->data_result, 200);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function age_get ()
    {
    	try {
    		// 變數
    		$data_input = array();
    		// 引用
    		$this->load->model('user_age_model');
    		// input
    		$data_input['spacing'] = $this->get('spacing');
    		$this->data_result['input'] = $data_input;
    		// 查詢資料
    		$query = $this->user_age_model->get_rows_User_age('*');
    		if ($query->num_rows () > 0) {
    			foreach ( $query->result () as $row ) {
    				
    			}
    		}
    		$this->response($this->data_result, 200);
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    //
    public function login_count_get ()
    {
    	try {
    		// 變數
    		$data_input = array();
    		// 引用
    		$this->load->model('vidol_user/login_model');
    		// input
    		$data_input['start'] = ($this->get('start')) ? $this->get('start') : date('Ymd', strtotime(date('Y-m-d H:i:s') . " -7 day"));
    		$data_input['end'] = ($this->get('end')) ? $this->get('end') : date('Ymd', strtotime(date('Y-m-d H:i:s') . " -1 day"));
    		$data_input['chart'] = ($this->get('chart') == 'month') ? 'month' : 'day';
    		$data_input['time_zome'] = ($this->get('time_zome') == 'tw') ? 'tw' : 'utc';
    		$this->data_result['input'] = $data_input;
    		$date_str = ($data_input['chart'] == 'month') ? "Y-m" : "Y-m-d";
    		// 查詢資料
    		$function_string = sprintf("get_count_%s", $data_input['chart']);
    		$tmp_data = $this->login_model->$function_string($data_input['start'], $data_input['end']);
    		if (count($tmp_data) > 0) {
    			$this->data_result['ststus'] = true;
    			foreach ($tmp_data as $key => $val) {
    				//不重複登入
    				$count_str = sprintf("count_%s", $data_input['time_zome']);
    				//重複登入
    				$count_repeat_str = sprintf("count_repeat_%s", $data_input['time_zome']);
    				$this->data_result['data'][] = array(
    						'date' => $val->date,
    						'year' => $val->year,
    						'month' => $val->month,
    						'day' => $val->day,
    						'count_utc' => $val->count_utc,
    						'count_tw' => $val->count_tw,
    						'count' => $val->$count_str,
    						'count_repeat_utc' => $val->count_repeat_utc,
    						'count_repeat_tw' => $val->count_repeat_tw,
    						'count_repeat' => $val->$count_repeat_str
     				);
    			}
    		}
    		$this->response($this->data_result, 200);
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function user_all_order_get ()
    {
        try {
            if ($this->flexi_auth->is_privileged('Orders View')) {
                // 變數
                $data_input = array();
                // 引用
                $this->load->library('vidol/paykit_api');
                $this->load->model('order_model');
                // input
                $data_input['id'] = $this->get('id');
                if (! empty($data_input['id'])) {
                    // 寫log
                    $this->fun->logs(sprintf('查詢會員{%s}訂單清冊', $data_input['id']));
                    // paykit api 呼叫
                    $this->paykit_api->get_token();
                    $data_api = $this->paykit_api->get_user_all_orders($data_input['id']);
                    if(isset($data_api->result)){
                        //做紀錄
                        $this->order_model->insert_paykit_data($data_input['id'], $data_api->result);
                        //$this->order_model->insert_paykit_data($data_input['id'], $data_api->result);
                        $this->data_result['status'] = true;
                        $this->data_result['input'] = $data_input;
                        $this->data_result['data'] = $data_api->result;
                        $this->response($this->data_result, 200);
                    }else{
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
    
    public function promo_post ()
    {
    	try {
    		if ($this->flexi_auth->is_privileged('Orders View')) {
    			// 變數
    			$data_input = array();
    			// 引用
    			$this->load->library('vidol/paykit_api');
    			$this->load->model('order_model');
    			// input
    			$data_input['promo_package'] = $this->post('promo_package');
    			if (! empty($data_input['promo_package'])) {
    				// 寫log
    				$this->fun->logs(sprintf('查詢會員{%s}訂單清冊', $data_input['id']));
    				// paykit api 呼叫
    				$this->paykit_api->get_token();
    				$data_api = $this->paykit_api->get_user_all_orders($data_input['id']);
    				if(isset($data_api->result)){
    					//做紀錄
    					$this->order_model->insert_paykit_data($data_input['id'], $data_api->result);
    					//$this->order_model->insert_paykit_data($data_input['id'], $data_api->result);
    					$this->data_result['status'] = true;
    					$this->data_result['input'] = $data_input;
    					$this->data_result['data'] = $data_api->result;
    					$this->response($this->data_result, 200);
    				}else{
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
    
    public function vidol_post ()
    {
    	try {
    		// 開始時間標記
    		$this->benchmark->mark ( 'code_start' );
			// 權限檢查
    		if (!$this->flexi_auth->is_privileged('Accounts Add')) {
    			// 權限錯誤
    			$this->data_result ['message'] = $this->lang->line ( 'permissions_error' );
    			$this->data_result ['code'] = $this->config->item ( 'permissions_error' );
    			$this->response ( $this->data_result, 403 );
    			return;
    		}
    		// 引入
    		$this->config->load ( 'restful_status_code' );
    		//$this->load->model ( 'vidol_dealer/dealers_model' );
    		//$this->load->model ( 'vidol_dealer/user_binding_model' );
    		$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
    		// 變數
    		$data_input = array ();
    		$this->data_result = array (
    				'result' => array (),
    				'code' => $this->config->item ( 'system_default' ),
    				'message' => '',
    				'time' => 0
    		);
    		// 接收變數
    		$data_input ['vidol_email'] = $this->post ( 'vidol_email' );
    		$data_input ['debug'] = $this->post ( 'debug' );
    		// 必填檢查
    		if (empty ( $data_input ['vidol_email'] )) {
    			// 必填錯誤
    			$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
    			$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
    			$this->response ( $this->data_result, 416 );
    			return;
    		}
    		
    		
    		// DEBUG印出
    		if ($data_input ['debug'] == 'debug') {
    			$this->data_result ['debug'] ['data_input'] = $data_input;
    		}
    		// 結束時間標記
    		$this->benchmark->mark ( 'code_end' );
    		// 標記時間計算
    		$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
    		$this->response ( $this->data_result, 200 );
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
}
