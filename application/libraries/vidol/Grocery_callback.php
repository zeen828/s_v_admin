<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Grocery_callback {
	private $CI;
	public function __construct() {
		$this->CI = & get_instance ();
	}
	
	/**
	 * 帳號編輯密碼改為空
	 *
	 * @param unknown $value        	
	 * @param unknown $primary_key        	
	 * @param unknown $from        	
	 * @param unknown $row        	
	 */
	public function form_account_password($value, $primary_key = null, $from = null, $row = null) {
		return '<input type="password" name="uacc_password" value="" />';
	}
	
	/**
	 * 新增帳號用
	 * 密碼需透過flexi_auth加密
	 *
	 * @param unknown $post_array        	
	 */
	public function add_accounts($post_array) {
		$this->CI->load->library ( 'flexi_auth' );
		return $this->CI->flexi_auth->insert_user ( $post_array ['uacc_email'], $post_array ['uacc_username'], $post_array ['uacc_password'], array (), $post_array ['uacc_group_fk'], $post_array ['uacc_active'] );
	}
	
	/**
	 * 修改帳號用
	 * 密碼需透過flexi_auth加密
	 *
	 * @param unknown $post_array        	
	 * @param unknown $primary_key        	
	 */
	public function edit_accounts($post_array, $primary_key) {
		$this->CI->load->library ( 'flexi_auth' );
		if (empty ( $post_array ['uacc_password'] )) {
			unset ( $post_array ['uacc_password'] );
		}
		return $this->CI->flexi_auth->update_user ( $primary_key, $post_array );
	}
	
	/**
	 * 建立序號設定沒有輸入就跑預設值
	 *
	 * @param unknown $post_array        	
	 */
	public function add_coupon_set($post_array) {
		$this->CI->load->model ( 'vidol_billing/coupon_set_model' );
		if (empty ( $post_array ['cs_package_no'] )) {
			unset ( $post_array ['cs_package_no'] );
		}
		if (empty ( $post_array ['cs_discount'] )) {
			unset ( $post_array ['cs_discount'] );
		}
		if (empty ( $post_array ['cs_cash'] )) {
			unset ( $post_array ['cs_cash'] );
		}
		if (empty ( $post_array ['cs_assign'] )) {
			unset ( $post_array ['cs_assign'] );
		}
		return $this->CI->coupon_set_model->insert_Coupon_set_for_data ( $post_array );
	}
	
	/**
	 * 編輯序號設定後要更改排程旗標
	 *
	 * @param unknown $post_array        	
	 * @param unknown $primary_key        	
	 * @return boolean
	 */
	public function edit_coupon_set($post_array, $primary_key) {
		$this->CI->load->model ( 'vidol_billing/coupon_set_model' );
		$post_array ['cs_produce'] = 1;
		$post_array ['cs_expired'] = 1;
		return $this->CI->coupon_set_model->update_Coupon_set_for_data ( $primary_key, $post_array );
	}
	public function callback_column_Orders_tbl_field_o_status($value, $row) {
		switch ($value) {
			case '-1' :
				$result = '<span class="text-red">付款失敗</span>';
				break;
			case '0' :
				$result = '<span class="text-yellow">等待付款</span>';
				break;
			case '1' :
				$result = '<span class="text-green">付款成功</span>';
				break;
			case '2' :
				$result = '<span class="text-red">退訂單</span>';
				break;
			default :
				$result = '<span class="text-yellow">等待付款</span>';
				break;
		}
		return $result;
	}
	function callback_config_to_item_url($primary_key, $row) {
		return site_url ( '/backend/vote_config/config_item' . $primary_key );
	}
	function callback_config_to_lotters_url($primary_key, $row) {
		switch ($row->lc_method) {
			case 'one' :
				return site_url ( 'one' ) . '?country=' . $primary_key;
				break;
			case 'list' :
			default :
				return site_url ( '/backend/lotteries/lottery_list/' . $primary_key );
				break;
		}
	}
}
