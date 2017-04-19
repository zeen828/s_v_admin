<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Paykits extends CI_Controller
{

    private $data_result;

    function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
        $this->load->helper('formats');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        //$this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        show_404();
    }
    
    /**
     * 爬取每日訂單清單
     * 
     */
    public function day_orders ()
    {
    	try {
    		//引用
    		$this->load->library('vidol/paykit_api');
    		$this->load->model('vidol_billing/orders_model');
    		$this->load->model('vidol_billing/sold_packages_model');
    		//變數
    		$data_input = array();
    		$data_input['package_id'] = '0';		//packageID
    		$data_input['day'] = '2016-01-01';		//查詢日期
    		$data_input['count'] = 0;				//資料庫目前記錄筆數查詢
    		$data_input['status'] = 0;				//開始筆數
    		$data_input['limit'] = 100;				//取得資料筆數
    		$data_input['api_token'] = '';			//API的token
    		$data_input['api_url'] = '';			//API網址
    		$data_input['api_count'] = '0';			//API取得資料筆數
    		$data_input['api_count_write'] = '0';	//寫DB次數
    		$data_input['api_repeat'] = '';			//重複資料
    		//增加跑排程資料
    		$this->orders_model->add_cron_order_tmp();
    		//取得要爬的日期&token
    		$query = $this->orders_model->get_cron_order_tmp();
    		if ($query->num_rows() > 0)
    		{
    			$cron_row = $query->row();
    			//要執行排程日期
    			$data_input['day'] = $cron_row->c_run_date;
    			//api token
    			if(empty($cron_row->c_token)){
    				// paykit api 呼叫
    				$data_input['api_token'] = $this->paykit_api->get_token();
    				// 將token寫回排程
    				$this->orders_model->update_cron_order_tmp($cron_row->c_pk, array('c_token'=>$data_input['api_token']));
    			}else{
    				// paykit api 呼叫
    				$this->paykit_api->set_token($cron_row->c_token);
    			}
    			//資料庫目前記錄筆數查詢
    			$data_input['count'] = $this->orders_model->get_day_order_count($data_input['day']);
	    		//預設||取得排程記錄
    			$query = $this->orders_model->get_cron_paikey_api_log_tmp($data_input['day']);
    			if ($query->num_rows() > 0)
    			{
    				$log_row = $query->row();
    				$data_input['status'] = $log_row->cpal_page + $data_input['limit'];
    			}else{
    				$data_input['status'] = 0;
    			}
    			unset($log_row);
	    		//取得API呼叫網址
	    		$data_input['api_url'] = $this->paykit_api->get_all_users_transactions_by_date($data_input['day'], $data_input['status'], $data_input['limit']);
	    		//取得前日交易資料
	    		$data_api = $this->paykit_api->get_api();
	    		if (isset($data_api->result) && count($data_api->result) > 0) {
	    			//取得API查尋獲得資料筆數
	    			$data_input['api_count'] = count($data_api->result);
	    			foreach($data_api->result as $key=>$val){
	    				//print_r($val);
	    				$package_id = 0;
	    				// 先處理package在處理order
	    				// packages
	    				if(count($val->packages) > 0 && isset($val->packages['0'])){
	    					//紀錄
	    					$data_input['package_id'] = $val->packages['0']->package;
	    					//$api_json = json_encode($val->packages['0']);
	    					$this->sold_packages_model->insert_package(
	    							$data_input['package_id'],
	    							$val->packages['0']->name,
	    							$val->packages['0']->cost,
	    							$val->packages['0']->finalCost,
	    							$val->packages['0']->createdAt,
	    							json_encode($val->packages['0'])
	    					);
	    				}else{
	    					$data_input['package_id'] = 0;
	    				}
	    				// 建立order
	    				$w_id = $this->orders_model->insert_day_order(
	    						$val->id,
	    						$data_input['package_id'],
	    						$val->packageNames,
	    						$val->createdAt
	    				);
	    				//echo "ID:", $w, "<br/>\n";
	    				if(empty($w_id)){
	    					$data_input['api_repeat'][] = $val->id;
	    				}else{
	    					$data_input['api_count_write']++;
	    				}
	    				unset($key);
	    				unset($val);
	    				unset($w_id);
	    			}
	    		}else{
	    			//api沒資料結束該日期
	    			$this->orders_model->update_cron_order_tmp($cron_row->c_pk, array('c_count'=>$data_input['count'], 'c_status'=>0));
	    			
	    		}
	    		unset($cron_row);
	    		//狀態
	    		$this->data_result['status'] = true;
	    		//寫入log
	    		$this->orders_model->add_cron_paikey_api_log_tmp(
	    				$data_input['api_url'],
	    				$data_input['day'],
	    				$data_input['status'],
	    				$data_input['limit'],
	    				json_encode($data_input['api_repeat']),
	    				$data_input['count'],
	    				$data_input['api_count'],
	    				$data_input['api_count_write']
   				);
    		}
			//輸出
    		$this->data_result['input'] = $data_input;
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
	
    public function orders ()
    {
    	try {
    		//引用
    		$this->load->library('vidol/paykit_api');
    		$this->load->model('vidol_billing/orders_model');
    		$this->load->model('vidol_billing/pay2goInvoice_model');
    		//變數
    		$data_input = array();
    		$data_input['limit'] = 30;				//取得資料筆數
    		$data_input['api_token'] = '';			//API的token
    		//增加跑排程資料
    		$this->orders_model->add_cron_order_tmp();
    		//取得要爬的日期&token
    		$query = $this->orders_model->get_order_paykit($data_input['limit']);
    		if ($query->num_rows() > 0){
    			foreach ($query->result() as $row){
    				$copy = false;
    				$data_array = array();
    				$data_array['o_paykit'] = '0';
    				print_r($row);
    				if(empty($data_input['api_token'])){
    					// paykit api 呼叫
    					$data_input['api_token'] = $this->paykit_api->get_token();
    				}else{
    					// paykit api 呼叫
    					$this->paykit_api->set_token($data_input['api_token']);
    				}
    				//取得API呼叫網址
    				$data_api = $this->paykit_api->get_orders($row->o_order_sn);
    				//echo 'API';
    				print_r($data_api);
    				//支付寶付款訊息
    				if (isset($data_api->pay2goResponse)) {
    					$data_array['o_remark_response'] = json_encode($data_api->pay2goResponse);
    					//訂單號碼
    					$data_api->pay2goResponse->order_sn = $row->o_order_sn;
    					//IP
    					if(isset($data_api->pay2goResponse->IP)){
    						$data_array['o_ip'] = $data_api->pay2goResponse->IP;
    					}
    					//付款金額
    					if(isset($data_api->pay2goResponse->Amt)){
    						$data_array['o_price'] = $data_api->pay2goResponse->Amt;
    					}
    					if(isset($data_api->pay2goResponse->RespondCode)){
    						//信用卡繳費
    						$data_array['o_payment_no'] = 1;
    					}
    					if(isset($data_api->pay2goResponse->TokenUseStatus)){
    						//信用卡快速結帳
    						$data_array['o_payment_no'] = 2;
    					}
    					if(isset($data_api->pay2goResponse->PayBankCode)){
    						//ATM繳費
    						$data_array['o_payment_no'] = 4;
    					}
    					if(isset($data_api->pay2goResponse->CodeNo)){
    						//超商代碼繳費
    						$data_array['o_payment_no'] = 5;
    					}
    					if(isset($data_api->pay2goResponse->Barcode_1)){
    						//條碼繳費
    						$data_array['o_payment_no'] = 6;
    					}
    					unset($data_api->pay2goResponse->Gateway);
    					unset($data_api->pay2goResponse->TokenValue);
    					unset($data_api->pay2goResponse->TokenLife);
    					unset($data_api->pay2goResponse->Exp);
    					$this->pay2goInvoice_model->insert_Pay2goResponse_for_data($data_api->pay2goResponse);
    				}
    				//購買資料
    				if (isset($data_api->buyer)) {
    					$data_array['o_remark_buyer'] = json_encode($data_api->buyer);
    					if(isset($data_api->buyer->userId)){
    						$data_array['o_mongo_id'] = $data_api->buyer->userId;
    					}
    				}
    				//支付寶發票訊息
    				if (isset($data_api->pay2goInvoiceParams)) {
   						$data_array['o_remark_invoice'] = json_encode($data_api->pay2goInvoiceParams);
    				}
    				//優惠卷
    				if(isset($data_api->coupon)){
    					$data_array['o_coupon_no'] = $data_api->coupon;
    					$data_array['o_coupon_sn'] = $data_api->coupon;
    				}
    				switch ($data_api->status) {
    					case 'cancel':
    						$copy = false;
    						$data_array['o_status'] = -2;
    						break;
    					case 'fail':
    						$copy = false;
    						$data_array['o_status'] = -1;
    						break;
    					case 'pending':
    						$copy = false;
    						$data_array['o_status'] = 0;
    						break;
    					case 'success':
    						$copy = true;
    						$data_array['o_status'] = 1;
    						break;
    				}
    				$this->orders_model->update_order_array($row->o_pk, $data_array);
    				if($copy){
    					$this->orders_model->copy_order_to_cashs($row->o_pk);
    				}
    			}
    		}
    		//輸出
    		$this->data_result['input'] = $data_input;
    		$this->output->set_content_type('application/json');
    		$this->output->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function test ()
    {
    	$this->load->library('vidol/paykit_api');
    	$this->load->model('vidol_billing/orders_model');
    	$this->load->model('vidol_billing/pay2go_model');
    	//$this->orders_model->copy_order_to_cashs_test(1);
    	
    }
}
