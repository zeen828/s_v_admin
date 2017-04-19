<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paykit_api
{

    private $CI;

    private $api_domain;
    
    private $api_url;

    private $secretKey;

    private $access_token;

    private $data_result;

    public function __construct ()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('vidol');
        $this->set_status('production');
    }

    public function debug ()
    {
        echo "<br/>\n", "api_domain : ", $this->api_domain, "<br/>\n";
        echo "<br/>\n", "secretKey : ", $this->secretKey, "<br/>\n";
        echo "<br/>\n", "access_token : ", $this->access_token, "<br/>\n";
        echo "<br/>\n", "<br/>\n";
    }

    /**
     * 切換到開發環境
     * 
     * @param string $dev            
     */
    public function set_status ($dev = 'production')
    {
        $paykit = $this->CI->config->item('paykit');
        if ($dev == 'staging') {
            $this->api_domain = $paykit['staging']['api_domain'];
            $this->secretKey = $paykit['staging']['secretKey'];
        } else {
            $this->api_domain = $paykit['production']['api_domain'];
            $this->secretKey = $paykit['production']['secretKey'];
        }
    }
	
    /**
     * 設定API的token
     * @param unknown $token	token
     */
    public function set_token ($token)
    {
    	$this->access_token = $token;
    }
    
    /**
     * 取得API的token
     * action : GET
     */
    public function get_token ()
    {
    	$this->api_url = sprintf('%s/v1/auth/requestToken?secretKey=%s', $this->api_domain, $this->secretKey);
    	//echo $this->api_url;
        $get_token = file_get_contents($this->api_url);
        $tmptoken = json_decode($get_token, true);
        $this->access_token = $tmptoken['token'];
        return $this->access_token;
    }
    
    /**
     * 回傳呼叫API網址
     * @return string
     */
    public function get_api_url ()
    {
    	return $this->api_url;
    }
    
	/**
	 * 查詢產品包
	 * action : GET
	 * 
	 * @return mixed
	 */
    public function get_packages ($page = 1)
    {
    	// get api GET https://paykit.mstage.io/v1/packages(產品包)
    	$this->api_url = sprintf('%s/v1/packages?page=%d&perPage=100', $this->api_domain, $page);
    	//echo $this->api_url;
    	$curl_handle = curl_init();
    	curl_setopt($curl_handle, CURLOPT_URL, $this->api_url);
    	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
    			'token:' . $this->access_token
    	));
    	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    	$buffer = curl_exec($curl_handle);
    	curl_close($curl_handle);
    	$this->data_result = json_decode($buffer);
    	return $this->data_result;
    }
    
    /**
     * 查詢訂單資料
     * action : GET
     *
     * @param unknown $transtionID	訂單號碼
     * @return
     */
    public function get_orders ($transtionID)
    {
        // get api GET https://paykit.mstage.io/v1/orders/:transtionID(訂單資料)
    	$this->api_url = sprintf('%s/v1/orders/%s', $this->api_domain, $transtionID);
    	//echo $this->api_url;
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
                'token:' . $this->access_token
        ));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $this->data_result = json_decode($buffer);
        return $this->data_result;
    }

    /**
     * 查詢訂單資料
     * action : GET
     *
     * @param unknown $userId	會員ID
     * @return
     */
    public function get_user_all_orders ($userId)
    {
        // get api GET
        // https://paykit.mstage.io/v1/users/:userId/transactions(使用者所有訂單)
    	$this->api_url = sprintf('%s/v1/users/%s/transactions', $this->api_domain, $userId);
    	//echo $this->api_url;
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
                'token:' . $this->access_token
        ));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $this->data_result = json_decode($buffer);
        return $this->data_result;
    }

    /**
     * 取得每日全部訂單
     * action : GET
     * 
     * @param unknown $dayDate		查詢日期
     * @param unknown $pageSize		顯示筆數
     * @param unknown $page			開始筆數(非頁數)
     * @return
     */
    public function get_all_users_transactions_by_date ($dayDate, $page, $pageSize)
    {
        // get api GET
        // https://paykit.mstage.io/v1/users/transactions?createdAt=2016-07-26&page=開始筆數非頁數&perPage=顯示筆數
        //$url = sprintf('%s/v1/users/transactions?createdAt=%s&perPage=%s&page=%s', $this->api_domain, $dayDate, $pageSize, $page);
        //echo $url;
        $this->api_url = sprintf('%s/v1/users/transactions?createdAt=%s&perPage=%s&page=%s', $this->api_domain, $dayDate, $pageSize, $page);
        //echo $this->api_url;
        return $this->api_url;
        //$curl_handle = curl_init();
        //curl_setopt($curl_handle, CURLOPT_URL, $this->api_url);
        //curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
                //'token:' . $this->access_token
        //));
        //curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        //$buffer = curl_exec($curl_handle);
        //curl_close($curl_handle);
        //var_dump($buffer);
        //$this->data_result = json_decode($buffer);
        //return $this->data_result;
    }
    
    /**
     * CRUL 取得API資料
     * @return mixed
     */
    public function get_api ()
    {
    	//echo $this->api_url;
    	$curl_handle = curl_init();
    	curl_setopt($curl_handle, CURLOPT_URL, $this->api_url);
    	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
    			'token:' . $this->access_token
    	));
    	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    	$buffer = curl_exec($curl_handle);
    	curl_close($curl_handle);
    	//var_dump($buffer);
    	$this->data_result = json_decode($buffer);
    	return $this->data_result;
    }
}
