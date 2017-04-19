<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Coupon_set_model extends CI_Model
{
	private $table_name = 'Coupon_set_tbl';
	private $fields_pk = 'cs_pk';
	
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
    
    public function insert_Coupon_set_for_data($data){
    	$this->w_db->insert($this->table_name, $data);
    	$id = $this->w_db->insert_id();
    	//echo $this->w_db->last_query();
    	return $id;
    }
    
    public function update_Coupon_set_for_data($pk, $data){
    	$this->w_db->where($this->fields_pk, $pk);
    	$this->w_db->update($this->table_name, $data);
    	$result = $this->w_db->affected_rows();
    	//echo $this->w_db->last_query();
    	return $result;
    }
    
    public function get_row_Coupon_set_by_pk ($select, $pk)
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
     * 更新序號產生設定改為已產生序號完成
     * @param unknown $set_no	序號設定編號
     * @return unknown
     */
    public function update_coupon_set_produce ($set_no)
    {
    	$this->w_db->where('cs_pk', $set_no);
    	$this->w_db->set('cs_produce', '0');
    	$this->w_db->update('Coupon_set_tbl');
    	$result = $this->w_db->affected_rows();
    	//echo $this->r_db->last_query();
    	return $result;
    }
    
    /**
     * 更新序號產生設定改為已過期
     * @param unknown $set_no
     * @return unknown
     */
    public function update_coupon_set_expired ($set_no)
    {
    	$this->w_db->where('cs_pk', $set_no);
    	$this->w_db->set('cs_expired', '0');
    	$this->w_db->update('Coupon_set_tbl');
    	$result = $this->w_db->affected_rows();
    	//echo $this->r_db->last_query();
    	return $result;
    }
    
    /**
     * 取得序號設定(還沒完成產生序號且還沒超過期限的)
     * @return unknown
     */
    public function get_coupon_set_produce ()
    {
    	$this->r_db->where('cs_produce', '1');
    	$this->r_db->where('cs_expired', '1');
    	$this->r_db->where('cs_status', '1');
    	$query = $this->r_db->get('Coupon_set_tbl');
    	//echo $this->r_db->last_query();
    	return $query;
    }
    
    /**
     * 取得序號設定(超過期限的)
     * @return unknown
     */
    public function get_coupon_set_expired ()
    {
    	$this->r_db->where('cs_expired', '1');
    	$this->r_db->where('cs_status', '1');
    	$this->r_db->where('cs_time_end <= NOW()');
    	$query = $this->r_db->get('Coupon_set_tbl');
    	//echo $this->r_db->last_query();
    	return $query;
    }
    
}
