<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Coupon_model extends CI_Model
{
	private $table_name = 'Coupon_tbl';
	private $fields_pk = 'c_pk';
	
    public function __construct ()
    {
        parent::__construct();
        // $this->load->config('set/databases_fiels', TRUE);
        $this->r_db = $this->load->database('vidol_billing_read', TRUE);
        $this->w_db = $this->load->database('vidol_billing_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function insert_Coupon_for_data($data){
    	$this->w_db->insert($this->table_name, $data);
    	$id = $this->w_db->insert_id();
    	//echo $this->w_db->last_query();
    	return $id;
    }
    
    public function update_Coupon_for_data($pk, $data){
    	$this->w_db->where($this->fields_pk, $pk);
    	$this->w_db->update($this->table_name, $data);
    	$result = $this->w_db->affected_rows();
    	//echo $this->w_db->last_query();
    	return $result;
    }
    
    public function get_row_Coupon_by_pk ($select, $pk)
    {
    	if(!empty($select)){
    		$this->r_db->select($select);
    	}
    	$this->r_db->where($this->fields_pk, $pk);
    	$query = $this->r_db->get($this->table_name);
    	//echo $this->r_db->last_query();
    	if ($query->num_rows() > 0){
    		return $query->row();
    	}
    	return false;
    }
    
    /**
     * 建立序號資料
     * @param unknown $set_no
     * @param unknown $set_title
     * @param unknown $set_type
     * @param unknown $set_package_no
     * @param unknown $set_cs_discount
     * @param unknown $set_cash
     * @param unknown $sn
     * @param unknown $repeat
     * @param unknown $user_repeat
     * @param unknown $assign
     * @return unknown
     */
    public function insert_coupon ($set_no, $set_title, $set_type, $set_package_no, $set_cs_discount, $set_cash, $sn, $repeat, $user_repeat, $assign)
    {
    	$this->w_db->set('c_set_no', $set_no);
    	$this->w_db->set('c_set_title', $set_title);
    	$this->w_db->set('c_set_type', $set_type);
    	if(!empty($set_package_no)){
    		$this->w_db->set('c_set_package_no', $set_package_no);
    	}
    	if(!empty($set_cs_discount)){
    		$this->w_db->set('c_set_discount', $set_cs_discount);
    	}
    	if(!empty($set_cash)){
    		$this->w_db->set('c_set_cash', $set_cash);
    	}
    	$this->w_db->set('c_sn', $sn);
    	if(!empty($repeat)){
    		$this->w_db->set('c_repeat', $repeat);
    	}
    	if(!empty($user_repeat)){
    		$this->w_db->set('c_user_repeat', $user_repeat);
    	}
    	if(!empty($assign)){
    		$this->w_db->set('c_assign', $assign);
    	}
    	$this->w_db->set('c_status', '1');
    	$this->w_db->insert('Coupon_tbl');
    	$id = $this->w_db->insert_id();
    	//echo $this->w_db->last_query();
    	return $id;
    }
    
    /**
     * 更新序號改為已過期
     * @param unknown $set_no
     * @return unknown
     */
    public function update_coupon_expired ($set_no)
    {
    	$this->w_db->where('c_set_no', $set_no);
    	$this->w_db->set('c_status', '0');
    	$this->w_db->update('Coupon_tbl');
    	$result = $this->w_db->affected_rows();
    	//echo $this->r_db->last_query();
    	return $result;
    }
    
    /**
     * 取得序號設定已產生的序號筆數
     * @param unknown $set_no	序號設定編號
     * @return unknown
     */
    public function get_coupon_count_by_coupon_set_no ($set_no)
    {
    	$this->r_db->where('c_set_no', $set_no);
    	$count = $this->r_db->count_all_results('Coupon_tbl');
    	//echo $this->r_db->last_query();
    	return $count;
    }
    
}
