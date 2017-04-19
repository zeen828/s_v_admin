<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

class Orders_model extends CI_Model
{
	private $table_name = 'Orders_tbl';
	private $fields_pk = 'o_pk';
	
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
	
	public function insert_Orders_for_data($data){
		$this->w_db->insert($this->table_name, $data);
		$id = $this->w_db->insert_id();
		//echo $this->w_db->last_query();
		return $id;
	}
	
	public function update_Orders_for_data($pk, $data){
		$this->w_db->where($this->fields_pk, $pk);
		$this->w_db->update($this->table_name, $data);
		$result = $this->w_db->affected_rows();
		//echo $this->w_db->last_query();
		return $result;
	}
	
	public function get_row_Orders_by_pk ($select, $pk)
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
	
	public function get_orders_by_search($start, $end, $status=null, $oredr_sn=null, $user=null)
	{
		$this->r_db->select('o_pk,o_order_sn,o_mongo_id,o_member_id,o_package_no,o_package_title,o_package_unit,o_package_unit_value,o_coupon_no,o_coupon_title,o_cost,o_price,o_invoice_type,o_invoice,o_status,o_time_creat,o_time_update,o_time_active,o_time_deadline,o_ip,o_note');
		if(!empty($start) && !empty($end)){
			$this->r_db->where('o_time_creat BETWEEN \'' . $start . '\' AND \'' . $end . '\'');
		}
		if(is_numeric($status)){
			$this->r_db->where('o_status', $status);			
		}
		if(!empty($oredr_sn)){
			$this->r_db->where('o_order_sn', $oredr_sn);
		}
		if(!empty($user)){
			$this->r_db->group_start()->where('o_user_no', $user)->or_where('o_mongo_id', $user)->or_where('o_member_id', $user)->group_end();			
		}
		$this->r_db->order_by('o_pk', 'ASC');
		$query = $this->r_db->get('Orders_tbl');
		//echo $this->r_db->last_query();
		return $query;
	}
	
/*下面為爬paykit資料用*/
	
	//暫時-建立爬程資料(跑排程時順便建立 以爬完改使用新的建立邏輯停用)
	public function add_cron_order_tmp_stop()
	{
		$sql = "INSERT INTO `cron_order_tmp` (`c_run_date`) SELECT DATE_ADD(`c_run_date`, INTERVAL 1 DAY) FROM  `cron_order_tmp` ORDER BY `c_run_date` DESC LIMIT 1;";
		$this->w_db->simple_query($sql);
		$id = $this->w_db->insert_id();
		//echo $this->w_db->last_query();
		return $id;
	}
	
	//不重複建立前一天要爬資料
	public function add_cron_order_tmp()
	{
		$yesterday = date('Y-m-d', strtotime(date('Y-m-d') . '-1 day'));
		$this->r_db->where('c_run_date', $yesterday); // 節目
		$count = $this->r_db->count_all_results('cron_order_tmp');
		//echo $this->r_db->last_query();
		if($count == 0){
			$this->w_db->set('c_run_date', $yesterday);
			$this->w_db->insert('cron_order_tmp');
			$id = $this->w_db->insert_id();
			//echo $this->w_db->last_query();
			return $id;
		}
		return $count;
	}
	
	//暫時-取得爬程資料
	public function get_cron_order_tmp()
	{
		$this->r_db->where('c_status', '1');//(0:不用執行,1:要執行)
		$this->r_db->order_by('c_run_date', 'ASC');
		$this->r_db->limit(1);
		$query = $this->r_db->get('cron_order_tmp');
		//echo $this->r_db->last_query();
		return $query;
	}
	
	//暫時-更新爬程資料
	public function update_cron_order_tmp ($pk, $data_array)
	{
		$this->w_db->where('c_pk', $pk);
		$this->w_db->update('cron_order_tmp', $data_array);
		$count = $this->w_db->affected_rows();
		//echo $this->w_db->last_query();
		return $count;
	}
	
	//暫時-建立爬取紀錄
	public function add_cron_paikey_api_log_tmp($api_url, $dayte, $page, $perPage, $repeat, $count, $count_api, $count_write)
	{
		$this->w_db->set('cpal_api_url', $api_url);
		$this->w_db->set('cpal_dayte', $dayte);
		$this->w_db->set('cpal_page', $page);
		$this->w_db->set('cpal_perPage', $perPage);
		$this->w_db->set('cpal_repeat', $repeat);
		$this->w_db->set('cpal_count', $count);
		$this->w_db->set('cpal_count_api', $count_api);
		$this->w_db->set('cpal_count_write', $count_write);
		$this->w_db->insert('cron_paikey_api_log_tbl');
		$id = $this->w_db->insert_id();
		//echo $this->w_db->last_query();
		return $id;
	}
	
	//暫時-取的最新一筆爬取紀錄
	public function get_cron_paikey_api_log_tmp ($date)
	{
		$this->r_db->where('cpal_dayte', $date);
		$this->r_db->order_by('cpal_pk', 'DESC');
		$this->r_db->limit(1);
		$query = $this->r_db->get('cron_paikey_api_log_tbl');
		//echo $this->r_db->last_query();
		return $query;
	}
		
	/**
	 * 取得某一天日期訂單筆數(當日所有訂單爬資料用)
	 * @param unknown $day_date			查詢時間
	 * @return unknown
	 */
	public function get_day_order_count ($day_date)
	{
		$this->r_db->like('o_time_creat', $day_date, 'after');
		$count = $this->r_db->count_all_results('Orders_tbl');
		//echo $this->r_db->last_query();
		return $count;
	}
	
	/**
	 * 建立每日訂單資料(當日所有訂單爬資料用)
	 * @param unknown $order_sn			訂單號碼
	 * @param unknown $package_id		產品包編號
	 * @param unknown $package_title	踭品包標題
	 * @param unknown $time_creat		建立時間
	 * @return unknown
	 */
	public function insert_day_order ($order_sn, $package_id, $package_title, $time_creat)
	{
		$this->r_db->where('o_order_sn', $order_sn); // 節目
		$count = $this->r_db->count_all_results('Orders_tbl');
		//echo $this->r_db->last_query();
		if($count == 0){
			$this->w_db->set('o_order_sn', $order_sn);
			$this->w_db->set('o_package_no', $package_id);
			$this->w_db->set('o_package_title', $package_title);
			$this->w_db->set('o_status', '0');
			$this->w_db->set('o_time_creat', $time_creat);
			$this->w_db->insert('Orders_tbl');
			$id = $this->w_db->insert_id();
			//echo $this->w_db->last_query();
			return $id;
		}else{
			return false;			
		}
		//如果是唯一已存在就不動作(pk會虛報 不使用開方法)
		//$sql = "INSERT INTO `Orders_tbl` (`o_order_sn`, `o_package_no`, `o_package_title`, `o_status`, `o_time_creat`) VALUES ('%s', '%s', '%s', '0', '%s') ON DUPLICATE KEY UPDATE `o_package_no` = '0', `o_package_title` = '1';";
		//$sql = sprintf($sql, $order_sn, $package_id, $package_title, $time_creat, $order_sn, $package_id);
		//$this->w_db->simple_query($sql);
		//$id = $this->w_db->insert_id();
		//echo $this->w_db->last_query();
		//return $id;
	}
	
	/**
	 * 查詢要排paykit的訂單
	 * @param unknown $limit	筆數
	 * @return unknown
	 */
	public function get_order_paykit ($limit)
	{
		$this->r_db->where('o_paykit', '1');//(0:不用執行,1:要執行)
		$this->r_db->limit($limit);
		$query = $this->r_db->get('Orders_tbl');
		//echo $this->r_db->last_query();
		return $query;
	}
	
	/**
	 * 更新訂單資料(塞陣列)
	 * @param unknown $pk
	 * @param unknown $data_array
	 * @return unknown
	 */
	public function update_order_array ($pk, $data_array)
	{
		$this->w_db->where('o_pk', $pk);
		$this->w_db->update('Orders_tbl', $data_array);
		$count = $this->w_db->affected_rows();
		//echo $this->w_db->last_query();
		return $count;
	}
	
	/**
	 * 複製一筆訂單到cash
	 * @param unknown $pk
	 * @return unknown
	 */
	public function copy_order_to_cashs($pk)
	{
		//取基本資料
		$this->r_db->where('o_pk', $pk);
		$query = $this->r_db->get('Orders_tbl');
		//echo $this->r_db->last_query();
		if($query->num_rows() > 0){
			$row = $query->row();
			//print_r($row);
			$this->r_db->where('oc_order_sn', $row->o_order_sn); // 節目
			$count = $this->r_db->count_all_results('Order_cashs_tbl');
			//echo $this->r_db->last_query();
			if($count == 0){
				//$this->w_db->set('oc_pk', $row->);
				//$this->w_db->set('oc_order_no', $row->o_pk);
				$this->w_db->set('oc_order_sn', $row->o_order_sn);
				$this->w_db->set('oc_user_creat', $row->o_user_creat);
				$this->w_db->set('oc_user_no', $row->o_user_no);
				$this->w_db->set('oc_mongo_id', $row->o_mongo_id);
				$this->w_db->set('oc_member_id', $row->o_member_id);
				$this->w_db->set('oc_package_no', $row->o_package_no);
				$this->w_db->set('oc_package_title', $row->o_package_title);
				$this->w_db->set('oc_payment_no', $row->o_payment_no);
				$this->w_db->set('oc_cost', $row->o_cost);
				$this->w_db->set('oc_price', $row->o_price);
				$this->w_db->set('oc_coupon_no', $row->o_coupon_no);
				$this->w_db->set('oc_coupon_sn', $row->o_coupon_sn);
				$this->w_db->set('oc_coupon_title', $row->o_coupon_title);
				//$this->w_db->set('oc_coupon_type', $row->o_coupon_type);
				//$this->w_db->set('oc_coupon_value', $row->o_coupon_value);
				//$this->w_db->set('oc_discount_rate', $row->o_discount_rate);
				$this->w_db->set('oc_expenses', $row->o_expenses);
				$this->w_db->set('oc_subtotal', $row->o_subtotal);
				$this->w_db->set('oc_rs', $row->o_rs);
				//$this->w_db->set('oc_donate', $row->o_donate);
				$this->w_db->set('oc_invoice_type', $row->o_invoice_type);
				$this->w_db->set('oc_invoice', $row->o_invoice);
				$this->w_db->set('oc_remark_buyer', $row->o_remark_buyer);
				$this->w_db->set('oc_remark_response', $row->o_remark_response);
				$this->w_db->set('oc_remark_invoice', $row->o_remark_invoice);
				$this->w_db->set('oc_status', $row->o_status);
				//$this->w_db->set('oc_active_status', $row->);
				$this->w_db->set('oc_time_creat', $row->o_time_creat);
				$this->w_db->set('oc_time_update', $row->o_time_update);
				//$this->w_db->set('oc_time_active', $row->);
				$this->w_db->set('oc_ip', $row->o_ip);
				$this->w_db->insert('Order_cashs_tbl');
				$id = $this->w_db->insert_id();
				//echo $this->w_db->last_query();
				return $id;
			}
		}
		return false;
	}
}
