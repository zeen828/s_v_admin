<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Sold_packages_model extends CI_Model
{
	private $table_name = 'Sold_packages_tbl';
	private $fields_pk = 'sp_pk';
	
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
    
    public function insert_Sold_packages_for_data($data){
    	$this->w_db->insert($this->table_name, $data);
    	$id = $this->w_db->insert_id();
    	//echo $this->w_db->last_query();
    	return $id;
    }
    
    public function update_Sold_packages_for_data($pk, $data){
    	$this->w_db->where($this->fields_pk, $pk);
    	$this->w_db->update($this->table_name, $data);
    	$result = $this->w_db->affected_rows();
    	//echo $this->w_db->last_query();
    	return $result;
    }
    
    public function get_row_Sold_packages_by_pk ($select, $pk)
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
     * 建日產品包資料
     * @param unknown $package_id
     * @param unknown $package_title
     * @param unknown $cost
     * @param unknown $final_cost
     * @param unknown $time_creat
     * @param unknown $api_json
     * @return unknown
     */
    public function insert_package ($package_id, $package_title, $cost, $final_cost, $time_creat, $api_json)
    {
    	//如果是唯一已存在就不動作
    	$sql = "INSERT IGNORE INTO `Sold_packages_tbl` (`sp_pk`, `sp_title`, `sp_des`, `sp_cost`, `sp_price`, `sp_show`, `sp_sort`, `sp_status`, `sp_time_start`, `sp_time_end`, `sp_time_creat`, `sp_remark`) VALUES ('%s', '%s', '%s', '%s', '%s', '1', '%s', '1', '2016-01-01 00:00:01', '2030-01-01 00:00:00', '%s', '%s');";
    	$sql = sprintf($sql, $package_id, $package_title, $package_title, $cost, $final_cost, $package_id, $time_creat, $api_json);
    	$this->w_db->simple_query($sql);
    	$id = $this->w_db->insert_id();
    	//echo $this->w_db->last_query();
    	return $id;
    }
    
    public function get_packages_by_status ()
    {
    	$this->r_db->select('sp_pk,sp_title,sp_des');
    	$this->r_db->where('sp_status', '1');
    	$this->r_db->where('sp_time_start <= NOW()');
    	$this->r_db->where('sp_time_end >= NOW()');
    	$this->r_db->order_by('sp_sort', 'ASC');
    	$query = $this->r_db->get('Sold_packages_tbl');
    	//echo $this->r_db->last_query();
    	return $query;
    }
}
