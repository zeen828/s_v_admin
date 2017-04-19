<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Coupons extends CI_Controller
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
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        show_404();
    }

    /**
     * 產生序號
     * http://xxx.xxx.xxx/cron/coupons/coupon_sn
     * ./1 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron coupons coupon_sn
     */
    public function coupon_sn ()
    {
    	try {
    		$this->load->model('vidol_billing/coupon_model');
    		$this->load->model('vidol_billing/coupon_set_model');
    		$this->load->helper('string');
    		$query = $this->coupon_set_model->get_coupon_set_produce();
    		if ($query->num_rows() > 0){
	    		foreach ($query->result() as $row){
	    			//print_r($row);
	    			$coupon_count = $this->coupon_model->get_coupon_count_by_coupon_set_no($row->cs_pk);
	    			if($coupon_count < $row->cs_count){
	    				do{
	    					$sn = strtoupper(random_string('alnum', $row->cs_word));
	    					$id = $this->coupon_model->insert_coupon(
	    							$row->cs_pk,
	    							$row->cs_title,
	    							$row->cs_type,
	    							$row->cs_package_no,
	    							$row->cs_discount,
	    							$row->cs_cash,
	    							$sn,
	    							$row->cs_repeat,
	    							$row->cs_user_repeat,
	    							$row->cs_assign
	    					);
	     					if(!empty($id)){
	    						$coupon_count++;
	     					}
	    				}while($coupon_count < $row->cs_count);
	    			}
	    			$this->data_result['data']['coupon'][] = $coupon_count;
	    			$this->data_result['data']['coupon_set'][] = $this->coupon_set_model->update_coupon_set_produce($row->cs_pk);
	    		}
	    		$this->data_result['status'] = true;
    		}
    		$this->output->set_content_type('application/json');
    		$this->output->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    /**
     * 檢查過期序號
     * http://xxx.xxx.xxx/cron/coupons/coupon_sn_expired
     * * ./2 * * * php /var/www/codeigniter/3.0.6/admin/index.php cron coupons coupon_sn_expired
     */
    public function coupon_sn_expired ()
    {
    	try {
    		$this->load->model('vidol_billing/coupon_model');
    		$this->load->model('vidol_billing/coupon_set_model');
    		$query = $this->coupon_set_model->get_coupon_set_expired();
    		if ($query->num_rows() > 0){
    			foreach ($query->result() as $row){
    				//print_r();
    				$this->data_result['data']['coupon'][$row->cs_pk] = $this->coupon_model->update_coupon_expired($row->cs_pk);
    				$this->data_result['data']['coupon_set'][$row->cs_pk] = $this->coupon_set_model->update_coupon_set_expired($row->cs_pk);
    				
    			}
    			$this->data_result['status'] = true;
    		}
    		$this->output->set_content_type('application/json');
    		$this->output->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
}
